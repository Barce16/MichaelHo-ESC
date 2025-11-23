<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InclusionChangeRequest;
use App\Models\Event;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InclusionChangeRequestController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index()
    {
        $changeRequests = InclusionChangeRequest::with(['event', 'customer.user'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.change-requests.index', compact('changeRequests'));
    }

    public function show(InclusionChangeRequest $changeRequest)
    {
        $changeRequest->load(['event.package', 'customer.user', 'reviewedBy']);

        return view('admin.change-requests.show', compact('changeRequest'));
    }

    public function approve(Request $request, InclusionChangeRequest $changeRequest)
    {
        abort_if($changeRequest->status !== InclusionChangeRequest::STATUS_PENDING, 403, 'This request has already been processed.');

        $request->validate([
            'admin_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($changeRequest, $request) {
            $event = $changeRequest->event;

            // Store old values for event progress log
            $oldTotal = $event->billing ? $event->billing->total_amount : 0;

            // Update change request status
            $changeRequest->update([
                'status' => InclusionChangeRequest::STATUS_APPROVED,
                'admin_notes' => $request->admin_notes,
                'reviewed_by' => auth()->id(),
                'reviewed_at' => now(),
            ]);

            // Apply the changes to the event
            $newInclusionIds = collect($changeRequest->new_inclusions)->pluck('id');
            $inclusionNotes = $changeRequest->inclusion_notes ?? [];

            // Get price snapshots
            $inclusionPrices = collect($changeRequest->new_inclusions)
                ->pluck('price', 'id')
                ->toArray();

            // Sync inclusions with price snapshots and notes
            $syncData = [];
            foreach ($newInclusionIds as $incId) {
                $syncData[$incId] = [
                    'price_snapshot' => (float) ($inclusionPrices[$incId] ?? 0),
                    'notes' => $inclusionNotes[$incId] ?? null,
                ];
            }
            $event->inclusions()->sync($syncData);

            // Recalculate billing properly (like admin side)
            $this->recalculateBilling($event);

            // Refresh event to get updated billing
            $event->refresh();
            $newTotal = $event->billing->total_amount;

            // Get added and removed inclusions for event progress log
            $addedInclusions = collect($changeRequest->new_inclusions)
                ->whereIn('id', collect($changeRequest->added_inclusions)->pluck('id'));
            $removedInclusions = collect($changeRequest->removed_inclusions);

            // Build change details for progress log
            $changes = [];
            if ($addedInclusions->count() > 0) {
                $added = $addedInclusions->pluck('name')->toArray();
                $changes[] = "Added: " . implode(', ', $added);
            }
            if ($removedInclusions->count() > 0) {
                $removed = $removedInclusions->pluck('name')->toArray();
                $changes[] = "Removed: " . implode(', ', $removed);
            }

            $changeDetails = !empty($changes)
                ? implode(". ", $changes) . "."
                : "Inclusions modified.";

            // Create event progress record
            \App\Models\EventProgress::create([
                'event_id' => $event->id,
                'status' => 'Change Request Approved',
                'details' => "Customer's inclusion change request approved. {$changeDetails} Total amount changed from ₱" . number_format($oldTotal, 2) . " to ₱" . number_format($newTotal, 2) . ".",
                'progress_date' => now(),
            ]);

            // 📧 NOTIFY CUSTOMER - Change Request Approved
            $customer = $changeRequest->customer;
            if ($customer->user) {
                // 1. Send email notification
                $customer->user->notify(new \App\Notifications\ChangeRequestApprovedNotification($changeRequest));

                // 2. Create in-app notification
                $this->notificationService->notifyCustomerChangeRequestApproved($changeRequest);
            }
        });

        return redirect()
            ->route('admin.change-requests.index')
            ->with('success', 'Change request approved and applied successfully. Customer has been notified.');
    }

    /**
     * Recalculate billing based on package and inclusions
     * (Same method from admin EventController)
     */
    protected function recalculateBilling(Event $event)
    {
        $event->load(['package', 'inclusions', 'billing']);

        // Calculate total from coordination + event_styling + inclusions
        // DO NOT use package->price as it includes base inclusions
        $coordinationPrice = $event->package->coordination_price ?? 0;
        $eventStylingPrice = $event->package->event_styling_price ?? 0;
        $inclusionsTotal = $event->inclusions->sum('pivot.price_snapshot');

        $newTotal = $coordinationPrice + $eventStylingPrice + $inclusionsTotal;

        // Get or create billing
        $billing = $event->billing;
        if (!$billing) {
            $billing = new \App\Models\Billing();
            $billing->event_id = $event->id;
        }

        // Update billing total only (balance is computed via accessor)
        $billing->total_amount = $newTotal;

        $billing->save();
    }

    public function reject(Request $request, InclusionChangeRequest $changeRequest)
    {
        abort_if($changeRequest->status !== InclusionChangeRequest::STATUS_PENDING, 403, 'This request has already been processed.');

        $request->validate([
            'admin_notes' => ['required', 'string', 'max:1000'],
        ]);

        $changeRequest->update([
            'status' => InclusionChangeRequest::STATUS_REJECTED,
            'admin_notes' => $request->admin_notes,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        // 🔔 NOTIFY CUSTOMER - Change Request Rejected
        $customer = $changeRequest->customer;
        if ($customer->user) {
            // 1. Send email notification
            $customer->user->notify(new \App\Notifications\ChangeRequestRejectedNotification($changeRequest));

            // 2. Create in-app notification
            $this->notificationService->notifyCustomerChangeRequestRejected($changeRequest);
        }

        return redirect()
            ->route('admin.change-requests.index')
            ->with('success', 'Change request rejected.');
    }
}
