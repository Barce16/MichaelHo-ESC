<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class RevenueReportExport implements FromCollection, WithHeadings, WithMapping, WithTitle, ShouldAutoSize, WithEvents
{
    protected $payments;
    protected $dateFrom;
    protected $dateTo;
    protected $totalAmount;

    public function __construct($payments, $dateFrom, $dateTo, $totalAmount)
    {
        $this->payments = $payments;
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
        $this->totalAmount = $totalAmount;
    }

    public function collection()
    {
        return $this->payments;
    }

    public function headings(): array
    {
        return [
            ['MichaelHo Events'],
            ['Event Management System - Revenue Report'],
            ['Period: ' . $this->dateFrom->format('M d, Y') . ' - ' . $this->dateTo->format('M d, Y')],
            ['Generated: ' . now()->format('M d, Y g:i A')],
            [], // Empty row
            ['Payment Date', 'Event Name', 'Customer Name', 'Payment Method', 'Amount', 'Status'],
        ];
    }

    public function map($payment): array
    {
        return [
            $payment->created_at->format('M d, Y'),
            $payment->billing->event->name ?? '-',
            $payment->billing->event->customer->customer_name ?? '-',
            ucwords(str_replace('_', ' ', $payment->payment_method)),
            number_format($payment->amount, 2),
            ucfirst($payment->status),
        ];
    }

    public function title(): string
    {
        return 'Revenue Report';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Style the header rows
                $event->sheet->getStyle('A1:F1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 16,
                    ],
                ]);

                $event->sheet->getStyle('A2:F2')->applyFromArray([
                    'font' => [
                        'size' => 12,
                    ],
                ]);

                // Style the column headers
                $event->sheet->getStyle('A6:F6')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '059669'],
                    ],
                ]);

                // Add total row
                $lastRow = $event->sheet->getHighestRow() + 1;
                $event->sheet->setCellValue('D' . $lastRow, 'TOTAL REVENUE:');
                $event->sheet->setCellValue('E' . $lastRow, number_format($this->totalAmount, 2));

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
