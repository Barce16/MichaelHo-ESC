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
            $attach = [];
            foreach ($newInclusionIds as $incId) {
                $attach[$incId] = [
                    'price_snapshot' => (float) ($inclusionPrices[$incId] ?? 0),
                    'notes' => $inclusionNotes[$incId] ?? null,
                ];
            }
            $event->inclusions()->sync($attach);

            // Update billing if exists
            if ($event->billing) {
                $event->billing->update([
                    'total_amount' => $changeRequest->new_total,
                    'balance' => $changeRequest->new_total - $event->billing->amount_paid,
                ]);
            }

            // 🔔 NOTIFY CUSTOMER - Change Request Approved
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
            ->with('success', 'Change request approved and applied successfully.');
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
