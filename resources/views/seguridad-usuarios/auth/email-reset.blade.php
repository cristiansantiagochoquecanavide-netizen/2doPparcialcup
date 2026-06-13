<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperación de Contraseña</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #3498db;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .header h1 {
            color: #3498db;
            margin: 0;
        }
        .header p {
            color: #666;
            margin: 5px 0 0 0;
        }
        .content {
            padding: 20px 0;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
        .footer {
            border-top: 1px solid #eee;
            padding-top: 20px;
            font-size: 12px;
            color: #999;
            text-align: center;
        }
        .warning {
            background-color: #fff3cd;
            border: 1px solid #ffc107;
            padding: 12px;
            border-radius: 5px;
            margin: 15px 0;
            color: #856404;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>CUP FICCT</h1>
            <p>Sistema Web de Admisión Universitaria</p>
        </div>

        <div class="content">
            <p>Hola {{ $usuario->nombre_usuario }},</p>

            <p>Hemos recibido una solicitud para recuperar tu contraseña en la plataforma CUP FICCT. Si fuiste tú quien realizó esta solicitud, haz clic en el botón de abajo para establecer una nueva contraseña.</p>

            <div style="text-align: center;">
                <a href="{{ $resetUrl }}" class="button">
                    Recuperar Contraseña
                </a>
            </div>

            <p>O copia y pega este enlace en tu navegador:</p>
            <p style="background-color: #f0f0f0; padding: 10px; border-radius: 5px; word-break: break-all;">
                {{ $resetUrl }}
            </p>

            <div class="warning">
                <strong>⚠️ Importante:</strong> Este enlace expirará en 24 horas. Si no solicitaste un cambio de contraseña, ignora este correo.
            </div>

            <p>Si tienes problemas para hacer clic en el botón, puedes copiar y pegar el enlace anterior en tu navegador.</p>

            <p>Saludos,<br>
            El equipo de CUP FICCT</p>
        </div>

        <div class="footer">
            <p>Este es un correo automático, por favor no lo respondas directamente.</p>
            <p>Sistema Web de Admisión Universitaria © 2026</p>
        </div>
    </div>
</body>
</html>
