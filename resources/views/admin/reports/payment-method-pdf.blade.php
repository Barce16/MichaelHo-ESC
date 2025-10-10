<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Revenue by Payment Method</title>
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
        }

        th {
            background: #14B8A6;
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
            background: #d1fae5;
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
        <p>Event Management System - Revenue by Payment Method</p>
        <p>Period: {{ $dateFrom->format('M d, Y') }} - {{ $dateTo->format('M d, Y') }}</p>
        <p>Generated: {{ now()->format('M d, Y g:i A') }}</p>
    </div>

    <div class="stats">
        <div class="stat-box">
            <strong>Total Payments</strong>
            {{ $stats['total_payments'] }}
        </div>
        <div class="stat-box">
            <strong>Total Amount</strong>
            Php {{ number_format($stats['total_amount'], 2) }}
        </div>
        @if($stats['most_used'])
        <div class="stat-box">
            <strong>Most Used Method</strong>
            {{ $stats['most_used']->payment_method_label }}<br>
            Php {{ number_format($stats['most_used']->total_revenue, 2) }}
        </div>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>Payment Method</th>
                <th style="text-align: center;">Count</th>
                <th style="text-align: right;">Total Revenue</th>
                <th style="text-align: right;">Percentage</th>
            </tr>
        </thead>
        <tbody>
            @foreach($paymentMethods as $method)
            @php
            $percentage = $stats['total_amount'] > 0 ? ($method->total_revenue / $stats['total_amount']) * 100 : 0;
            @endphp
            <tr>
                <td>{{ $method->payment_method_label }}</td>
                <td style="text-align: center;">{{ $method->payment_count }}</td>
                <td style="text-align: right;">Php {{ number_format($method->total_revenue, 2) }}</td>
                <td style="text-align: right;">{{ number_format($percentage, 2) }}%</td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td>TOTAL</td>
                <td style="text-align: center;">{{ $stats['total_payments'] }}</td>
                <td style="text-align: right;">Php {{ number_format($stats['total_amount'], 2) }}</td>
                <td style="text-align: right;">100%</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p>MichaelHo Events - Event Management System</p>
        <p>Contact: michaelhoevents@gmail.com | Phone: 0917 306 2531</p>
    </div>
</body>

</html>