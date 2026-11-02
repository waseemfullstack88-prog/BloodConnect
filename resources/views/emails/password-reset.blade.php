<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="x-apple-disable-message-reformatting">
    <title>🔐 Reset Your Password — BloodConnect</title>
    <style>
        @media only screen and (max-width: 620px) {
            .email-wrapper { padding: 12px !important; }
            .email-body { padding: 28px 20px !important; }
        }
    </style>
</head>
<body style="margin:0; padding:0; background-color:#f4f4f7; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;">

<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" class="email-wrapper" style="background-color:#f4f4f7; padding:32px 16px;">
    <tr>
        <td align="center">
            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="600" style="max-width:600px; width:100%;">

                <!-- Header -->
                <tr>
                    <td align="center" style="background: linear-gradient(135deg, #8B0000 0%, #E63946 100%); border-radius:16px 16px 0 0; padding:36px 40px;">
                        <div style="background:rgba(255,255,255,0.15); border-radius:50%; width:72px; height:72px; display:inline-flex; align-items:center; justify-content:center; margin-bottom:16px;">
                            <span style="font-size:36px;">🔐</span>
                        </div>
                        <h1 style="color:#ffffff; font-size:26px; font-weight:800; margin:0; letter-spacing:-0.5px;">Password Reset Request</h1>
                        <p style="color:rgba(255,255,255,0.8); font-size:14px; margin:8px 0 0; text-transform:uppercase; letter-spacing:2px;">BloodConnect Security</p>
                    </td>
                </tr>

                <!-- Body -->
                <tr>
                    <td class="email-body" style="background:#ffffff; padding:40px 48px; border-radius:0 0 16px 16px;">

                        <p style="color:#374151; font-size:17px; font-weight:700; margin:0 0 8px;">Hello {{ $name }},</p>
                        <p style="color:#6b7280; font-size:15px; line-height:1.7; margin:0 0 28px;">
                            We received a request to reset the password for your <strong style="color:#E63946;">{{ ucfirst($role) }}</strong> account on BloodConnect.
                            If you made this request, click the button below to create a new password.
                        </p>

                        <!-- CTA Button -->
                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin-bottom:28px;">
                            <tr>
                                <td align="center">
                                    <a href="{{ $resetUrl }}"
                                       style="display:inline-block; background:linear-gradient(135deg,#E63946,#a81c26); color:#ffffff; text-decoration:none; font-size:17px; font-weight:700; padding:16px 40px; border-radius:12px; letter-spacing:0.3px; box-shadow:0 6px 20px rgba(230,57,70,0.35);">
                                        🔑 Reset My Password
                                    </a>
                                </td>
                            </tr>
                        </table>

                        <!-- Link fallback -->
                        <p style="color:#9ca3af; font-size:13px; margin:0 0 6px;">If the button doesn't work, copy and paste this link into your browser:</p>
                        <p style="word-break:break-all; font-size:13px; color:#E63946; margin:0 0 28px;">{{ $resetUrl }}</p>

                        <!-- Expiry notice -->
                        <div style="background:#fff7f7; border-left:4px solid #E63946; border-radius:8px; padding:16px 20px; margin-bottom:24px;">
                            <p style="color:#374151; font-size:14px; margin:0; font-weight:600;">⏰ This link expires in 60 minutes.</p>
                            <p style="color:#6b7280; font-size:13px; margin:6px 0 0;">If you did not request a password reset, please ignore this email. Your password will remain unchanged.</p>
                        </div>

                        <!-- Spam note -->
                        <p style="color:#9ca3af; font-size:12px; margin:0 0 24px;">
                            💡 <strong>Tip:</strong> If you don't see this email in your inbox, check your <strong>Spam</strong> or <strong>Junk</strong> folder and mark it as "Not Spam" to receive future emails in your inbox.
                        </p>

                        <hr style="border:none; border-top:1px solid #f3f4f6; margin:28px 0;">

                        <p style="color:#6b7280; font-size:13px; margin:0;">
                            This email was sent automatically by <strong style="color:#E63946;">BloodConnect</strong>. Please do not reply to this email.
                        </p>
                    </td>
                </tr>

                <!-- Footer -->
                <tr>
                    <td align="center" style="padding:20px 0;">
                        <p style="color:#9ca3af; font-size:12px; margin:0;">
                            © {{ date('Y') }} BloodConnect Pakistan — Saving Lives Together ❤️
                        </p>
                    </td>
                </tr>

            </table>
        </td>
    </tr>
</table>
</body>
</html>
