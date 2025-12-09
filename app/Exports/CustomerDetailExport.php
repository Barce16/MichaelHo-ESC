<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Support\Collection;

class CustomerDetailExport implements WithMultipleSheets
{
    protected $customer;
    protected $events;
    protected $allPayments;
    protected $stats;

    public function __construct($customer, $events, $allPayments, $stats)
    {
        $this->customer = $customer;
        $this->events = $events;
        $this->allPayments = $allPayments;
        $this->stats = $stats;
    }

    public function sheets(): array
    {
        return [
            new CustomerSummarySheet($this->customer, $this->stats),
            new CustomerEventsSheet($this->customer, $this->events),
            new CustomerPaymentsSheet($this->customer, $this->allPayments, $this->stats),
        ];
    }
}

// ========== SUMMARY SHEET ==========
class CustomerSummarySheet implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, WithEvents
{
    protected $customer;
    protected $stats;

    public function __construct($customer, $stats)
    {
        $this->customer = $customer;
        $this->stats = $stats;
    }

    public function collection()
    {
        return new Collection([
            ['Customer Name', $this->customer->customer_name],
            ['Email', $this->customer->email],
            ['Phone', $this->customer->phone ?? $this->customer->phone ?? 'N/A'],
            ['Address', $this->customer->address ?? 'N/A'],
            ['Customer Since', $this->customer->created_at->format('M d, Y')],
            ['Account Status', $this->customer->user ? ucfirst($this->customer->user->status) : 'No Account'],
            ['', ''],
            ['FINANCIAL SUMMARY', ''],
            ['Total Events', $this->stats['total_events']],
            ['Total Billed', 'Php ' . number_format($this->stats['total_billed'], 2)],
            ['Total Paid', 'Php ' . number_format($this->stats['total_paid'], 2)],
            ['Outstanding Balance', 'Php ' . number_format($this->stats['total_balance'], 2)],
        ]);
    }

    public function headings(): array
    {
        return [
            ['MichaelHo Events'],
            ['Customer Detail Report'],
            ['Generated: ' . now()->format('M d, Y g:i A')],
            [],
            ['CUSTOMER INFORMATION', ''],
        ];
    }

    public function title(): string
    {
        return 'Summary';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getStyle('A1:B1')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 16],
                ]);
                $event->sheet->getStyle('A5:B5')->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '4F46E5'],
                    ],
                    'font' => ['color' => ['rgb' => 'FFFFFF'], 'bold' => true],
                ]);
                // Financial Summary header
                $event->sheet->getStyle('A13:B13')->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '10B981'],
                    ],
                    'font' => ['color' => ['rgb' => 'FFFFFF'], 'bold' => true],
                ]);
            },
        ];
    }
}

// ========== EVENTS SHEET ==========
class CustomerEventsSheet implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, WithEvents
{
    protected $customer;
    protected $events;

    public function __construct($customer, $events)
    {
        $this->customer = $customer;
        $this->events = $events;
    }

    public function collection()
    {
        $rows = new Collection();

        foreach ($this->events as $event) {
            $inclTotal = $event->inclusions->sum(fn($i) => $i->pivot->price_snapshot ?? $i->price);
            $coordPrice = $event->package->coordination_price ?? 25000;
            $stylingPrice = $event->package->event_styling_price ?? 55000;
            $totalAmount = $event->billing?->total_amount ?? 0;
            $paidAmount = $event->billing?->payments->where('status', 'approved')->sum('amount') ?? 0;
            $balance = $totalAmount - $paidAmount;

            $rows->push([
                $event->name,
                \Carbon\Carbon::parse($event->event_date)->format('M d, Y'),
                $event->venue ?? 'TBD',
                $event->package?->name ?? '-',
                ucwords(str_replace('_', ' ', $event->status)),
                $event->inclusions->count(),
                'Php ' . number_format($inclTotal, 2),
                'Php ' . number_format($coordPrice, 2),
                'Php ' . number_format($stylingPrice, 2),
                'Php ' . number_format($totalAmount, 2),
                'Php ' . number_format($paidAmount, 2),
                'Php ' . number_format($balance, 2),
            ]);

            // Add inclusions as sub-rows
            if ($event->inclusions->count() > 0) {
                foreach ($event->inclusions as $inclusion) {
                    $rows->push([
                        '  └ ' . $inclusion->name,
                        '',
                        '',
                        $inclusion->category?->value ?? '',
                        '',
                        '',
                        'Php ' . number_format($inclusion->pivot->price_snapshot ?? $inclusion->price, 2),
                        '',
                        '',
                        '',
                        '',
                        '',
                    ]);
                }
            }

            // Empty row between events
            $rows->push(['', '', '', '', '', '', '', '', '', '', '', '']);
        }

        return $rows;
    }

    public function headings(): array
    {
        return [
            ['MichaelHo Events - Events Breakdown'],
            ['Customer: ' . $this->customer->customer_name],
            ['Generated: ' . now()->format('M d, Y g:i A')],
            [],
            [
                'Event Name',
                'Date',
                'Venue',
                'Package',
                'Status',
                'Inclusions',
                'Inclusions Total',
                'Coordination Fee',
                'Styling Fee',
                'Grand Total',
                'Paid',
                'Balance'
            ],
        ];
    }

    public function title(): string
    {
        return 'Events';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getStyle('A1:L1')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 14],
                ]);
                $event->sheet->getStyle('A5:L5')->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '7C3AED'],
                    ],
                ]);
            },
        ];
    }
}

// ========== PAYMENTS SHEET ==========
class CustomerPaymentsSheet implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, WithEvents
{
    protected $customer;
    protected $allPayments;
    protected $stats;

    public function __construct($customer, $allPayments, $stats)
    {
        $this->customer = $customer;
        $this->allPayments = $allPayments;
        $this->stats = $stats;
    }

    public function collection()
    {
        return $this->allPayments->map(function ($payment) {
            return [
                $payment->created_at->format('M d, Y'),
                $payment->billing->event->name ?? '-',
                ucwords(str_replace('_', ' ', $payment->payment_type)),
                ucwords(str_replace('_', ' ', $payment->payment_method)),
                'Php ' . number_format($payment->amount, 2),
                ucfirst($payment->status),
                $payment->reference_number ?? '-',
            ];
        });
    }

    public function headings(): array
    {
        return [
            ['MichaelHo Events - Payment History'],
            ['Customer: ' . $this->customer->customer_name],
            ['Total Paid (Approved): Php ' . number_format($this->stats['total_paid'], 2)],
            ['Generated: ' . now()->format('M d, Y g:i A')],
            [],
            ['Date', 'Event', 'Type', 'Method', 'Amount', 'Status', 'Reference'],
        ];
    }

    public function title(): string
    {
        return 'Payments';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getStyle('A1:G1')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 14],
                ]);
                $event->sheet->getStyle('A3:G3')->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'D1FAE5'],
                    ],
                ]);
                $event->sheet->getStyle('A6:G6')->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '059669'],
                    ],
                ]);
            },
        ];
    }
}
