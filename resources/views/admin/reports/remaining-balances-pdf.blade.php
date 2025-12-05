<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Events with Remaining Balances</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .header p {
            margin: 5px 0;
            color: #666;
        }

        .stats {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }

        .stat-box {
            display: table-cell;
            padding: 10px;
            background: #f3f4f6;
            border: 1px solid #ddd;
            text-align: center;
            width: 25%;
        }

        .stat-box strong {
            display: block;
            margin-bottom: 5px;
            font-size: 10px;
            color: #666;
        }

        .stat-box .value {
            font-size: 14px;
            font-weight: bold;
        }

        .stat-box.red .value {
            color: #DC2626;
        }

        .stat-box.orange .value {
            color: #EA580C;
        }

        .stat-box.blue .value {
            color: #2563EB;
        }

        .stat-box.purple .value {
            color: #7C3AED;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 10px;
        }

        th {
            background: #DC2626;
            color: white;
            padding: 8px 5px;
            text-align: left;
        }

        th.right {
            text-align: right;
        }

        td {
            padding: 6px 5px;
            border-bottom: 1px solid #ddd;
        }

        td.right {
            text-align: right;
        }

        .text-green {
            color: #15803D;
        }

        .text-red {
            color: #DC2626;
        }

        .text-orange {
            color: #EA580C;
        }

        .text-gray {
            color: #9CA3AF;
        }

        .small {
            font-size: 9px;
            color: #666;
        }

        .total-row {
            font-weight: bold;
            background: #FEE2E2;
            font-size: 12px;
        }

        .subtotal-row {
            background: #F9FAFB;
            font-size: 10px;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>MichaelHo Events</h1>
        <p>Event Management System - Events with Remaining Balances</p>
        <p>Generated: {{ now()->format('M d, Y g:i A') }}</p>
    </div>

    <div class="stats">
        <div class="stat-box red">
            <strong>Events with Balance</strong>
            <div class="value">{{ $stats['total_events'] }}</div>
        </div>
        <div class="stat-box orange">
            <strong>Total Outstanding</strong>
            <div class="value">Php {{ number_format($stats['total_outstanding'], 2) }}</div>
        </div>
        <div class="stat-box blue">
            <strong>Package Balance</strong>
            <div class="value">Php {{ number_format($stats['package_outstanding'], 2) }}</div>
        </div>
        <div class="stat-box purple">
            <strong>Unpaid Expenses</strong>
            <div class="value">Php {{ number_format($stats['expenses_outstanding'], 2) }}</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Event</th>
                <th>Date</th>
                <th>Customer</th>
                <th class="right">Package</th>
                <th class="right">Expenses</th>
                <th class="right">Paid</th>
                <th class="right">Balance</th>
            </tr>
        </thead>
        <tbody>
            @foreach($events as $event)
            <tr>
                <td>
                    {{ $event->name }}
                    @if($event->unpaid_expenses_count > 0)
                    <br><span class="small text-orange">{{ $event->unpaid_expenses_count }} unpaid expense(s)</span>
                    @endif
                </td>
                <td>{{ $event->event_date->format('M d, Y') }}</td>
                <td>
                    {{ $event->customer->customer_name }}
                    <br><span class="small">{{ $event->customer->phone ?? $event->customer->email }}</span>
                </td>
                <td class="right">Php {{ number_format($event->package_total, 2) }}</td>
                <td class="right">
                    @if($event->expenses_total > 0)
                    <span class="text-orange">Php {{ number_format($event->expenses_total, 2) }}</span>
                    @else
                    <span class="text-gray">-</span>
                    @endif
                </td>
                <td class="right text-green">Php {{ number_format($event->total_paid, 2) }}</td>
                <td class="right text-red" style="font-weight: bold;">
                    Php {{ number_format($event->remaining_balance, 2) }}
                    @if($event->package_balance > 0 && $event->unpaid_expenses > 0)
                    <br><span class="small">Pkg: {{ number_format($event->package_balance, 2) }} | Exp: {{
                        number_format($event->unpaid_expenses, 2) }}</span>
                    @endif
                </td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="6" style="text-align: right;">TOTAL OUTSTANDING BALANCE:</td>
                <td class="right">Php {{ number_format($stats['total_outstanding'], 2) }}</td>
            </tr>
            @if($stats['expenses_outstanding'] > 0)
            <tr class="subtotal-row">
                <td colspan="6" style="text-align: right;">Package Balance:</td>
                <td class="right">Php {{ number_format($stats['package_outstanding'], 2) }}</td>
            </tr>
            <tr class="subtotal-row">
                <td colspan="6" style="text-align: right;">Unpaid Expenses:</td>
                <td class="right text-orange">Php {{ number_format($stats['expenses_outstanding'], 2) }}</td>
            </tr>
            @endif
        </tbody>
    </table>

    <div class="footer">
        <p>MichaelHo Events - Event Management System</p>
        <p>Contact: michaelhoevents@gmail.com | Phone: 0917 306 2531</p>
    </div>
</body>

</html>