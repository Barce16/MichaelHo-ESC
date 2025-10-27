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
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .header {
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
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

        .content {
            padding: 40px 30px;
        }

        .greeting {
            font-size: 20px;
            color: #2d3748;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .message {
            color: #4a5568;
            line-height: 1.8;
            margin-bottom: 30px;
        }

        .info-box {
            background-color: #f7fafc;
            border-left: 4px solid #8b5cf6;
            padding: 20px;
            margin: 25px 0;
            border-radius: 4px;
        }

        .info-box h3 {
            margin: 0 0 15px 0;
            color: #2d3748;
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
        }

        .info-value {
            color: #2d3748;
            font-weight: 600;
            text-align: right;
        }

        .billing-comparison {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin: 25px 0;
        }

        .billing-card {
            background: #f8fafc;
            border: 2px solid #e2e8f0;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
        }

        .billing-card h4 {
            margin: 0 0 10px 0;
            color: #718096;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .billing-card .amount {
            font-size: 24px;
            font-weight: 700;
            color: #2d3748;
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

        .inclusions-list {
            background: #faf5ff;
            border: 2px solid #e9d5ff;
            padding: 20px;
            margin: 25px 0;
            border-radius: 8px;
        }

        .inclusions-list h3 {
            margin: 0 0 15px 0;
            color: #6b21a8;
            font-size: 16px;
        }

        .inclusion-category {
            margin-bottom: 20px;
        }

        .inclusion-category:last-child {
            margin-bottom: 0;
        }

        .category-name {
            font-weight: 600;
            color: #7c3aed;
            margin-bottom: 10px;
            font-size: 14px;
        }

        .inclusion-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 12px;
            background: white;
            margin: 5px 0;
            border-radius: 4px;
            border-left: 3px solid #8b5cf6;
        }

        .inclusion-name {
            color: #4a5568;
            font-size: 14px;
        }

        .inclusion-price {
            color: #7c3aed;
            font-weight: 600;
            font-size: 14px;
        }

        .billing-summary {
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            border: 2px solid #3b82f6;
            padding: 25px;
            margin: 25px 0;
            border-radius: 8px;
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
            padding: 10px 0;
            font-size: 14px;
            color: #1e3a8a;
        }

        .billing-row.total {
            border-top: 2px solid #3b82f6;
            margin-top: 10px;
            padding-top: 15px;
            font-size: 18px;
            font-weight: 700;
        }

        .highlight-box {
            background: linear-gradient(135deg, #fff7ed 0%, #ffedd5 100%);
            border-left: 4px solid #f97316;
            padding: 20px;
            margin: 20px 0;
            border-radius: 4px;
        }

        .highlight-box h4 {
            margin: 0 0 10px 0;
            color: #9a3412;
            font-size: 16px;
        }

        .highlight-box p {
            margin: 5px 0;
            color: #7c2d12;
            font-size: 14px;
        }

        .button {
            display: inline-block;
            padding: 15px 40px;
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            margin: 20px 0;
            box-shadow: 0 4px 6px rgba(139, 92, 246, 0.3);
        }

        .footer {
            background-color: #2d3748;
            color: #cbd5e0;
            padding: 30px;
            text-align: center;
            font-size: 14px;
        }

        .footer a {
            color: #8b5cf6;
            text-decoration: none;
        }

        .divider {
            height: 1px;
            background: linear-gradient(to right, transparent, #e2e8f0, transparent);
            margin: 30px 0;
        }

        .contact-card {
            background: #f8fafc;
            border: 2px solid #e2e8f0;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }

        .contact-card h4 {
            margin: 0 0 15px 0;
            color: #2d3748;
            font-size: 16px;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 0;
            color: #4a5568;
        }

        .contact-item a {
            color: #8b5cf6;
            text-decoration: none;
            font-weight: 600;
        }

        .change-indicator {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            margin-left: 10px;
        }

        .change-indicator.increase {
            background: #fef3c7;
            color: #92400e;
        }

        .change-indicator.decrease {
            background: #dcfce7;
            color: #166534;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <img src="{{ asset('images/favicon.png') }}" alt="Michael Ho Events" class="logo">
            <h1>📝 Event Updated</h1>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">Hello {{ $customer->customer_name }}!</div>

            <div class="message">
                <p>We've updated the inclusions for your event <strong>{{ $event->name }}</strong>.</p>
                <p>Your new billing details are shown below.</p>
            </div>

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

            <div class="divider"></div>

            <!-- Current Inclusions -->
            @if($event->inclusions->count() > 0)
            <div class="inclusions-list">
                <h3>✓ Current Inclusions</h3>
                @php
                $grouped = $event->inclusions->groupBy('category');
                @endphp
                @foreach($grouped as $category => $items)
                <div class="inclusion-category">
                    <div class="category-name">{{ $category }}</div>
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
                <div class="billing-row">
                    <span>Package Base Price:</span>
                    <span>₱{{ number_format($event->package->price, 2) }}</span>
                </div>
                <div class="billing-row">
                    <span>Inclusions Total:</span>
                    <span>₱{{ number_format($event->inclusions->sum('pivot.price_snapshot'), 2) }}</span>
                </div>
                <div class="billing-row total">
                    <span>Total Amount:</span>
                    <span>₱{{ number_format($event->billing->total_amount, 2) }}</span>
                </div>
                @if($event->billing->total_paid > 0)
                <div class="billing-row">
                    <span style="color: #059669;">Amount Paid:</span>
                    <span style="color: #059669;">₱{{ number_format($event->billing->total_paid, 2) }}</span>
                </div>
                @endif
                @if($event->billing->remaining_balance > 0)
                <div class="billing-row">
                    <span style="font-weight: 600;">Remaining Balance:</span>
                    <span style="font-weight: 600;">₱{{ number_format($event->billing->remaining_balance, 2) }}</span>
                </div>
                @endif
            </div>
            @endif

            <!-- Important Note -->
            <div class="highlight-box">
                <h4>📌 Important Note</h4>
                <p>These changes have been made to better accommodate your event requirements.</p>
                <p>If you have any questions about these updates, please don't hesitate to contact us.</p>
            </div>

            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ route('customer.events.show', $event) }}" class="button">View Event Details</a>
            </div>

            <div class="divider"></div>

            <!-- Contact Information -->
            <div class="contact-card">
                <h4>📞 Questions?</h4>
                <div class="contact-item">
                    <span>📧</span>
                    <span>Email: <a href="mailto:michaelhoevents@gmail.com">michaelhoevents@gmail.com</a></span>
                </div>
                <div class="contact-item">
                    <span>📱</span>
                    <span>Phone: <a href="tel:+639173062531">+63 917 306 2531</a></span>
                </div>
            </div>

            <div class="message" style="margin-top: 30px; text-align: center;">
                <p style="font-size: 18px; color: #2d3748; font-weight: 600;">
                    Thank you for choosing Michael Ho Events!
                </p>
                <p style="color: #6b7280;">
                    We're committed to making your event perfect.
                </p>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p style="margin: 0 0 10px 0; font-size: 16px; font-style: italic;">Creating memories, one event at a time
            </p>
            <p style="margin: 0;">© {{ date('Y') }} Michael Ho Events Styling & Coordination. All rights reserved.</p>
        </div>
    </div>
</body>

</html>