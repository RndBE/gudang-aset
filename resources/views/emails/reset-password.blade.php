<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Reset Password AWASS</title>
</head>

<body style="margin:0;padding:0;background:#f3f4f6;font-family:Arial,Helvetica,sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0" style="padding:30px 10px;">
        <tr>
            <td align="center">

                <table width="100%" max-width="520" cellpadding="0" cellspacing="0"
                    style="background:#ffffff;border-radius:12px;padding:28px;border:1px solid #e5e7eb;">

                    <tr>
                        <td align="center" style="padding-bottom:18px;">
                            <div style="font-size:22px;font-weight:bold;color:#111827;">
                                AWASS
                            </div>
                            <div style="font-size:13px;color:#6b7280;">
                                Advance Warehouse Smart System
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td style="font-size:14px;color:#374151;line-height:1.6;">
                            Halo <strong>{{ $user->nama_lengkap ?? 'Pengguna AWASS' }}</strong>,
                            <br><br>
                            Kami menerima permintaan untuk mereset kata sandi akun kamu.
                            Klik tombol di bawah untuk membuat password baru.
                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="padding:28px 0;">
                            <a href="{{ $resetLink }}"
                                style="background:#C58D2A;color:#ffffff;text-decoration:none;padding:12px 22px;border-radius:8px;font-weight:bold;display:inline-block;">
                                Reset Password
                            </a>
                        </td>
                    </tr>

                    <tr>
                        <td style="font-size:13px;color:#6b7280;line-height:1.6;">
                            Link ini berlaku selama <strong>60 menit</strong>.
                            Jika kamu tidak merasa meminta reset password, abaikan email ini.
                        </td>
                    </tr>

                    <tr>
                        <td style="padding-top:25px;font-size:11px;color:#9ca3af;text-align:center;">
                            © {{ date('Y') }} AWASS System. All rights reserved.
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>

</html>
