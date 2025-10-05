<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CustomersReportExport implements FromCollection, WithHeadings, WithMapping
{
    protected $customers;

    public function __construct($customers)
    {
        $this->customers = $customers;
    }

    public function collection()
    {
        return $this->customers;
    }

    public function headings(): array
    {
        return [
            'Customer Name',
            'Email',
            'Phone',
            'Total Events',
            'Total Spent',
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
}
