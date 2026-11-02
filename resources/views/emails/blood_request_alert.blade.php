<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="x-apple-disable-message-reformatting">
    <title>🩸 Blood Request Alert — BloodConnect</title>
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
            .blood-group-badge { font-size: 52px !important; padding: 18px 28px !important; }
            .detail-row { display: block !important; width: 100% !important; }
            .detail-cell { display: block !important; width: 100% !important; padding: 8px 0 !important; }
            .cta-button { display: block !important; text-align: center !important; }
            .contact-col { display: block !important; width: 100% !important; margin-bottom: 12px !important; }
        }
    </style>
</head>
<body style="margin:0; padding:0; background-color:#f4f4f7; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; -webkit-font-smoothing: antialiased;">

@php
    $urgencyLabel = $bloodRequest->urgency_level;
    $urgencyBg    = match($urgencyLabel) {
        'Critical' => '#E63946',
        'High'     => '#f39c12',
        default    => '#2196F3',
    };
    $urgencyTextColor = '#ffffff';
@endphp

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
                                    <span style="font-size:42px; line-height:1; display:inline-block;">🩸</span>
                                </td>
                            </tr>
                            <tr>
                                <td align="center">
                                    <h1 style="margin:0; font-size:28px; font-weight:800; color:#ffffff; letter-spacing:-0.5px; line-height:1.1;">
                                        BloodConnect
                                    </h1>
                                    <p style="margin:4px 0 0; font-size:12px; font-weight:600; color:rgba(255,255,255,0.75); letter-spacing:3px; text-transform:uppercase;">
                                        Saving Lives Together
                                    </p>
                                </td>
                            </tr>
                        </table>

                        <!-- Divider wave effect -->
                        <div style="margin-top:24px; border-top: 1px solid rgba(255,255,255,0.2);"></div>

                        <!-- Alert headline -->
                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin-top:20px;">
                            <tr>
                                <td align="center">
                                    <!-- Urgency Badge -->
                                    <span style="display:inline-block; background-color:rgba(255,255,255,0.18); border: 1.5px solid rgba(255,255,255,0.45); color:#ffffff; font-size:11px; font-weight:800; letter-spacing:2.5px; text-transform:uppercase; padding:5px 16px; border-radius:50px; margin-bottom:14px;">
                                        &#9888;&#65039;&nbsp; {{ strtoupper($urgencyLabel) }} ALERT
                                    </span>
                                    <h2 style="margin:0; font-size:22px; font-weight:700; color:#ffffff; line-height:1.35;">
                                        Urgent Blood Donation Needed
                                    </h2>
                                    <p style="margin:8px 0 0; font-size:15px; color:rgba(255,255,255,0.85); line-height:1.5;">
                                        Your blood group <strong style="color:#ffffff;">{{ $bloodRequest->blood_group }}</strong> matches a patient in need.
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
                            Hi <strong style="color:#1a1a2e;">{{ $donor->name }}</strong>,
                        </p>
                        <p style="margin:12px 0 0; font-size:15px; color:#555555; line-height:1.7;">
                            A patient in your area urgently needs <strong style="color:#E63946;">{{ $bloodRequest->blood_group }}</strong> blood.
                            As a registered donor with a matching blood group, you can make a life-saving difference right now.
                        </p>
                    </td>
                </tr>

                <!-- ═══════════════════════════════════════ -->
                <!--          BLOOD GROUP HERO CARD          -->
                <!-- ═══════════════════════════════════════ -->
                <tr>
                    <td style="padding: 28px 40px 0;">

                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background: linear-gradient(135deg, #fff5f5 0%, #fff0f0 100%); border: 2px solid #ffd0d3; border-radius:14px; overflow:hidden;">
                            <tr>
                                <!-- Left: Blood group badge -->
                                <td width="160" style="padding:28px 20px; text-align:center; border-right:2px dashed #ffd0d3; vertical-align:middle;">
                                    <div class="blood-group-badge" style="display:inline-block; background: linear-gradient(135deg, #c0202e, #E63946); color:#ffffff; font-size:60px; font-weight:900; padding:20px 24px; border-radius:14px; line-height:1; letter-spacing:-2px; box-shadow:0 6px 24px rgba(230,57,70,0.35);">
                                        {{ $bloodRequest->blood_group }}
                                    </div>
                                    <p style="margin:10px 0 0; font-size:11px; font-weight:700; color:#E63946; text-transform:uppercase; letter-spacing:1.5px;">Blood Group</p>
                                </td>

                                <!-- Right: Request details -->
                                <td style="padding:28px 24px; vertical-align:top;">

                                    <!-- Patient Name -->
                                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin-bottom:14px;">
                                        <tr>
                                            <td>
                                                <p style="margin:0; font-size:11px; font-weight:700; color:#999999; text-transform:uppercase; letter-spacing:1.5px;">Patient Name</p>
                                                <p style="margin:4px 0 0; font-size:17px; font-weight:700; color:#1a1a2e;">{{ $bloodRequest->patient_name }}</p>
                                            </td>
                                        </tr>
                                    </table>

                                    <!-- Divider -->
                                    <div style="border-top:1px solid #ffd0d3; margin-bottom:14px;"></div>

                                    <!-- Units & Urgency side by side -->
                                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                        <tr>
                                            <td class="detail-cell" width="50%" style="vertical-align:top; padding-right:10px;">
                                                <p style="margin:0; font-size:11px; font-weight:700; color:#999999; text-transform:uppercase; letter-spacing:1.5px;">Units Needed</p>
                                                <p style="margin:4px 0 0; font-size:22px; font-weight:800; color:#E63946;">
                                                    {{ $bloodRequest->units }}
                                                    <span style="font-size:13px; font-weight:600; color:#777777;">bag{{ $bloodRequest->units > 1 ? 's' : '' }}</span>
                                                </p>
                                            </td>
                                            <td class="detail-cell" width="50%" style="vertical-align:top; padding-left:10px;">
                                                <p style="margin:0; font-size:11px; font-weight:700; color:#999999; text-transform:uppercase; letter-spacing:1.5px;">Urgency</p>
                                                <p style="margin:4px 0 0;">
                                                    <span style="display:inline-block; background-color:{{ $urgencyBg }}; color:#ffffff; font-size:12px; font-weight:800; padding:4px 14px; border-radius:50px; letter-spacing:0.5px;">
                                                        {{ $urgencyLabel }}
                                                    </span>
                                                </p>
                                            </td>
                                        </tr>
                                    </table>

                                    <!-- Divider -->
                                    <div style="border-top:1px solid #ffd0d3; margin:14px 0;"></div>

                                    <!-- Hospital Location -->
                                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                        <tr>
                                            <td>
                                                <p style="margin:0; font-size:11px; font-weight:700; color:#999999; text-transform:uppercase; letter-spacing:1.5px;">Hospital / Location</p>
                                                <p style="margin:4px 0 0; font-size:15px; font-weight:600; color:#1a1a2e;">
                                                    📍 {{ $bloodRequest->hospital_location }}
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
                <!--         REQUESTER CONTACT SECTION       -->
                <!-- ═══════════════════════════════════════ -->
                <tr>
                    <td style="padding: 24px 40px 0;">

                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color:#f8f9ff; border:1.5px solid #e8eaf6; border-radius:12px; overflow:hidden;">
                            <tr>
                                <td style="padding:20px 24px 14px;">
                                    <p style="margin:0 0 14px; font-size:13px; font-weight:800; color:#444444; text-transform:uppercase; letter-spacing:1.5px; border-bottom:1.5px solid #e8eaf6; padding-bottom:12px;">
                                        👤&nbsp; Requester Contact
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:0 24px 22px;">
                                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                        <tr>
                                            <!-- Name -->
                                            <td class="contact-col" width="34%" style="vertical-align:top; padding-right:10px;">
                                                <p style="margin:0; font-size:11px; font-weight:700; color:#aaaaaa; text-transform:uppercase; letter-spacing:1px;">Name</p>
                                                <p style="margin:5px 0 0; font-size:15px; font-weight:700; color:#1a1a2e;">
                                                    {{ optional($requester)->name ?? 'N/A' }}
                                                </p>
                                            </td>

                                            <!-- Phone -->
                                            <td class="contact-col" width="33%" style="vertical-align:top; padding: 0 10px;">
                                                <p style="margin:0; font-size:11px; font-weight:700; color:#aaaaaa; text-transform:uppercase; letter-spacing:1px;">📞 Phone</p>
                                                <p style="margin:5px 0 0; font-size:15px; font-weight:700; color:#1a1a2e;">
                                                    {{ optional($requester)->phone ?? 'N/A' }}
                                                </p>
                                            </td>

                                            <!-- WhatsApp -->
                                            <td class="contact-col" width="33%" style="vertical-align:top; padding-left:10px;">
                                                <p style="margin:0; font-size:11px; font-weight:700; color:#aaaaaa; text-transform:uppercase; letter-spacing:1px;">
                                                    <span style="color:#25D366;">&#9679;</span> WhatsApp
                                                </p>
                                                @if(optional($requester)->whatsapp_number)
                                                    <p style="margin:5px 0 0;">
                                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $requester->whatsapp_number) }}"
                                                           style="font-size:15px; font-weight:700; color:#25D366; text-decoration:none;">
                                                            {{ $requester->whatsapp_number }}
                                                        </a>
                                                    </p>
                                                @else
                                                    <p style="margin:5px 0 0; font-size:15px; font-weight:700; color:#1a1a2e;">N/A</p>
                                                @endif
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
                            Ready to be a hero? Log in to your BloodConnect dashboard to view the full request and connect with the patient's family.
                        </p>
                        <!-- CTA Button -->
                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="margin:0 auto;">
                            <tr>
                                <td align="center" style="border-radius:50px; background: linear-gradient(135deg, #c0202e, #E63946);">
                                    <a href="{{ url('/dashboard') }}"
                                       class="cta-button"
                                       style="display:inline-block; padding:16px 48px; font-size:16px; font-weight:800; color:#ffffff; text-decoration:none; border-radius:50px; letter-spacing:0.3px; line-height:1;">
                                        🩸&nbsp; View Blood Request
                                    </a>
                                </td>
                            </tr>
                        </table>

                        <!-- Secondary note -->
                        <p style="margin:18px 0 0; font-size:13px; color:#aaaaaa;">
                            Or copy this link: <a href="{{ url('/dashboard') }}" style="color:#E63946; text-decoration:none; font-weight:600;">{{ url('/dashboard') }}</a>
                        </p>
                    </td>
                </tr>

                <!-- ═══════════════════════════════════════ -->
                <!--           MOTIVATIONAL STRIP            -->
                <!-- ═══════════════════════════════════════ -->
                <tr>
                    <td style="padding: 28px 40px 0;">
                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background: linear-gradient(135deg, #1a1a2e, #16213e); border-radius:12px;">
                            <tr>
                                <td style="padding: 22px 28px; text-align:center;">
                                    <p style="margin:0; font-size:16px; font-weight:700; color:#ffffff; line-height:1.5;">
                                        ❤️&nbsp; <em>"One donation can save up to 3 lives."</em>
                                    </p>
                                    <p style="margin:6px 0 0; font-size:13px; color:rgba(255,255,255,0.55);">
                                        Every second counts. Your action today can change a family's tomorrow.
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
                            You received this alert because your blood group
                            <strong style="color:#E63946;">{{ $donor->blood_group }}</strong>
                            matches this request and you are a registered donor on BloodConnect.
                        </p>

                        <!-- Divider -->
                        <div style="border-top:1px solid #f0f0f0; margin:16px auto; max-width:80%;"></div>

                        <p style="margin:0; font-size:12px; color:#bbbbbb; line-height:1.6;">
                            &copy; {{ date('Y') }} BloodConnect &mdash; Connecting Donors &amp; Patients across Punjab, Pakistan.<br>
                            If you no longer wish to receive these notifications, please update your
                            <a href="{{ url('/dashboard') }}" style="color:#E63946; text-decoration:none; font-weight:600;">notification preferences</a>.
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
