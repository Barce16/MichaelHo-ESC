<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use App\Models\Event;
use App\Models\Customer;
use App\Models\Package;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $isCustomer = $user->user_type === 'customer';
        $isStaff = $user->user_type === 'staff';
        $isAdmin = $user->user_type === 'admin';

        if ($isAdmin) {
            // ============ ADMIN VIEW ============
            $totalEvents = Event::count();
            $totalCustomers = Customer::count();

            // Calculate payments this month from billings
            $paymentsThisMonth = Payment::whereMonth('payment_date', now()->month)
                ->whereYear('payment_date', now()->year)
                ->where('status', 'approved')
                ->sum('amount');

            $pendingTasks = Event::whereIn('status', ['requested', 'approved', 'request_meeting'])->count();

            $recentEvents = Event::with(['customer.user'])
                ->latest('event_date')
                ->take(10)
                ->get();

            // Chart data - Events by Status
            $statusData = [
                Event::where('status', 'requested')->count(),
                Event::where('status', 'approved')->count(),
                Event::where('status', 'scheduled')->count(),
                Event::where('status', 'completed')->count(),
                Event::where('status', 'cancelled')->count(),
            ];

            // Revenue data for last 6 months
            $revenueLabels = [];
            $revenueData = [];
            for ($i = 5; $i >= 0; $i--) {
                $month = now()->subMonths($i);
                $revenueLabels[] = $month->format('M');
                $revenueData[] = Payment::whereMonth('payment_date', $month->month)
                    ->whereYear('payment_date', $month->year)
                    ->where('status', 'approved')
                    ->sum('amount');
            }

            // Events per month for last 12 months
            $eventsLabels = [];
            $eventsData = [];
            for ($i = 11; $i >= 0; $i--) {
                $month = now()->subMonths($i);
                $eventsLabels[] = $month->format('M');
                $eventsData[] = Event::whereMonth('event_date', $month->month)
                    ->whereYear('event_date', $month->year)
                    ->count();
            }

            return view('dashboard', compact(
                'totalEvents',
                'totalCustomers',
                'paymentsThisMonth',
                'pendingTasks',
                'recentEvents',
                'statusData',
                'revenueLabels',
                'revenueData',
                'eventsLabels',
                'eventsData'
            ));
        } elseif ($isStaff) {
            // ============ STAFF VIEW ============
            $staff = $user->staff;

            if (!$staff) {
                return view('dashboard', [
                    'staffAssignedEvents' => 0,
                    'staffCompletedTasks' => 0,
                    'staffHoursThisMonth' => 0,
                    'staffEarningsThisMonth' => 0,
                    'staffEarningsLastMonth' => 0,
                    'staffTotalEarnings' => 0,
                    'todaysSchedule' => collect(),
                    'staffAssignedEventsList' => collect(),
                    'staffScheduleLabels' => ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
                    'staffScheduleData' => [0, 0, 0, 0],
                    'staffEarningsLabels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    'staffEarningsData' => [0, 0, 0, 0, 0, 0],
                ]);
            }

            // Count assigned events (upcoming)
            $staffAssignedEvents = $staff->events()
                ->where('event_date', '>=', now())
                ->count();

            // Count completed tasks this month
            $staffCompletedTasks = $staff->events()
                ->where('status', 'completed')
                ->whereMonth('event_date', now()->month)
                ->whereYear('event_date', now()->year)
                ->count();

            // Calculate hours this month (estimate: 8 hours per event)
            $eventsThisMonth = $staff->events()
                ->whereMonth('event_date', now()->month)
                ->whereYear('event_date', now()->year)
                ->count();
            $staffHoursThisMonth = $eventsThisMonth * 8;

            // Calculate earnings this month from event_staff pivot table
            $staffEarningsThisMonth = DB::table('event_staff')
                ->join('events', 'event_staff.event_id', '=', 'events.id')
                ->where('event_staff.staff_id', $staff->id)
                ->where('event_staff.pay_status', 'paid')
                ->whereMonth('events.event_date', now()->month)
                ->whereYear('events.event_date', now()->year)
                ->sum('event_staff.pay_rate');

            // Calculate earnings last month
            $staffEarningsLastMonth = DB::table('event_staff')
                ->join('events', 'event_staff.event_id', '=', 'events.id')
                ->where('event_staff.staff_id', $staff->id)
                ->where('event_staff.pay_status', 'paid')
                ->whereMonth('events.event_date', now()->subMonth()->month)
                ->whereYear('events.event_date', now()->subMonth()->year)
                ->sum('event_staff.pay_rate');

            // Calculate total earnings
            $staffTotalEarnings = DB::table('event_staff')
                ->where('staff_id', $staff->id)
                ->where('pay_status', 'paid')
                ->sum('pay_rate');

            // Today's Schedule
            $todaysSchedule = $staff->events()
                ->whereDate('event_date', today())
                ->with('customer')
                ->get();

            // Assigned Events List (upcoming)
            $staffAssignedEventsList = $staff->events()
                ->where('event_date', '>=', now())
                ->with('customer')
                ->orderBy('event_date', 'asc')
                ->take(10)
                ->get()
                ->map(function ($event) use ($staff) {
                    // Get assignment details from pivot
                    $assignment = DB::table('event_staff')
                        ->where('event_id', $event->id)
                        ->where('staff_id', $staff->id)
                        ->first();

                    $event->assignment_role = $assignment->assignment_role ?? 'Staff';
                    $event->pay_rate = $assignment->pay_rate ?? 0;
                    $event->pay_status = $assignment->pay_status ?? 'pending';

                    return $event;
                });

            // Chart data - Weekly schedule for this month
            $staffScheduleLabels = ['Week 1', 'Week 2', 'Week 3', 'Week 4'];
            $staffScheduleData = [];
            for ($i = 0; $i < 4; $i++) {
                $startOfWeek = now()->startOfMonth()->addWeeks($i);
                $endOfWeek = $startOfWeek->copy()->endOfWeek();

                $count = $staff->events()
                    ->whereBetween('event_date', [$startOfWeek, $endOfWeek])
                    ->count();

                $staffScheduleData[] = $count;
            }

            // Earnings for last 6 months
            $staffEarningsLabels = [];
            $staffEarningsData = [];
            for ($i = 5; $i >= 0; $i--) {
                $month = now()->subMonths($i);
                $staffEarningsLabels[] = $month->format('M');

                $earnings = DB::table('event_staff')
                    ->join('events', 'event_staff.event_id', '=', 'events.id')
                    ->where('event_staff.staff_id', $staff->id)
                    ->where('event_staff.pay_status', 'paid')
                    ->whereMonth('events.event_date', $month->month)
                    ->whereYear('events.event_date', $month->year)
                    ->sum('event_staff.pay_rate');

                $staffEarningsData[] = $earnings;
            }

            return view('dashboard', compact(
                'staffAssignedEvents',
                'staffCompletedTasks',
                'staffHoursThisMonth',
                'staffEarningsThisMonth',
                'staffEarningsLastMonth',
                'staffTotalEarnings',
                'todaysSchedule',
                'staffAssignedEventsList',
                'staffScheduleLabels',
                'staffScheduleData',
                'staffEarningsLabels',
                'staffEarningsData'
            ));
        } else {
            // ============ CUSTOMER VIEW ============
            $customer = $user->customer;

            if (!$customer) {
                return view('dashboard', [
                    'upcoming'     => 0,
                    'recentEvents' => collect(),
                    'packages'     => collect(),
                    'customerStatusData' => [0, 0, 0],
                    'customerPaymentLabels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    'customerPaymentData' => [0, 0, 0, 0, 0, 0],
                ]);
            }

            $packages = Package::with(['inclusions'])
                ->where('is_active', true)
                ->orderBy('price')
                ->take(6)
                ->get();

            $recentEvents = Event::where('customer_id', $customer->id)
                ->orderByDesc('event_date')
                ->limit(10)
                ->get();

            $upcoming = Event::where('customer_id', $customer->id)
                ->where('event_date', '>=', now())
                ->count();

            // Chart data - Customer event status
            $customerStatusData = [
                Event::where('customer_id', $customer->id)
                    ->whereIn('status', ['approved', 'scheduled', 'meeting'])
                    ->where('event_date', '>=', now())
                    ->count(),
                Event::where('customer_id', $customer->id)
                    ->where('status', 'completed')
                    ->count(),
                Event::where('customer_id', $customer->id)
                    ->whereIn('status', ['cancelled', 'rejected'])
                    ->count(),
            ];

            // Payment history for last 6 months
            $customerPaymentLabels = [];
            $customerPaymentData = [];
            for ($i = 5; $i >= 0; $i--) {
                $month = now()->subMonths($i);
                $customerPaymentLabels[] = $month->format('M');

                // Get payments for this customer's events
                $payments = Payment::whereHas('billing.event', function ($q) use ($customer) {
                    $q->where('customer_id', $customer->id);
                })
                    ->whereMonth('payment_date', $month->month)
                    ->whereYear('payment_date', $month->year)
                    ->where('status', 'approved')
                    ->sum('amount');

                $customerPaymentData[] = $payments;
            }

            return view('dashboard', compact(
                'upcoming',
                'recentEvents',
                'packages',
                'customerStatusData',
                'customerPaymentLabels',
                'customerPaymentData'
            ));
        }
    }
}
