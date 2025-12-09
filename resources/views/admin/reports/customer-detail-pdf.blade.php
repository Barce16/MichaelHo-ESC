<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Customer Detail Report - {{ $customer->customer_name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }

        .header h1 {
            margin: 0;
            font-size: 22px;
            color: #1f2937;
        }

        .header p {
            margin: 4px 0;
            color: #666;
            font-size: 11px;
        }

        .section {
            margin-bottom: 25px;
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 12px;
            padding-bottom: 5px;
            border-bottom: 2px solid #4f46e5;
        }

        .customer-info {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .customer-info table {
            width: 100%;
        }

        .customer-info td {
            padding: 5px 10px;
            vertical-align: top;
        }

        .customer-info .label {
            color: #64748b;
            font-size: 10px;
            text-transform: uppercase;
        }

        .customer-info .value {
            font-weight: bold;
            color: #1e293b;
            font-size: 12px;
        }

        .stats-grid {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }

        .stat-box {
            display: table-cell;
            padding: 12px;
            background: #f3f4f6;
            border: 1px solid #ddd;
            text-align: center;
            width: 25%;
        }

        .stat-box .label {
            font-size: 9px;
            text-transform: uppercase;
            color: #6b7280;
            margin-bottom: 4px;
        }

        .stat-box .value {
            font-size: 16px;
            font-weight: bold;
            color: #1f2937;
        }

        .stat-box.green .value {
            color: #059669;
        }

        .stat-box.red .value {
            color: #dc2626;
        }

        .stat-box.amber .value {
            color: #d97706;
        }

        .event-card {
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            margin-bottom: 20px;
            page-break-inside: avoid;
        }

        .event-header {
            background: linear-gradient(to right, #f8fafc, #f1f5f9);
            padding: 12px 15px;
            border-bottom: 1px solid #e5e7eb;
        }

        .event-header h4 {
            margin: 0 0 5px 0;
            font-size: 13px;
            color: #1f2937;
        }

        .event-header .meta {
            font-size: 10px;
            color: #6b7280;
        }

        .event-body {
            padding: 15px;
        }

        .two-column {
            display: table;
            width: 100%;
        }

        .column {
            display: table-cell;
            width: 48%;
            vertical-align: top;
            padding-right: 15px;
        }

        .column:last-child {
            padding-right: 0;
            padding-left: 15px;
        }

        .sub-section-title {
            font-size: 11px;
            font-weight: bold;
            color: #374151;
            margin-bottom: 8px;
            padding-bottom: 3px;
            border-bottom: 1px solid #e5e7eb;
        }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }

        table.data-table th {
            background: #4f46e5;
            color: white;
            padding: 8px;
            text-align: left;
            font-size: 9px;
            text-transform: uppercase;
        }

        table.data-table td {
            padding: 6px 8px;
            border-bottom: 1px solid #e5e7eb;
        }

        table.data-table tr:nth-child(even) {
            background: #f9fafb;
        }

        table.simple-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }

        table.simple-table td {
            padding: 4px 0;
            border-bottom: 1px solid #e5e7eb;
        }

        table.simple-table td:last-child {
            text-align: right;
            font-weight: 500;
        }

        table.simple-table tr.total {
            background: #fef3c7;
            font-weight: bold;
        }

        table.simple-table tr.total td {
            padding: 6px 0;
        }

        .status-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-completed {
            background: #d1fae5;
            color: #065f46;
        }

        .status-scheduled {
            background: #dbeafe;
            color: #1e40af;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-rejected {
            background: #fee2e2;
            color: #991b1b;
        }

        .status-approved {
            background: #d1fae5;
            color: #065f46;
        }

        .payment-summary {
            margin-top: 10px;
            text-align: right;
            font-size: 10px;
        }

        .payment-summary span {
            margin-left: 15px;
        }

        .total-row {
            font-weight: bold;
            background: #d1fae5;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 9px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
            padding-top: 10px;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .font-bold {
            font-weight: bold;
        }

        .text-green {
            color: #059669;
        }

        .text-red {
            color: #dc2626;
        }

        .page-break {
            page-break-before: always;
        }
    </style>
</head>

<body>
    {{-- Header --}}
    <div class="header">
        <h1>MichaelHo Events</h1>
        <p>Event Management System</p>
        <p style="font-size: 14px; font-weight: bold; margin-top: 10px;">Customer Detail Report</p>
        <p>Generated: {{ now()->format('M d, Y g:i A') }}</p>
    </div>

    {{-- Customer Information --}}
    <div class="section">
        <div class="section-title">Customer Information</div>
        <div class="customer-info">
            <table>
                <tr>
                    <td style="width: 50%;">
                        <div class="label">Full Name</div>
                        <div class="value">{{ $customer->customer_name }}</div>
                    </td>
                    <td style="width: 50%;">
                        <div class="label">Email Address</div>
                        <div class="value">{{ $customer->email }}</div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="label">Phone Number</div>
                        <div class="value">{{ $customer->phone ?? $customer->phone ?? 'N/A' }}</div>
                    </td>
                    <td>
                        <div class="label">Address</div>
                        <div class="value">{{ $customer->address ?? 'N/A' }}</div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="label">Customer Since</div>
                        <div class="value">{{ $customer->created_at->format('M d, Y') }}</div>
                    </td>
                    <td>
                        <div class="label">Account Status</div>
                        <div class="value">
                            @if($customer->user)
                            {{ ucfirst($customer->user->status) }}
                            @else
                            No Account
                            @endif
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    {{-- Financial Summary --}}
    <div class="section">
        <div class="section-title">Financial Summary</div>
        <div class="stats-grid">
            <div class="stat-box">
                <div class="label">Total Events</div>
                <div class="value">{{ $stats['total_events'] }}</div>
            </div>
            <div class="stat-box amber">
                <div class="label">Total Billed</div>
                <div class="value">Php {{ number_format($stats['total_billed'], 2) }}</div>
            </div>
            <div class="stat-box green">
                <div class="label">Total Paid</div>
                <div class="value">Php {{ number_format($stats['total_paid'], 2) }}</div>
            </div>
            <div class="stat-box red">
                <div class="label">Outstanding Balance</div>
                <div class="value">Php {{ number_format($stats['total_balance'], 2) }}</div>
            </div>
        </div>
    </div>

    {{-- Events Breakdown --}}
    <div class="section">
        <div class="section-title">Events Breakdown</div>

        @forelse($events as $index => $event)
        @if($index > 0 && $index % 2 == 0)
        <div class="page-break"></div>
        @endif

        <div class="event-card">
            <div class="event-header">
                <table style="width: 100%;">
                    <tr>
                        <td style="width: 70%;">
                            <h4>{{ $event->name }}</h4>
                            <div class="meta">
                                📅 {{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}
                                &nbsp;|&nbsp;
                                📍 {{ $event->venue ?? 'TBD' }}
                                @if($event->package)
                                &nbsp;|&nbsp;
                                📦 {{ $event->package->name }}
                                @endif
                            </div>
                        </td>
                        <td style="width: 30%; text-align: right;">
                            <span
                                class="status-badge status-{{ $event->status === 'completed' ? 'completed' : ($event->status === 'scheduled' ? 'scheduled' : (in_array($event->status, ['rejected', 'cancelled']) ? 'rejected' : 'pending')) }}">
                                {{ ucwords(str_replace('_', ' ', $event->status)) }}
                            </span>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="event-body">
                <div class="two-column">
                    {{-- Inclusions Column --}}
                    <div class="column">
                        <div class="sub-section-title">Inclusions ({{ $event->inclusions->count() }})</div>
                        @if($event->inclusions->count() > 0)
                        <table class="simple-table">
                            @foreach($event->inclusions as $inclusion)
                            <tr>
                                <td>{{ $inclusion->name }}</td>
                                <td>Php {{ number_format($inclusion->pivot->price_snapshot ?? $inclusion->price, 2) }}
                                </td>
                            </tr>
                            @endforeach
                        </table>
                        @else
                        <p style="color: #9ca3af; font-style: italic;">No inclusions recorded</p>
                        @endif
                    </div>

                    {{-- Billing Column --}}
                    <div class="column">
                        <div class="sub-section-title">Billing Breakdown</div>
                        @if($event->billing)
                        @php
                        $inclTotal = $event->inclusions->sum(fn($i) => $i->pivot->price_snapshot ?? $i->price);
                        $coordPrice = $event->package->coordination_price ?? 25000;
                        $stylingPrice = $event->package->event_styling_price ?? 55000;
                        @endphp
                        <table class="simple-table">
                            <tr>
                                <td>Coordination Fee</td>
                                <td>Php {{ number_format($coordPrice, 2) }}</td>
                            </tr>
                            <tr>
                                <td>Event Styling Fee</td>
                                <td>Php {{ number_format($stylingPrice, 2) }}</td>
                            </tr>
                            <tr>
                                <td>Inclusions Total</td>
                                <td>Php {{ number_format($inclTotal, 2) }}</td>
                            </tr>
                            <tr class="total">
                                <td><strong>Grand Total</strong></td>
                                <td><strong>Php {{ number_format($event->billing->total_amount, 2) }}</strong></td>
                            </tr>
                        </table>
                        @else
                        <p style="color: #9ca3af; font-style: italic;">No billing information</p>
                        @endif
                    </div>
                </div>

                {{-- Payments for this event --}}
                @if($event->billing && $event->billing->payments->count() > 0)
                <div style="margin-top: 15px;">
                    <div class="sub-section-title">Payment History</div>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Method</th>
                                <th style="text-align: right;">Amount</th>
                                <th style="text-align: center;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($event->billing->payments as $payment)
                            <tr>
                                <td>{{ $payment->created_at->format('M d, Y') }}</td>
                                <td style="text-transform: capitalize;">{{ str_replace('_', ' ', $payment->payment_type)
                                    }}</td>
                                <td style="text-transform: capitalize;">{{ str_replace('_', ' ',
                                    $payment->payment_method) }}</td>
                                <td style="text-align: right;">Php {{ number_format($payment->amount, 2) }}</td>
                                <td style="text-align: center;">
                                    <span class="status-badge status-{{ $payment->status }}">{{
                                        ucfirst($payment->status) }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @php
                    $eventPaid = $event->billing->payments->where('status', 'approved')->sum('amount');
                    $eventBalance = $event->billing->total_amount - $eventPaid;
                    @endphp
                    <div class="payment-summary">
                        <span><strong>Paid:</strong> <span class="text-green">Php {{ number_format($eventPaid, 2)
                                }}</span></span>
                        <span><strong>Balance:</strong> <span
                                class="{{ $eventBalance > 0 ? 'text-red' : 'text-green' }}">Php {{
                                number_format($eventBalance, 2) }}</span></span>
                    </div>
                </div>
                @endif
            </div>
        </div>
        @empty
        <p style="text-align: center; color: #9ca3af; padding: 30px;">This customer has no events yet.</p>
        @endforelse
    </div>

    {{-- Complete Payment History --}}
    @if($allPayments->count() > 0)
    <div class="page-break"></div>
    <div class="section">
        <div class="section-title">Complete Payment History</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Event</th>
                    <th>Type</th>
                    <th>Method</th>
                    <th style="text-align: right;">Amount</th>
                    <th style="text-align: center;">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($allPayments as $payment)
                <tr>
                    <td>{{ $payment->created_at->format('M d, Y') }}</td>
                    <td>{{ $payment->billing->event->name ?? '-' }}</td>
                    <td style="text-transform: capitalize;">{{ str_replace('_', ' ', $payment->payment_type) }}</td>
                    <td style="text-transform: capitalize;">{{ str_replace('_', ' ', $payment->payment_method) }}</td>
                    <td style="text-align: right;">Php {{ number_format($payment->amount, 2) }}</td>
                    <td style="text-align: center;">
                        <span class="status-badge status-{{ $payment->status }}">{{ ucfirst($payment->status) }}</span>
                    </td>
                </tr>
                @endforeach
                <tr class="total-row">
                    <td colspan="4" style="text-align: right; padding: 8px;"><strong>Total Paid (Approved):</strong>
                    </td>
                    <td style="text-align: right; padding: 8px;"><strong>Php {{ number_format($stats['total_paid'], 2)
                            }}</strong></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>
    @endif

    {{-- Footer --}}
    <div class="footer">
        <p>MichaelHo Events - Event Management System</p>
        <p>Contact: michaelhoevents@gmail.com | Phone: 0917 306 2531</p>
    </div>
</body>

</html>