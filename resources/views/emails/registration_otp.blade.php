<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="x-apple-disable-message-reformatting">
    <title>🔑 Verify Your Registration — BloodConnect</title>
    <!--[if mso]>
    <noscript>
        <xml>
            <o:OfficeDocumentSettings>
                <o:PixelsPerInch>96</o:PixelsPerInch>
            </o:OfficeDocumentSettings>
        </xml>
    </noscript>
    <![endif]-->
    <style>
        @media only screen and (max-width: 620px) {
            .email-wrapper { padding: 12px !important; }
            .email-body { padding: 28px 20px !important; }
            .otp-box { font-size: 32px !important; letter-spacing: 4px !important; padding: 14px !important; }
        }
    </style>
</head>
<body style="margin:0; padding:0; background-color:#f4f4f7; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; -webkit-font-smoothing: antialiased;">

<!-- Email Wrapper -->
<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" class="email-wrapper" style="background-color:#f4f4f7; padding: 32px 16px;">
    <tr>
        <td align="center">

            <!-- Email Container -->
            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="600" style="max-width:600px; width:100%; background-color:#ffffff; border-radius:16px; overflow:hidden; box-shadow:0 8px 40px rgba(0,0,0,0.10);">

                <!-- ═══════════════════════════════════════ -->
                <!--              HEADER BANNER              -->
                <!-- ═══════════════════════════════════════ -->
                <tr>
                    <td style="background: linear-gradient(135deg, #c0202e 0%, #E63946 50%, #ff6b6b 100%); padding: 36px 40px 28px; text-align:center;">

                        <!-- Logo -->
                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tr>
                                <td align="center" style="padding-bottom: 6px;">
                                    <span style="font-size:42px; line-height:1; display:inline-block;">🔑</span>
                                </td>
                            </tr>
                            <tr>
                                <td align="center">
                                    <h1 style="margin:0; font-size:28px; font-weight:800; color:#ffffff; letter-spacing:-0.5px; line-height:1.1;">
                                        BloodConnect
                                    </h1>
                                    <p style="margin:4px 0 0; font-size:12px; font-weight:600; color:rgba(255,255,255,0.75); letter-spacing:3px; text-transform:uppercase;">
                                        Verify Your Identity
                                    </p>
                                </td>
                            </tr>
                        </table>

                        <!-- Divider wave effect -->
                        <div style="margin-top:24px; border-top: 1px solid rgba(255,255,255,0.2);"></div>

                        <!-- Header Headline -->
                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin-top:20px;">
                            <tr>
                                <td align="center">
                                    <h2 style="margin:0; font-size:22px; font-weight:700; color:#ffffff; line-height:1.35;">
                                        One-Time Password (OTP)
                                    </h2>
                                </td>
                            </tr>
                        </table>

                    </td>
                </tr>

                <!-- ═══════════════════════════════════════ -->
                <!--           GREETING TO USER              -->
                <!-- ═══════════════════════════════════════ -->
                <tr>
                    <td class="email-body" style="padding: 36px 40px 0;">
                        <p style="margin:0; font-size:16px; color:#333333; line-height:1.6;">
                            Hi <strong style="color:#1a1a2e;">{{ $name }}</strong>,
                        </p>
                        <p style="margin:12px 0 0; font-size:15px; color:#555555; line-height:1.7;">
                            Thank you for initiating your registration on BloodConnect! We are thrilled to welcome you to our community.
                        </p>
                        <p style="margin:12px 0 0; font-size:15px; color:#555555; line-height:1.7;">
                            To complete your email verification and activate your profile, please enter the following 6-digit OTP code on the verification page:
                        </p>
                    </td>
                </tr>

                <!-- ═══════════════════════════════════════ -->
                <!--               OTP CARD                  -->
                <!-- ═══════════════════════════════════════ -->
                <tr>
                    <td style="padding: 28px 40px 0;">

                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background: linear-gradient(135deg, #fff5f5 0%, #fff0f0 100%); border: 2px solid #ffd0d3; border-radius:14px; text-align:center;">
                            <tr>
                                <td style="padding: 32px 24px;">
                                    <div class="otp-box" style="display:inline-block; letter-spacing: 8px; font-size:42px; font-weight:900; color:#E63946; background:#ffffff; border: 1.5px solid #ffd0d3; padding:16px 36px; border-radius:12px; box-shadow:0 6px 20px rgba(230,57,70,0.1);">
                                        {{ $otp }}
                                    </div>
                                    <p style="margin:16px 0 0; font-size:13px; font-weight:700; color:#555555; text-transform:uppercase; letter-spacing:1.5px;">
                                        Valid for 15 Minutes Only
                                    </p>
                                </td>
                            </tr>
                        </table>

                    </td>
                </tr>

                <!-- ═══════════════════════════════════════ -->
                <!--           MOTIVATIONAL STRIP            -->
                <!-- ═══════════════════════════════════════ -->
                <tr>
                    <td style="padding: 32px 40px 0;">
                        <p style="margin:0; font-size:14px; color:#777777; line-height:1.6; text-align:center;">
                            If you did not initiate this registration request, you can safely ignore this email. No account will be created until this code is successfully entered.
                        </p>
                    </td>
                </tr>

                <!-- ═══════════════════════════════════════ -->
                <!--                 FOOTER                  -->
                <!-- ═══════════════════════════════════════ -->
                <tr>
                    <td style="padding: 28px 40px 36px; text-align:center; border-top: 1px solid #f0f0f0; margin-top:28px;">

                        <!-- Footer logo -->
                        <p style="margin:0 0 8px; font-size:18px; font-weight:800; color:#E63946;">
                            Submitting Circle of Hope
                        </p>

                        <!-- Divider -->
                        <div style="border-top:1px solid #f0f0f0; margin:16px auto; max-width:80%;"></div>

                        <p style="margin:0; font-size:12px; color:#bbbbbb; line-height:1.6;">
                            &copy; {{ date('Y') }} BloodConnect &mdash; Connecting Donors &amp; Patients across Punjab, Pakistan.<br>
                            This is an automated security notification. Please do not reply to this message.
                        </p>

                    </td>
                </tr>

            </table>
            <!-- /Email Container -->

        </td>
    </tr>
</table>
<!-- /Email Wrapper -->

</body>
</html>
