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
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
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
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.7;
            }
        }

        .countdown-number {
            font-size: 64px;
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
            border-left: 4px solid #ef4444;
        }

        .detail-row {
            margin: 10px 0;
        }

        .label {
            font-weight: bold;
            color: #dc2626;
        }

        .urgent-box {
            background: #fef2f2;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border: 2px solid #fecaca;
        }

        .urgent-box h3 {
            color: #991b1b;
            margin-top: 0;
        }

        .contact-box {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: center;
            border: 1px solid #e5e7eb;
        }

        .button {
            display: inline-block;
            padding: 12px 30px;
            background: #ef4444;
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
            <div class="countdown-badge">⚡ ALMOST HERE!</div>
            <div class="countdown-number">3</div>
            <h1 style="margin: 0; font-size: 24px;">Days Until Your Event!</h1>
        </div>

        <div class="content">
            <p>Hello, <strong>{{ $event->customer->customer_name }}</strong>!</p>

            <p>The countdown is on! Your special event is just <strong>3 DAYS</strong> away!
                We can hardly contain our excitement to help make your celebration absolutely perfect.</p>

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

            <div class="urgent-box">
                <h3>⏰ Last-Minute Checklist</h3>
                <ul style="margin: 0; padding-left: 20px;">
                    <li>Verify all payments have been completed</li>
                    <li>Confirm your arrival time at the venue</li>
                    <li>Prepare any personal items you'll need</li>
                    <li>Have our contact number ready for the event day</li>
                    <li>Get plenty of rest - you want to look your best!</li>
                </ul>
            </div>

            <p>Our team is finalizing all the details and will be fully prepared for your event.
                If you have any last-minute questions, concerns, or special requests, <strong>please contact us
                    immediately</strong>.</p>

            <div class="contact-box">
                <p style="margin: 0; font-size: 14px; color: #666;">Need to reach us urgently?</p>
                <p style="margin: 10px 0; font-size: 18px; font-weight: bold; color: #333;">📞 0917-306-2531</p>
                <p style="margin: 0; font-size: 14px; color: #666;">We're here to help!</p>
            </div>

            <center>
                <a href="{{ url('/customer/events/' . $event->id) }}" class="button">View Event Details</a>
            </center>

            <p style="margin-top: 30px;">With excitement,<br><strong>Michael Ho Events Team</strong></p>
        </div>

        <div class="footer">
            <p>Michael Ho Events - Styling and Coordination</p>
            <p>This is an automated reminder. Please do not reply to this email.</p>
        </div>
    </div>
</body>

</html>