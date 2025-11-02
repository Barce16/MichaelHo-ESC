<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Verification Issue</title>
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
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
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

        .alert-box {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            border-left: 4px solid #ef4444;
            padding: 25px;
            margin: 25px 0;
            border-radius: 8px;
        }

        .alert-box h3 {
            margin: 0 0 15px 0;
            color: #991b1b;
            font-size: 18px;
        }

        .alert-box p {
            margin: 5px 0;
            color: #7f1d1d;
        }

        .reason-box {
            background-color: #fef3c7;
            border: 2px solid #f59e0b;
            padding: 20px;
            margin: 25px 0;
            border-radius: 8px;
        }

        .reason-box h4 {
            margin: 0 0 10px 0;
            color: #92400e;
            font-size: 16px;
        }

        .reason-box p {
            margin: 0;
            color: #78350f;
            font-size: 14px;
            line-height: 1.6;
        }

        .info-box {
            background-color: #f7fafc;
            border-left: 4px solid #ef4444;
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

        .instructions-box {
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            border-left: 4px solid #3b82f6;
            padding: 20px;
            margin: 20px 0;
            border-radius: 4px;
        }

        .instructions-box h4 {
            margin: 0 0 15px 0;
            color: #1e40af;
            font-size: 16px;
        }

        .checklist {
            list-style: none;
            padding: 0;
            margin: 15px 0;
        }

        .checklist li {
            padding: 8px 0 8px 30px;
            position: relative;
            color: #1e3a8a;
            font-size: 14px;
        }

        .checklist li::before {
            content: "→";
            position: absolute;
            left: 0;
            color: #3b82f6;
            font-weight: bold;
            font-size: 18px;
        }

        .button {
            display: inline-block;
            padding: 15px 40px;
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            margin: 20px 0;
            box-shadow: 0 4px 6px rgba(239, 68, 68, 0.3);
            transition: transform 0.2s;
        }

        .button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(239, 68, 68, 0.4);
        }

        .help-box {
            background-color: #f8fafc;
            border: 2px solid #e2e8f0;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }

        .help-box h4 {
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
            color: #ef4444;
            text-decoration: none;
            font-weight: 600;
        }

        .footer {
            background-color: #2d3748;
            color: #cbd5e0;
            padding: 30px;
            text-align: center;
            font-size: 14px;
        }

        .footer a {
            color: #ef4444;
            text-decoration: none;
        }

        .divider {
            height: 1px;
            background: linear-gradient(to right, transparent, #e2e8f0, transparent);
            margin: 30px 0;
        }

        .highlight-amount {
            background: linear-gradient(135deg, #fff7ed 0%, #ffedd5 100%);
            border: 2px solid #f97316;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
            text-align: center;
        }

        .highlight-amount .label {
            color: #9a3412;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .highlight-amount .amount {
            font-size: 32px;
            font-weight: 800;
            color: #ea580c;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <img src="{{ asset('images/favicon.png') }}" alt="Michael Ho Events" class="logo">
            <h1>⚠️ Payment Issue</h1>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">
                Hello {{ strtolower($customer->gender) === 'male' ? 'Mr.' : (strtolower($customer->gender) === 'female'
                ? 'Ms.' : '') }} {{ $customer->customer_name }},
            </div>


            <div class="message">
                <p>We've reviewed your {{ $paymentTypeLabel }} submission, but unfortunately we need you to resubmit
                    your payment proof.</p>
            </div>

            <!-- Alert -->
            <div class="alert-box">
                <h3>🔍 Payment Verification Issue</h3>
                <p>Your {{ $paymentTypeLabel }} of <strong>₱{{ number_format($payment->amount, 2) }}</strong> could not
                    be verified.</p>
            </div>

            <!-- Reason -->
            @if($reason)
            <div class="reason-box">
                <h4>📋 Reason for Rejection:</h4>
                <p>{{ $reason }}</p>
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
                <div class="info-row">
                    <span class="info-label">Payment Type:</span>
                    <span class="info-value">{{ $paymentTypeLabel }}</span>
                </div>
            </div>

            <!-- Amount to Pay -->
            <div class="highlight-amount">
                <div class="label">Amount Required</div>
                <div class="amount">₱{{ number_format($payment->amount, 2) }}</div>
            </div>

            <div class="divider"></div>

            <!-- Instructions -->
            <div class="instructions-box">
                <h4>📝 How to Resubmit Your Payment</h4>
                <ul class="checklist">
                    <li>Log in to your dashboard</li>
                    <li>Navigate to your event details</li>
                    <li>Click the payment button again</li>
                    <li>Upload a clear photo of your payment receipt</li>
                    <li>Make sure the receipt shows:
                        <ul style="margin: 10px 0 0 20px; padding-left: 0;">
                            <li style="padding: 5px 0;">✓ Payment amount</li>
                            <li style="padding: 5px 0;">✓ Date of payment</li>
                            <li style="padding: 5px 0;">✓ Reference number (if applicable)</li>
                            <li style="padding: 5px 0;">✓ All text is readable</li>
                        </ul>
                    </li>
                </ul>
            </div>

            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ route('customer.events.show', $event) }}" class="button">Resubmit Payment Now</a>
            </div>

            <div class="divider"></div>

            <!-- Help Section -->
            <div class="help-box">
                <h4>💡 Tips for Clear Payment Proof</h4>
                <ul style="margin: 10px 0; padding-left: 20px; color: #4a5568;">
                    <li style="padding: 5px 0;">Take the photo in good lighting</li>
                    <li style="padding: 5px 0;">Make sure all text is clear and readable</li>
                    <li style="padding: 5px 0;">Include the entire receipt in the photo</li>
                    <li style="padding: 5px 0;">Use JPG, JPEG, or PNG format</li>
                    <li style="padding: 5px 0;">File size should be under 10MB</li>
                </ul>
            </div>

            <div class="message" style="margin-top: 30px;">
                <p><strong>Need help?</strong> If you have questions about this rejection or need assistance with your
                    payment, please don't hesitate to contact us.</p>
            </div>

            <div class="help-box" style="margin-top: 20px;">
                <h4>📞 Contact Us</h4>
                <div class="contact-item">
                    <span>📧</span>
                    <span>Email: <a href="mailto:michaelhoevents@gmail.com">michaelhoevents@gmail.com</a></span>
                </div>
                <div class="contact-item">
                    <span>📱</span>
                    <span>Phone: <a href="tel:+639173062531">+63 917 306 2531</a></span>
                </div>
            </div>

            <div class="message"
                style="margin-top: 30px; padding: 20px; background-color: #fffbeb; border-radius: 8px; border-left: 4px solid #f59e0b;">
                <p style="margin: 0; color: #92400e; font-size: 14px;">
                    <strong>Important:</strong> Please resubmit your payment proof as soon as possible to avoid any
                    delays in processing your event booking.
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