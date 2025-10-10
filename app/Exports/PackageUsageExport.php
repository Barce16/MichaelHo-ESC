<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class PackageUsageExport implements FromCollection, WithHeadings, WithMapping, WithTitle, ShouldAutoSize, WithEvents
{
    protected $packages;
    protected $dateFrom;
    protected $dateTo;
    protected $totalRevenue;

    public function __construct($packages, $dateFrom, $dateTo, $totalRevenue)
    {
        $this->packages = $packages;
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
        $this->totalRevenue = $totalRevenue;
    }

    public function collection()
    {
        return $this->packages;
    }

    public function headings(): array
    {
        return [
            ['MichaelHo Events'],
            ['Event Management System - Package Usage Report'],
            ['Period: ' . $this->dateFrom->format('M d, Y') . ' - ' . $this->dateTo->format('M d, Y')],
            ['Generated: ' . now()->format('M d, Y g:i A')],
            [],
            ['Rank', 'Package Name', 'Package Type', 'Package Price', 'Total Events', 'Total Revenue'],
        ];
    }

    public function map($package): array
    {
        static $rank = 0;
        $rank++;

        return [
            $rank,
            $package->name,
            $package->type ?? '-',
            number_format($package->price, 2),
            $package->total_events,
            number_format($package->total_revenue, 2),
        ];
    }

    public function title(): string
    {
        return 'Package Usage';
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
                        'startColor' => ['rgb' => 'F97316'],
                    ],
                ]);

                // Add total row
                $lastRow = $event->sheet->getHighestRow() + 1;
                $event->sheet->setCellValue('E' . $lastRow, 'TOTAL REVENUE:');
                $event->sheet->setCellValue('F' . $lastRow, number_format($this->totalRevenue, 2));

                $event->sheet->getStyle('E' . $lastRow . ':F' . $lastRow)->applyFromArray([
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
