<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>System Summary Report</title>
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

        .section {
            margin-bottom: 30px;
        }

        .section-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 15px;
            padding-bottom: 5px;
            border-bottom: 2px solid #333;
        }

        .stats-grid {
            display: table;
            width: 100%;
            margin-bottom: 15px;
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
            font-size: 11px;
            text-transform: uppercase;
        }

        .stat-box .value {
            font-size: 24px;
            font-weight: bold;
        }

        .stat-box-large {
            padding: 20px;
            background: #e5e7eb;
            border: 2px solid #333;
            margin-bottom: 15px;
        }

        .stat-box-large strong {
            display: block;
            margin-bottom: 8px;
            font-size: 12px;
        }

        .stat-box-large .value {
            font-size: 32px;
            font-weight: bold;
        }

        .two-col {
            display: table;
            width: 100%;
        }

        .col {
            display: table-cell;
            width: 48%;
            padding: 15px;
            background: #f3f4f6;
            border: 1px solid #ddd;
        }

        .col:first-child {
            margin-right: 4%;
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
        <p>Event Management System - System Summary Report</p>
        <p>Generated: {{ now()->format('M d, Y g:i A') }}</p>
    </div>

    {{-- Customer Statistics --}}
    <div class="section">
        <div class="section-title">Customer Statistics</div>
        <div class="two-col">
            <div class="col">
                <strong>Total Customers</strong>
                <div class="value">{{ $summary->total_customers }}</div>
            </div>
            <div class="col" style="margin-left: 4%;">
                <strong>Active Customers</strong>
                <div class="value">{{ $summary->active_customers }}</div>
            </div>
        </div>
    </div>

    {{-- Event Statistics --}}
    <div class="section">
        <div class="section-title">Event Statistics</div>

        <div class="stat-box-large" style="text-align: center;">
            <strong>Total Events</strong>
            <div class="value">{{ $summary->total_events }}</div>
        </div>

        <div class="stats-grid">
            <div class="stat-box">
                <strong>Requested</strong>
                <div class="value">{{ $summary->requested_events }}</div>
            </div>
            <div class="stat-box">
                <strong>Approved</strong>
                <div class="value">{{ $summary->approved_events }}</div>
            </div>
            <div class="stat-box">
                <strong>Scheduled</strong>
                <div class="value">{{ $summary->scheduled_events }}</div>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-box">
                <strong>Completed</strong>
                <div class="value">{{ $summary->completed_events }}</div>
            </div>
            <div class="stat-box">
                <strong>Rejected</strong>
                <div class="value">{{ $summary->rejected_events }}</div>
            </div>
        </div>
    </div>

    {{-- Financial Overview --}}
    <div class="section">
        <div class="section-title">Financial Overview</div>

        <div class="two-col">
            <div class="col">
                <strong>Total Revenue (Billings)</strong>
                <div class="value">Php {{ number_format($summary->total_revenue, 2) }}</div>
            </div>
            <div class="col" style="margin-left: 4%;">
                <strong>Collected Revenue (Paid)</strong>
                <div class="value">Php {{ number_format($summary->collected_revenue, 2) }}</div>
            </div>
        </div>

        @php
        $outstanding = $summary->total_revenue - $summary->collected_revenue;
        $collectionRate = $summary->total_revenue > 0 ? ($summary->collected_revenue / $summary->total_revenue) * 100 :
        0;
        @endphp

        <div style="margin-top: 15px;">
            <div class="two-col">
                <div class="col">
                    <strong>Outstanding Balance</strong>
                    <div class="value">Php {{ number_format($outstanding, 2) }}</div>
                </div>
                <div class="col" style="margin-left: 4%;">
                    <strong>Collection Rate</strong>
                    <div class="value">{{ number_format($collectionRate, 1) }}%</div>
                </div>
            </div>
        </div>
    </div>

    <div class="footer">
        <p>MichaelHo Events - Event Management System</p>
        <p>Contact: michaelhoevents@gmail.com | Phone: 0917 306 2531</p>
    </div>
</body>

</html>