<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Approved</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sa:;
            ns-serif;
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
            background: linear-gradient(135deg, #f97316 0%, #dc2626 100%);
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
            border-left: 4px solid #f97316;
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

        .credentials-box {
            background: linear-gradient(135deg, #f97316 0%, #dc2626 100%);
            color: #5a5a5a;
            padding: 25px;
            border-radius: 8px;
            margin: 25px 0;
            text-align: center;
        }

        .credentials-box h3 {
            margin: 0 0 20px 0;
            font-size: 18px;
        }

        .credential-item {
            background-color: rgba(255, 255, 255, 0.2);
            padding: 15px;
            border-radius: 6px;
            margin: 10px 0;
            backdrop-filter: blur(10px);
        }

        .credential-label {
            font-size: 12px;
            opacity: 0.9;
            margin-bottom: 5px;
        }

        .credential-value {
            font-size: 20px;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .payment-highlight {
            background: linear-gradient(135deg, #fff7ed 0%, #ffedd5 100%);
            border: 3px solid #f97316;
            padding: 30px;
            border-radius: 12px;
            margin: 30px 0;
            text-align: center;
        }

        .payment-highlight h3 {
            margin: 0 0 10px 0;
            color: #c2410c;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .payment-amount {
            font-size: 48px;
            font-weight: 800;
            color: #ea580c;
            margin: 10px 0;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        }

        .payment-description {
            color: #7c2d12;
            font-size: 14px;
            margin-top: 10px;
        }

        .payment-box {
            background-color: #edf2f7;
            border: 2px solid #f97316;
            padding: 25px;
            border-radius: 8px;
            margin: 25px 0;
        }

        .payment-box h3 {
            margin: 0 0 20px 0;
            color: #2d3748;
            font-size: 18px;
            text-align: center;
        }

        .payment-method {
            background-color: white;
            padding: 15px;
            border-radius: 6px;
            margin: 15px 0;
            border-left: 4px solid #48bb78;
        }

        .payment-method h4 {
            margin: 0 0 10px 0;
            color: #2d3748;
            font-size: 16px;
        }

        .payment-detail {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
            font-size: 14px;
        }

        .payment-detail-label {
            color: #718096;
        }

        .payment-detail-value {
            color: #2d3748;
            font-weight: 600;
        }

        .button {
            display: inline-block;
            padding: 15px 40px;
            background: linear-gradient(135deg, #f97316 0%, #dc2626 100%);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            margin: 20px 0;
            box-shadow: 0 4px 6px rgba(249, 115, 22, 0.3);
            transition: transform 0.2s;
        }

        .button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(249, 115, 22, 0.4);
        }

        .warning-box {
            background-color: #fff5f5;
            border-left: 4px solid #fc8181;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
            color: #742a2a;
            font-size: 14px;
        }

        .highlight-box {
            background-color: #fef5e7;
            border-left: 4px solid #f39c12;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
            color: #7d6608;
            font-size: 14px;
        }

        .info-callout {
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            border-left: 4px solid #3b82f6;
            padding: 20px;
            margin: 20px 0;
            border-radius: 4px;
        }

        .info-callout h4 {
            margin: 0 0 10px 0;
            color: #1e40af;
            font-size: 16px;
        }

        .info-callout p {
            margin: 5px 0;
            color: #1e3a8a;
            font-size: 14px;
        }

        .footer {
            background-color: #2d3748;
            color: #cbd5e0;
            padding: 30px;
            text-align: center;
            font-size: 14px;
        }

        .footer a {
            color: #f97316;
            text-decoration: none;
        }

        .divider {
            height: 1px;
            background: linear-gradient(to right, transparent, #e2e8f0, transparent);
            margin: 30px 0;
        }

        .step-list {
            counter-reset: step-counter;
            list-style: none;
            padding: 0;
        }

        .step-list li {
            counter-increment: step-counter;
            margin-bottom: 20px;
            padding-left: 40px;
            position: relative;
            color: #4a5568;
        }

        .step-list li::before {
            content: counter(step-counter);
            position: absolute;
            left: 0;
            top: 0;
            width: 28px;
            height: 28px;
            background: linear-gradient(135deg, #f97316 0%, #dc2626 100%);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <!-- Header with Logo -->
        <div class="header">
            <img src="{{ asset('images/favicon.png') }}" alt="Michael Ho Events" class="logo">
            <h1>🎉 Booking Approved!</h1>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">
                Hello {{ strtolower($customer->gender ?? '') === 'male' ? 'Mr.' : (strtolower($customer->gender ?? '')
                === 'female' ? 'Ms.' : 'Mr./Mrs.') }}
                {{ $customer->customer_name }}!
            </div>


            <div class="message">
                <p><strong>Congratulations!</strong> We are thrilled to inform you that your event booking has been
                    <strong>approved</strong>.
                </p>
                <p>We're excited to be part of your special day and can't wait to bring your vision to life!</p>
            </div>

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
                <div class="info-row">
                    <span class="info-label">Estimated Total:</span>
                    <span class="info-value">₱{{ $total }}</span>
                </div>
            </div>

            <div class="divider"></div>

            <!-- Introductory Payment Highlight -->
            <div class="payment-highlight">
                <h3>💳 Introductory Payment Required</h3>
                <div class="payment-amount">₱5,000.00</div>
                <p class="payment-description">
                    Pay this amount to secure your event booking and schedule your planning meeting
                </p>
            </div>

            <div class="info-callout">
                <h4>ℹ️ About the Introductory Payment</h4>
                <p>• This ₱5,000 payment secures your event date and allows us to begin planning</p>
                <p>• This amount will be <strong>deducted from your total downpayment</strong> later</p>
                <p>• After verification, we'll schedule a meeting to finalize all details</p>
            </div>

            <div class="divider"></div>

            <!-- Account Credentials -->
            <div class="credentials-box">
                <h3>🔐 Your Account Access</h3>
                <p style="margin: 0 0 15px 0; opacity: 0.9;">
                    We've created a dashboard account for you to submit payment proof and monitor your booking status.
                </p>

                <div class="credential-item">
                    <div class="credential-label">Username</div>
                    <div class="credential-value">{{ $username }}</div>
                </div>

                <div class="credential-item">
                    <div class="credential-label">Password</div>
                    <div class="credential-value">{{ $password }}</div>
                </div>
            </div>

            <div class="warning-box">
                <strong>🔒 Security Notice:</strong> Please change your password immediately after logging in for your
                account security.
            </div>

            <div class="divider"></div>

            <!-- Payment Instructions -->
            <div class="payment-box">
                <h3>💰 Payment Methods</h3>

                <!-- GCash -->
                <div class="payment-method">
                    <h4>📱 GCash</h4>
                    <div class="payment-detail">
                        <span class="payment-detail-label">Account Name:</span>
                        <span class="payment-detail-value">MICHAEL HO</span>
                    </div>
                    <div class="payment-detail">
                        <span class="payment-detail-label">Mobile Number:</span>
                        <span class="payment-detail-value">0917-306-2531</span>
                    </div>
                </div>

                <!-- Bank Transfer -->
                <div class="payment-method">
                    <h4>🏦 Bank Transfer</h4>
                    <div class="payment-detail">
                        <span class="payment-detail-label">Bank Name:</span>
                        <span class="payment-detail-value">Bank of the Philippine Islands</span>
                    </div>
                    <div class="payment-detail">
                        <span class="payment-detail-label">Account Name:</span>
                        <span class="payment-detail-value">Michael Ho</span>
                    </div>
                    <div class="payment-detail">
                        <span class="payment-detail-label">Account Number:</span>
                        <span class="payment-detail-value">2060-0023-74</span>
                    </div>
                </div>

                <div style="margin-top: 20px; padding: 15px; background-color: #fef3c7; border-radius: 6px;">
                    <p style="margin: 0; color: #92400e; font-size: 14px; font-weight: 600;">
                        ⚠️ After making your payment, please log in to your dashboard and upload your payment receipt
                        for verification.
                    </p>
                </div>
            </div>

            <div style="text-align: center;">
                <a href="{{ route('login') }}" class="button">Login to Dashboard & Submit Payment Proof</a>
            </div>

            <div class="divider"></div>

            <!-- Next Steps -->
            <div class="message">
                <h3 style="color: #2d3748; margin-bottom: 20px;">📋 What Happens Next?</h3>
                <ol class="step-list">
                    <li>Make your <strong>₱5,000 introductory payment</strong> using any method above</li>
                    <li>Log in to your dashboard using the credentials provided</li>
                    <li><strong>Upload your payment receipt/proof</strong> for verification</li>
                    <li>Our team will verify your payment within 24-48 hours</li>
                    <li>Once approved, we'll schedule your <strong>planning meeting</strong></li>
                    <li>After the meeting, you'll receive the downpayment request to finalize your booking</li>
                </ol>
            </div>

            <div class="highlight-box">
                <strong>💡 Important:</strong> Your event date is temporarily reserved. To fully secure it, please
                submit your introductory payment as soon as possible.
            </div>

            <div class="message" style="margin-top: 30px;">
                <p>If you have any questions or need assistance, please don't hesitate to contact us.</p>
                <p style="margin-top: 20px;">
                    <strong>📧 Email:</strong> <a href="mailto:michaelhoevents@gmail.com"
                        style="color: #f97316;">michaelhoevents@gmail.com</a><br>
                    <strong>📞 Phone:</strong> <a href="tel:+639173062531" style="color: #f97316;">+63 917 306 2531</a>
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