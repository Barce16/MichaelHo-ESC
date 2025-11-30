<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Payment;
use App\Models\Customer;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EventsReportExport;
use App\Exports\RevenueReportExport;
use App\Exports\CustomersReportExport;
use App\Exports\CustomerSpendingExport;
use App\Exports\EventStatusExport;
use App\Exports\PackageUsageExport;
use App\Exports\PaymentMethodExport;
use App\Exports\RemainingBalancesExport;
use App\Exports\CustomerDetailExport;


class ReportController extends Controller
{
    public function index()
    {
        return view('admin.reports.index');
    }

    public function eventsReport(Request $request)
    {
        $dateFrom = $request->date('from') ?? now()->startOfMonth();
        $dateTo = $request->date('to') ?? now()->endOfMonth();

        $events = Event::with(['customer', 'package', 'billing'])
            ->whereBetween('event_date', [$dateFrom, $dateTo])
            ->orderBy('event_date')
            ->get();

        $stats = [
            'total_events' => $events->count(),
            'by_status' => $events->groupBy('status')->map->count(),
            'total_revenue' => $events->sum(fn($e) => $e->billing?->total_amount ?? 0),
        ];

        // Handle export requests
        if ($request->has('export')) {
            if ($request->export === 'pdf') {
                return $this->exportEventsPdf($events, $stats, $dateFrom, $dateTo);
            }
            if ($request->export === 'csv') {
                return Excel::download(
                    new EventsReportExport($events, $dateFrom, $dateTo, $stats['total_revenue']),
                    'events-report-' . now()->format('Y-m-d') . '.csv'
                );
            }
        }

        return view('admin.reports.events', compact('events', 'stats', 'dateFrom', 'dateTo'));
    }

    // ========== EVENTS REPORT ==========
    private function exportEventsPdf($events, $stats, $dateFrom, $dateTo)
    {
        $pdf = Pdf::loadView('admin.reports.events-pdf', [
            'events' => $events,
            'stats' => $stats,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
        ])->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', true);

        return $pdf->download('events-report-' . now()->format('Y-m-d') . '.pdf');
    }

    // ========== REVENUE REPORT ==========
    public function revenueReport(Request $request)
    {
        $dateFrom = $request->date('from') ?? now()->startOfMonth();
        $dateTo = $request->date('to') ?? now()->endOfMonth();

        $payments = Payment::with(['billing.event.customer'])
            ->where('status', 'approved')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->orderBy('created_at')
            ->get();

        $stats = [
            'total_payments' => $payments->count(),
            'total_amount' => $payments->sum('amount'),
            'by_method' => $payments->groupBy('payment_method')->map->sum('amount'),
        ];

        // Handle export requests
        if ($request->has('export')) {
            if ($request->export === 'pdf') {
                return $this->exportRevenuePdf($payments, $stats, $dateFrom, $dateTo);
            }
            if ($request->export === 'csv') {
                return Excel::download(
                    new RevenueReportExport($payments, $dateFrom, $dateTo, $stats['total_amount']),
                    'revenue-report-' . now()->format('Y-m-d') . '.csv'
                );
            }
        }

        return view('admin.reports.revenue', compact('payments', 'stats', 'dateFrom', 'dateTo'));
    }

    private function exportRevenuePdf($payments, $stats, $dateFrom, $dateTo)
    {
        $pdf = Pdf::loadView('admin.reports.revenue-pdf', [
            'payments' => $payments,
            'stats' => $stats,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
        ])->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', true);

        return $pdf->download('revenue-report-' . now()->format('Y-m-d') . '.pdf');
    }

    // ========== CUSTOMERS REPORT ==========
    public function customersReport(Request $request)
    {
        $dateFrom = $request->date('from') ?? now()->startOfYear();
        $dateTo = $request->date('to') ?? now()->endOfYear();

        $customers = Customer::with(['events' => function ($q) use ($dateFrom, $dateTo) {
            $q->whereBetween('event_date', [$dateFrom, $dateTo]);
        }, 'events.billing'])
            ->withCount(['events' => function ($q) use ($dateFrom, $dateTo) {
                $q->whereBetween('event_date', [$dateFrom, $dateTo]);
            }])
            ->get();

        $stats = [
            'total_customers' => $customers->count(),
            'active_customers' => $customers->filter(fn($c) => $c->events_count > 0)->count(),
            'total_events' => $customers->sum('events_count'),
            'total_revenue' => $customers->sum(fn($c) => $c->events->sum(fn($e) => $e->billing?->total_amount ?? 0)),
        ];

        // Handle export requests
        if ($request->has('export')) {
            if ($request->export === 'pdf') {
                return $this->exportCustomersPdf($customers, $stats, $dateFrom, $dateTo);
            }
            if ($request->export === 'csv') {
                return Excel::download(
                    new CustomersReportExport($customers, $dateFrom, $dateTo, $stats['total_revenue']),
                    'customers-report-' . now()->format('Y-m-d') . '.csv'
                );
            }
        }

        return view('admin.reports.customers', compact('customers', 'stats', 'dateFrom', 'dateTo'));
    }

