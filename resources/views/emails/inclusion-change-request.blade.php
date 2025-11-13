<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inclusion Change Request</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 10px 10px 0 0;
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .content {
            background: #f9fafb;
            padding: 30px;
            border: 1px solid #e5e7eb;
            border-top: none;
        }

        .info-box {
            background: white;
            border-left: 4px solid #667eea;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }

        .info-box strong {
            color: #667eea;
        }

        .changes-section {
            margin: 20px 0;
        }

        .added {
            background: #ecfdf5;
            border-left: 4px solid #10b981;
            padding: 15px;
            margin: 10px 0;
            border-radius: 4px;
        }

        .added h3 {
            color: #065f46;
            margin: 0 0 10px 0;
        }

        .removed {
            background: #fef2f2;
            border-left: 4px solid #ef4444;
            padding: 15px;
            margin: 10px 0;
            border-radius: 4px;
        }

        .removed h3 {
            color: #991b1b;
            margin: 0 0 10px 0;
        }

        .item-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .item-list li {
            padding: 8px 0;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
        }

        .item-list li:last-child {
            border-bottom: none;
        }

        .total-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin: 10px 0;
            padding: 10px 0;
        }

        .total-row.main {
            border-top: 2px solid rgba(255, 255, 255, 0.3);
            font-size: 20px;
            font-weight: bold;
        }

        .button {
            display: inline-block;
            padding: 12px 30px;
            background: #667eea;
            color: white !important;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            margin: 20px 0;
        }

        .button:hover {
            background: #5568d3;
        }

        .footer {
            text-align: center;
            color: #6b7280;
            font-size: 14px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>{{ $isUpdate ? '📝 Inclusion Change Request Updated' : '🔔 New Inclusion Change Request' }}</h1>
    </div>

    <div class="content">
        <p>{{ $isUpdate ? 'A customer has updated their' : 'A customer has submitted a new' }} inclusion change request
            for their event.</p>

        <div class="info-box">
            <p><strong>Event:</strong> {{ $event->name }}</p>
            <p><strong>Customer:</strong> {{ $customer->user->name }}</p>
            <p><strong>Event Date:</strong> {{ \Carbon\Carbon::parse($event->event_date)->format('F d, Y') }}</p>
            @if($event->venue)
            <p><strong>Venue:</strong> {{ $event->venue }}</p>
            @endif
        </div>

        <div class="changes-section">
            <h2>Requested Changes:</h2>

            @if(count($addedInclusions) > 0)
            <div class="added">
                <h3>✅ Added Inclusions ({{ count($addedInclusions) }})</h3>
                <ul class="item-list">
                    @foreach($addedInclusions as $item)
                    <li>
                        <span>{{ $item['name'] }}</span>
                        <strong>+₱{{ number_format($item['price'], 2) }}</strong>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif

            @if(count($removedInclusions) > 0)
            <div class="removed">
                <h3>❌ Removed Inclusions ({{ count($removedInclusions) }})</h3>
                <ul class="item-list">
                    @foreach($removedInclusions as $item)
                    <li>
                        <span>{{ $item['name'] }}</span>
                        <strong>-₱{{ number_format($item['price'], 2) }}</strong>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif

            @if(count($addedInclusions) === 0 && count($removedInclusions) === 0)
            <div class="info-box">
                <p>No inclusion changes detected.</p>
            </div>
            @endif
        </div>

        <div class="total-box">
            <div class="total-row">
                <span>Current Total:</span>
                <strong>₱{{ number_format($changeRequest->old_total, 2) }}</strong>
            </div>
            <div class="total-row">
                <span>New Total:</span>
                <strong>₱{{ number_format($changeRequest->new_total, 2) }}</strong>
            </div>
            <div class="total-row main">
                <span>Total Change:</span>
                <strong>{{ $differenceText }}</strong>
            </div>
        </div>

        <center>
            <a href="{{ route('admin.change-requests.show', $changeRequest) }}" class="button">
                Review Request Now →
            </a>
        </center>

        <p style="margin-top: 30px; color: #6b7280; font-size: 14px;">
            Please review this change request and approve or reject it from your admin dashboard.
        </p>
    </div>

    <div class="footer">
        <p>This is an automated notification from {{ config('app.name') }}</p>
        <p>© {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
    </div>
</body>

</html>