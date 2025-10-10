<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class PaymentMethodExport implements FromCollection, WithHeadings, WithMapping, WithTitle, ShouldAutoSize, WithEvents
{
    protected $paymentMethods;
    protected $dateFrom;
    protected $dateTo;
    protected $totalAmount;

    public function __construct($paymentMethods, $dateFrom, $dateTo, $totalAmount)
    {
        $this->paymentMethods = $paymentMethods;
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
        $this->totalAmount = $totalAmount;
    }

    public function collection()
    {
        return $this->paymentMethods;
    }

    public function headings(): array
    {
        return [
            ['MichaelHo Events'],
            ['Event Management System - Revenue by Payment Method'],
            ['Period: ' . $this->dateFrom->format('M d, Y') . ' - ' . $this->dateTo->format('M d, Y')],
            ['Generated: ' . now()->format('M d, Y g:i A')],
            [],
            ['Payment Method', 'Payment Count', 'Total Revenue', 'Percentage'],
        ];
    }

    public function map($method): array
    {
        $percentage = $this->totalAmount > 0 ? ($method->total_revenue / $this->totalAmount) * 100 : 0;

        return [
            $method->payment_method_label,
            $method->payment_count,
            number_format($method->total_revenue, 2),
            number_format($percentage, 2) . '%',
        ];
    }

    public function title(): string
    {
        return 'Payment Methods';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Style the header rows
                $event->sheet->getStyle('A1:D1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 16,
                    ],
                ]);

                $event->sheet->getStyle('A2:D2')->applyFromArray([
                    'font' => [
                        'size' => 12,
                    ],
                ]);

                // Style the column headers
                $event->sheet->getStyle('A6:D6')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '14B8A6'],
                    ],
                ]);

                // Add total row
                $lastRow = $event->sheet->getHighestRow() + 1;
                $event->sheet->setCellValue('A' . $lastRow, 'TOTAL');
                $event->sheet->setCellValue('B' . $lastRow, $this->paymentMethods->sum('payment_count'));
                $event->sheet->setCellValue('C' . $lastRow, number_format($this->totalAmount, 2));
                $event->sheet->setCellValue('D' . $lastRow, '100%');

                $event->sheet->getStyle('A' . $lastRow . ':D' . $lastRow)->applyFromArray([
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
