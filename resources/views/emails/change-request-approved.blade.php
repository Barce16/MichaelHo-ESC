<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Request Approved</title>
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
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
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

        .success-icon {
            width: 64px;
            height: 64px;
            margin: 0 auto 20px;
            background-color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
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
            background-color: #f0fdf4;
            border-left: 4px solid #10b981;
            padding: 20px;
            margin: 25px 0;
            border-radius: 4px;
        }

        .info-box h3 {
            margin: 0 0 15px 0;
            color: #065f46;
            font-size: 16px;
            font-weight: 600;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #d1fae5;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            color: #047857;
            font-weight: 500;
        }

        .info-value {
            color: #065f46;
            font-weight: 600;
            text-align: right;
        }

        .changes-section {
            margin: 25px 0;
        }

        .added-section {
            background-color: #f0fdf4;
            border-left: 4px solid #10b981;
            padding: 20px;
            margin: 15px 0;
            border-radius: 4px;
        }

        .added-section h4 {
            margin: 0 0 15px 0;
            color: #065f46;
            font-size: 16px;
            font-weight: 600;
        }

        .removed-section {
            background-color: #fef2f2;
            border-left: 4px solid #ef4444;
            padding: 20px;
            margin: 15px 0;
            border-radius: 4px;
        }

        .removed-section h4 {
            margin: 0 0 15px 0;
            color: #991b1b;
            font-size: 16px;
            font-weight: 600;
        }

        .item-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .item-list li {
            padding: 10px 0;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
        }

        .item-list li:last-child {
            border-bottom: none;
        }

        .total-box {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 20px;
            border-radius: 8px;
            margin: 25px 0;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin: 10px 0;
            padding: 10px 0;
        }

        .total-row.main {
            border-top: 2px solid rgba(255, 255, 255, 0.3);
            font-size: 20px;
            font-weight: bold;
        }

        .button {
            display: inline-block;
            padding: 15px 40px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            margin: 20px 0;
            box-shadow: 0 4px 6px rgba(102, 126, 234, 0.3);
        }

        .button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(102, 126, 234, 0.4);
        }

        .footer {
            background-color: #2d3748;
            color: #cbd5e0;
            padding: 30px;
            text-align: center;
            font-size: 14px;
        }

        .footer a {
            color: #10b981;
            text-decoration: none;
        }

        .divider {
            height: 1px;
            background: linear-gradient(to right, transparent, #e2e8f0, transparent);
            margin: 30px 0;
        }

        .admin-note {
            background-color: #eff6ff;
            border-left: 4px solid #3b82f6;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }

        .admin-note h4 {
            margin: 0 0 10px 0;
            color: #1e40af;
            font-size: 14px;
            font-weight: 600;
        }

        .admin-note p {
            margin: 0;
            color: #1e40af;
            line-height: 1.6;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <div class="success-icon">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="3">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
            </div>
            <h1>Request Approved!</h1>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">
                Good news, {{ $customer->customer_name }}!
            </div>

            <div class="message">
                <p>Your inclusion change request for <strong>{{ $event->name }}</strong> has been approved and the
                    changes have been applied to your event.</p>
            </div>

            <!-- Event Details -->
            <div class="info-box">
                <h3>Event Details</h3>
                <div class="info-row">
                    <span class="info-label">Event Name:</span>
                    <span class="info-value">{{ $event->name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Event Date:</span>
                    <span class="info-value">{{ \Carbon\Carbon::parse($event->event_date)->format('F d, Y') }}</span>
                </div>
                @if($event->venue)
                <div class="info-row">
                    <span class="info-label">Venue:</span>
                    <span class="info-value">{{ $event->venue }}</span>
                </div>
                @endif
            </div>

            <!-- Changes Summary -->
            @if(count($addedInclusions) > 0 || count($removedInclusions) > 0)
            <div class="changes-section">
                <h3 style="color: #2d3748; margin-bottom: 15px;">What Changed:</h3>

                @if(count($addedInclusions) > 0)
                <div class="added-section">
                    <h4>✅ Added Inclusions ({{ count($addedInclusions) }})</h4>
                    <ul class="item-list">
                        @foreach($addedInclusions as $item)
                        <li>
                            <span>{{ $item['name'] }}</span>
                            <strong style="color: #065f46;">+₱{{ number_format($item['price'], 2) }}</strong>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @if(count($removedInclusions) > 0)
                <div class="removed-section">
                    <h4>❌ Removed Inclusions ({{ count($removedInclusions) }})</h4>
                    <ul class="item-list">
                        @foreach($removedInclusions as $item)
                        <li>
                            <span>{{ $item['name'] }}</span>
                            <strong style="color: #991b1b;">-₱{{ number_format($item['price'], 2) }}</strong>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>
            @endif

            <!-- Billing Summary -->
            <div class="total-box">
                <div class="total-row">
                    <span>Previous Total:</span>
                    <strong>₱{{ number_format($changeRequest->old_total, 2) }}</strong>
                </div>
                <div class="total-row">
                    <span>New Total:</span>
                    <strong>₱{{ number_format($changeRequest->new_total, 2) }}</strong>
                </div>
                <div class="total-row main">
                    <span>Total Change:</span>
                    <strong>{{ $differenceText }}</strong>
                </div>
            </div>

            <!-- Admin Note (if provided) -->
            @if($changeRequest->admin_notes)
            <div class="admin-note">
                <h4>Note from Admin:</h4>
                <p>{{ $changeRequest->admin_notes }}</p>
            </div>
            @endif

            <div style="text-align: center;">
                <a href="{{ route('customer.events.show', $event) }}" class="button">
                    View Event Details →
                </a>
            </div>

            <div class="divider"></div>

            <div class="message" style="margin-top: 30px;">
                <p>Your updated inclusions are now active. If you have any questions, please don't hesitate to contact
                    us.</p>
                <p style="margin-top: 20px;">
                    <strong>Email:</strong> <a href="mailto:michaelhoevents@gmail.com"
                        style="color: #667eea;">michaelhoevents@gmail.com</a><br>
                    <strong>Phone:</strong> <a href="tel:+639173062531" style="color: #667eea;">+63 917 306 2531</a>
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