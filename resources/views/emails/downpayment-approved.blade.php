<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Downpayment Approved - Event Scheduled</title>
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
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
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

        .celebration-banner {
            background: linear-gradient(135deg, #fae8ff 0%, #e9d5ff 100%);
            border: 3px solid #8b5cf6;
            padding: 30px;
            margin: 30px 0;
            border-radius: 12px;
            text-align: center;
        }

        .celebration-banner h2 {
            margin: 0 0 15px 0;
            color: #6b21a8;
            font-size: 32px;
        }

        .celebration-banner p {
            margin: 10px 0;
            color: #7c3aed;
            font-size: 18px;
            font-weight: 600;
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
            border-left: 4px solid #8b5cf6;
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

        .billing-summary {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            border: 2px solid #10b981;
            padding: 25px;
            margin: 25px 0;
            border-radius: 8px;
        }

        .billing-summary h3 {
            margin: 0 0 20px 0;
            color: #065f46;
            font-size: 18px;
            text-align: center;
        }

        .billing-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            font-size: 14px;
            color: #166534;
        }

        .billing-row.total {
            border-top: 2px solid #10b981;
            margin-top: 10px;
            padding-top: 15px;
            font-size: 18px;
            font-weight: 700;
        }

        .highlight-box {
            background: linear-gradient(135deg, #fff7ed 0%, #ffedd5 100%);
            border-left: 4px solid #f97316;
            padding: 20px;
            margin: 20px 0;
            border-radius: 4px;
        }

        .highlight-box h4 {
            margin: 0 0 10px 0;
            color: #9a3412;
            font-size: 16px;
        }

        .highlight-box p {
            margin: 5px 0;
            color: #7c2d12;
            font-size: 14px;
        }

        .button {
            display: inline-block;
            padding: 15px 40px;
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            margin: 20px 0;
            box-shadow: 0 4px 6px rgba(139, 92, 246, 0.3);
            transition: transform 0.2s;
        }

        .button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(139, 92, 246, 0.4);
        }

        .footer {
            background-color: #2d3748;
            color: #cbd5e0;
            padding: 30px;
            text-align: center;
            font-size: 14px;
        }

        .footer a {
            color: #8b5cf6;
            text-decoration: none;
        }

        .divider {
            height: 1px;
            background: linear-gradient(to right, transparent, #e2e8f0, transparent);
            margin: 30px 0;
        }

        .checklist {
            list-style: none;
            padding: 0;
            margin: 20px 0;
        }

        .checklist li {
            padding: 12px 0 12px 35px;
            position: relative;
            color: #4a5568;
            line-height: 1.6;
        }

        .checklist li::before {
            content: "✓";
            position: absolute;
            left: 0;
            top: 10px;
            width: 24px;
            height: 24px;
            background: #10b981;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 14px;
        }

        .contact-card {
            background: #f8fafc;
            border: 2px solid #e2e8f0;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }

        .contact-card h4 {
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
            color: #8b5cf6;
            text-decoration: none;
            font-weight: 600;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <img src="{{ asset('images/favicon.png') }}" alt="Michael Ho Events" class="logo">
            <h1>🎊 Event Scheduled!</h1>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">
                Hello {{ strtolower($customer->gender ?? '') === 'male' ? 'Mr.' : (strtolower($customer->gender ?? '')
                === 'female' ? 'Ms.' : 'Mr./Mrs.') }}
                {{ $customer->customer_name }}!
            </div>


            <div class="message">
                <p><strong>Fantastic news!</strong> Your downpayment has been verified and approved.</p>
                <p>Your event is now <strong>officially scheduled</strong> and our team is ready to make it
                    unforgettable!</p>
            </div>

            <!-- Celebration Banner -->
            <div class="celebration-banner">
                <h2>🎉 Booking Confirmed!</h2>
                <p>Your event is scheduled for</p>
                <p style="font-size: 24px; margin: 15px 0;">{{ $event->event_date->format('F d, Y') }}</p>
            </div>

            <!-- Payment Confirmation -->
            <div class="success-badge">
                <h3>✓ Downpayment Verified</h3>
                <div class="amount">₱{{ number_format($payment->amount, 2) }}</div>
                <p style="color: #065f46; margin: 10px 0 0 0;">Downpayment Received</p>
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
                    <span class="info-label">Status:</span>
                    <span class="info-value" style="color: #8b5cf6; font-weight: 700;">SCHEDULED ✓</span>
                </div>
            </div>

            <div class="divider"></div>

            <!-- Billing Summary -->
            @if($event->billing)
            <div class="billing-summary">
                <h3>💰 Payment Summary</h3>
                <div class="billing-row">
                    <span>Introductory Payment:</span>
                    <span>₱{{ number_format(5000, 2) }}</span>
                </div>
                <div class="billing-row">
                    <span>Downpayment:</span>
                    <span>₱{{ number_format($payment->amount, 2) }}</span>
                </div>
                <div class="billing-row">
                    <span style="font-weight: 600;">Total Paid:</span>
                    <span style="font-weight: 600;">₱{{ number_format(5000 + $payment->amount, 2) }}</span>
                </div>
                @if($event->billing->remaining_balance > 0)
                <div class="billing-row total">
                    <span>Remaining Balance:</span>
                    <span>₱{{ number_format($event->billing->remaining_balance, 2) }}</span>
                </div>
                @endif
            </div>
            @endif

            <!-- What's Next -->
            <div class="highlight-box">
                <h4>🚀 What Happens Now?</h4>
                <p>Your event is officially in our calendar! Here's what to expect:</p>
            </div>

            <div class="message">
                <ul class="checklist">
                    <li>Our event coordinator will reach out to finalize all details</li>
                    <li>We'll assign a dedicated team to your event</li>
                    <li>You can track progress through your dashboard</li>
                    <li>We'll keep you updated every step of the way</li>
                    <li>Final balance will be settled closer to the event date</li>
                </ul>
            </div>

            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ route('customer.events.show', $event) }}" class="button">View Event Dashboard</a>
            </div>

            <div class="divider"></div>

            <!-- Contact Information -->
            <div class="contact-card">
                <h4>📞 Need to Reach Us?</h4>
                <div class="contact-item">
                    <span>📧</span>
                    <span>Email: <a href="mailto:michaelhoevents@gmail.com">michaelhoevents@gmail.com</a></span>
                </div>
                <div class="contact-item">
                    <span>📱</span>
                    <span>Phone: <a href="tel:+639173062531">+63 917 306 2531</a></span>
                </div>
            </div>

            <div class="message" style="margin-top: 30px; text-align: center;">
                <p style="font-size: 18px; color: #2d3748; font-weight: 600;">
                    Thank you for choosing Michael Ho Events!
                </p>
                <p style="color: #6b7280;">
                    We're honored to be part of your special day and promise to deliver an unforgettable experience.
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