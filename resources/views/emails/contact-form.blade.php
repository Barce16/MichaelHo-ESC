<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Contact Form Submission</title>
</head>

<body
    style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">

    <div style="background-color: #f8f9fa; padding: 30px; border-radius: 8px;">
        <h2 style="color: #1a1a1a; margin-top: 0; font-size: 24px;">New Contact Form Submission</h2>

        <div style="background-color: white; padding: 20px; border-radius: 4px; margin-top: 20px;">

            <div style="margin-bottom: 20px;">
                <p style="margin: 0; color: #666; font-size: 12px; text-transform: uppercase; letter-spacing: 1px;">Name
                </p>
                <p style="margin: 5px 0 0 0; font-size: 16px; font-weight: 600;">{{ $contactData['name'] }}</p>
            </div>

            <div style="margin-bottom: 20px;">
                <p style="margin: 0; color: #666; font-size: 12px; text-transform: uppercase; letter-spacing: 1px;">
                    Email</p>
                <p style="margin: 5px 0 0 0; font-size: 16px;">
                    <a href="mailto:{{ $contactData['email'] }}" style="color: #1a1a1a; text-decoration: none;">
                        {{ $contactData['email'] }}
                    </a>
                </p>
            </div>

            @if(!empty($contactData['phone']))
            <div style="margin-bottom: 20px;">
                <p style="margin: 0; color: #666; font-size: 12px; text-transform: uppercase; letter-spacing: 1px;">
                    Phone</p>
                <p style="margin: 5px 0 0 0; font-size: 16px;">
                    <a href="tel:{{ $contactData['phone'] }}" style="color: #1a1a1a; text-decoration: none;">
                        {{ $contactData['phone'] }}
                    </a>
                </p>
            </div>
            @endif

            <div style="margin-bottom: 0;">
                <p style="margin: 0; color: #666; font-size: 12px; text-transform: uppercase; letter-spacing: 1px;">
                    Message</p>
                <p style="margin: 5px 0 0 0; font-size: 16px; line-height: 1.8; white-space: pre-line;">{{
                    $contactData['message'] }}</p>
            </div>

        </div>

        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd; text-align: center;">
            <p style="margin: 0; color: #666; font-size: 12px;">
                This email was sent from your website contact form on {{ now()->format('F d, Y \a\t g:i A') }}
            </p>
        </div>
    </div>

</body>

</html>