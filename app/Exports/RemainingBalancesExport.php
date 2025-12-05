<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RemainingBalancesExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected $events;
    protected $stats;

    public function __construct($events, $stats)
    {
        $this->events = $events;
        $this->stats = $stats;
    }

    public function collection()
    {
        return $this->events;
    }

    public function headings(): array
    {
        return [
            'Event Name',
            'Event Date',
            'Customer Name',
            'Email',
            'Phone',
            'Package Total',
            'Expenses Total',
            'Total Paid',
            'Package Balance',
            'Unpaid Expenses',
            'Total Balance',
        ];
    }

    public function map($event): array
    {
        return [
            $event->name,
            $event->event_date->format('M d, Y'),
            $event->customer->customer_name,
            $event->customer->email,
            $event->customer->phone ?? '',
            $event->package_total,
            $event->expenses_total,
            $event->total_paid,
            $event->package_balance,
            $event->unpaid_expenses,
            $event->remaining_balance,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Get last row
        $lastRow = $this->events->count() + 1;

        // Add summary rows
        $summaryRow = $lastRow + 2;
        $sheet->setCellValue('J' . $summaryRow, 'TOTAL OUTSTANDING:');
        $sheet->setCellValue('K' . $summaryRow, $this->stats['total_outstanding']);

        $sheet->setCellValue('J' . ($summaryRow + 1), 'Package Balance:');
        $sheet->setCellValue('K' . ($summaryRow + 1), $this->stats['package_outstanding']);

        $sheet->setCellValue('J' . ($summaryRow + 2), 'Unpaid Expenses:');
        $sheet->setCellValue('K' . ($summaryRow + 2), $this->stats['expenses_outstanding']);

        return [
            1 => ['font' => ['bold' => true], 'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'DC2626']
            ], 'font' => ['color' => ['rgb' => 'FFFFFF'], 'bold' => true]],
            $summaryRow => ['font' => ['bold' => true]],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 30,
            'B' => 15,
            'C' => 25,
            'D' => 25,
            'E' => 15,
            'F' => 15,
            'G' => 15,
            'H' => 15,
            'I' => 15,
            'J' => 15,
            'K' => 15,
        ];
    }
}
