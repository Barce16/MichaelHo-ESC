<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule Update - {{ $event->name }}</title>
</head>

<body
    style="margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f3f4f6;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f3f4f6; padding: 40px 20px;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0"
                    style="background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                    <!-- Header -->
                    <tr>
                        <td
                            style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); padding: 40px 40px; text-align: center;">
                            <div
                                style="width: 70px; height: 70px; background-color: rgba(255,255,255,0.2); border-radius: 50%; margin: 0 auto 20px; display: flex; align-items: center; justify-content: center;">
                                <span style="font-size: 36px;">📅</span>
                            </div>
                            <h1 style="color: #ffffff; margin: 0; font-size: 28px; font-weight: 700;">
                                @if($action === 'created')
                                New Schedule Added
                                @else
                                Schedule Updated
                                @endif
                            </h1>
                            <p style="color: rgba(255,255,255,0.9); margin: 10px 0 0; font-size: 16px;">
                                {{ $event->name }}
                            </p>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px;">
                            <!-- Greeting -->
                            <p style="color: #374151; font-size: 16px; line-height: 1.6; margin: 0 0 20px;">
                                Dear <strong>{{ $event->customer->customer_name }}</strong>,
                            </p>

                            <p style="color: #374151; font-size: 16px; line-height: 1.6; margin: 0 0 30px;">
                                @if($action === 'created')
                                A new schedule has been added to your event. Here are the details:
                                @else
                                Your event schedule has been updated. Here are the latest details:
                                @endif
                            </p>

                            <!-- Schedules Table -->
                            <table width="100%" cellpadding="0" cellspacing="0"
                                style="margin-bottom: 30px; border: 1px solid #e5e7eb; border-radius: 12px; overflow: hidden;">
                                <thead>
                                    <tr style="background-color: #fef3c7;">
                                        <th
                                            style="padding: 14px 16px; text-align: left; font-size: 13px; font-weight: 600; color: #92400e; text-transform: uppercase; letter-spacing: 0.5px;">
                                            Item</th>
                                        <th
                                            style="padding: 14px 16px; text-align: left; font-size: 13px; font-weight: 600; color: #92400e; text-transform: uppercase; letter-spacing: 0.5px;">
                                            Date</th>
                                        <th
                                            style="padding: 14px 16px; text-align: left; font-size: 13px; font-weight: 600; color: #92400e; text-transform: uppercase; letter-spacing: 0.5px;">
                                            Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($schedules as $index => $schedule)
                                    @php
                                    $schedule->load('inclusion');
                                    @endphp
                                    <tr style="background-color: {{ $index % 2 === 0 ? '#ffffff' : '#fefce8' }};">
                                        <td style="padding: 14px 16px; border-top: 1px solid #e5e7eb;">
                                            <span style="color: #1f2937; font-weight: 500;">{{
                                                $schedule->inclusion->name ?? 'N/A' }}</span>
                                            @if($schedule->remarks)
                                            <br><span style="color: #6b7280; font-size: 13px;">{{ $schedule->remarks
                                                }}</span>
                                            @endif
                                        </td>
                                        <td style="padding: 14px 16px; border-top: 1px solid #e5e7eb; color: #374151;">
                                            {{ \Carbon\Carbon::parse($schedule->scheduled_date)->format('M d, Y') }}
                                        </td>
                                        <td style="padding: 14px 16px; border-top: 1px solid #e5e7eb; color: #374151;">
                                            @if($schedule->scheduled_time)
                                            {{ \Carbon\Carbon::parse($schedule->scheduled_time)->format('g:i A') }}
                                            @else
                                            <span style="color: #9ca3af;">TBD</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <!-- Event Info Box -->
                            <table width="100%" cellpadding="0" cellspacing="0"
                                style="background-color: #fffbeb; border-radius: 12px; padding: 20px; margin-bottom: 30px;">
                                <tr>
                                    <td>
                                        <h3
                                            style="color: #92400e; margin: 0 0 12px; font-size: 14px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                                            Event Information</h3>
                                        <p style="color: #78350f; margin: 0 0 8px; font-size: 15px;">
                                            <strong>Event:</strong> {{ $event->name }}
                                        </p>
                                        <p style="color: #78350f; margin: 0 0 8px; font-size: 15px;">
                                            <strong>Date:</strong> {{
                                            \Carbon\Carbon::parse($event->event_date)->format('F d, Y') }}
                                        </p>
                                        @if($event->venue)
                                        <p style="color: #78350f; margin: 0; font-size: 15px;">
                                            <strong>Venue:</strong> {{ $event->venue }}
                                        </p>
                                        @endif
                                    </td>
                                </tr>
                            </table>

                            <!-- CTA Button -->
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center" style="padding: 10px 0 30px;">
                                        <a href="{{ route('customer.events.show', $event) }}"
                                            style="display: inline-block; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: #ffffff; text-decoration: none; padding: 16px 40px; border-radius: 10px; font-weight: 600; font-size: 16px; box-shadow: 0 4px 14px rgba(245, 158, 11, 0.4);">
                                            View All Schedules
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <!-- Note -->
                            <p
                                style="color: #6b7280; font-size: 14px; line-height: 1.6; margin: 0; text-align: center;">
                                If you have any questions about your schedule, please don't hesitate to contact us.
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #1f2937; padding: 30px 40px; text-align: center;">
                            <p style="color: #d1d5db; margin: 0 0 8px; font-size: 16px; font-weight: 600;">
                                Michael Ho Events Styling & Coordination
                            </p>
                            <p style="color: #9ca3af; margin: 0 0 4px; font-size: 14px;">
                                📧 michaelhoevents@gmail.com
                            </p>
                            <p style="color: #9ca3af; margin: 0; font-size: 14px;">
                                📱 0917 306 2531
                            </p>
                        </td>
                    </tr>
                </table>

                <!-- Sub-footer -->
                <table width="600" cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="padding: 20px; text-align: center;">
                            <p style="color: #9ca3af; font-size: 12px; margin: 0;">
                                This is an automated notification. Please do not reply directly to this email.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>