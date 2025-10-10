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
            padding: 15px;
            background: #f3f4f6;
            border: 1px solid #ddd;
            text-align: center;
        }

        .stat-box strong {
            display: block;
            margin-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 11px;
        }

        th {
            background: #DC2626;
            color: white;
            padding: 10px;
            text-align: left;
        }

        td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }

        .total-row {
            font-weight: bold;
            background: #FEE2E2;
            font-size: 14px;
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
        <div class="stat-box">
            <strong>Events with Balance</strong>
            {{ $stats['total_events'] }}
        </div>
        <div class="stat-box">
            <strong>Total Outstanding</strong>
            Php {{ number_format($stats['total_balance'], 2) }}
        </div>
        @if($stats['largest_balance'])
        <div class="stat-box">
            <strong>Largest Balance</strong>
            {{ $stats['largest_balance']->event_name }}<br>
            Php {{ number_format($stats['largest_balance']->balance, 2) }}
        </div>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>Event</th>
                <th>Date</th>
                <th>Customer</th>
                <th>Email</th>
                <th style="text-align: right;">Total</th>
                <th style="text-align: right;">Paid</th>
                <th style="text-align: right;">Balance</th>
            </tr>
        </thead>
        <tbody>
            @foreach($events as $event)
            <tr>
                <td>{{ $event->event_name }}</td>
                <td>{{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}</td>
                <td>{{ $event->customer_name }}</td>
                <td>{{ $event->email }}</td>
                <td style="text-align: right;">Php {{ number_format($event->total_amount, 2) }}</td>
                <td style="text-align: right;">Php {{ number_format($event->paid_amount, 2) }}</td>
                <td style="text-align: right;">Php {{ number_format($event->balance, 2) }}</td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="6" style="text-align: right;">TOTAL OUTSTANDING BALANCE:</td>
                <td style="text-align: right;">Php {{ number_format($stats['total_balance'], 2) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p>MichaelHo Events - Event Management System</p>
        <p>Contact: michaelhoevents@gmail.com | Phone: 0917 306 2531</p>
    </div>
</body>

</html>