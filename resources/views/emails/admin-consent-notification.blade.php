<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Admin Consent Notification</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f6f8; font-family: Arial, Helvetica, sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f6f8; padding: 30px 0;">
        <tr>
            <td align="center">

                <!-- Main Container -->
                <table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff; border-radius:8px; overflow:hidden; box-shadow:0 3px 8px rgba(0,0,0,0.05);">

                    <!-- Header -->
                    <tr>
                        <td style="background-color:#2c3e50; padding:20px; text-align:center;">
                            <h2 style="color:#ffffff; margin:0; font-weight:600;">
                                New Document Signed
                            </h2>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding:30px; color:#333333; font-size:14px; line-height:1.6;">

                            <p style="margin-top:0;">
                                Hello,
                            </p>

                            <p>
                                A new document has been submitted by a patient.
                                Please find the attached signed consent form for your review.
                            </p>

                            <table width="100%" cellpadding="8" cellspacing="0" style="margin-top:20px; border-collapse:collapse;">
                                <tr style="background-color:#f8f9fa;">
                                    <td style="border:1px solid #eaeaea;"><strong>Patient ID</strong></td>
                                    <td style="border:1px solid #eaeaea;">{{ $patient->patient_id ?? 'N/A' }}</td>
                                </tr>
                               <tr>
                                    <td style="border:1px solid #eaeaea;"><strong>Name</strong></td>
                                    <td style="border:1px solid #eaeaea;">
                                        {{ $patient->first_name ?? '' }} {{ $patient->last_name ?? 'N/A' }}
                                    </td>
                                </tr>
                                <tr style="background-color:#f8f9fa;">
                                    <td style="border:1px solid #eaeaea;"><strong>Email</strong></td>
                                    <td style="border:1px solid #eaeaea;">{{ $patient->email ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td style="border:1px solid #eaeaea;"><strong>Submitted At</strong></td>
                                    <td style="border:1px solid #eaeaea;">{{ now()->format('F d, Y h:i A') }}</td>
                                </tr>
                            </table>

                            <p style="margin-top:25px;">
                                Kindly review the attached document at your earliest convenience.
                            </p>

                        </td>
                    </tr>

                    <!-- Footer -->
                    {{-- <tr>
                        <td style="background-color:#f1f1f1; padding:20px; text-align:center; font-size:12px; color:#777777;">
                            © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                        </td>
                    </tr> --}}

                </table>

            </td>
        </tr>
    </table>

</body>
</html>
