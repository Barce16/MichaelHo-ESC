<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Event Detail Report - {{ $event->name }}</title>
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
            border-bottom: 2px solid #7c3aed;
        }

        .info-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 15px;
        }

        .info-box table {
            width: 100%;
        }

        .info-box td {
            padding: 5px 10px;
            vertical-align: top;
        }

        .info-box .label {
            color: #64748b;
            font-size: 10px;
            text-transform: uppercase;
        }

        .info-box .value {
            font-weight: bold;
            color: #1e293b;
            font-size: 12px;
        }

        .event-title {
            font-size: 18px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 10px;
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
            font-size: 14px;
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

        .stat-box.blue .value {
            color: #2563eb;
        }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
            margin-bottom: 15px;
        }

        table.data-table th {
            background: #7c3aed;
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
            padding: 6px 0;
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
            padding: 8px 0;
        }

        .status-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 10px;
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

        .status-ongoing {
            background: #e0e7ff;
            color: #3730a3;
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

        .timeline {
            margin-left: 20px;
        }

        .timeline-item {
            position: relative;
            padding-left: 25px;
            padding-bottom: 15px;
            border-left: 2px solid #e5e7eb;
        }

        .timeline-item:last-child {
            border-left: 2px solid transparent;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -6px;
            top: 0;
            width: 10px;
            height: 10px;
            background: #7c3aed;
            border-radius: 50%;
        }

        .timeline-date {
            font-size: 9px;
            color: #6b7280;
            margin-bottom: 3px;
        }

        .timeline-title {
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 3px;
        }

        .timeline-content {
            color: #4b5563;
            font-size: 10px;
        }

        .feedback-box {
            background: #fef3c7;
            border: 1px solid #fcd34d;
            border-radius: 6px;
            padding: 15px;
        }

        .stars {
            color: #f59e0b;
            font-size: 14px;
            margin-bottom: 8px;
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

        .total-row {
            font-weight: bold;
            background: #d1fae5;
        }
    </style>
</head>

<body>
    {{-- Header --}}
    <div class="header">
        <h1>MichaelHo Events</h1>
        <p>Event Management System</p>
        <p style="font-size: 14px; font-weight: bold; margin-top: 10px;">Event Detail Report</p>
        <p>Generated: {{ now()->format('M d, Y g:i A') }}</p>
    </div>

    {{-- Event Overview --}}
    <div class="section">
        <div class="section-title">Event Overview</div>
        <div class="info-box">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                <span class="event-title">{{ $event->name }}</span>
                <span
                    class="status-badge status-{{ $event->status === 'completed' ? 'completed' : ($event->status === 'scheduled' ? 'scheduled' : (in_array($event->status, ['rejected', 'cancelled']) ? 'rejected' : ($event->status === 'ongoing' ? 'ongoing' : 'pending'))) }}">
                    {{ ucwords(str_replace('_', ' ', $event->status)) }}
                </span>
            </div>
            <table>
                <tr>
                    <td style="width: 33%;">
                        <div class="label">Event Date</div>
                        <div class="value">{{ \Carbon\Carbon::parse($event->event_date)->format('F d, Y') }}</div>
                    </td>
                    <td style="width: 33%;">
                        <div class="label">Venue</div>
                        <div class="value">{{ $event->venue ?? 'TBD' }}</div>
                    </td>
                    <td style="width: 33%;">
                        <div class="label">Theme</div>
                        <div class="value">{{ $event->theme ?? 'N/A' }}</div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="label">Package</div>
                        <div class="value">{{ $event->package->name ?? 'N/A' }}</div>
                    </td>
                    <td>
                        <div class="label">Expected Guests</div>
                        <div class="value">{{ $event->guests ?? 'N/A' }}</div>
                    </td>
                    <td>
                        <div class="label">Booked On</div>
                        <div class="value">{{ $event->created_at->format('M d, Y') }}</div>
                    </td>
                </tr>
            </table>
            @if($event->notes)
            <div style="margin-top: 10px; padding-top: 10px; border-top: 1px solid #e5e7eb;">
                <div class="label">Notes</div>
                <div style="color: #4b5563;">{{ $event->notes }}</div>
            </div>
            @endif
        </div>
    </div>

    {{-- Customer Information --}}
    <div class="section">
        <div class="section-title">Customer Information</div>
        <div class="info-box">
            <table>
                <tr>
                    <td style="width: 50%;">
                        <div class="label">Customer Name</div>
                        <div class="value">{{ $event->customer->customer_name ?? 'N/A' }}</div>
                    </td>
                    <td style="width: 50%;">
                        <div class="label">Email Address</div>
                        <div class="value">{{ $event->customer->email ?? 'N/A' }}</div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="label">Phone Number</div>
                        <div class="value">{{ $event->customer->phone ?? $event->customer->contact_number ?? 'N/A' }}
                        </div>
                    </td>
                    <td>
                        <div class="label">Address</div>
                        <div class="value">{{ $event->customer->address ?? 'N/A' }}</div>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    {{-- Financial Summary --}}
    <div class="section">
        <div class="section-title">Financial Summary</div>
        <div class="stats-grid">
            <div class="stat-box amber">
                <div class="label">Total Amount</div>
                <div class="value">Php {{ number_format($stats['total_amount'], 2) }}</div>
            </div>
            <div class="stat-box green">
                <div class="label">Total Paid</div>
                <div class="value">Php {{ number_format($stats['total_paid'], 2) }}</div>
            </div>
            <div class="stat-box red">
                <div class="label">Remaining Balance</div>
                <div class="value">Php {{ number_format($stats['remaining_balance'], 2) }}</div>
            </div>
            <div class="stat-box blue">
                <div class="label">Payment Progress</div>
                <div class="value">{{ $stats['payment_percentage'] }}%</div>
            </div>
        </div>
    </div>

    {{-- Billing Breakdown --}}
    <div class="section">
        <div class="section-title">Billing Breakdown</div>
        <div class="two-column">
            <div class="column">
                <h4 style="font-size: 11px; margin-bottom: 8px;">Inclusions ({{ $event->inclusions->count() }})</h4>
                @if($event->inclusions->count() > 0)
                <table class="simple-table">
                    @foreach($event->inclusions as $inclusion)
                    <tr>
                        <td>{{ $inclusion->name }}</td>
                        <td>Php {{ number_format($inclusion->pivot->price_snapshot ?? $inclusion->price, 2) }}</td>
                    </tr>
                    @endforeach
                </table>
                @else
                <p style="color: #9ca3af; font-style: italic;">No inclusions recorded</p>
                @endif
            </div>
            <div class="column">
                <h4 style="font-size: 11px; margin-bottom: 8px;">Cost Summary</h4>
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
                        <td>Inclusions Subtotal</td>
                        <td>Php {{ number_format($inclTotal, 2) }}</td>
                    </tr>
                    <tr class="total">
                        <td><strong>Grand Total</strong></td>
                        <td><strong>Php {{ number_format($event->billing->total_amount, 2) }}</strong></td>
                    </tr>
                </table>
                <div style="margin-top: 10px; font-size: 10px;">
                    <div>
                        Introductory: Php {{ number_format($event->billing->intro_amount ?? 5000, 2) }}
                        @if($event->billing->intro_paid) <span class="text-green">✓ Paid</span> @endif
                    </div>
                    <div>
                        Downpayment: Php {{ number_format($event->billing->downpayment_amount ?? 0, 2) }}
                        @if($event->billing->downpayment_paid) <span class="text-green">✓ Paid</span> @endif
                    </div>
                </div>
                @else
                <p style="color: #9ca3af; font-style: italic;">No billing information</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Payment History --}}
    <div class="page-break"></div>
    <div class="section">
        <div class="section-title">Payment History</div>
        @if($payments->count() > 0)
        <table class="data-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Method</th>
                    <th>Reference</th>
                    <th style="text-align: right;">Amount</th>
                    <th style="text-align: center;">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($payments as $payment)
                <tr>
                    <td>{{ $payment->created_at->format('M d, Y') }}</td>
                    <td style="text-transform: capitalize;">{{ str_replace('_', ' ', $payment->payment_type) }}</td>
                    <td style="text-transform: capitalize;">{{ str_replace('_', ' ', $payment->payment_method) }}</td>
                    <td style="font-family: monospace; font-size: 9px;">{{ $payment->reference_number ?? '-' }}</td>
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
        @else
        <p style="text-align: center; color: #9ca3af; padding: 20px;">No payments recorded yet.</p>
        @endif
    </div>

    {{-- Event Progress --}}
    @if($progressUpdates->count() > 0)
    <div class="section">
        <div class="section-title">Event Progress & Updates</div>
        <div class="timeline">
            @foreach($progressUpdates as $progress)
            <div class="timeline-item">
                <div class="timeline-date">{{ $progress->created_at->format('M d, Y h:i A') }}</div>
                <div class="timeline-title">{{ $progress->status }}</div>
                <div class="timeline-content">{{ $progress->details }}</div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Staff Assignments --}}
    @if($staffAssignments->count() > 0)
    <div class="section">
        <div class="section-title">Staff Assignments</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Staff Member</th>
                    <th>Role</th>
                    <th style="text-align: right;">Pay Rate</th>
                    <th style="text-align: center;">Work Status</th>
                    <th style="text-align: center;">Pay Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($staffAssignments as $assignment)
                <tr>
                    <td>{{ $assignment->user->name ?? 'N/A' }}</td>
                    <td style="text-transform: capitalize;">{{ $assignment->pivot->assignment_role ?? 'Staff' }}</td>
                    <td style="text-align: right;">Php {{ number_format($assignment->pivot->pay_rate ?? 0, 2) }}</td>
                    <td style="text-align: center;">
                        <span
                            class="status-badge status-{{ $assignment->pivot->work_status === 'finished' ? 'completed' : ($assignment->pivot->work_status === 'ongoing' ? 'ongoing' : 'pending') }}">
                            {{ ucfirst($assignment->pivot->work_status ?? 'Assigned') }}
                        </span>
                    </td>
                    <td style="text-align: center;">
                        <span
                            class="status-badge status-{{ $assignment->pivot->pay_status === 'paid' ? 'completed' : ($assignment->pivot->pay_status === 'approved' ? 'scheduled' : 'pending') }}">
                            {{ ucfirst($assignment->pivot->pay_status ?? 'Pending') }}
                        </span>
                    </td>
                </tr>
                @endforeach
                <tr style="background: #fed7aa;">
                    <td colspan="2" style="text-align: right; padding: 8px;"><strong>Total Payroll:</strong></td>
                    <td style="text-align: right; padding: 8px;"><strong>Php {{
                            number_format($staffAssignments->sum(fn($s) => $s->pivot->pay_rate ?? 0), 2) }}</strong>
                    </td>
                    <td colspan="2"></td>
                </tr>
            </tbody>
        </table>
    </div>
    @endif

    {{-- Feedback --}}
    @if($event->feedback)
    <div class="section">
        <div class="section-title">Customer Feedback</div>
        <div class="feedback-box">
            <div class="stars">
                @for($i = 1; $i <= 5; $i++) {{ $i <=$event->feedback->rating ? '★' : '☆' }}
                    @endfor
                    <span style="color: #1f2937; font-size: 11px; margin-left: 5px;">{{ $event->feedback->rating
                        }}/5</span>
            </div>
            @if($event->feedback->comment)
            <p style="font-style: italic; color: #4b5563; margin: 10px 0;">"{{ $event->feedback->comment }}"</p>
            @endif
            <p style="font-size: 9px; color: #6b7280;">Submitted on {{ $event->feedback->created_at->format('M d, Y') }}
            </p>
        </div>
    </div>
    @endif

    {{-- Footer --}}
    <div class="footer">
        <p>MichaelHo Events - Event Management System</p>
        <p>Contact: michaelhoevents@gmail.com | Phone: 0917 306 2531</p>
    </div>
</body>

</html>