<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RevenueReportExport implements FromCollection, WithHeadings, WithMapping
{
    protected $payments;

    public function __construct($payments)
    {
        $this->payments = $payments;
    }

    public function collection()
    {
        return $this->payments;
    }

    public function headings(): array
    {
        return [
            'Payment Date',
            'Event Name',
            'Customer Name',
            'Payment Method',
            'Amount',
            'Status',
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
}
