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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
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
            border-left: 4px solid #667eea;
        }

        .detail-row {
            margin: 10px 0;
        }

        .label {
            font-weight: bold;
            color: #667eea;
        }

        .button {
            display: inline-block;
            padding: 12px 30px;
            background: #667eea;
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
            <h1 style="margin: 0; font-size: 28px;">🎉 Your Event is Today!</h1>
        </div>

        <div class="content">
            <p>Good morning, <strong>{{ $event->customer->customer_name }}</strong>!</p>

            <p>This is a friendly reminder that your special event is happening <strong>TODAY</strong>! We're excited to
                make your celebration memorable.</p>

            <div class="event-details">
                <div class="detail-row">
                    <span class="label">📅 Event:</span> {{ $event->name }}
                </div>
                <div class="detail-row">
                    <span class="label">📍 Venue:</span> {{ $event->venue ?? 'To be confirmed' }}
                </div>
                <div class="detail-row">
                    <span class="label">⏰ Date:</span> {{ \Carbon\Carbon::parse($event->event_date)->format('l, F d, Y')
                    }}
                </div>
                <div class="detail-row">
                    <span class="label">📦 Package:</span> {{ $event->package->name }}
                </div>
            </div>

            <p>Our team is fully prepared and will be there to ensure everything runs smoothly. We hope you have a
                wonderful celebration!</p>

            <center>
                <a href="{{ url('/customer/events/' . $event->id) }}" class="button">View Event Details</a>
            </center>

            <p style="margin-top: 20px;">If you have any last-minute questions or concerns, please don't hesitate to
                contact us immediately.</p>

            <p style="margin-top: 30px;">Best wishes,<br><strong>Michael Ho Events Team</strong></p>
        </div>

        <div class="footer">
            <p>Michael Ho Events - Styling and Coordination</p>
            <p>This is an automated reminder. Please do not reply to this email.</p>
        </div>
    </div>
</body>

</html>