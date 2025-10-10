<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Customer Spending Report</title>
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
            background: #3B82F6;
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
        <p>Event Management System - Customer Spending Report</p>
        <p>Period: {{ $dateFrom->format('M d, Y') }} - {{ $dateTo->format('M d, Y') }}</p>
        <p>Generated: {{ now()->format('M d, Y g:i A') }}</p>
    </div>

    <div class="stats">
        <div class="stat-box">
            <strong>Total Customers</strong>
            {{ $stats['total_customers'] }}
        </div>
        <div class="stat-box">
            <strong>Total Revenue</strong>
            Php {{ number_format($stats['total_revenue'], 2) }}
        </div>
        <div class="stat-box">
            <strong>Avg Per Customer</strong>
            Php {{ number_format($stats['avg_per_customer'], 2) }}
        </div>
        @if($stats['top_spender'])
        <div class="stat-box">
            <strong>Top Spender</strong>
            {{ $stats['top_spender']->customer_name }}<br>
            Php {{ number_format($stats['top_spender']->total_spent, 2) }}
        </div>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>Rank</th>
                <th>Customer Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th style="text-align: center;">Events</th>
                <th style="text-align: right;">Total Spent</th>
            </tr>
        </thead>
        <tbody>
            @foreach($customers as $index => $customer)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $customer->customer_name }}</td>
                <td>{{ $customer->email }}</td>
                <td>{{ $customer->phone ?? '-' }}</td>
                <td style="text-align: center;">{{ $customer->total_events }}</td>
                <td style="text-align: right;">Php {{ number_format($customer->total_spent, 2) }}</td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="5" style="text-align: right;">TOTAL REVENUE:</td>
                <td style="text-align: right;">Php {{ number_format($stats['total_revenue'], 2) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p>MichaelHo Events - Event Management System</p>
        <p>Contact: michaelhoevents@gmail.com | Phone: 0917 306 2531</p>
    </div>
</body>

</html>