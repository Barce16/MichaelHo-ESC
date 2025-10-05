<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class CustomersReportExport implements FromCollection, WithHeadings, WithMapping, WithTitle, ShouldAutoSize, WithEvents
{
    protected $customers;
    protected $dateFrom;
    protected $dateTo;
    protected $totalRevenue;

    public function __construct($customers, $dateFrom, $dateTo, $totalRevenue)
    {
        $this->customers = $customers;
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
        $this->totalRevenue = $totalRevenue;
    }

    public function collection()
    {
        return $this->customers;
    }

    public function headings(): array
    {
        return [
            ['MichaelHo Events'],
            ['Event Management System - Customers Report'],
            ['Period: ' . $this->dateFrom->format('M d, Y') . ' - ' . $this->dateTo->format('M d, Y')],
            ['Generated: ' . now()->format('M d, Y g:i A')],
            [], // Empty row
            ['Customer Name', 'Email', 'Phone', 'Total Events', 'Total Spent'],
        ];
    }

    public function map($customer): array
    {
        $totalSpent = $customer->events->sum(fn($e) => $e->billing?->total_amount ?? 0);

        return [
            $customer->customer_name,
            $customer->email,
            $customer->phone ?? '-',
            $customer->events_count,
            number_format($totalSpent, 2),
        ];
    }

    public function title(): string
    {
        return 'Customers Report';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Style the header rows
                $event->sheet->getStyle('A1:E1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 16,
                    ],
                ]);

                $event->sheet->getStyle('A2:E2')->applyFromArray([
                    'font' => [
                        'size' => 12,
                    ],
                ]);

                // Style the column headers
                $event->sheet->getStyle('A6:E6')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '9333EA'],
                    ],
                ]);

                // Add total row
                $lastRow = $event->sheet->getHighestRow() + 1;
                $event->sheet->setCellValue('D' . $lastRow, 'TOTAL REVENUE:');
                $event->sheet->setCellValue('E' . $lastRow, number_format($this->totalRevenue, 2));

                $event->sheet->getStyle('D' . $lastRow . ':E' . $lastRow)->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'D1FAE5'],
                    ],
                ]);
            },
        ];
    }
}
