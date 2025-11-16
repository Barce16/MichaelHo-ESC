<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Inclusions Updated</title>
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
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
            padding: 30px 20px;
            text-align: center;
        }

        .logo {
            width: 60px;
            height: 60px;
            margin-bottom: 15px;
        }

        .header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }

        .content {
            padding: 30px 25px;
        }

        .greeting {
            font-size: 18px;
            color: #2d3748;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .message {
            color: #4a5568;
            line-height: 1.6;
            margin-bottom: 20px;
            font-size: 14px;
        }

        /* Compact Changes Summary */
        .changes-summary {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border: 2px solid #0ea5e9;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
        }

        .changes-summary h2 {
            margin: 0 0 15px 0;
            color: #0c4a6e;
            font-size: 16px;
            text-align: center;
        }

        .summary-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
        }

        .stat-box {
            background: white;
            padding: 12px;
            border-radius: 8px;
            text-align: center;
            border: 2px solid #e0f2fe;
        }

        .stat-box .number {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 3px;
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
            font-size: 11px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Compact Changes Section */
        .changes-section {
            margin: 20px 0;
            border-radius: 10px;
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
            padding: 15px 20px;
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
            font-size: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .section-header .count {
            background: rgba(255, 255, 255, 0.2);
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 12px;
        }

        .changes-list {
            padding: 15px 20px;
        }

        .change-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 12px;
            margin: 8px 0;
            background: white;
            border-radius: 6px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .change-item .item-info {
            flex: 1;
        }

        .change-item .item-name {
            font-weight: 600;
            color: #1e293b;
            font-size: 13px;
            margin-bottom: 2px;
        }

        .change-item .item-category {
            font-size: 11px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .change-item .item-price {
            font-size: 15px;
            font-weight: 700;
            margin-left: 15px;
        }

        .changes-section.added .item-price {
            color: #059669;
        }

        .changes-section.removed .item-price {
            color: #dc2626;
        }

        .change-item .icon {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            margin-right: 12px;
        }

        .changes-section.added .icon {
            background: #dcfce7;
            color: #059669;
        }

        .changes-section.removed .icon {
            background: #fee2e2;
            color: #dc2626;
        }

        /* Compact Billing Comparison */
        .billing-comparison {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin: 20px 0;
        }

        .billing-card {
            background: #f8fafc;
            border: 2px solid #e2e8f0;
            padding: 18px;
            border-radius: 10px;
            text-align: center;
        }

        .billing-card h4 {
            margin: 0 0 10px 0;
            color: #64748b;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }

        .billing-card .amount {
            font-size: 26px;
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
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 11px;
            font-weight: 600;
            margin-top: 8px;
        }

        .change-indicator.increase {
            background: #fef3c7;
            color: #92400e;
        }

        .change-indicator.decrease {
            background: #dcfce7;
            color: #166534;
        }

        /* Compact Info Sections */
        .info-box {
            background-color: #f7fafc;
            border-left: 3px solid #8b5cf6;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }

        .info-box h3 {
            margin: 0 0 12px 0;
            color: #2d3748;
            font-size: 14px;
            font-weight: 600;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 6px 0;
            font-size: 13px;
        }

        .info-label {
            color: #718096;
            font-weight: 500;
        }

        .info-value {
            color: #2d3748;
            font-weight: 600;
            text-align: right;
        }

        /* Collapsible Current Inclusions */
        .current-inclusions {
            background: #faf5ff;
            border: 2px solid #e9d5ff;
            padding: 15px;
            margin: 20px 0;
            border-radius: 10px;
        }

        .current-inclusions h3 {
            margin: 0 0 12px 0;
            color: #6b21a8;
            font-size: 14px;
            text-align: center;
        }

        .inclusion-category {
            margin-bottom: 15px;
        }

        .category-name {
            font-weight: 600;
            color: #7c3aed;
            margin-bottom: 8px;
            font-size: 13px;
            padding-bottom: 6px;
            border-bottom: 2px solid #e9d5ff;
        }

        .inclusion-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 12px;
            background: white;
            margin: 6px 0;
            border-radius: 5px;
            border-left: 3px solid #8b5cf6;
        }

        .inclusion-name {
            color: #4a5568;
            font-size: 12px;
        }

        .inclusion-price {
            color: #7c3aed;
            font-weight: 600;
            font-size: 12px;
        }

        /* Compact Billing Summary */
        .billing-summary {
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            border: 2px solid #3b82f6;
            padding: 18px;
            margin: 20px 0;
            border-radius: 10px;
        }

        .billing-summary h3 {
            margin: 0 0 12px 0;
            color: #1e40af;
            font-size: 14px;
            text-align: center;
        }

        .billing-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            font-size: 13px;
            color: #1e3a8a;
        }

        .billing-row.total {
            border-top: 2px solid #3b82f6;
            margin-top: 8px;
            padding-top: 10px;
            font-size: 16px;
            font-weight: 700;
        }

        .highlight-box {
            background: linear-gradient(135deg, #fff7ed 0%, #ffedd5 100%);
            border-left: 3px solid #f97316;
            padding: 15px;
            margin: 15px 0;
            border-radius: 4px;
        }

        .highlight-box h4 {
            margin: 0 0 8px 0;
            color: #9a3412;
            font-size: 13px;
        }

        .highlight-box p {
            margin: 4px 0;
            color: #7c2d12;
            font-size: 12px;
            line-height: 1.5;
        }

        .button {
            display: inline-block;
            padding: 12px 30px;
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            margin: 15px 0;
            box-shadow: 0 4px 6px rgba(139, 92, 246, 0.3);
        }

        .contact-card {
            background: #f8fafc;
            border: 2px solid #e2e8f0;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
        }

        .contact-card h4 {
            margin: 0 0 10px 0;
            color: #2d3748;
            font-size: 13px;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 5px 0;
            color: #4a5568;
            font-size: 12px;
        }

        .contact-item a {
            color: #8b5cf6;
            text-decoration: none;
            font-weight: 600;
        }

        .footer {
            background-color: #2d3748;
            color: #cbd5e0;
            padding: 20px;
            text-align: center;
            font-size: 12px;
        }

        .divider {
            height: 1px;
            background: linear-gradient(to right, transparent, #e2e8f0, transparent);
            margin: 20px 0;
        }

        .subtotal-row {
            text-align: right;
            margin-top: 10px;
            padding-top: 10px;
            font-size: 13px;
            font-weight: 600;
        }

        .changes-section.added .subtotal-row {
            border-top: 2px solid #10b981;
            color: #059669;
        }

        .changes-section.removed .subtotal-row {
            border-top: 2px solid #ef4444;
            color: #dc2626;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <img src="{{ asset('images/favicon.png') }}" alt="Michael Ho Events" class="logo">
            <h1>📝 Inclusions Updated</h1>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">
                Hello {{ strtolower($customer->gender ?? '') === 'male' ? 'Mr.' : (strtolower($customer->gender ?? '')
                === 'female' ? 'Ms.' : 'Mr./Mrs.') }}
                {{ $customer->customer_name }}!
            </div>

            <div class="message">
                <p>We've updated inclusions for <strong>{{ $event->name }}</strong> on {{ $event->event_date->format('M
                    d, Y') }}.</p>
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
                        ✅ Added Inclusions
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
                    <div class="subtotal-row">
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
                        ❌ Removed Inclusions
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
                    <div class="subtotal-row">
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

            <!-- Event Details -->
            <div class="info-box">
                <h3>📅 Event Details</h3>
                <div class="info-row">
                    <span class="info-label">Event Name:</span>
                    <span class="info-value">{{ $event->name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Date:</span>
                    <span class="info-value">{{ $event->event_date->format('F d, Y') }}</span>
                </div>
                @if($event->venue)
                <div class="info-row">
                    <span class="info-label">Venue:</span>
                    <span class="info-value">{{ $event->venue }}</span>
                </div>
                @endif
                <div class="info-row">
                    <span class="info-label">Package:</span>
                    <span class="info-value">{{ $event->package->name }}</span>
                </div>
            </div>

            <!-- Current Inclusions (All) -->
            @if($event->inclusions->count() > 0)
            <div class="current-inclusions">
                <h3>✓ All Current Inclusions ({{ $event->inclusions->count() }} items)</h3>
                @php
                $grouped = $event->inclusions->groupBy('category');
                @endphp
                @foreach($grouped as $category => $items)
                <div class="inclusion-category">
                    <div class="category-name">{{ $category }} ({{ $items->count() }})</div>
                    @foreach($items as $inclusion)
                    <div class="inclusion-item">
                        <span class="inclusion-name">{{ $inclusion->name }}</span>
                        @if($inclusion->pivot->price_snapshot > 0)
                        <span class="inclusion-price">₱{{ number_format($inclusion->pivot->price_snapshot, 2) }}</span>
                        @endif
                    </div>
                    @endforeach
                </div>
                @endforeach
            </div>
            @endif

            <!-- Updated Billing Summary -->
            @if($event->billing)
            <div class="billing-summary">
                <h3>💰 Updated Billing Summary</h3>
                @php
                $coordinationPrice = $event->package->coordination_price ?? 0;
                $eventStylingPrice = $event->package->event_styling_price ?? 0;
                $packageBasePrice = $coordinationPrice + $eventStylingPrice;
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

            <div class="highlight-box">
                <h4>📌 Note</h4>
                <p>Billing updated to reflect changes. Contact us with any questions.</p>
            </div>

            <div style="text-align: center; margin: 20px 0;">
                <a href="{{ route('customer.events.show', $event) }}" class="button">View Complete Event Details</a>
            </div>

            <div class="divider"></div>

            <!-- Contact Information -->
            <div class="contact-card">
                <h4>📞 Contact Us</h4>
                <div class="contact-item">
                    <span>📧 <a href="mailto:michaelhoevents@gmail.com">michaelhoevents@gmail.com</a></span>
                </div>
                <div class="contact-item">
                    <span>📱 <a href="tel:+639173062531">+63 917 306 2531</a></span>
                </div>
            </div>

            <div class="message" style="margin-top: 15px; text-align: center;">
                <p style="font-size: 14px; color: #2d3748; font-weight: 600; margin: 0;">
                    Thank you for choosing Michael Ho Events!
                </p>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p style="margin: 0;">© {{ date('Y') }} Michael Ho Events. All rights reserved.</p>
        </div>
    </div>
</body>

</html>