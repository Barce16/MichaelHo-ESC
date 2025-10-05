<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Payment;
use App\Models\Customer;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EventsReportExport;
use App\Exports\RevenueReportExport;
use App\Exports\CustomersReportExport;

class ReportController extends Controller
{
    public function index()
    {
        return view('admin.reports.index');
    }

    public function eventsReport(Request $request)
    {
        try {
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
                    return Excel::download(new EventsReportExport($events), 'events-report-' . now()->format('Y-m-d') . '.csv');
                }
            }

            return view('admin.reports.events', compact('events', 'stats', 'dateFrom', 'dateTo'));
        } catch (\Exception $e) {
            dd($e->getMessage(), $e->getFile(), $e->getLine());
        }
    }
    private function exportEventsPdf($events, $stats, $dateFrom, $dateTo)
    {
        $pdf = Pdf::loadView('admin.reports.events-pdf', [
            'events' => $events,
            'stats' => $stats,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
        ]);

        return $pdf->download('events-report-' . now()->format('Y-m-d') . '.pdf');
    }

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
                return Excel::download(new RevenueReportExport($payments), 'revenue-report-' . now()->format('Y-m-d') . '.csv');
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
        ]);

        return $pdf->download('revenue-report-' . now()->format('Y-m-d') . '.pdf');
    }

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
                return Excel::download(new CustomersReportExport($customers), 'customers-report-' . now()->format('Y-m-d') . '.csv');
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
}
