<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class EventStatusExport implements FromCollection, WithHeadings, WithMapping, WithTitle, ShouldAutoSize, WithEvents
{
    protected $statusSummary;
    protected $dateFrom;
    protected $dateTo;
    protected $totalEvents;

    public function __construct($statusSummary, $dateFrom, $dateTo, $totalEvents)
    {
        $this->statusSummary = $statusSummary;
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
        $this->totalEvents = $totalEvents;
    }

    public function collection()
    {
        return $this->statusSummary;
    }

    public function headings(): array
    {
        return [
            ['MichaelHo Events'],
            ['Event Management System - Event Status Summary'],
            ['Period: ' . $this->dateFrom->format('M d, Y') . ' - ' . $this->dateTo->format('M d, Y')],
            ['Generated: ' . now()->format('M d, Y g:i A')],
            [],
            ['Status', 'Event Count', 'Percentage'],
        ];
    }

    public function map($status): array
    {
        $percentage = $this->totalEvents > 0 ? ($status->event_count / $this->totalEvents) * 100 : 0;

        return [
            $status->status_label,
            $status->event_count,
            number_format($percentage, 2) . '%',
        ];
    }

    public function title(): string
    {
        return 'Event Status';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Style the header rows
                $event->sheet->getStyle('A1:C1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 16,
                    ],
                ]);

                $event->sheet->getStyle('A2:C2')->applyFromArray([
                    'font' => [
                        'size' => 12,
                    ],
                ]);

                // Style the column headers
                $event->sheet->getStyle('A6:C6')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'EAB308'],
                    ],
                ]);

                // Add total row
                $lastRow = $event->sheet->getHighestRow() + 1;
                $event->sheet->setCellValue('A' . $lastRow, 'TOTAL');
                $event->sheet->setCellValue('B' . $lastRow, $this->totalEvents);
                $event->sheet->setCellValue('C' . $lastRow, '100%');

                $event->sheet->getStyle('A' . $lastRow . ':C' . $lastRow)->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'FEF3C7'],
                    ],
                ]);
            },
        ];
    }
}
