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

        .section-title.purple {
            border-bottom-color: #9333ea;
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

        .stat-box.purple .value {
            color: #9333ea;
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

        table.data-table.purple th {
            background: #9333ea;
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

        table.simple-table tr.purple-total {
            background: #f3e8ff;
            font-weight: bold;
        }

        table.simple-table tr.purple-total td {
            padding: 8px 0;
            color: #7c3aed;
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

        .status-unpaid {
            background: #fef3c7;
            color: #92400e;
        }

        .status-paid {
            background: #d1fae5;
            color: #065f46;
        }

        .status-expense {
            background: #fef3c7;
            color: #d97706;
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
            margin-top: 10px;
        }

        .timeline-item {
            padding: 10px 15px;
            border-left: 3px solid #7c3aed;
            margin-bottom: 10px;
            background: #f8fafc;
        }

        .timeline-date {
            font-size: 9px;
            color: #6b7280;
        }

        .timeline-title {
            font-weight: bold;
            color: #1f2937;
            margin: 3px 0;
        }

        .timeline-content {
            font-size: 10px;
            color: #4b5563;
        }

        .feedback-box {
            background: #fefce8;
            border: 1px solid #fde047;
            border-radius: 6px;
            padding: 15px;
        }

        .stars {
            color: #fbbf24;
            font-size: 16px;
        }

        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px solid #333;
            text-align: center;
            font-size: 10px;
            color: #666;
        }

        .page-break {
            page-break-before: always;
        }

        .total-row {
            background: #d1fae5 !important;
        }

        .text-green {
            color: #059669;
        }

        .text-red {
            color: #dc2626;
        }

        .text-purple {
            color: #9333ea;
        }

        .expense-row-total {
            background: #f3e8ff !important;
        }

        .expense-row-unpaid {
            background: #fef3c7 !important;
        }

        .expense-row-paid {
            background: #d1fae5 !important;
        }

        .progress-bar-container {
            background: #e5e7eb;
            border-radius: 4px;
            height: 12px;
            margin: 10px 0;
            overflow: hidden;
        }

        .progress-bar {
            background: linear-gradient(to right, #10b981, #059669);
            height: 100%;
            border-radius: 4px;
        }

        .progress-info {
            display: table;
            width: 100%;
            font-size: 10px;
            color: #6b7280;
        }

        .progress-info span {
            display: table-cell;
        }

        .progress-info span:last-child {
            text-align: right;
        }

        .category-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 9px;
            background: #f3e8ff;
            color: #7c3aed;
            text-transform: capitalize;
        }
    </style>
</head>

<body>
    {{-- Header --}}
    <div class="header">
        <h1>{{ $event->name }}</h1>
        <p>Event ID: #{{ str_pad($event->id, 6, '0', STR_PAD_LEFT) }}</p>
        <p>Generated: {{ now()->format('F d, Y h:i A') }}</p>
        @php
        $statusColors = [
        'requested' => '#f59e0b',
        'approved' => '#10b981',
        'request_meeting' => '#f97316',
        'meeting' => '#3b82f6',
        'scheduled' => '#6366f1',
        'ongoing' => '#14b8a6',
        'completed' => '#22c55e',
        'rejected' => '#f43f5e',
        'cancelled' => '#6b7280',
        ];
        @endphp
        <p style="margin-top: 8px;">
            <span
                style="background: {{ $statusColors[$event->status] ?? '#6b7280' }}; color: white; padding: 4px 12px; border-radius: 12px; font-size: 10px; font-weight: bold;">
                {{ strtoupper(str_replace('_', ' ', $event->status)) }}
            </span>
        </p>
    </div>

    {{-- Event & Customer Info --}}
    <div class="section">
        <div class="section-title">Event & Customer Information</div>
        <div class="info-box">
            <table>
                <tr>
                    <td width="50%">
                        <div class="label">Event Date</div>
                        <div class="value">{{ $event->event_date->format('F d, Y') }}</div>
                    </td>
                    <td width="50%">
                        <div class="label">Customer Name</div>
                        <div class="value">{{ $event->customer->customer_name ?? 'N/A' }}</div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="label">Venue</div>
                        <div class="value">{{ $event->venue ?? 'TBD' }}</div>
                    </td>
                    <td>
                        <div class="label">Email</div>
                        <div class="value">{{ $event->customer->email ?? 'N/A' }}</div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="label">Theme</div>
                        <div class="value">{{ $event->theme ?? 'N/A' }}</div>
                    </td>
                    <td>
                        <div class="label">Phone Number</div>
                        <div class="value">{{ $event->customer->phone ?? $event->customer->contact_number ?? 'N/A' }}
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="label">Package</div>
                        <div class="value">{{ $event->package->name ?? 'N/A' }}</div>
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
                <div class="label">Package Total</div>
                <div class="value">Php {{ number_format($stats['package_total'] ?? $stats['total_amount'], 2) }}</div>
            </div>
            <div class="stat-box purple">
                <div class="label">Expenses</div>
                <div class="value">Php {{ number_format($stats['expenses_total'] ?? 0, 2) }}</div>
                @if(($stats['unpaid_expenses_count'] ?? 0) > 0)
                <div style="font-size: 8px; color: #9333ea;">{{ $stats['unpaid_expenses_count'] }} unpaid</div>
                @endif
            </div>
            <div class="stat-box green">
                <div class="label">Total Paid</div>
                <div class="value">Php {{ number_format($stats['total_paid'], 2) }}</div>
            </div>
            <div class="stat-box red">
                <div class="label">Balance Due</div>
                <div class="value">Php {{ number_format($stats['remaining_balance'], 2) }}</div>
            </div>
        </div>

        {{-- Payment Progress Bar --}}
        <div style="margin-bottom: 15px;">
            <div class="progress-info">
                <span>Payment Progress: {{ $stats['payment_percentage'] }}%</span>
                <span>Php {{ number_format($stats['total_paid'], 2) }} / Php {{ number_format($stats['grand_total'] ??
                    ($stats['package_total'] + ($stats['expenses_total'] ?? 0)), 2) }}</span>
            </div>
            <div class="progress-bar-container">
                <div class="progress-bar" style="width: {{ min($stats['payment_percentage'], 100) }}%;"></div>
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
                $packageTotal = $coordPrice + $stylingPrice + $inclTotal;
                $expensesTotal = $stats['expenses_total'] ?? 0;
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
                        <td><strong>Package Total</strong></td>
                        <td><strong>Php {{ number_format($packageTotal, 2) }}</strong></td>
                    </tr>
                    @if($expensesTotal > 0)
                    <tr class="purple-total">
                        <td><strong>+ Additional Expenses</strong></td>
                        <td><strong>Php {{ number_format($expensesTotal, 2) }}</strong></td>
                    </tr>
                    <tr style="background: #e5e7eb;">
                        <td><strong>Grand Total</strong></td>
                        <td><strong>Php {{ number_format($packageTotal + $expensesTotal, 2) }}</strong></td>
                    </tr>
                    @endif
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
                    <td>
                        @if($payment->payment_type === 'expense')
                        <span class="status-badge status-expense">Expense</span>
                        @else
                        <span style="text-transform: capitalize;">{{ str_replace('_', ' ', $payment->payment_type)
                            }}</span>
                        @endif
                    </td>
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

    {{-- Additional Expenses Section --}}
    @if($expenses->count() > 0)
    <div class="section">
        <div class="section-title purple">Additional Expenses ({{ $expenses->count() }})</div>
        <table class="data-table purple">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Category</th>
                    <th>Date</th>
                    <th style="text-align: right;">Amount</th>
                    <th style="text-align: center;">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($expenses as $expense)
                <tr>
                    <td>
                        {{ $expense->description }}
                        @if($expense->notes)
                        <div style="font-size: 8px; color: #6b7280;">{{ Str::limit($expense->notes, 40) }}</div>
                        @endif
                    </td>
                    <td>
                        <span class="category-badge">{{ str_replace('_', ' ', $expense->category) }}</span>
                    </td>
                    <td>{{ $expense->expense_date->format('M d, Y') }}</td>
                    <td style="text-align: right;">Php {{ number_format($expense->amount, 2) }}</td>
                    <td style="text-align: center;">
                        @if($expense->isPaid())
                        <span class="status-badge status-paid">Paid</span>
                        @else
                        <span class="status-badge status-unpaid">Unpaid</span>
                        @endif
                    </td>
                </tr>
                @endforeach
                <tr class="expense-row-total">
                    <td colspan="3" style="text-align: right; padding: 8px;"><strong>Total Expenses:</strong></td>
                    <td style="text-align: right; padding: 8px; color: #7c3aed;"><strong>Php {{
                            number_format($stats['expenses_total'] ?? $expenses->sum('amount'), 2) }}</strong></td>
                    <td></td>
                </tr>
                @if(($stats['unpaid_expenses'] ?? 0) > 0)
                <tr class="expense-row-unpaid">
                    <td colspan="3" style="text-align: right; padding: 6px; font-size: 10px;">Unpaid:</td>
                    <td style="text-align: right; padding: 6px; font-size: 10px; color: #d97706;">Php {{
                        number_format($stats['unpaid_expenses'], 2) }}</td>
                    <td></td>
                </tr>
                @endif
                @if(($stats['expenses_paid'] ?? 0) > 0)
                <tr class="expense-row-paid">
                    <td colspan="3" style="text-align: right; padding: 6px; font-size: 10px;">Paid:</td>
                    <td style="text-align: right; padding: 6px; font-size: 10px; color: #059669;">Php {{
                        number_format($stats['expenses_paid'], 2) }}</td>
                    <td></td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
    @endif

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