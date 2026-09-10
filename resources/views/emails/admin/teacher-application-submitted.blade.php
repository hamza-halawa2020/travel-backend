<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>New Teacher Application</title>
</head>
<body style="margin:0; padding:0; background:#eff6ff; font-family:Tahoma, Arial, Helvetica, sans-serif; color:#1f2937;">
    <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="background:radial-gradient(circle at top, #dbeafe 0%, #eff6ff 45%, #f8fafc 100%); padding:28px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="max-width:680px; background:#ffffff; border-radius:16px; overflow:hidden; border:1px solid #bfdbfe; box-shadow:0 14px 34px rgba(37, 99, 235, 0.14);">
                    <tr>
                        <td style="background:linear-gradient(130deg, #1d4ed8, #2563eb 55%, #38bdf8); padding:26px 24px;">
                            <p style="margin:0; color:#dbeafe; font-size:12px; letter-spacing:1px; text-transform:uppercase;">Hiring Notification</p>
                            <h1 style="margin:8px 0 0; color:#ffffff; font-size:26px; line-height:1.25;">New Teacher Application</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:24px;">
                            <p style="margin:0 0 14px; font-size:15px; font-weight:700;">Hello Admin,</p>
                            <p style="margin:0 0 18px; font-size:15px; color:#4b5563; line-height:1.7;">
                                A new teacher application was submitted on <strong>{{ config('app.name') }}</strong>.
                            </p>

                            <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:separate; border-spacing:0; margin-bottom:18px; font-size:14px; background:#f8fbff; border:1px solid #bfdbfe; border-radius:12px; overflow:hidden;">
                                <tr><td style="padding:10px 12px; color:#1d4ed8; width:170px; border-bottom:1px solid #bfdbfe;">Applicant Name</td><td style="padding:10px 12px; border-bottom:1px solid #bfdbfe;"><strong>{{ $application->name }}</strong></td></tr>
                                <tr><td style="padding:10px 12px; color:#1d4ed8; border-bottom:1px solid #bfdbfe;">Email</td><td style="padding:10px 12px; border-bottom:1px solid #bfdbfe;">{{ $application->email }}</td></tr>
                                <tr><td style="padding:10px 12px; color:#1d4ed8; border-bottom:1px solid #bfdbfe;">Phone</td><td style="padding:10px 12px; border-bottom:1px solid #bfdbfe;">{{ $application->phone }}</td></tr>
                                <tr><td style="padding:10px 12px; color:#1d4ed8; border-bottom:1px solid #bfdbfe;">Country</td><td style="padding:10px 12px; border-bottom:1px solid #bfdbfe;">{{ $application->country }}</td></tr>
                                <tr><td style="padding:10px 12px; color:#1d4ed8; border-bottom:1px solid #bfdbfe;">Job Title</td><td style="padding:10px 12px; border-bottom:1px solid #bfdbfe;">{{ $application->job_title }}</td></tr>
                                <tr><td style="padding:10px 12px; color:#1d4ed8;">Submitted At</td><td style="padding:10px 12px;">{{ $application->created_at?->toDateTimeString() }}</td></tr>
                            </table>

                            <p style="margin:0 0 8px; font-size:13px; color:#1e40af; letter-spacing:.3px; text-transform:uppercase;">Application Message</p>
                            <div style="background:#f8fbff; border:1px solid #93c5fd; border-radius:12px; padding:14px; white-space:pre-wrap; word-break:break-word; font-size:14px; line-height:1.7;">{{ $application->description }}</div>

                            <p style="margin:22px 0 0;">
                                <a href="{{ url('/admin/staff') }}" style="display:inline-block; background:linear-gradient(135deg, #1d4ed8, #2563eb); color:#ffffff; text-decoration:none; padding:11px 18px; border-radius:10px; font-size:14px; font-weight:700;">
                                    Open Staff Applications
                                </a>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:14px 24px; background:#f8fbff; border-top:1px solid #bfdbfe; color:#1e40af; font-size:12px;">
                            Sent automatically by {{ config('app.name') }} notifications.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
