<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }

        .countdown-badge {
            display: inline-block;
            background: rgba(255, 255, 255, 0.2);
            padding: 8px 20px;
            border-radius: 20px;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .countdown-number {
            font-size: 48px;
            font-weight: bold;
            margin: 10px 0;
        }

        .content {
            background: #f9f9f9;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }

        .event-details {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #f59e0b;
        }

        .detail-row {
            margin: 10px 0;
        }

        .label {
            font-weight: bold;
            color: #d97706;
        }

        .preparation-box {
            background: #fef3c7;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border: 1px solid #fcd34d;
        }

        .preparation-box h3 {
            color: #92400e;
            margin-top: 0;
        }

        .button {
            display: inline-block;
            padding: 12px 30px;
            background: #f59e0b;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }

        .footer {
            text-align: center;
            padding: 20px;
            color: #666;
            font-size: 12px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <div class="countdown-badge">⏰ COUNTDOWN ALERT</div>
            <div class="countdown-number">7</div>
            <h1 style="margin: 0; font-size: 24px;">Days Until Your Event!</h1>
        </div>

        <div class="content">
            <p>Hello, <strong>{{ $event->customer->customer_name }}</strong>!</p>

            <p>Can you believe it? Your special event is just <strong>ONE WEEK</strong> away!
                The excitement is building, and our team is working hard to ensure everything is ready for your
                celebration.</p>

            <div class="event-details">
                <div class="detail-row">
                    <span class="label">🎉 Event:</span> {{ $event->name }}
                </div>
                <div class="detail-row">
                    <span class="label">📍 Venue:</span> {{ $event->venue ?? 'To be confirmed' }}
                </div>
                <div class="detail-row">
                    <span class="label">📆 Date:</span> {{ \Carbon\Carbon::parse($event->event_date)->format('l, F d,
                    Y') }}
                </div>
                <div class="detail-row">
                    <span class="label">📦 Package:</span> {{ $event->package->name }}
                </div>
            </div>

            <div class="preparation-box">
                <h3>🔔 Final Week Reminders</h3>
                <ul style="margin: 0; padding-left: 20px;">
                    <li><strong>Payments:</strong> Please ensure all outstanding payments are settled</li>
                    <li><strong>Final Guest Count:</strong> Confirm your final headcount</li>
                    <li><strong>Special Requests:</strong> Last chance for any special arrangements</li>
                    <li><strong>Contact Info:</strong> Ensure we have your updated contact details</li>
                </ul>
            </div>

            <p>Our preparation team is in full swing! If there are any last-minute details you'd like to discuss
                or changes you need to make, please contact us as soon as possible.</p>

            <center>
                <a href="{{ url('/customer/events/' . $event->id) }}" class="button">View Event Details</a>
            </center>

            <p style="margin-top: 20px; color: #666; font-size: 14px;">
                <strong>Tip:</strong> Make sure to get plenty of rest in the coming days so you can fully enjoy your
                special celebration!
            </p>

            <p style="margin-top: 30px;">Excitedly yours,<br><strong>Michael Ho Events Team</strong></p>
        </div>

        <div class="footer">
            <p>Michael Ho Events - Styling and Coordination</p>
            <p>This is an automated reminder. Please do not reply to this email.</p>
        </div>
    </div>
</body>

</html>