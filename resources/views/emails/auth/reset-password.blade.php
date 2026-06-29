<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Restablecer contraseña — GoWayki</title>
</head>
<body style="margin:0; padding:0; background:#f4f5f7; font-family: Arial, Helvetica, sans-serif; color:#111827;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f5f7; padding:32px 16px;">
        <tr>
            <td align="center">
                <table width="100%" cellpadding="0" cellspacing="0" style="max-width:620px; background:#ffffff; border-radius:24px; overflow:hidden; box-shadow:0 18px 45px rgba(17,24,39,0.08);">

                    <tr>
                        <td style="background:linear-gradient(135deg,#ff5a45,#f83a34,#d82027); padding:34px 32px; text-align:center;">
                            <div style="display:inline-block; width:72px; height:72px; line-height:72px; border-radius:22px; background:#ffffff; color:#f83a34; font-size:34px; font-weight:800; margin-bottom:18px;">
                                G
                            </div>

                            <h1 style="margin:0; color:#ffffff; font-size:30px; font-weight:800; letter-spacing:-0.5px;">
                                GoWayki
                            </h1>

                            <p style="margin:8px 0 0; color:rgba(255,255,255,0.88); font-size:15px;">
                                Transporte inteligente para moverte mejor por Arequipa
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:36px 36px 18px;">
                            <h2 style="margin:0 0 16px; font-size:24px; color:#111827; font-weight:800;">
                                Restablece tu contraseña
                            </h2>

                            <p style="margin:0 0 16px; font-size:16px; line-height:1.7; color:#374151;">
                                Hola {{ $userName }},
                            </p>

                            <p style="margin:0 0 22px; font-size:16px; line-height:1.7; color:#374151;">
                                Recibimos una solicitud para restablecer la contraseña de tu cuenta en <strong>GoWayki</strong>.
                                Para continuar, haz clic en el siguiente botón.
                            </p>

                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center" style="padding:12px 0 28px;">
                                        <a href="{{ $resetUrl }}"
                                           style="display:inline-block; background:#f83a34; color:#ffffff; text-decoration:none; padding:15px 28px; border-radius:14px; font-size:15px; font-weight:800; box-shadow:0 14px 28px rgba(248,58,52,0.28);">
                                            Restablecer contraseña
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <div style="background:#fff3f2; border:1px solid #ffe0dd; border-radius:18px; padding:18px 20px; margin-bottom:24px;">
                                <p style="margin:0; font-size:14px; line-height:1.6; color:#7f1d1d;">
                                    Este enlace caduca en <strong>{{ $expireMinutes }} minutos</strong>. Si tú no solicitaste este cambio,
                                    puedes ignorar este mensaje con seguridad.
                                </p>
                            </div>

                            <p style="margin:0 0 18px; font-size:14px; line-height:1.7; color:#6b7280;">
                                Si el botón no funciona, copia y pega este enlace en tu navegador:
                            </p>

                            <p style="margin:0; word-break:break-all; font-size:13px; line-height:1.6;">
                                <a href="{{ $resetUrl }}" style="color:#f83a34; text-decoration:underline;">
                                    {{ $resetUrl }}
                                </a>
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:20px 36px 34px;">
                            <hr style="border:none; border-top:1px solid #e5e7eb; margin:0 0 22px;">

                            <p style="margin:0; font-size:14px; line-height:1.7; color:#6b7280;">
                                Saludos,<br>
                                <strong style="color:#111827;">Equipo GoWayki</strong>
                            </p>
                        </td>
                    </tr>

                </table>

                <p style="margin:22px 0 0; font-size:12px; color:#9ca3af; text-align:center;">
                    © {{ date('Y') }} GoWayki. Todos los derechos reservados.
                </p>
            </td>
        </tr>
    </table>
</body>
</html>
