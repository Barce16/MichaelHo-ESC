<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Request Update</title>
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
            background: linear-gradient(135deg, #718096 0%, #4a5568 100%);
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
            border-left: 4px solid #718096;
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

        .reason-box {
            background-color: #fff5f5;
            border-left: 4px solid #f56565;
            padding: 20px;
            margin: 25px 0;
            border-radius: 4px;
        }

        .reason-box h3 {
            margin: 0 0 10px 0;
            color: #742a2a;
            font-size: 16px;
            font-weight: 600;
        }

        .reason-box p {
            margin: 0;
            color: #742a2a;
            line-height: 1.6;
        }

        .changes-section {
            margin: 25px 0;
            background-color: #f7fafc;
            padding: 20px;
            border-radius: 8px;
        }

        .changes-section h3 {
            margin: 0 0 15px 0;
            color: #2d3748;
        }

        .item-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .item-list li {
            padding: 8px 0;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            color: #4a5568;
        }

        .item-list li:last-child {
            border-bottom: none;
        }

        .alternative-box {
            background-color: #f0fff4;
            border-left: 4px solid #48bb78;
            padding: 20px;
            margin: 25px 0;
            border-radius: 4px;
        }

        .alternative-box h3 {
            margin: 0 0 15px 0;
            color: #22543d;
            font-size: 16px;
            font-weight: 600;
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
            color: #667eea;
            text-decoration: none;
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
            <h1>Change Request Update</h1>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">
                Hello {{ $customer->customer_name }},
            </div>

            <div class="message">
                <p>Thank you for submitting an inclusion change request for your event <strong>{{ $event->name
                        }}</strong>.</p>
                <p>After careful review, we are unable to approve the requested changes at this time.</p>
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

            <!-- Rejection Reason -->
            <div class="reason-box">
                <h3>Reason</h3>
                <p>{{ $rejectionReason }}</p>
            </div>

            <!-- Requested Changes (for reference) -->
            @if(count($addedInclusions) > 0 || count($removedInclusions) > 0)
            <div class="changes-section">
                <h3>Your Requested Changes:</h3>

                @if(count($addedInclusions) > 0)
                <h4 style="color: #2d3748; font-size: 14px; margin: 15px 0 10px 0;">Items you wanted to add:</h4>
                <ul class="item-list">
                    @foreach($addedInclusions as $item)
                    <li>
                        <span>{{ $item['name'] }}</span>
                        <span style="color: #718096;">₱{{ number_format($item['price'], 2) }}</span>
                    </li>
                    @endforeach
                </ul>
                @endif

                @if(count($removedInclusions) > 0)
                <h4 style="color: #2d3748; font-size: 14px; margin: 15px 0 10px 0;">Items you wanted to remove:</h4>
                <ul class="item-list">
                    @foreach($removedInclusions as $item)
                    <li>
                        <span>{{ $item['name'] }}</span>
                        <span style="color: #718096;">₱{{ number_format($item['price'], 2) }}</span>
                    </li>
                    @endforeach
                </ul>
                @endif
            </div>
            @endif

            <div class="divider"></div>

            <!-- Next Steps -->
            <div class="alternative-box">
                <h3>What You Can Do:</h3>
                <ul style="color: #22543d; line-height: 2; margin: 10px 0 0 20px;">
                    <li>Contact us to discuss alternative options or modifications</li>
                    <li>Submit a new change request with different selections</li>
                    <li>Keep your current package as-is</li>
                    <li>Call or email us for a consultation</li>
                </ul>
            </div>

            <div style="text-align: center;">
                <a href="{{ route('customer.events.show', $event) }}" class="button">
                    View Event Details →
                </a>
            </div>

            <div class="divider"></div>

            <div class="message" style="margin-top: 30px;">
                <p>We appreciate your understanding. If you'd like to discuss this further or explore other options,
                    please don't hesitate to reach out.</p>
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