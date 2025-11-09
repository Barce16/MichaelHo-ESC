<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Progress Update</title>
</head>

<body
    style="margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; background-color: #f4f4f7; min-height: 100vh;">

    <!-- Email Wrapper -->
    <table cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color: #f4f4f7; padding: 20px 0;">
        <tr>
            <td align="center">

                <!-- Main Container -->
                <table cellpadding="0" cellspacing="0" border="0" width="600"
                    style="background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">

                    <!-- Header -->
                    <tr>
                        <td style="background-color: #000000; padding: 40px 30px; text-align: center;">
                            <h1
                                style="margin: 0; color: #ffffff; font-size: 28px; font-weight: 700; letter-spacing: -0.5px;">
                                Michael Ho Events</h1>
                            <p style="margin: 10px 0 0 0; color: #ffffff; font-size: 14px; opacity: 0.9;">Your Event
                                Progress Update</p>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding: 35px 30px;">

                            <!-- Event Title -->
                            <h2
                                style="margin: 0 0 25px 0; color: #1e293b; font-size: 24px; font-weight: 600; letter-spacing: -0.5px;">
                                {{ $event->name }}</h2>

                            <!-- Order Info Box -->
                            <table cellpadding="0" cellspacing="0" border="0" width="100%"
                                style="background-color: #f8fafc; border-left: 4px solid #000000; margin: 25px 0;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                            <tr>
                                                <td style="padding: 5px 0;">
                                                    <span
                                                        style="font-weight: 600; color: #64748b; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Order
                                                        ID:</span>
                                                    <span
                                                        style="color: #1e293b; font-size: 14px; font-weight: 500; margin-left: 10px;">#{{
                                                        str_pad($event->id, 6, '0', STR_PAD_LEFT) }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 5px 0;">
                                                    <span
                                                        style="font-weight: 600; color: #64748b; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Event
                                                        Date:</span>
                                                    <span
                                                        style="color: #1e293b; font-size: 14px; font-weight: 500; margin-left: 10px;">{{
                                                        $event->event_date->format('F d, Y') }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 5px 0;">
                                                    <span
                                                        style="font-weight: 600; color: #64748b; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Venue:</span>
                                                    <span
                                                        style="color: #1e293b; font-size: 14px; font-weight: 500; margin-left: 10px;">{{
                                                        $event->venue ?? 'TBA' }}</span>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- Progress Timeline Container -->
                            <table cellpadding="0" cellspacing="0" border="0" width="100%"
                                style="background-color: #fafbfc; border: 1px solid #e2e8f0; border-radius: 8px; margin: 30px 0;">
                                <tr>
                                    <td style="padding: 30px;">

                                        <!-- Timeline Header -->
                                        <h3
                                            style="margin: 0 0 25px 0; color: #1e293b; font-size: 18px; font-weight: 600;">
                                            📋 Event Progress Timeline</h3>

                                        @if($event->progress && $event->progress->count() > 0)
                                        @foreach($event->progress as $item)
                                        <!-- Timeline Item -->
                                        <table cellpadding="0" cellspacing="0" border="0" width="100%"
                                            style="margin-bottom: 20px;">
                                            <tr>
                                                <!-- Timeline Dot Column -->
                                                <td width="40" valign="top" style="padding-right: 15px;">
                                                    <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                                        <tr>
                                                            <td align="center">
                                                                @if($loop->first)
                                                                <!-- Active Dot -->
                                                                <div
                                                                    style="width: 16px; height: 16px; background-color: #000000; border-radius: 50%; border: 3px solid #e5e5e5; margin: 0 auto;">
                                                                </div>
                                                                @else
                                                                <!-- Inactive Dot -->
                                                                <div
                                                                    style="width: 12px; height: 12px; background-color: #cbd5e1; border-radius: 50%; border: 2px solid #ffffff; margin: 0 auto;">
                                                                </div>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        @if(!$loop->last)
                                                        <tr>
                                                            <td align="center">
                                                                <!-- Connecting Line -->
                                                                <div
                                                                    style="width: 2px; height: 60px; background-color: #e2e8f0; margin: 5px auto 0 auto;">
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        @endif
                                                    </table>
                                                </td>

                                                <!-- Timeline Content Column -->
                                                <td valign="top" style="padding-bottom: 15px;">
                                                    @if($loop->first)
                                                    <!-- Latest Badge -->
                                                    <table cellpadding="0" cellspacing="0" border="0"
                                                        style="margin-bottom: 8px;">
                                                        <tr>
                                                            <td
                                                                style="background-color: #000000; color: #ffffff; padding: 4px 12px; border-radius: 12px; font-size: 10px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                                                                LATEST UPDATE
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    @endif

                                                    <!-- Status -->
                                                    <div
                                                        style="font-size: 16px; font-weight: 600; margin-bottom: 6px; color: {{ $loop->first ? '#1e293b' : '#64748b' }};">
                                                        {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                                                    </div>

                                                    <!-- Date -->
                                                    <div style="color: #94a3b8; font-size: 13px; margin-bottom: 10px;">
                                                        {{ $item->progress_date->format('F d, Y g:i A') }}
                                                    </div>

                                                    <!-- Details -->
                                                    @if($item->details)
                                                    <table cellpadding="0" cellspacing="0" border="0" width="100%"
                                                        style="margin-top: 10px;">
                                                        <tr>
                                                            <td
                                                                style="background-color: {{ $loop->first ? '#f5f5f5' : '#f8fafc' }}; padding: 12px 16px; border-left: 3px solid {{ $loop->first ? '#000000' : '#e2e8f0' }}; border-radius: 4px;">
                                                                <div
                                                                    style="color: {{ $loop->first ? '#475569' : '#94a3b8' }}; font-size: 14px; line-height: 1.5;">
                                                                    {{ $item->details }}
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    @endif
                                                </td>
                                            </tr>
                                        </table>
                                        @endforeach
                                        @else
                                        <!-- Default Timeline Item -->
                                        <table cellpadding="0" cellspacing="0" border="0" width="100%"
                                            style="margin-bottom: 20px;">
                                            <tr>
                                                <!-- Timeline Dot Column -->
                                                <td width="40" valign="top" style="padding-right: 15px;">
                                                    <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                                        <tr>
                                                            <td align="center">
                                                                <!-- Active Dot -->
                                                                <div
                                                                    style="width: 16px; height: 16px; background-color: #000000; border-radius: 50%; border: 3px solid #e5e5e5; margin: 0 auto;">
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>

                                                <!-- Timeline Content Column -->
                                                <td valign="top" style="padding-bottom: 15px;">
                                                    <!-- Latest Badge -->
                                                    <table cellpadding="0" cellspacing="0" border="0"
                                                        style="margin-bottom: 8px;">
                                                        <tr>
                                                            <td
                                                                style="background-color: #000000; color: #ffffff; padding: 4px 12px; border-radius: 12px; font-size: 10px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                                                                LATEST UPDATE
                                                            </td>
                                                        </tr>
                                                    </table>

                                                    <!-- Status -->
                                                    <div
                                                        style="font-size: 16px; font-weight: 600; margin-bottom: 6px; color: #1e293b;">
                                                        Event Scheduled
                                                    </div>

                                                    <!-- Date -->
                                                    <div style="color: #94a3b8; font-size: 13px; margin-bottom: 10px;">
                                                        {{ now()->format('F d, Y g:i A') }}
                                                    </div>

                                                    <!-- Details -->
                                                    <table cellpadding="0" cellspacing="0" border="0" width="100%"
                                                        style="margin-top: 10px;">
                                                        <tr>
                                                            <td
                                                                style="background-color: #f5f5f5; padding: 12px 16px; border-left: 3px solid #000000; border-radius: 4px;">
                                                                <div
                                                                    style="color: #475569; font-size: 14px; line-height: 1.5;">
                                                                    Your event has been scheduled and we're getting
                                                                    ready!
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                        @endif

                                    </td>
                                </tr>
                            </table>

                            <!-- Divider -->
                            <hr style="border: none; height: 1px; background-color: #e2e8f0; margin: 30px 0;">

                            <!-- CTA Section -->
                            <table cellpadding="0" cellspacing="0" border="0" width="100%"
                                style="background-color: #f8fafc; border-radius: 8px; margin: 20px 0;">
                                <tr>
                                    <td align="center" style="padding: 25px;">
                                        <p
                                            style="margin: 0 0 15px 0; color: #475569; font-size: 14px; line-height: 1.6;">
                                            Stay up-to-date with your event! View complete details and real-time updates
                                            in your account dashboard.
                                        </p>
                                        <table cellpadding="0" cellspacing="0" border="0">
                                            <tr>
                                                <td
                                                    style="background-color: #000000; padding: 12px 30px; border-radius: 25px;">
                                                    <a href="#"
                                                        style="color: #ffffff; text-decoration: none; font-weight: 600; font-size: 14px; display: inline-block;">View
                                                        Event Dashboard</a>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td
                            style="background-color: #f8fafc; padding: 30px; text-align: center; border-top: 1px solid #e2e8f0;">
                            <div style="font-size: 18px; font-weight: 700; color: #334155; margin-bottom: 15px;">Michael
                                Ho Events</div>

                            <!-- Footer Links -->
                            <div style="margin: 15px 0;">
                                <a href="#"
                                    style="color: #000000; text-decoration: none; margin: 0 10px; font-size: 12px; font-weight: 500;">Website</a>
                                <a href="#"
                                    style="color: #000000; text-decoration: none; margin: 0 10px; font-size: 12px; font-weight: 500;">Support</a>
                                <a href="#"
                                    style="color: #000000; text-decoration: none; margin: 0 10px; font-size: 12px; font-weight: 500;">Contact</a>
                            </div>

                            <p style="color: #94a3b8; font-size: 12px; margin: 10px 0 5px 0;">© {{ date('Y') }} Michael
                                Ho Events. All rights reserved.</p>
                            <p style="color: #94a3b8; font-size: 11px; margin: 5px 0;">This is an automated
                                notification. Please do not reply to this email.</p>
                        </td>
                    </tr>

                </table>
                <!-- End Main Container -->

            </td>
        </tr>
    </table>
    <!-- End Email Wrapper -->

</body>

</html>