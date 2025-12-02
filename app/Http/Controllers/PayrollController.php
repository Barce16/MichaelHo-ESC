<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class PayrollController extends Controller
{

    public function index(Request $request)
    {
        $from   = $request->date('from') ?? now()->startOfMonth()->toDateString();
        $to     = $request->date('to')   ?? now()->endOfMonth()->toDateString();
        $status = $request->string('status')->toString();

        $events = DB::table('event_staff AS es')
            ->join('events AS e', 'e.id', '=', 'es.event_id')
            ->join('staffs AS s', 's.id', '=', 'es.staff_id')
            ->join('users AS u', 'u.id', '=', 's.user_id')
            ->select(
                'e.id AS event_id',
                'e.name AS event_name',
                'e.event_date',
                's.id AS staff_id',
                'u.name AS staff_name',
                'es.assignment_role',
                'es.pay_rate',
                'es.pay_status'
            )
            ->whereBetween('e.event_date', [$from, $to]);

        if ($status) {
            $events->where('es.pay_status', $status);
        }

        $events = $events->orderBy('e.event_date')->get();

        $groupedEvents = $events->groupBy('event_id');

        return view('payroll.index', compact('groupedEvents', 'from', 'to', 'status'));
    }


    public function lines($eventId)
    {
        $event = Event::with('staffs')->findOrFail($eventId);

        return view('payroll.lines', compact('event'));
    }


    public function mark(Request $request)
    {
        $data = $request->validate([
            'ids'    => ['required', 'array'],
            'ids.*'  => ['integer'],
            'status' => ['required', 'in:pending,approved,paid'],
        ]);

        DB::table('event_staff')
            ->whereIn('id', $data['ids'])
            ->update(['pay_status' => $data['status']]);

        return back()->with('success', 'Payroll lines updated.');
    }

    public function viewStaffs($eventId)
    {
        $event = Event::with('staffs')->findOrFail($eventId);
        return view('payroll.view_staffs', compact('event'));
    }

    public function markAsPaid($eventId, $staffId)
    {
        DB::table('event_staff')
            ->where('event_id', $eventId)
            ->where('staff_id', $staffId)
            ->update(['pay_status' => 'paid']);

        return back()->with('success', 'Staff marked as paid.');
    }

    /**
     * Download payslip PDF for paid staff
     */
    public function downloadPayslip(Event $event, Staff $staff)
    {
        // Check if staff is assigned to this event
        $pivot = $event->staffs()->where('staff_id', $staff->id)->first()?->pivot;

        if (!$pivot) {
            return back()->with('error', 'Staff is not assigned to this event.');
        }

        // Check if staff is paid
        if ($pivot->pay_status !== 'paid') {
            return back()->with('error', 'Payslip is only available for paid staff.');
        }

        // Get admin user for signature (prefer one with signature, or get first admin)
        $admin = \App\Models\User::where('user_type', 'admin')
            ->whereNotNull('signature_path')
            ->first()
            ?? \App\Models\User::where('user_type', 'admin')->first();

        $pdf = Pdf::loadView('admin.payroll.payslip-pdf', compact('event', 'staff', 'pivot', 'admin'))
            ->setPaper('a4', 'portrait')
            ->setOption('margin-top', 5)
            ->setOption('margin-bottom', 5)
            ->setOption('margin-left', 10)
            ->setOption('margin-right', 10);

        $filename = 'payslip-' . $staff->id . '-event-' . $event->id . '-' . now()->format('Y-m-d') . '.pdf';

        // Use stream() to open in browser instead of download()
        return $pdf->stream($filename);
    }
}
