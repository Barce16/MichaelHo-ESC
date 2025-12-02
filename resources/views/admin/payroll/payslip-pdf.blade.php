<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Staff Payslip</title>
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
            border-bottom: 2px solid #334155;
            padding-bottom: 12px;
        }

        .company-name {
            font-size: 18px;
            font-weight: bold;
            color: #1e293b;
            margin-bottom: 5px;
        }

        .company-details {
            font-size: 9px;
            color: #666;
            line-height: 1.4;
        }

        .payslip-title {
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            margin: 12px 0;
            color: #1e293b;
        }

        .payslip-number {
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
            color: #334155;
            font-size: 11px;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 4px;
        }

        .info-row {
            display: table;
            width: 100%;
            margin-bottom: 5px;
        }

        .info-label {
            display: table-cell;
            width: 140px;
            font-weight: bold;
            color: #555;
            font-size: 9px;
        }

        .info-value {
            display: table-cell;
            color: #333;
            font-size: 9px;
        }

        .two-column {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }

        .column {
            display: table-cell;
            width: 48%;
            vertical-align: top;
        }

        .column-spacer {
            display: table-cell;
            width: 4%;
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
            color: #334155;
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
            background-color: #e2e8f0;
            padding: 12px;
            margin-top: 10px;
            border-radius: 4px;
        }

        .total-label {
            font-size: 11px;
            font-weight: bold;
            color: #1e293b;
        }

        .total-value {
            font-size: 14px;
            font-weight: bold;
            color: #1e293b;
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
            margin-top: 30px;
            display: table;
            width: 100%;
        }

        .signature-box {
            display: table-cell;
            width: 45%;
            text-align: center;
            padding: 10px;
        }

        .signature-name {
            font-weight: bold;
            font-size: 10px;
            margin-bottom: 30px;
        }

        .signature-line {
            border-top: 1px solid #333;
            padding-top: 5px;
            font-size: 9px;
            margin-top: 30px;
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

        .status-badge-paid {
            background-color: #334155;
            color: #ffffff;
        }

        .acknowledgment {
            margin-top: 20px;
            padding: 15px;
            background-color: #fefce8;
            border: 1px solid #fef08a;
            border-radius: 6px;
            font-size: 9px;
        }

        .acknowledgment-title {
            font-weight: bold;
            margin-bottom: 8px;
            color: #854d0e;
        }

        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 80px;
            color: rgba(0, 0, 0, 0.03);
            font-weight: bold;
            z-index: -1;
        }
    </style>
</head>

<body>
    <div class="watermark">PAID</div>

    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="company-name">Michael Ho Events Styling & Coordination</div>
            <div class="company-details">
                14 extention st, Cagayan De Oro City, 9000 Misamis Oriental | Phone: 0917-306-2531 | Email:
                michaelhoevents@gmail.com
            </div>
        </div>

        <!-- Payslip Title -->
        <div class="payslip-title">STAFF PAYSLIP</div>

        <!-- Payslip Number & Date -->
        <div class="payslip-number">
            <strong>Payslip No:</strong> PS-{{ str_pad($event->id, 4, '0', STR_PAD_LEFT) }}-{{ str_pad($staff->id, 4,
            '0', STR_PAD_LEFT) }} |
            <strong>Date Issued:</strong> {{ now()->format('F d, Y') }}
        </div>

        <!-- Two Column Layout: Staff Info & Event Info -->
        <div class="two-column">
            <!-- Staff Information -->
            <div class="column">
                <div class="info-section">
                    <h3>STAFF INFORMATION</h3>
                    <div class="info-row">
                        <div class="info-label">Staff Name:</div>
                        <div class="info-value">{{ $staff->name }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Contact Number:</div>
                        <div class="info-value">{{ $staff->contact_number ?? 'N/A' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Role Type:</div>
                        <div class="info-value">{{ ucfirst($staff->role_type ?? 'Staff') }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Assignment Role:</div>
                        <div class="info-value">{{ $pivot->assignment_role }}</div>
                    </div>
                </div>
            </div>

            <div class="column-spacer"></div>

            <!-- Event Information -->
            <div class="column">
                <div class="info-section">
                    <h3>EVENT DETAILS</h3>
                    <div class="info-row">
                        <div class="info-label">Event Name:</div>
                        <div class="info-value">{{ $event->name }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Event Date:</div>
                        <div class="info-value">{{ $event->event_date->format('F d, Y') }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Venue:</div>
                        <div class="info-value">{{ $event->venue ?? 'N/A' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Customer:</div>
                        <div class="info-value">{{ $event->customer->customer_name }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Details -->
        <div class="payment-details">
            <h3>PAYMENT DETAILS</h3>

            <div class="payment-row">
                <div class="payment-label">Assignment Role:</div>
                <div class="payment-value">{{ $pivot->assignment_role }}</div>
            </div>

            <div class="payment-row">
                <div class="payment-label">Work Status:</div>
                <div class="payment-value">
                    <span class="status-badge">{{ strtoupper($pivot->work_status ?? 'ASSIGNED') }}</span>
                </div>
            </div>

            <div class="payment-row">
                <div class="payment-label">Payment Status:</div>
                <div class="payment-value">
                    <span class="status-badge status-badge-paid">PAID</span>
                </div>
            </div>

            <div class="payment-row">
                <div class="payment-label">Pay Rate Type:</div>
                <div class="payment-value">{{ ucfirst($staff->rate_type ?? 'Fixed') }}</div>
            </div>

            <!-- Total -->
            <div class="total-row">
                <div class="payment-row" style="border: none;">
                    <div class="payment-label total-label">TOTAL AMOUNT PAID:</div>
                    <div class="payment-value total-value">₱{{ number_format($pivot->pay_rate, 2) }}</div>
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

                $amount = floor($pivot->pay_rate);
                $amountInWords = convertNumberToWords($amount);
                $cents = round(($pivot->pay_rate - $amount) * 100);

                if ($cents > 0) {
                $amountInWords .= ' and ' . convertNumberToWords($cents) . ' Centavos';
                }

                echo $amountInWords . ' Pesos Only';
                @endphp
            </em>
        </div>

        <!-- Acknowledgment Section -->
        <div class="acknowledgment">
            <div class="acknowledgment-title">ACKNOWLEDGMENT</div>
            <p>I hereby acknowledge receipt of the above-stated amount as payment for my services rendered for the event
                indicated above. I confirm that this payment is complete and I have no further claims against Michael Ho
                Events Styling & Coordination for this particular event assignment.</p>
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
                    Employer's Signature
                </div>
            </div>
            <div style="display: table-cell; width: 10%;"></div>
            <div class="signature-box">
                <div class="signature-name">{{ $staff->name }}</div>
                <div class="signature-line">
                    Staff Signature / Date Received
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>This payslip serves as an official record of payment. Please keep for your records.</p>
            <p>For inquiries, contact us at 0917-306-2531 or michaelhoevents@gmail.com</p>
        </div>
    </div>
</body>

</html>