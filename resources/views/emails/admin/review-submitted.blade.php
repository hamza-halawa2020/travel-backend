<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>New Review Submitted</title>
</head>
<body style="margin:0; padding:0; background:#fff7ed; font-family:Tahoma, Arial, Helvetica, sans-serif; color:#1f2937;">
    <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="background:radial-gradient(circle at top, #ffedd5 0%, #fff7ed 45%, #f5f7fb 100%); padding:28px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="max-width:680px; background:#ffffff; border-radius:16px; overflow:hidden; border:1px solid #fed7aa; box-shadow:0 14px 34px rgba(194, 65, 12, 0.14);">
                    <tr>
                        <td style="background:linear-gradient(130deg, #9a3412, #ea580c 55%, #fb923c); padding:26px 24px;">
                            <p style="margin:0; color:#ffedd5; font-size:12px; letter-spacing:1px; text-transform:uppercase;">Reviews Moderation</p>
                            <h1 style="margin:8px 0 0; color:#ffffff; font-size:26px; line-height:1.25;">New Student Review</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:24px;">
                            <p style="margin:0 0 14px; font-size:15px; font-weight:700;">Hello Admin,</p>
                            <p style="margin:0 0 18px; font-size:15px; color:#4b5563; line-height:1.7;">
                                A new review was submitted on <strong>{{ config('app.name') }}</strong>.
                            </p>

                            <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:separate; border-spacing:0; margin-bottom:18px; font-size:14px; background:#fffaf5; border:1px solid #fed7aa; border-radius:12px; overflow:hidden;">
                                <tr><td style="padding:10px 12px; color:#7c2d12; width:170px; border-bottom:1px solid #fed7aa;">Reviewer Name</td><td style="padding:10px 12px; border-bottom:1px solid #fed7aa;"><strong>{{ $review->name }}</strong></td></tr>
                                <tr>
                                    <td style="padding:10px 12px; color:#7c2d12; border-bottom:1px solid #fed7aa;">Current Status</td>
                                    <td style="padding:10px 12px; border-bottom:1px solid #fed7aa;">
                                        <span style="display:inline-block; padding:4px 10px; border-radius:999px; font-size:12px; font-weight:700; color:#ffffff; background:{{ $review->status ? '#15803d' : '#9a3412' }};">
                                            {{ $review->status ? 'Approved' : 'Pending approval' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr><td style="padding:10px 12px; color:#7c2d12;">Submitted At</td><td style="padding:10px 12px;">{{ $review->created_at?->toDateTimeString() }}</td></tr>
                            </table>

                            <p style="margin:0 0 8px; font-size:13px; color:#9a3412; letter-spacing:.3px; text-transform:uppercase;">Review Text</p>
                            <div style="background:#fff7ed; border:1px solid #fdba74; border-radius:12px; padding:14px; white-space:pre-wrap; word-break:break-word; font-size:14px; line-height:1.7;">{{ $review->review }}</div>

                            <p style="margin:22px 0 0;">
                                <a href="{{ url('/admin/reviews') }}" style="display:inline-block; background:linear-gradient(135deg, #9a3412, #ea580c); color:#ffffff; text-decoration:none; padding:11px 18px; border-radius:10px; font-size:14px; font-weight:700;">
                                    Open Reviews Moderation
                                </a>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:14px 24px; background:#fffaf5; border-top:1px solid #fed7aa; color:#9a3412; font-size:12px;">
                            Sent automatically by {{ config('app.name') }} notifications.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
