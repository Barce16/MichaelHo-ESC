<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class RemainingBalancesExport implements FromCollection, WithHeadings, WithMapping, WithTitle, ShouldAutoSize, WithEvents
{
    protected $events;
    protected $totalBalance;

    public function __construct($events, $totalBalance)
    {
        $this->events = $events;
        $this->totalBalance = $totalBalance;
    }

    public function collection()
    {
        return $this->events;
    }

    public function headings(): array
    {
        return [
            ['MichaelHo Events'],
            ['Event Management System - Events with Remaining Balances'],
            ['Generated: ' . now()->format('M d, Y g:i A')],
            [],
            ['Event Name', 'Event Date', 'Customer Name', 'Email', 'Phone', 'Total Amount', 'Paid Amount', 'Balance'],
        ];
    }

    public function map($event): array
    {
        return [
            $event->event_name,
            \Carbon\Carbon::parse($event->event_date)->format('M d, Y'),
            $event->customer_name,
            $event->email,
            $event->phone ?? '-',
            number_format($event->total_amount, 2),
            number_format($event->paid_amount, 2),
            number_format($event->balance, 2),
        ];
    }

    public function title(): string
    {
        return 'Remaining Balances';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Style the header rows
                $event->sheet->getStyle('A1:H1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 16,
                    ],
                ]);

                $event->sheet->getStyle('A2:H2')->applyFromArray([
                    'font' => [
                        'size' => 12,
                    ],
                ]);

                // Style the column headers
                $event->sheet->getStyle('A5:H5')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'DC2626'],
                    ],
                ]);

                // Add total row
                $lastRow = $event->sheet->getHighestRow() + 1;
                $event->sheet->setCellValue('G' . $lastRow, 'TOTAL BALANCE:');
                $event->sheet->setCellValue('H' . $lastRow, number_format($this->totalBalance, 2));

                $event->sheet->getStyle('G' . $lastRow . ':H' . $lastRow)->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'FEE2E2'],
                    ],
                ]);
            },
        ];
    }
}
