<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>New Contact Message</title>
</head>
<body style="margin:0; padding:0; background:#eef2ff; font-family:Tahoma, Arial, Helvetica, sans-serif; color:#1f2937;">
    <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="background:radial-gradient(circle at top, #e0f2fe 0%, #eef2ff 45%, #f5f7fb 100%); padding:28px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="max-width:680px; background:#ffffff; border-radius:16px; overflow:hidden; border:1px solid #dbeafe; box-shadow:0 14px 34px rgba(2, 132, 199, 0.14);">
                    <tr>
                        <td style="background:linear-gradient(130deg, #0f766e, #0ea5e9 55%, #38bdf8); padding:26px 24px;">
                            <p style="margin:0; color:#e0f2fe; font-size:12px; letter-spacing:1px; text-transform:uppercase;">Contact Form Alert</p>
                            <h1 style="margin:8px 0 0; color:#ffffff; font-size:26px; line-height:1.25;">New Contact Message</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:24px;">
                            <p style="margin:0 0 14px; font-size:15px; font-weight:700;">Hello Admin,</p>
                            <p style="margin:0 0 18px; font-size:15px; color:#4b5563; line-height:1.7;">
                                You received a new travel enquiry from <strong>{{ config('app.name') }}</strong>.
                            </p>

                            <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:separate; border-spacing:0; margin-bottom:18px; font-size:14px; background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; overflow:hidden;">
                                <tr><td style="padding:10px 12px; color:#6b7280; width:170px; border-bottom:1px solid #e2e8f0;">Sender Name</td><td style="padding:10px 12px; border-bottom:1px solid #e2e8f0;"><strong>{{ $contact->name }}</strong></td></tr>
                                <tr><td style="padding:10px 12px; color:#6b7280; border-bottom:1px solid #e2e8f0;">Phone / WhatsApp</td><td style="padding:10px 12px; border-bottom:1px solid #e2e8f0;">{{ $contact->phone }}</td></tr>
                                <tr><td style="padding:10px 12px; color:#6b7280; border-bottom:1px solid #e2e8f0;">Email</td><td style="padding:10px 12px; border-bottom:1px solid #e2e8f0;">{{ $contact->email ?? '-' }}</td></tr>
                                <tr><td style="padding:10px 12px; color:#6b7280; border-bottom:1px solid #e2e8f0;">Service</td><td style="padding:10px 12px; border-bottom:1px solid #e2e8f0;">{{ $contact->service ?? '-' }}</td></tr>
                                <tr><td style="padding:10px 12px; color:#6b7280; border-bottom:1px solid #e2e8f0;">From / Pickup</td><td style="padding:10px 12px; border-bottom:1px solid #e2e8f0;">{{ $contact->from ?? '-' }}</td></tr>
                                <tr><td style="padding:10px 12px; color:#6b7280; border-bottom:1px solid #e2e8f0;">To / Destination</td><td style="padding:10px 12px; border-bottom:1px solid #e2e8f0;">{{ $contact->to ?? '-' }}</td></tr>
                                <tr><td style="padding:10px 12px; color:#6b7280;">Submitted At</td><td style="padding:10px 12px;">{{ $contact->created_at?->toDateTimeString() }}</td></tr>
                            </table>

                            <p style="margin:0 0 8px; font-size:13px; color:#64748b; letter-spacing:.3px; text-transform:uppercase;">Travel Details</p>
                            <div style="background:#f8fafc; border:1px solid #cbd5e1; border-radius:12px; padding:14px; white-space:pre-wrap; word-break:break-word; font-size:14px; line-height:1.7;">{{ $contact->details ?? '-' }}</div>

                            <p style="margin:22px 0 0;">
                                <a href="{{ url('/admin/contacts') }}" style="display:inline-block; background:linear-gradient(135deg, #0f766e, #0284c7); color:#ffffff; text-decoration:none; padding:11px 18px; border-radius:10px; font-size:14px; font-weight:700;">
                                    Open Contact Messages
                                </a>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:14px 24px; background:#f8fafc; border-top:1px solid #e2e8f0; color:#64748b; font-size:12px;">
                            Sent automatically by {{ config('app.name') }} notifications.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
