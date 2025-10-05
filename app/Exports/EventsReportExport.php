<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class EventsReportExport implements FromCollection, WithHeadings, WithMapping
{
    protected $events;

    public function __construct($events)
    {
        $this->events = $events;
    }

    public function collection()
    {
        return $this->events;
    }

    public function headings(): array
    {
        return [
            'Event Date',
            'Event Name',
            'Customer Name',
            'Customer Email',
            'Package',
            'Status',
            'Total Amount',
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
}
