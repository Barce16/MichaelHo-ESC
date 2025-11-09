<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Official Receipt</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 10px;
            line-height: 1.5;
            color: #333;
        }

        .container {
            padding: 15px 30px;
            max-width: 800px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 18px;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 12px;
        }

        .company-name {
            font-size: 18px;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 5px;
        }

        .company-details {
            font-size: 9px;
            color: #666;
            line-height: 1.4;
        }

        .receipt-title {
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            margin: 12px 0;
            color: #1e3a8a;
        }

        .receipt-number {
            text-align: right;
            font-size: 9px;
            color: #666;
            margin-bottom: 15px;
        }

        .info-section {
            margin-bottom: 15px;
        }

        .info-section h3 {
            margin-bottom: 8px;
            color: #1e40af;
            font-size: 11px;
        }

        .info-row {
            display: table;
            width: 100%;
            margin-bottom: 5px;
        }

        .info-label {
            display: table-cell;
            width: 120px;
            font-weight: bold;
            color: #555;
            font-size: 9px;
        }

        .info-value {
            display: table-cell;
            color: #333;
            font-size: 9px;
        }

        .payment-details {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 15px;
            margin: 15px 0;
        }

        .payment-details h3 {
            margin-bottom: 10px;
            color: #1e40af;
            font-size: 11px;
        }

        .payment-row {
            display: table;
            width: 100%;
            padding: 6px 0;
            border-bottom: 1px solid #e2e8f0;
        }

        .payment-row:last-child {
            border-bottom: none;
        }

        .payment-label {
            display: table-cell;
            font-weight: bold;
            color: #555;
            font-size: 9px;
        }

        .payment-value {
            display: table-cell;
            text-align: right;
            color: #333;
            font-size: 9px;
        }

        .total-row {
            background-color: #dbeafe;
            padding: 12px;
            margin-top: 10px;
            border-radius: 4px;
        }

        .total-label {
            font-size: 11px;
            font-weight: bold;
            color: #1e40af;
        }

        .total-value {
            font-size: 14px;
            font-weight: bold;
            color: #1e40af;
        }

        .amount-words {
            margin: 12px 0;
            padding: 10px;
            background-color: #f8fafc;
            border-radius: 4px;
            font-size: 9px;
        }

        .footer {
            margin-top: 18px;
            padding-top: 12px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            font-size: 8px;
            color: #666;
            line-height: 1.5;
        }

        .signature-section {
            margin-top: 20px;
            display: table;
            width: 100%;
        }

        .signature-box {
            display: table-cell;
            width: 45%;
            text-align: center;
        }

        .signature-name {
            font-weight: bold;
            font-size: 10px;
            margin-bottom: 8px;
        }

        .signature-line {
            border-top: 1px solid #333;
            padding-top: 5px;
            font-size: 9px;
        }

        .status-badge {
            display: inline-block;
            padding: 3px 10px;
            background-color: #d1fae5;
            color: #065f46;
            border-radius: 15px;
            font-weight: bold;
            font-size: 8px;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="company-name">Michael Ho Events Styling & Coordination</div>
            <div class="company-details">
                14 extention st, Cagayan De Oro City, 9000 Misamis Oriental | Phone: 0917-306-2531 | Email:
                michaelhoevents@gmail.com
            </div>
        </div>

        <!-- Receipt Title -->
        <div class="receipt-title">OFFICIAL RECEIPT</div>

        <!-- Receipt Number & Date -->
        <div class="receipt-number">
            <strong>Receipt No:</strong> OR-{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }} |
            <strong>Date Issued:</strong> {{ ($payment->receipt_created_at ?? $payment->payment_date)->format('F d, Y')
            }}
        </div>

        <!-- Customer & Event Information Combined -->
        <div class="info-section">
            <h3>CUSTOMER & EVENT DETAILS:</h3>
            <div class="info-row">
                <div class="info-label">Customer:</div>
                <div class="info-value">{{ $customer->customer_name }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Event:</div>
                <div class="info-value">{{ $event->name }} | {{ $event->event_date->format('M d, Y') }} | {{
                    $event->venue }}</div>
            </div>
        </div>

        <!-- Payment Details -->
        <div class="payment-details">
            <h3>PAYMENT DETAILS:</h3>

            <div class="payment-row">
                <div class="payment-label">Payment Type:</div>
                <div class="payment-value">{{ $payment->getTypeLabel() }}</div>
            </div>

            <div class="payment-row">
                <div class="payment-label">Payment Method:</div>
                <div class="payment-value">{{ $payment->getMethodLabel() }}</div>
            </div>

            @if($payment->reference_number)
            <div class="payment-row">
                <div class="payment-label">Reference Number:</div>
                <div class="payment-value">{{ $payment->reference_number }}</div>
            </div>
            @endif

            <div class="payment-row">
                <div class="payment-label">Payment Date:</div>
                <div class="payment-value">{{ $payment->payment_date->format('F d, Y') }}</div>
            </div>

            <div class="payment-row">
                <div class="payment-label">Status:</div>
                <div class="payment-value">
                    <span class="status-badge">{{ strtoupper($payment->status) }}</span>
                </div>
            </div>

            <!-- Total -->
            <div class="total-row">
                <div class="payment-row" style="border: none;">
                    <div class="payment-label total-label">TOTAL AMOUNT RECEIVED:</div>
                    <div class="payment-value total-value">Php {{ number_format($payment->amount, 2) }}</div>
                </div>
            </div>
        </div>

        <!-- Amount in Words -->
        <div class="amount-words">
            <strong>Amount in Words:</strong>
            <em>
                @php
                function convertNumberToWords($number) {
                $ones = ['', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine'];
                $tens = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];
                $teens = ['Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen',
                'Eighteen', 'Nineteen'];

                if ($number == 0) return 'Zero';

                $result = '';

                if ($number >= 1000000) {
                $millions = floor($number / 1000000);
                $result .= convertNumberToWords($millions) . ' Million ';
                $number %= 1000000;
                }

                if ($number >= 1000) {
                $thousands = floor($number / 1000);
                $result .= convertNumberToWords($thousands) . ' Thousand ';
                $number %= 1000;
                }

                if ($number >= 100) {
                $hundreds = floor($number / 100);
                if (isset($ones[$hundreds])) {
                $result .= $ones[$hundreds] . ' Hundred ';
                }
                $number %= 100;
                }

                if ($number >= 20) {
                $tensDigit = floor($number / 10);
                if (isset($tens[$tensDigit])) {
                $result .= $tens[$tensDigit] . ' ';
                }
                $number %= 10;
                } elseif ($number >= 10) {
                $teensIndex = $number - 10;
                if (isset($teens[$teensIndex])) {
                $result .= $teens[$teensIndex] . ' ';
                }
                $number = 0;
                }

                if ($number > 0 && isset($ones[$number])) {
                $result .= $ones[$number] . ' ';
                }

                return trim($result);
                }

                $amount = floor($payment->amount);
                $amountInWords = convertNumberToWords($amount);
                $cents = round(($payment->amount - $amount) * 100);

                if ($cents > 0) {
                $amountInWords .= ' and ' . convertNumberToWords($cents) . ' Centavos';
                }

                echo $amountInWords . ' Pesos Only';
                @endphp
            </em>
        </div>

        <!-- Signature Section -->
        <div class="signature-section">
            <div class="signature-box">
                @if(isset($admin) && $admin->signature_path)
                <img src="{{ public_path('storage/' . $admin->signature_path) }}"
                    style="max-height: 40px; margin: 0 auto 5px; display: block;" alt="Signature">
                @endif
                <div class="signature-name">{{ $admin->name ?? 'Authorized Personnel' }}</div>
                <div class="signature-line">
                    Authorized Signature
                </div>
            </div>
            <div style="display: table-cell; width: 10%;"></div>
            <div class="signature-box">
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>For inquiries, contact us at 0917-306-2531 or michaelhoevents@gmail.com | Thank you for your business!
            </p>
        </div>
    </div>
</body>

</html>