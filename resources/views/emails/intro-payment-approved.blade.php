<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Introductory Payment Approved</title>
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

        .success-badge {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            border-left: 4px solid #10b981;
            padding: 20px;
            margin: 25px 0;
            border-radius: 8px;
            text-align: center;
        }

        .success-badge h3 {
            margin: 0 0 10px 0;
            color: #065f46;
            font-size: 18px;
        }

        .success-badge .amount {
            font-size: 36px;
            font-weight: 800;
            color: #059669;
            margin: 10px 0;
        }

        .info-box {
            background-color: #f7fafc;
            border-left: 4px solid #3b82f6;
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

        .highlight-box {
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            border-left: 4px solid #3b82f6;
            padding: 20px;
            margin: 20px 0;
            border-radius: 4px;
        }

        .highlight-box h4 {
            margin: 0 0 10px 0;
            color: #1e40af;
            font-size: 16px;
        }

        .highlight-box p {
            margin: 5px 0;
            color: #1e3a8a;
            font-size: 14px;
        }

        .button {
            display: inline-block;
            padding: 15px 40px;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            margin: 20px 0;
            box-shadow: 0 4px 6px rgba(59, 130, 246, 0.3);
            transition: transform 0.2s;
        }

        .button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(59, 130, 246, 0.4);
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

        .step-list {
            counter-reset: step-counter;
            list-style: none;
            padding: 0;
        }

        .step-list li {
            counter-increment: step-counter;
            margin-bottom: 15px;
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
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
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
        <!-- Header -->
        <div class="header">
            <img src="{{ asset('images/favicon.png') }}" alt="Michael Ho Events" class="logo">
            <h1>✅ Payment Approved!</h1>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">
                Hello {{ strtolower($customer->gender) === 'male' ? 'Mr.' : (strtolower($customer->gender) === 'female'
                ? 'Ms.' : '') }} {{ $customer->customer_name }}!
            </div>


            <div class="message">
                <p><strong>Great news!</strong> Your introductory payment has been verified and approved.</p>
                <p>We're excited to move forward with planning your special event!</p>
            </div>

            <!-- Payment Confirmation -->
            <div class="success-badge">
                <h3>✓ Payment Verified</h3>
                <div class="amount">₱{{ number_format($payment->amount, 2) }}</div>
                <p style="color: #065f46; margin: 10px 0 0 0;">Introductory Payment</p>
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
                    <span class="info-label">Status:</span>
                    <span class="info-value" style="color: #3b82f6;">Awaiting Downpayment Request</span>
                </div>
            </div>

            <div class="divider"></div>

            <!-- Next Steps -->
            <div class="highlight-box">
                <h4>🎯 What Happens Next?</h4>
                <p>Our event coordinator will contact you within 24-48 hours to:</p>
            </div>

            <div class="message">
                <ol class="step-list">
                    <li>Schedule your <strong>planning meeting</strong> to discuss all event details</li>
                    <li>Finalize your event requirements and customizations</li>
                    <li>Request the <strong>downpayment amount</strong> to secure your booking</li>
                    <li>Begin coordinating with our team of specialists</li>
                </ol>
            </div>

            <div class="highlight-box">
                <h4>💡 Important Note</h4>
                <p>The ₱15,000 introductory payment you made will be <strong>deducted from your total
                        downpayment</strong>. You'll only pay the remaining balance when we request the downpayment.</p>
            </div>

            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ route('customer.events.show', $event) }}" class="button">View Event Dashboard</a>
            </div>

            <div class="divider"></div>

            <div class="message" style="margin-top: 30px;">
                <p>If you have any questions or need to discuss your event, please feel free to reach out!</p>
                <p style="margin-top: 20px;">
                    <strong>📧 Email:</strong> <a href="mailto:michaelhoevents@gmail.com"
                        style="color: #10b981;">michaelhoevents@gmail.com</a><br>
                    <strong>📞 Phone:</strong> <a href="tel:+639173062531" style="color: #10b981;">+63 917 306 2531</a>
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