    private function exportCustomersPdf($customers, $stats, $dateFrom, $dateTo)
    {
        $pdf = Pdf::loadView('admin.reports.customers-pdf', [
            'customers' => $customers,
            'stats' => $stats,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
        ])->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', true);

        return $pdf->download('customers-report-' . now()->format('Y-m-d') . '.pdf');
    }

    // ========== CUSTOMER SPENDING REPORT ==========
    public function customerSpending(Request $request)
    {
        $dateFrom = $request->date('from') ?? now()->startOfYear();
        $dateTo = $request->date('to') ?? now()->endOfYear();

        $customers = DB::table('customers as c')
            ->select(
                'c.id',
                'c.customer_name',
                'c.email',
                'c.phone',
                DB::raw('COUNT(DISTINCT e.id) as total_events'),
                DB::raw('COALESCE(SUM(p.amount), 0) as total_spent')
            )
            ->join('events as e', 'c.id', '=', 'e.customer_id')
            ->join('billings as b', 'e.id', '=', 'b.event_id')
            ->join('payments as p', 'b.id', '=', 'p.billing_id')
            ->where('p.status', 'approved')
            ->whereBetween('e.event_date', [$dateFrom, $dateTo])
            ->groupBy('c.id', 'c.customer_name', 'c.email', 'c.phone')
            ->orderByDesc('total_spent')
            ->get();

        $stats = [
            'total_customers' => $customers->count(),
            'total_revenue' => $customers->sum('total_spent'),
            'avg_per_customer' => $customers->count() > 0 ? $customers->avg('total_spent') : 0,
            'top_spender' => $customers->first(),
        ];

        if ($request->has('export')) {
            if ($request->export === 'pdf') {
                return $this->exportCustomerSpendingPdf($customers, $stats, $dateFrom, $dateTo);
            }
            if ($request->export === 'csv') {
                return Excel::download(
                    new CustomerSpendingExport($customers, $dateFrom, $dateTo, $stats['total_revenue']),
                    'customer-spending-' . now()->format('Y-m-d') . '.csv'
                );
            }
        }

        return view('admin.reports.customer-spending', compact('customers', 'stats', 'dateFrom', 'dateTo'));
    }

    private function exportCustomerSpendingPdf($customers, $stats, $dateFrom, $dateTo)
    {
        $pdf = Pdf::loadView('admin.reports.customer-spending-pdf', [
            'customers' => $customers,
            'stats' => $stats,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
        ])->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', true);

        return $pdf->download('customer-spending-' . now()->format('Y-m-d') . '.pdf');
    }

    // ========== PACKAGE USAGE REPORT ==========
    public function packageUsage(Request $request)
    {
        $dateFrom = $request->date('from') ?? now()->startOfYear();
        $dateTo = $request->date('to') ?? now()->endOfYear();

        $packages = DB::table('packages as p')
            ->select(
                'p.id',
                'p.name',
                'p.type',
                'p.price',
                DB::raw('COUNT(e.id) as total_events'),
                DB::raw('COALESCE(SUM(b.total_amount), 0) as total_revenue')
            )
            ->leftJoin('events as e', 'p.id', '=', 'e.package_id')
            ->leftJoin('billings as b', 'e.id', '=', 'b.event_id')
            ->whereBetween('e.event_date', [$dateFrom, $dateTo])
            ->groupBy('p.id', 'p.name', 'p.type', 'p.price')
            ->orderByDesc('total_events')
            ->get();

        $stats = [
            'total_packages' => $packages->count(),
            'most_popular' => $packages->first(),
            'total_bookings' => $packages->sum('total_events'),
            'total_revenue' => $packages->sum('total_revenue'),
        ];

        if ($request->has('export')) {
            if ($request->export === 'pdf') {
                return $this->exportPackageUsagePdf($packages, $stats, $dateFrom, $dateTo);
            }
            if ($request->export === 'csv') {
                return Excel::download(
                    new PackageUsageExport($packages, $dateFrom, $dateTo, $stats['total_revenue']),
                    'package-usage-' . now()->format('Y-m-d') . '.csv'
                );
            }
        }

        return view('admin.reports.package-usage', compact('packages', 'stats', 'dateFrom', 'dateTo'));
    }

