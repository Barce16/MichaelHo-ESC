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
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
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
            border-left: 4px solid #6366f1;
        }

        .detail-row {
            margin: 10px 0;
        }

        .label {
            font-weight: bold;
            color: #6366f1;
        }

        .checklist {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }

        .checklist h3 {
            color: #6366f1;
            margin-top: 0;
        }

        .checklist ul {
            margin: 0;
            padding-left: 20px;
        }

        .checklist li {
            margin: 8px 0;
        }

        .button {
            display: inline-block;
            padding: 12px 30px;
            background: #6366f1;
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
            <div class="countdown-badge">📅 1 MONTH TO GO</div>
            <h1 style="margin: 10px 0 0 0; font-size: 24px;">Your Event is Coming Up!</h1>
        </div>

        <div class="content">
            <p>Hello, <strong>{{ $event->customer->customer_name }}</strong>!</p>

            <p>We're excited to remind you that your special event is just <strong>one month away</strong>! Time flies,
                and we want to make sure everything is perfect for your big day.</p>

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

            <div class="checklist">
                <h3>✅ 1-Month Checklist</h3>
                <ul>
                    <li>Review and finalize your event inclusions</li>
                    <li>Confirm venue details and arrangements</li>
                    <li>Check your payment status and settle any remaining balance</li>
                    <li>Finalize guest count and special requests</li>
                    <li>Discuss any theme or decoration preferences with us</li>
                </ul>
            </div>

            <p>This is the perfect time to make any last adjustments or add special touches to your event.
                Our team is here to help you every step of the way!</p>

            <center>
                <a href="{{ url('/customer/events/' . $event->id) }}" class="button">Review Your Event</a>
            </center>

            <p style="margin-top: 20px;">If you have any questions or need to make changes, don't hesitate to reach out
                to us.</p>

            <p style="margin-top: 30px;">Warm regards,<br><strong>Michael Ho Events Team</strong></p>
        </div>

        <div class="footer">
            <p>Michael Ho Events - Styling and Coordination</p>
            <p>This is an automated reminder. Please do not reply to this email.</p>
        </div>
    </div>
</body>

</html>