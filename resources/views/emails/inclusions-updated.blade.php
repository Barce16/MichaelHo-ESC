<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Updated Event Inclusions</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f7fafc;
        }

        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .header {
            background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
            padding: 25px 20px;
            text-align: center;
            color: white;
        }

        .header h1 {
            margin: 0;
            font-size: 22px;
            font-weight: 600;
        }

        .header .subtitle {
            margin-top: 5px;
            font-size: 14px;
            opacity: 0.9;
        }

        .content {
            padding: 25px 20px;
        }

        .alert-box {
            background: #dbeafe;
            border-left: 4px solid #0284c7;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 6px;
        }

        .alert-box p {
            margin: 0;
            color: #075985;
            font-size: 14px;
        }

        .alert-box strong {
            color: #0369a1;
            font-weight: 600;
        }

        .summary-stats {
            display: flex;
            justify-content: space-around;
            background: #f0f9ff;
            border: 2px solid #0ea5e9;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
        }

        .stat-box {
            text-align: center;
        }

        .stat-box .number {
            font-size: 24px;
            font-weight: 700;
        }

        .stat-box.added .number {
            color: #059669;
        }

        .stat-box.removed .number {
            color: #dc2626;
        }

        .stat-box.total .number {
            color: #0284c7;
        }

        .stat-box .label {
            font-size: 11px;
            color: #64748b;
            text-transform: uppercase;
            margin-top: 3px;
        }

        .changes-section {
            margin: 15px 0;
            border-radius: 8px;
            overflow: hidden;
        }

        .section-header {
            padding: 12px 15px;
            color: white;
            font-size: 14px;
            font-weight: 600;
        }

        .section-header.added {
            background: #059669;
        }

        .section-header.removed {
            background: #dc2626;
        }

        .changes-list {
            background: #f8fafc;
            padding: 10px;
        }

        .change-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            margin: 5px 0;
            background: white;
            border-radius: 6px;
            font-size: 13px;
        }

        .item-name {
            font-weight: 600;
            color: #1e293b;
        }

        .item-category {
            font-size: 10px;
            color: #64748b;
            text-transform: uppercase;
        }

        .item-price {
            font-weight: 700;
            font-size: 14px;
            margin-left: 15px;
        }

        .changes-section.added .item-price {
            color: #059669;
        }

        .changes-section.removed .item-price {
            color: #dc2626;
        }

        .subtotal {
            text-align: right;
            padding: 10px;
            font-size: 13px;
            font-weight: 600;
            border-top: 2px solid;
        }

        .changes-section.added .subtotal {
            border-color: #10b981;
            color: #059669;
        }

        .changes-section.removed .subtotal {
            border-color: #ef4444;
            color: #dc2626;
        }

        .billing-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 15px;
            background: #f8fafc;
            margin: 3px 0;
            border-radius: 4px;
            font-size: 13px;
        }

        .billing-row.highlight {
            background: #dbeafe;
            font-weight: 700;
            font-size: 16px;
            color: #0c4a6e;
        }

        .change-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            margin-left: 10px;
        }

        .change-badge.increase {
            background: #fef3c7;
            color: #92400e;
        }

        .change-badge.decrease {
            background: #dcfce7;
            color: #166534;
        }

        .info-compact {
            background: #f8fafc;
            border-left: 3px solid #0284c7;
            padding: 12px 15px;
            margin: 15px 0;
            border-radius: 6px;
            font-size: 12px;
        }

        .info-compact div {
            margin: 4px 0;
        }

        .info-compact strong {
            color: #0c4a6e;
            display: inline-block;
            min-width: 80px;
        }

        .button {
            display: inline-block;
            padding: 12px 30px;
            background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
            color: white !important;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            font-size: 14px;
            margin: 15px 0;
        }

        .footer {
            background-color: #2d3748;
            color: #cbd5e0;
            padding: 20px;
            text-align: center;
            font-size: 12px;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="header">
            <h1>🔔 Customer Update</h1>
            <div class="subtitle">Event Inclusions Modified</div>
        </div>

        <div class="content">
            <div class="alert-box">
                <p><strong>{{ $customer->customer_name }}</strong> updated inclusions for <strong>{{ $event->name
                        }}</strong></p>
            </div>

            @if($addedInclusions->count() > 0 || $removedInclusions->count() > 0)
            <div class="summary-stats">
                <div class="stat-box added">
                    <div class="number">{{ $addedInclusions->count() }}</div>
                    <div class="label">Added</div>
                </div>
                <div class="stat-box removed">
                    <div class="number">{{ $removedInclusions->count() }}</div>
                    <div class="label">Removed</div>
                </div>
                <div class="stat-box total">
                    <div class="number">{{ $event->inclusions->count() }}</div>
                    <div class="label">Total</div>
                </div>
            </div>
            @endif

            @if($addedInclusions->count() > 0)
            <div class="changes-section added">
                <div class="section-header added">✅ Added ({{ $addedInclusions->count() }})</div>
                <div class="changes-list">
                    @foreach($addedInclusions as $inclusion)
                    <div class="change-item">
                        <div>
                            <div class="item-name">{{ $inclusion->name }}</div>
                            <div class="item-category">{{ $inclusion->category }}</div>
                        </div>
                        <div class="item-price">₱{{ number_format($inclusion->price, 2) }}</div>
                    </div>
                    @endforeach
                    @if($addedInclusions->sum('price') > 0)
                    <div class="subtotal">+₱{{ number_format($addedInclusions->sum('price'), 2) }}</div>
                    @endif
                </div>
            </div>
            @endif

            @if($removedInclusions->count() > 0)
            <div class="changes-section removed">
                <div class="section-header removed">❌ Removed ({{ $removedInclusions->count() }})</div>
                <div class="changes-list">
                    @foreach($removedInclusions as $inclusion)
                    <div class="change-item">
                        <div>
                            <div class="item-name">{{ $inclusion->name }}</div>
                            <div class="item-category">{{ $inclusion->category }}</div>
                        </div>
                        <div class="item-price">₱{{ number_format($inclusion->pivot->price_snapshot ??
                            $inclusion->price, 2) }}</div>
                    </div>
                    @endforeach
                    @php
                    $removedTotal = $removedInclusions->sum(fn($item) => $item->pivot->price_snapshot ?? $item->price);
                    @endphp
                    @if($removedTotal > 0)
                    <div class="subtotal">-₱{{ number_format($removedTotal, 2) }}</div>
                    @endif
                </div>
            </div>
            @endif

            @if($oldTotal != $newTotal)
            <div style="margin: 20px 0;">
                .....................................................
                <div class="billing-row">
                    <span>Previous Total:</span>
                    <span>₱{{ number_format($oldTotal, 2) }}</span>
                </div>
                <div class="billing-row highlight">
                    <span>New Total:</span>
                    <span>
                        ₱{{ number_format($newTotal, 2) }}
                        @if($newTotal > $oldTotal)
                        <span class="change-badge increase">+₱{{ number_format($newTotal - $oldTotal, 2) }}</span>
                        @elseif($newTotal < $oldTotal) <span class="change-badge decrease">-₱{{ number_format($oldTotal
                            - $newTotal, 2) }}</span>
                    @endif
                    </span>
                </div>
            </div>
            @endif

            <div class="info-compact">
                <div><strong>Customer:</strong> {{ $customer->customer_name }} ({{ $customer->user->email }})</div>
                <div><strong>Event:</strong> {{ $event->name }}</div>
                <div><strong>Date:</strong> {{ $event->event_date->format('M d, Y') }}</div>
                <div><strong>Package:</strong> {{ $event->package->name }}</div>
            </div>

            <div style="text-align: center;">
                <a href="{{ route('admin.events.show', $event) }}" class="button">View Event Details</a>
            </div>
        </div>

        <div class="footer">
            <p style="margin: 0;">© {{ date('Y') }} Michael Ho Events. Admin Notification.</p>
        </div>
    </div>
</body>

</html>