    private function exportPackageUsagePdf($packages, $stats, $dateFrom, $dateTo)
    {
        $pdf = Pdf::loadView('admin.reports.package-usage-pdf', [
            'packages' => $packages,
            'stats' => $stats,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
        ])->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', true);

        return $pdf->download('package-usage-' . now()->format('Y-m-d') . '.pdf');
    }

    // ========== PAYMENT METHOD REPORT ==========
    public function paymentMethod(Request $request)
    {
        $dateFrom = $request->date('from') ?? now()->startOfMonth();
        $dateTo = $request->date('to') ?? now()->endOfMonth();

        $paymentMethods = DB::table('payments')
            ->select(
                'payment_method',
                DB::raw('COUNT(*) as payment_count'),
                DB::raw('SUM(amount) as total_revenue')
            )
            ->where('status', 'approved')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->groupBy('payment_method')
            ->orderByDesc('total_revenue')
            ->get()
            ->map(function ($item) {
                $item->payment_method_label = ucwords(str_replace('_', ' ', $item->payment_method));
                return $item;
            });

        $stats = [
            'total_payments' => $paymentMethods->sum('payment_count'),
            'total_amount' => $paymentMethods->sum('total_revenue'),
            'most_used' => $paymentMethods->first(),
        ];

        if ($request->has('export')) {
            if ($request->export === 'pdf') {
                return $this->exportPaymentMethodPdf($paymentMethods, $stats, $dateFrom, $dateTo);
            }
            if ($request->export === 'csv') {
                return Excel::download(
                    new PaymentMethodExport($paymentMethods, $dateFrom, $dateTo, $stats['total_amount']),
                    'payment-method-' . now()->format('Y-m-d') . '.csv'
                );
            }
        }

        return view('admin.reports.payment-method', compact('paymentMethods', 'stats', 'dateFrom', 'dateTo'));
    }

    private function exportPaymentMethodPdf($paymentMethods, $stats, $dateFrom, $dateTo)
    {
        $pdf = Pdf::loadView('admin.reports.payment-method-pdf', [
            'paymentMethods' => $paymentMethods,
            'stats' => $stats,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
        ])->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', true);

        return $pdf->download('payment-method-' . now()->format('Y-m-d') . '.pdf');
    }

    // ========== EVENT STATUS REPORT ==========
    public function eventStatus(Request $request)
    {
        $dateFrom = $request->date('from') ?? now()->startOfYear();
        $dateTo = $request->date('to') ?? now()->endOfYear();

        $statusSummary = DB::table('events')
            ->select(
                'status',
                DB::raw('COUNT(*) as event_count')
            )
            ->whereBetween('event_date', [$dateFrom, $dateTo])
            ->groupBy('status')
            ->orderByDesc('event_count')
            ->get()
            ->map(function ($item) {
                $item->status_label = ucwords(str_replace('_', ' ', $item->status));
                return $item;
            });

        $stats = [
            'total_events' => $statusSummary->sum('event_count'),
            'most_common' => $statusSummary->first(),
        ];

        if ($request->has('export')) {
            if ($request->export === 'pdf') {
                return $this->exportEventStatusPdf($statusSummary, $stats, $dateFrom, $dateTo);
            }
            if ($request->export === 'csv') {
                return Excel::download(
                    new EventStatusExport($statusSummary, $dateFrom, $dateTo, $stats['total_events']),
                    'event-status-' . now()->format('Y-m-d') . '.csv'
                );
            }
        }

        return view('admin.reports.event-status', compact('statusSummary', 'stats', 'dateFrom', 'dateTo'));
    }

    private function exportEventStatusPdf($statusSummary, $stats, $dateFrom, $dateTo)
    {
        $pdf = Pdf::loadView('admin.reports.event-status-pdf', [
            'statusSummary' => $statusSummary,
            'stats' => $stats,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
        ])->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', true);

        return $pdf->download('event-status-' . now()->format('Y-m-d') . '.pdf');
    }

    // ========== REMAINING BALANCES REPORT ==========
    public function remainingBalances(Request $request)
    {
        $events = DB::table('billings as b')
            ->select(
                'e.id',
                'e.name as event_name',
                'e.event_date',
                'c.customer_name',
                'c.email',
                'c.phone',
                'b.total_amount',
                'b.downpayment_amount as paid_amount',
                DB::raw('(b.total_amount - b.downpayment_amount) as balance')
            )
            ->join('events as e', 'b.event_id', '=', 'e.id')
            ->join('customers as c', 'e.customer_id', '=', 'c.id')
            ->whereRaw('(b.total_amount - b.downpayment_amount) > 0')
            ->orderByDesc(DB::raw('(b.total_amount - b.downpayment_amount)'))
            ->get();

        $stats = [
            'total_events' => $events->count(),
            'total_balance' => $events->sum('balance'),
            'largest_balance' => $events->first(),
        ];

        if ($request->has('export')) {
            if ($request->export === 'pdf') {
                return $this->exportRemainingBalancesPdf($events, $stats);
            }
            if ($request->export === 'csv') {
                return Excel::download(
                    new RemainingBalancesExport($events, $stats['total_balance']),
                    'remaining-balances-' . now()->format('Y-m-d') . '.csv'
                );
            }
        }

        return view('admin.reports.remaining-balances', compact('events', 'stats'));
    }

