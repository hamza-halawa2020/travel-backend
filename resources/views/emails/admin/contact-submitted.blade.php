<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>New Enquiry</title>
</head>
<body style="margin:0;padding:0;background:#f3f4f6;font-family:Arial,Helvetica,sans-serif;color:#111827;">
    <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="padding:32px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="max-width:600px;background:#ffffff;border-radius:10px;overflow:hidden;border:1px solid #e5e7eb;">

                    {{-- Header --}}
                    <tr>
                        <td style="background:#111827;padding:24px;">
                            <p style="margin:0;color:#ffffff;font-size:18px;font-weight:700;">New Enquiry</p>
                            <p style="margin:4px 0 0;color:#9ca3af;font-size:13px;">{{ $contact->created_at?->toDateTimeString() }}</p>
                        </td>
                    </tr>

                    {{-- Details --}}
                    <tr>
                        <td style="padding:24px;">
                            <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="font-size:14px;border-collapse:collapse;">

                                <tr>
                                    <td style="padding:10px 0;color:#6b7280;width:140px;border-bottom:1px solid #f3f4f6;">Name</td>
                                    <td style="padding:10px 0;font-weight:600;border-bottom:1px solid #f3f4f6;">{{ $contact->name }}</td>
                                </tr>

                                <tr>
                                    <td style="padding:10px 0;color:#6b7280;border-bottom:1px solid #f3f4f6;">Phone</td>
                                    <td style="padding:10px 0;border-bottom:1px solid #f3f4f6;">{{ $contact->phone }}</td>
                                </tr>

                                <tr>
                                    <td style="padding:10px 0;color:#6b7280;border-bottom:1px solid #f3f4f6;">Service</td>
                                    <td style="padding:10px 0;border-bottom:1px solid #f3f4f6;">{{ $contact->service ?? '-' }}</td>
                                </tr>

                                @if($contact->field_a)
                                <tr>
                                    <td style="padding:10px 0;color:#6b7280;border-bottom:1px solid #f3f4f6;">Field A</td>
                                    <td style="padding:10px 0;border-bottom:1px solid #f3f4f6;">{{ $contact->field_a }}</td>
                                </tr>
                                @endif

                                @if($contact->details)
                                <tr>
                                    <td style="padding:10px 0;color:#6b7280;vertical-align:top;">Details</td>
                                    <td style="padding:10px 0;white-space:pre-wrap;line-height:1.6;">{{ $contact->details }}</td>
                                </tr>
                                @endif

                            </table>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="padding:16px 24px;background:#f9fafb;border-top:1px solid #e5e7eb;">
                            <a href="{{ url('/admin/enquiries') }}" style="color:#111827;font-size:13px;text-decoration:none;font-weight:600;">View in Dashboard →</a>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
