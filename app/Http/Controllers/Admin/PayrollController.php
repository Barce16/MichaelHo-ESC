<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\NotificationService;

class PayrollController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');
        $search = $request->get('search', '');

        $query = Event::with(['staffs', 'customer'])
            ->whereHas('staffs')
            ->orderBy('event_date', 'desc');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($c) use ($search) {
                        $c->where('customer_name', 'like', "%{$search}%");
                    });
            });
        }

        $events = $query->paginate(15)->withQueryString();

        // Calculate summary stats
        $stats = [
            'total_events' => Event::whereHas('staffs')->count(),
            'pending_payroll' => Event::whereHas('staffs', function ($q) {
                $q->where('pay_status', 'pending');
            })->count(),
            'total_pending_amount' => DB::table('event_staff')
                ->where('pay_status', 'pending')
                ->sum('pay_rate'),
            'total_paid_amount' => DB::table('event_staff')
                ->where('pay_status', 'paid')
                ->sum('pay_rate'),
        ];

        return view('admin.payroll.index', compact('events', 'stats', 'status', 'search'));
    }

    public function viewStaffs(Event $event)
    {
        $event->load(['staffs', 'customer', 'package']);

        $totalPayroll = $event->staffs->sum('pivot.pay_rate');
        $paidAmount = $event->staffs->where('pivot.pay_status', 'paid')->sum('pivot.pay_rate');
        $pendingAmount = $event->staffs->where('pivot.pay_status', 'pending')->sum('pivot.pay_rate');

        return view('admin.payroll.view-staffs', compact('event', 'totalPayroll', 'paidAmount', 'pendingAmount'));
    }

    public function markAsPaid(Request $request, Event $event, Staff $staff)
    {

        $pivotData = $event->staffs()->where('staff_id', $staff->id)->first()->pivot;

        $event->staffs()->updateExistingPivot($staff->id, [
            'pay_status' => 'paid',
        ]);

        $payrollRecord = (object)[
            'staff' => $staff,
            'total_amount' => $pivotData->pay_rate,
            'event' => $event,
        ];

        $this->notificationService->notifyStaffPayrollPaid($payrollRecord);

        return back()->with('success', 'Staff payment marked as paid successfully');
    }

    public function markAsPending(Request $request, Event $event, Staff $staff)
    {
        $event->staffs()->updateExistingPivot($staff->id, [
            'pay_status' => 'pending',
        ]);

        return back()->with('success', 'Staff payment marked as pending');
    }
}
