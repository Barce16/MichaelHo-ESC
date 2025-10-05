<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class EventsReportExport implements FromCollection, WithHeadings, WithMapping, WithTitle, ShouldAutoSize, WithEvents
{
    protected $events;
    protected $dateFrom;
    protected $dateTo;
    protected $totalRevenue;

    public function __construct($events, $dateFrom, $dateTo, $totalRevenue)
    {
        $this->events = $events;
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
        $this->totalRevenue = $totalRevenue;
    }

    public function collection()
    {
        return $this->events;
    }

    public function headings(): array
    {
        return [
            ['MichaelHo Events'],
            ['Event Management System - Events Report'],
            ['Period: ' . $this->dateFrom->format('M d, Y') . ' - ' . $this->dateTo->format('M d, Y')],
            ['Generated: ' . now()->format('M d, Y g:i A')],
            [], // Empty row
            ['Event Date', 'Event Name', 'Customer Name', 'Customer Email', 'Package', 'Status', 'Total Amount'],
        ];
    }

    public function map($event): array
    {
        return [
            \Carbon\Carbon::parse($event->event_date)->format('M d, Y'),
            $event->name,
            $event->customer->customer_name,
            $event->customer->email,
            $event->package->name ?? '-',
            ucfirst($event->status),
            number_format($event->billing->total_amount ?? 0, 2),
        ];
    }

    public function title(): string
    {
        return 'Events Report';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Style the header rows
                $event->sheet->getStyle('A1:G1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 16,
                    ],
                ]);

                $event->sheet->getStyle('A2:G2')->applyFromArray([
                    'font' => [
                        'size' => 12,
                    ],
                ]);

                // Style the column headers
                $event->sheet->getStyle('A6:G6')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '4F46E5'],
                    ],
                ]);

                // Add total row
                $lastRow = $event->sheet->getHighestRow() + 1;
                $event->sheet->setCellValue('F' . $lastRow, 'TOTAL:');
                $event->sheet->setCellValue('G' . $lastRow, number_format($this->totalRevenue, 2));

                $event->sheet->getStyle('F' . $lastRow . ':G' . $lastRow)->applyFromArray([
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
