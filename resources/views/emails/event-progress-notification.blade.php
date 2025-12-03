<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Progress Update - {{ $event->name }}</title>
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
                            style="background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); padding: 40px 40px; text-align: center;">
                            <div
                                style="width: 70px; height: 70px; background-color: rgba(255,255,255,0.2); border-radius: 50%; margin: 0 auto 20px; display: flex; align-items: center; justify-content: center;">
                                <span style="font-size: 36px;">{{ $isUpdate ? '📝' : '📋' }}</span>
                            </div>
                            <h1 style="color: #ffffff; margin: 0; font-size: 28px; font-weight: 700;">
                                @if($isUpdate)
                                Progress Update Modified
                                @else
                                New Progress Update
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
                                @if($isUpdate)
                                A progress update for your event has been modified. Here are the updated details:
                                @else
                                We have a new progress update for your event! Here are the details:
                                @endif
                            </p>

                            <!-- Progress Card -->
                            <table width="100%" cellpadding="0" cellspacing="0"
                                style="background: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 100%); border-radius: 12px; overflow: hidden; margin-bottom: 30px;">
                                <tr>
                                    <td style="padding: 24px;">
                                        <!-- Status Badge -->
                                        <div style="margin-bottom: 16px;">
                                            <span
                                                style="display: inline-block; background-color: #6366f1; color: #ffffff; padding: 8px 16px; border-radius: 20px; font-size: 14px; font-weight: 600;">
                                                {{ $progress->status }}
                                            </span>
                                        </div>

                                        <!-- Date -->
                                        <p style="color: #4f46e5; font-size: 14px; margin: 0 0 12px; font-weight: 500;">
                                            📅 {{ \Carbon\Carbon::parse($progress->progress_date)->format('F d, Y') }}
                                        </p>

                                        <!-- Details -->
                                        @if($progress->details)
                                        <div
                                            style="background-color: #ffffff; border-radius: 8px; padding: 16px; margin-top: 16px;">
                                            <p style="color: #1f2937; font-size: 15px; line-height: 1.6; margin: 0;">
                                                {{ $progress->details }}
                                            </p>
                                        </div>
                                        @endif
                                    </td>
                                </tr>
                            </table>

                            <!-- Event Info Box -->
                            <table width="100%" cellpadding="0" cellspacing="0"
                                style="background-color: #f3f4f6; border-radius: 12px; padding: 20px; margin-bottom: 30px;">
                                <tr>
                                    <td>
                                        <h3
                                            style="color: #374151; margin: 0 0 12px; font-size: 14px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                                            Event Information</h3>
                                        <p style="color: #1f2937; margin: 0 0 8px; font-size: 15px;">
                                            <strong>Event:</strong> {{ $event->name }}
                                        </p>
                                        <p style="color: #1f2937; margin: 0 0 8px; font-size: 15px;">
                                            <strong>Date:</strong> {{
                                            \Carbon\Carbon::parse($event->event_date)->format('F d, Y') }}
                                        </p>
                                        @if($event->venue)
                                        <p style="color: #1f2937; margin: 0; font-size: 15px;">
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
                                            style="display: inline-block; background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); color: #ffffff; text-decoration: none; padding: 16px 40px; border-radius: 10px; font-weight: 600; font-size: 16px; box-shadow: 0 4px 14px rgba(99, 102, 241, 0.4);">
                                            View All Progress Updates
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <!-- Note -->
                            <p
                                style="color: #6b7280; font-size: 14px; line-height: 1.6; margin: 0; text-align: center;">
                                If you have any questions about your event progress, please don't hesitate to contact
                                us.
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