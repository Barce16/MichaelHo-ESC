<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Updated Event Inclusions</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f7fafc;
        }

        .email-container {
            max-width: 650px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .header {
            background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
            padding: 40px 20px;
            text-align: center;
        }

        .logo {
            width: 80px;
            height: 80px;
            margin-bottom: 20px;
        }

        .header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 28px;
            font-weight: 600;
        }

        .header .subtitle {
            color: #e0f2fe;
            margin-top: 10px;
            font-size: 16px;
        }

        .content {
            padding: 40px 30px;
        }

        .alert-box {
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            border-left: 4px solid #0284c7;
            padding: 20px;
            margin-bottom: 30px;
            border-radius: 8px;
        }

        .alert-box h3 {
            margin: 0 0 10px 0;
            color: #0c4a6e;
            font-size: 18px;
        }

        .alert-box p {
            margin: 5px 0;
            color: #075985;
            font-size: 15px;
        }

        .alert-box .customer-name {
            font-weight: 700;
            color: #0369a1;
        }

        .message {
            color: #4a5568;
            line-height: 1.8;
            margin-bottom: 30px;
        }

        /* Changes Summary Section */
        .changes-summary {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border: 2px solid #0ea5e9;
            border-radius: 12px;
            padding: 25px;
            margin: 30px 0;
        }

        .changes-summary h2 {
            margin: 0 0 20px 0;
            color: #0c4a6e;
            font-size: 20px;
            text-align: center;
        }

        .summary-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-top: 20px;
        }

        .stat-box {
            background: white;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            border: 2px solid #e0f2fe;
        }

        .stat-box .number {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .stat-box.added .number {
            color: #059669;
        }

        .stat-box.removed .number {
            color: #dc2626;
        }

        .stat-box.total .number {
            color: #0284c7;
        }

        .stat-box .label {
            font-size: 12px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Added Inclusions Section */
        .changes-section {
            margin: 30px 0;
            border-radius: 12px;
            overflow: hidden;
        }

        .changes-section.added {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            border: 2px solid #10b981;
        }

        .changes-section.removed {
            background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
            border: 2px solid #ef4444;
        }

        .section-header {
            padding: 20px 25px;
            border-bottom: 2px solid currentColor;
        }

        .section-header.added {
            background: #059669;
            border-color: #047857;
            color: white;
        }

        .section-header.removed {
            background: #dc2626;
            border-color: #b91c1c;
            color: white;
        }

        .section-header h3 {
            margin: 0;
            font-size: 18px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-header .count {
            background: rgba(255, 255, 255, 0.2);
            padding: 2px 10px;
            border-radius: 12px;
            font-size: 14px;
        }

        .changes-list {
            padding: 20px 25px;
        }

        .change-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            margin: 10px 0;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .change-item .item-info {
            flex: 1;
        }

        .change-item .item-name {
            font-weight: 600;
            color: #1e293b;
            font-size: 15px;
            margin-bottom: 4px;
        }

        .change-item .item-category {
            font-size: 12px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .change-item .item-price {
            font-size: 18px;
            font-weight: 700;
            margin-left: 20px;
        }

        .changes-section.added .item-price {
            color: #059669;
        }

        .changes-section.removed .item-price {
            color: #dc2626;
        }

        .change-item .icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            margin-right: 15px;
        }

        .changes-section.added .icon {
            background: #dcfce7;
            color: #059669;
        }

        .changes-section.removed .icon {
            background: #fee2e2;
            color: #dc2626;
        }

        .empty-state {
            padding: 30px;
            text-align: center;
            color: #94a3b8;
            font-style: italic;
        }

        /* Billing Comparison */
        .billing-comparison {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin: 30px 0;
        }

        .billing-card {
            background: #f8fafc;
            border: 2px solid #e2e8f0;
            padding: 25px;
            border-radius: 12px;
            text-align: center;
        }

        .billing-card h4 {
            margin: 0 0 15px 0;
            color: #64748b;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }

        .billing-card .amount {
            font-size: 32px;
            font-weight: 700;
            color: #1e293b;
        }

        .billing-card.new {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            border-color: #10b981;
        }

        .billing-card.new h4 {
            color: #065f46;
        }

        .billing-card.new .amount {
            color: #059669;
        }

        .change-indicator {
            display: inline-block;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            margin-top: 10px;
        }

        .change-indicator.increase {
            background: #fef3c7;
            color: #92400e;
        }

        .change-indicator.decrease {
            background: #dcfce7;
            color: #166534;
        }

        /* Customer & Event Info */
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin: 30px 0;
        }

        .info-box {
            background-color: #f7fafc;
            border-left: 4px solid #0284c7;
            padding: 20px;
            border-radius: 8px;
        }

        .info-box h3 {
            margin: 0 0 15px 0;
            color: #0c4a6e;
            font-size: 16px;
            font-weight: 600;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e2e8f0;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            color: #718096;
            font-weight: 500;
            font-size: 14px;
        }

        .info-value {
            color: #2d3748;
            font-weight: 600;
            text-align: right;
            font-size: 14px;
        }

        /* Billing Summary */
        .billing-summary {
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            border: 2px solid #3b82f6;
            padding: 25px;
            margin: 30px 0;
            border-radius: 12px;
        }

        .billing-summary h3 {
            margin: 0 0 20px 0;
            color: #1e40af;
            font-size: 18px;
            text-align: center;
        }

        .billing-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            font-size: 15px;
            color: #1e3a8a;
        }

        .billing-row.total {
            border-top: 2px solid #3b82f6;
            margin-top: 10px;
            padding-top: 15px;
            font-size: 20px;
            font-weight: 700;
        }

        .button {
            display: inline-block;
            padding: 15px 40px;
            background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            margin: 20px 0;
            box-shadow: 0 4px 6px rgba(2, 132, 199, 0.3);
        }

        .footer {
            background-color: #2d3748;
            color: #cbd5e0;
            padding: 30px;
            text-align: center;
            font-size: 14px;
        }

        .divider {
            height: 1px;
            background: linear-gradient(to right, transparent, #e2e8f0, transparent);
            margin: 30px 0;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <img src="{{ asset('images/favicon.png') }}" alt="Michael Ho Events" class="logo">
            <h1>🔔 Customer Update Alert</h1>
            <div class="subtitle">Event Inclusions Modified</div>
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Alert Box -->
            <div class="alert-box">
                <h3>📢 Customer Made Changes</h3>
                <p>
                    <span class="customer-name">{{ $customer->customer_name }}</span>
                    has updated the inclusions for their event
                    <strong>{{ $event->name }}</strong>
                </p>
            </div>

            <!-- Changes Summary -->
            @if($addedInclusions->count() > 0 || $removedInclusions->count() > 0)
            <div class="changes-summary">
                <h2>📊 Summary of Changes</h2>
                <div class="summary-stats">
                    <div class="stat-box added">
                        <div class="number">{{ $addedInclusions->count() }}</div>
                        <div class="label">Added</div>
                    </div>
                    <div class="stat-box removed">
                        <div class="number">{{ $removedInclusions->count() }}</div>
                        <div class="label">Removed</div>
                    </div>
                    <div class="stat-box total">
                        <div class="number">{{ $event->inclusions->count() }}</div>
                        <div class="label">Total Now</div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Added Inclusions -->
            @if($addedInclusions->count() > 0)
            <div class="changes-section added">
                <div class="section-header added">
                    <h3>
                        ✅ Customer Added
                        <span class="count">{{ $addedInclusions->count() }} {{ $addedInclusions->count() === 1 ? 'item'
                            : 'items' }}</span>
                    </h3>
                </div>
                <div class="changes-list">
                    @foreach($addedInclusions as $inclusion)
                    <div class="change-item">
                        <div class="icon">+</div>
                        <div class="item-info">
                            <div class="item-name">{{ $inclusion->name }}</div>
                            <div class="item-category">{{ $inclusion->category }}</div>
                        </div>
                        <div class="item-price">₱{{ number_format($inclusion->price, 2) }}</div>
                    </div>
                    @endforeach

                    @if($addedInclusions->sum('price') > 0)
                    <div
                        style="text-align: right; margin-top: 15px; padding-top: 15px; border-top: 2px solid #10b981; font-size: 16px; font-weight: 600; color: #059669;">
                        Subtotal Added: ₱{{ number_format($addedInclusions->sum('price'), 2) }}
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Removed Inclusions -->
            @if($removedInclusions->count() > 0)
            <div class="changes-section removed">
                <div class="section-header removed">
                    <h3>
                        ❌ Customer Removed
                        <span class="count">{{ $removedInclusions->count() }} {{ $removedInclusions->count() === 1 ?
                            'item' : 'items' }}</span>
                    </h3>
                </div>
                <div class="changes-list">
                    @foreach($removedInclusions as $inclusion)
                    <div class="change-item">
                        <div class="icon">-</div>
                        <div class="item-info">
                            <div class="item-name">{{ $inclusion->name }}</div>
                            <div class="item-category">{{ $inclusion->category }}</div>
                        </div>
                        <div class="item-price">₱{{ number_format($inclusion->pivot->price_snapshot ??
                            $inclusion->price, 2) }}</div>
                    </div>
                    @endforeach

                    @php
                    $removedTotal = $removedInclusions->sum(function($item) {
                    return $item->pivot->price_snapshot ?? $item->price;
                    });
                    @endphp

                    @if($removedTotal > 0)
                    <div
                        style="text-align: right; margin-top: 15px; padding-top: 15px; border-top: 2px solid #ef4444; font-size: 16px; font-weight: 600; color: #dc2626;">
                        Subtotal Removed: ₱{{ number_format($removedTotal, 2) }}
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Billing Comparison -->
            @if($oldTotal != $newTotal)
            <div class="billing-comparison">
                <div class="billing-card">
                    <h4>Previous Total</h4>
                    <div class="amount">₱{{ number_format($oldTotal, 2) }}</div>
                </div>
                <div class="billing-card new">
                    <h4>New Total</h4>
                    <div class="amount">₱{{ number_format($newTotal, 2) }}</div>
                    @if($newTotal > $oldTotal)
                    <span class="change-indicator increase">+₱{{ number_format($newTotal - $oldTotal, 2) }}</span>
                    @elseif($newTotal < $oldTotal) <span class="change-indicator decrease">-₱{{ number_format($oldTotal
                        - $newTotal, 2) }}</span>
                        @endif
                </div>
            </div>
            @endif

            <div class="divider"></div>

            <!-- Customer & Event Info Grid -->
            <div class="info-grid">
                <div class="info-box">
                    <h3>👤 Customer Details</h3>
                    <div class="info-row">
                        <span class="info-label">Name:</span>
                        <span class="info-value">{{ $customer->customer_name }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Email:</span>
                        <span class="info-value">{{ $customer->user->email }}</span>
                    </div>
                    @if($customer->phone)
                    <div class="info-row">
                        <span class="info-label">Phone:</span>
                        <span class="info-value">{{ $customer->phone }}</span>
                    </div>
                    @endif
                </div>

                <div class="info-box">
                    <h3>📅 Event Details</h3>
                    <div class="info-row">
                        <span class="info-label">Event:</span>
                        <span class="info-value">{{ $event->name }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Date:</span>
                        <span class="info-value">{{ $event->event_date->format('M d, Y') }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Package:</span>
                        <span class="info-value">{{ $event->package->name }}</span>
                    </div>
                </div>
            </div>

            <!-- Updated Billing Summary -->
            @if($event->billing)
            <div class="billing-summary">
                <h3>💰 Updated Billing Summary</h3>
                @php
                $coordinationPrice = $event->package->coordination_price ?? 0;
                $eventStylingPrice = $event->package->event_styling_price ?? 0;
                @endphp
                <div class="billing-row">
                    <span>Coordination:</span>
                    <span>₱{{ number_format($coordinationPrice, 2) }}</span>
                </div>
                <div class="billing-row">
                    <span>Event Styling:</span>
                    <span>₱{{ number_format($eventStylingPrice, 2) }}</span>
                </div>
                <div class="billing-row">
                    <span>Additional Inclusions:</span>
                    <span>₱{{ number_format($event->inclusions->sum('pivot.price_snapshot'), 2) }}</span>
                </div>
                <div class="billing-row total">
                    <span>Total Amount:</span>
                    <span>₱{{ number_format($event->billing->total_amount, 2) }}</span>
                </div>
                @if($event->billing->total_paid > 0)
                <div class="billing-row" style="color: #059669;">
                    <span style="font-weight: 600;">Amount Paid:</span>
                    <span style="font-weight: 600;">₱{{ number_format($event->billing->total_paid, 2) }}</span>
                </div>
                @endif
                @if($event->billing->remaining_balance > 0)
                <div class="billing-row" style="color: #dc2626;">
                    <span style="font-weight: 600;">Remaining Balance:</span>
                    <span style="font-weight: 600;">₱{{ number_format($event->billing->remaining_balance, 2) }}</span>
                </div>
                @endif
            </div>
            @endif

            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ route('admin.events.show', $event) }}" class="button">View Event in Admin Panel</a>
            </div>

            <div class="divider"></div>

            <div class="message" style="margin-top: 30px; text-align: center;">
                <p style="font-size: 16px; color: #475569;">
                    This is an automated notification. The customer has been notified that you will review the changes.
                </p>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p style="margin: 0 0 10px 0;">Michael Ho Events - Admin Notification</p>
            <p style="margin: 0;">© {{ date('Y') }} Michael Ho Events Styling & Coordination. All rights reserved.</p>
        </div>
    </div>
</body>

</html>