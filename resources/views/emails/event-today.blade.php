<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 0 auto;
            color: #333;
            line-height: 1.6;
        }

        .header {
            background: #000;
            color: #fff;
            padding: 30px;
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 28px;
        }

        .content {
            padding: 30px;
            background: #f9f9f9;
        }

        .credentials-box {
            background: #fff;
            border: 2px solid #000;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
        }

        .credential-row {
            display: flex;
            padding: 12px 0;
            border-bottom: 1px solid #e5e5e5;
        }

        .credential-row:last-child {
            border-bottom: none;
        }

        .credential-label {
            font-weight: bold;
            width: 120px;
            color: #666;
        }

        .credential-value {
            color: #000;
            font-family: monospace;
            font-size: 16px;
        }

        .event-box {
            background: #fff;
            padding: 20px;
            margin: 20px 0;
            border-left: 4px solid #000;
            border-radius: 0 4px 4px 0;
        }

        .button {
            display: inline-block;
            background: #000;
            color: #fff;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 6px;
            margin: 20px 0;
            font-weight: bold;
        }

        .warning {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
        }

        .footer {
            text-align: center;
            padding: 20px;
            color: #999;
            font-size: 12px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Michael Ho Events</h1>
        <p style="margin: 10px 0 0 0;">Welcome! Your Account is Ready</p>
    </div>

    <div class="content">
        <h2 style="margin-top: 0; color: #000;">Hello, Mr./Mrs. {{ $user->name }}!</h2>

        <p>Thank you for choosing Michael Ho Events for your special occasion. We've created an account for you so you
            can track your event progress online.</p>

        <div class="credentials-box">
            <h3 style="margin-top: 0; color: #000;">Your Login Credentials</h3>
            <div class="credential-row">
                <div class="credential-label">Username:</div>
                <div class="credential-value">{{ $user->username }}</div>
            </div>
            <div class="credential-row">
                <div class="credential-label">Password:</div>
                <div class="credential-value">{{ $password }}</div>
            </div>
        </div>

        <div class="warning">
            <strong>⚠️ Important:</strong> Please change your password after your first login for security.
        </div>

        <div style="text-align: center;">
            <a href="{{ url('/login') }}" class="button">Login to Your Account</a>
        </div>

        <div class="event-box">
            <h3 style="margin-top: 0; color: #000;">Your Event Details</h3>
            <p style="margin: 5px 0;"><strong>Event Name:</strong> {{ $event->name }}</p>
            <p style="margin: 5px 0;"><strong>Package:</strong> {{ $event->package->name }}</p>
            <p style="margin: 5px 0;"><strong>Date:</strong> {{ $event->event_date->format('F d, Y') }}</p>
            <p style="margin: 5px 0;"><strong>Venue:</strong> {{ $event->venue }}</p>
            @if($event->theme)
            <p style="margin: 5px 0;"><strong>Theme:</strong> {{ $event->theme }}</p>
            @endif
        </div>

        <!-- What's Next Section -->
        <div
            style="background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%); border: 2px solid #3b82f6; padding: 25px; margin: 25px 0; border-radius: 12px;">
            <h3 style="margin: 0 0 20px 0; color: #1d4ed8; font-size: 18px;">📌 What's Next?</h3>

            <div style="margin-bottom: 18px;">
                <div style="display: flex; align-items: flex-start;">
                    <div
                        style="background: #3b82f6; color: white; width: 28px; height: 28px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-weight: bold; margin-right: 12px; flex-shrink: 0; font-size: 14px;">
                        1</div>
                    <div>
                        <strong style="color: #1e40af;">Log in to Your Account</strong>
                        <p style="margin: 5px 0 0 0; color: #4b5563; font-size: 14px;">Use the credentials above to
                            access your customer dashboard.</p>
                    </div>
                </div>
            </div>

            <div style="margin-bottom: 18px;">
                <div style="display: flex; align-items: flex-start;">
                    <div
                        style="background: #3b82f6; color: white; width: 28px; height: 28px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-weight: bold; margin-right: 12px; flex-shrink: 0; font-size: 14px;">
                        2</div>
                    <div>
                        <strong style="color: #1e40af;">Change Your Password</strong>
                        <p style="margin: 5px 0 0 0; color: #4b5563; font-size: 14px;">For security, please update your
                            password after your first login.</p>
                    </div>
                </div>
            </div>

            <div style="margin-bottom: 18px;">
                <div style="display: flex; align-items: flex-start;">
                    <div
                        style="background: #3b82f6; color: white; width: 28px; height: 28px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-weight: bold; margin-right: 12px; flex-shrink: 0; font-size: 14px;">
                        3</div>
                    <div>
                        <strong style="color: #1e40af;">Track Your Event</strong>
                        <p style="margin: 5px 0 0 0; color: #4b5563; font-size: 14px;">View your event details,
                            inclusions, and payments in your dashboard.</p>
                    </div>
                </div>
            </div>

            <div style="margin-bottom: 0;">
                <div style="display: flex; align-items: flex-start;">
                    <div
                        style="background: #3b82f6; color: white; width: 28px; height: 28px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-weight: bold; margin-right: 12px; flex-shrink: 0; font-size: 14px;">
                        4</div>
                    <div>
                        <strong style="color: #1e40af;">Stay Updated</strong>
                        <p style="margin: 5px 0 0 0; color: #4b5563; font-size: 14px;">We'll send you reminders and
                            updates as your event date approaches.</p>
                    </div>
                </div>
            </div>
        </div>

        <p style="margin-top: 30px;">If you have any questions, feel free to contact us. We're excited to make your
            event memorable!</p>
    </div>

    <div class="footer">
        <p>© {{ date('Y') }} Michael Ho Events. All rights reserved.</p>
        <p>This email contains sensitive information. Please keep it secure.</p>
    </div>
</body>

</html>