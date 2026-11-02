<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="x-apple-disable-message-reformatting">
    <title>🎉 Hero Status Restored — BloodConnect</title>
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
            .hero-badge { font-size: 52px !important; padding: 18px 28px !important; }
            .cta-button { display: block !important; text-align: center !important; }
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
                    <td style="background: linear-gradient(135deg, #10b981 0%, #059669 50%, #34d399 100%); padding: 36px 40px 28px; text-align:center;">

                        <!-- Logo -->
                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tr>
                                <td align="center" style="padding-bottom: 6px;">
                                    <span style="font-size:42px; line-height:1; display:inline-block;">🎉</span>
                                </td>
                            </tr>
                            <tr>
                                <td align="center">
                                    <h1 style="margin:0; font-size:28px; font-weight:800; color:#ffffff; letter-spacing:-0.5px; line-height:1.1;">
                                        BloodConnect
                                    </h1>
                                    <p style="margin:4px 0 0; font-size:12px; font-weight:600; color:rgba(255,255,255,0.75); letter-spacing:3px; text-transform:uppercase;">
                                        Celebrating Our Life Savers
                                    </p>
                                </td>
                            </tr>
                        </table>

                        <!-- Divider wave effect -->
                        <div style="margin-top:24px; border-top: 1px solid rgba(255,255,255,0.2);"></div>

                        <!-- Announcement headline -->
                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin-top:20px;">
                            <tr>
                                <td align="center">
                                    <!-- Hero Badge -->
                                    <span style="display:inline-block; background-color:rgba(255,255,255,0.18); border: 1.5px solid rgba(255,255,255,0.45); color:#ffffff; font-size:11px; font-weight:800; letter-spacing:2.5px; text-transform:uppercase; padding:5px 16px; border-radius:50px; margin-bottom:14px;">
                                        🌟 HERO STATUS ACTIVATED
                                    </span>
                                    <h2 style="margin:0; font-size:22px; font-weight:700; color:#ffffff; line-height:1.35;">
                                        Welcome Back, Hero!
                                    </h2>
                                    <p style="margin:8px 0 0; font-size:15px; color:rgba(255,255,255,0.85); line-height:1.5;">
                                        Your 90-day recovery rest is complete. You are eligible to donate again!
                                    </p>
                                </td>
                            </tr>
                        </table>

                    </td>
                </tr>

                <!-- ═══════════════════════════════════════ -->
                <!--           GREETING TO DONOR             -->
                <!-- ═══════════════════════════════════════ -->
                <tr>
                    <td class="email-body" style="padding: 36px 40px 0;">
                        <p style="margin:0; font-size:16px; color:#333333; line-height:1.6;">
                            Dear <strong style="color:#065f46;">{{ $donor->name }}</strong>,
                        </p>
                        <p style="margin:12px 0 0; font-size:15px; color:#555555; line-height:1.7;">
                            We hope you are feeling great! Your generous action of donating blood previously helped save lives, and we can't thank you enough.
                        </p>
                        <p style="margin:12px 0 0; font-size:15px; color:#555555; line-height:1.7;">
                            As of today, your 90-day physical recovery and resting period has **successfully completed**. Your profile status has been automatically updated back to <strong style="color:#10b981;">Available Now</strong> in our directory.
                        </p>
                    </td>
                </tr>

                <!-- ═══════════════════════════════════════ -->
                <!--          ELIGIBILITY STATUS CARD        -->
                <!-- ═══════════════════════════════════════ -->
                <tr>
                    <td style="padding: 28px 40px 0;">

                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background: linear-gradient(135deg, #ecfdf5 0%, #f0fdf4 100%); border: 2px solid #a7f3d0; border-radius:14px; overflow:hidden;">
                            <tr>
                                <!-- Left: Blood group badge -->
                                <td width="160" style="padding:28px 20px; text-align:center; border-right:2px dashed #a7f3d0; vertical-align:middle;">
                                    <div class="hero-badge" style="display:inline-block; background: linear-gradient(135deg, #059669, #10b981); color:#ffffff; font-size:48px; font-weight:900; padding:16px 20px; border-radius:14px; line-height:1; letter-spacing:-2px; box-shadow:0 6px 24px rgba(16,185,129,0.35);">
                                        {{ $donor->blood_group }}
                                    </div>
                                    <p style="margin:10px 0 0; font-size:11px; font-weight:700; color:#059669; text-transform:uppercase; letter-spacing:1.5px;">Your Group</p>
                                </td>

                                <!-- Right: Status details -->
                                <td style="padding:28px 24px; vertical-align:middle;">

                                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin-bottom:10px;">
                                        <tr>
                                            <td>
                                                <p style="margin:0; font-size:11px; font-weight:700; color:#9ca3af; text-transform:uppercase; letter-spacing:1.5px;">Last Donation Date</p>
                                                <p style="margin:4px 0 0; font-size:16px; font-weight:700; color:#1f2937;">
                                                    📅 {{ $donor->last_donation_date ? $donor->last_donation_date->format('d M, Y') : 'N/A' }}
                                                </p>
                                            </td>
                                        </tr>
                                    </table>

                                    <!-- Divider -->
                                    <div style="border-top:1px solid #a7f3d0; margin-bottom:10px;"></div>

                                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                        <tr>
                                            <td>
                                                <p style="margin:0; font-size:11px; font-weight:700; color:#9ca3af; text-transform:uppercase; letter-spacing:1.5px;">Current Status</p>
                                                <p style="margin:4px 0 0; font-size:16px; font-weight:700; color:#059669;">
                                                    🟢 Available & Eligible to Donate
                                                </p>
                                            </td>
                                        </tr>
                                    </table>

                                </td>
                            </tr>
                        </table>

                    </td>
                </tr>

                <!-- ═══════════════════════════════════════ -->
                <!--              CTA BUTTON                 -->
                <!-- ═══════════════════════════════════════ -->
                <tr>
                    <td style="padding: 32px 40px 8px; text-align:center;">
                        <p style="margin:0 0 20px; font-size:15px; color:#555555; line-height:1.6;">
                            Log in to your BloodConnect dashboard to see if there are any active requests matching your blood group in your city today!
                        </p>
                        <!-- CTA Button -->
                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="margin:0 auto;">
                            <tr>
                                <td align="center" style="border-radius:50px; background: linear-gradient(135deg, #059669, #10b981);">
                                    <a href="{{ url('/dashboard') }}"
                                       class="cta-button"
                                       style="display:inline-block; padding:16px 48px; font-size:16px; font-weight:800; color:#ffffff; text-decoration:none; border-radius:50px; letter-spacing:0.3px; line-height:1;">
                                        🩸&nbsp; View Dashboard
                                    </a>
                                </td>
                            </tr>
                        </table>

                        <p style="margin:18px 0 0; font-size:13px; color:#aaaaaa;">
                            Or copy this link: <a href="{{ url('/dashboard') }}" style="color:#059669; text-decoration:none; font-weight:600;">{{ url('/dashboard') }}</a>
                        </p>
                    </td>
                </tr>

                <!-- ═══════════════════════════════════════ -->
                <!--           MOTIVATIONAL STRIP            -->
                <!-- ═══════════════════════════════════════ -->
                <tr>
                    <td style="padding: 28px 40px 0;">
                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background: linear-gradient(135deg, #111827, #1f2937); border-radius:12px;">
                            <tr>
                                <td style="padding: 22px 28px; text-align:center;">
                                    <p style="margin:0; font-size:16px; font-weight:700; color:#ffffff; line-height:1.5;">
                                        ❤️&nbsp; <em>"The blood you donate gives someone another chance at life."</em>
                                    </p>
                                    <p style="margin:6px 0 0; font-size:13px; color:rgba(255,255,255,0.55);">
                                        Thank you for standing tall as a shield for our community.
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <!-- ═══════════════════════════════════════ -->
                <!--                 FOOTER                  -->
                <!-- ═══════════════════════════════════════ -->
                <tr>
                    <td style="padding: 28px 40px 36px; text-align:center; border-top: 1px solid #f0f0f0; margin-top:28px;">

                        <!-- Footer logo -->
                        <p style="margin:0 0 8px; font-size:18px; font-weight:800; color:#E63946;">
                            🩸 BloodConnect
                        </p>

                        <!-- Footer notice -->
                        <p style="margin:0 0 12px; font-size:13px; color:#888888; line-height:1.6;">
                            You received this email because you are registered as a life saver on BloodConnect.
                        </p>

                        <!-- Divider -->
                        <div style="border-top:1px solid #f0f0f0; margin:16px auto; max-width:80%;"></div>

                        <p style="margin:0; font-size:12px; color:#bbbbbb; line-height:1.6;">
                            &copy; {{ date('Y') }} BloodConnect &mdash; Connecting Donors &amp; Patients across Punjab, Pakistan.<br>
                            If you have any questions or want to update your donor status, please visit your <a href="{{ url('/dashboard') }}" style="color:#059669; text-decoration:none; font-weight:600;">profile settings</a>.
                        </p>

                    </td>
                </tr>

            </table>
            <!-- /Email Container -->

            <!-- Below-container note -->
            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="600" style="max-width:600px; width:100%; margin-top:16px;">
                <tr>
                    <td style="text-align:center;">
                        <p style="margin:0; font-size:12px; color:#aaaaaa;">
                            This is an automated notification from BloodConnect. Please do not reply to this email.
                        </p>
                    </td>
                </tr>
            </table>

        </td>
    </tr>
</table>
<!-- /Email Wrapper -->

</body>
</html>