    private function exportRemainingBalancesPdf($events, $stats)
    {
        $pdf = Pdf::loadView('admin.reports.remaining-balances-pdf', [
            'events' => $events,
            'stats' => $stats,
        ])->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', true);

        return $pdf->download('remaining-balances-' . now()->format('Y-m-d') . '.pdf');
    }

    // ========== SYSTEM SUMMARY REPORT ==========
    public function systemSummary()
    {
        $summary = DB::selectOne("
            SELECT 
                (SELECT COUNT(*) FROM customers) as total_customers,
                (SELECT COUNT(DISTINCT c.id) 
                 FROM customers c 
                 INNER JOIN events e ON c.id = e.customer_id) as active_customers,
                (SELECT COUNT(*) FROM events) as total_events,
                (SELECT COUNT(*) FROM events WHERE status = 'requested') as requested_events,
                (SELECT COUNT(*) FROM events WHERE status = 'approved') as approved_events,
                (SELECT COUNT(*) FROM events WHERE status = 'scheduled') as scheduled_events,
                (SELECT COUNT(*) FROM events WHERE status = 'completed') as completed_events,
                (SELECT COUNT(*) FROM events WHERE status = 'rejected') as rejected_events,
                (SELECT COALESCE(SUM(total_amount), 0) FROM billings) as total_revenue,
                (SELECT COALESCE(SUM(amount), 0) FROM payments WHERE status = 'approved') as collected_revenue
        ");

        return view('admin.reports.system-summary', compact('summary'));
    }

    // ========== CUSTOMER DETAIL REPORT ==========
    public function customerDetail(Request $request)
    {
        // Get all customers with event count and total spent for the selection UI
        $customers = Customer::withCount('events')
            ->withSum(['events as total_spent' => function ($query) {
                $query->join('billings', 'events.id', '=', 'billings.event_id');
            }], 'billings.total_amount')
            ->orderBy('customer_name')
            ->get();

        // If no customer selected, show selection page
        if (!$request->has('customer_id')) {
            return view('admin.reports.customer-detail', compact('customers'));
        }

        // Get the selected customer with all related data
        $customer = Customer::with(['user'])
            ->findOrFail($request->customer_id);

        // Get all events for this customer with full details
        $events = Event::with([
            'package',
            'billing.payments',
            'inclusions'
        ])
            ->where('customer_id', $customer->id)
            ->orderBy('event_date', 'desc')
            ->get();

        // Get all payments for this customer (across all events)
        $allPayments = Payment::whereHas('billing.event', function ($q) use ($customer) {
            $q->where('customer_id', $customer->id);
        })
            ->with('billing.event')
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate statistics
        $stats = [
            'total_events' => $events->count(),
            'total_billed' => $events->sum(fn($e) => $e->billing?->total_amount ?? 0),
            'total_paid' => $allPayments->where('status', 'approved')->sum('amount'),
            'total_balance' => 0,
        ];
        $stats['total_balance'] = $stats['total_billed'] - $stats['total_paid'];

        // Handle export requests
        if ($request->has('export')) {
            if ($request->export === 'pdf') {
                return $this->exportCustomerDetailPdf($customer, $events, $allPayments, $stats);
            }
            if ($request->export === 'csv') {
                return Excel::download(
                    new CustomerDetailExport($customer, $events, $allPayments, $stats),
                    'customer-detail-' . str_replace(' ', '-', strtolower($customer->customer_name)) . '-' . now()->format('Y-m-d') . '.csv'
                );
            }
        }

        return view('admin.reports.customer-detail', compact('customers', 'customer', 'events', 'allPayments', 'stats'));
    }

    private function exportCustomerDetailPdf($customer, $events, $allPayments, $stats)
    {
        $pdf = Pdf::loadView('admin.reports.customer-detail-pdf', [
            'customer' => $customer,
            'events' => $events,
            'allPayments' => $allPayments,
            'stats' => $stats,
        ])
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', true)
            ->setPaper('a4', 'portrait');

        return $pdf->download('customer-detail-' . str_replace(' ', '-', strtolower($customer->customer_name)) . '-' . now()->format('Y-m-d') . '.pdf');
    }
}
