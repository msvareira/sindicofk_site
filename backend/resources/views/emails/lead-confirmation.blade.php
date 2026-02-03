<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #0B5345 0%, #D4AF37 100%);
            color: #ffffff;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 30px 20px;
        }
        .protocol {
            background: #D4AF37;
            color: #0B5345;
            padding: 5px 10px;
            border-radius: 4px;
            font-weight: bold;
            display: inline-block;
            margin: 10px 0;
        }
        .info-box {
            background: #f8f9fa;
            border-left: 4px solid #0B5345;
            padding: 15px;
            margin: 20px 0;
        }
        .button {
            display: inline-block;
            background: #25D366;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin: 10px 5px;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 14px;
            color: #666;
            border-top: 1px solid #dee2e6;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>✅ Recebemos seu Contato!</h1>
            <p style="margin: 5px 0 0 0;">Síndico FK</p>
        </div>
        
        <div class="content">
            <p>Olá, <strong>{{ $lead->nome }}</strong>!</p>
            
            <p>Obrigado por entrar em contato com a <strong>Síndico FK</strong>. Sua mensagem foi recebida com sucesso!</p>
            
            <div class="protocol">
                Protocolo de Atendimento: {{ $protocolo }}
            </div>
            
            <p>Nossa equipe analisará sua solicitação e retornará em breve através dos contatos fornecidos:</p>
            
            <div class="info-box">
                <p style="margin: 5px 0;"><strong>E-mail:</strong> {{ $lead->email }}</p>
                <p style="margin: 5px 0;"><strong>Telefone:</strong> {{ $lead->telefone }}</p>
                @if($lead->condominio)
                <p style="margin: 5px 0;"><strong>Condomínio:</strong> {{ $lead->condominio }}</p>
                @endif
            </div>
            
            <p><strong>Sua mensagem:</strong></p>
            <div style="background: #fff; border: 1px solid #dee2e6; border-radius: 4px; padding: 15px; margin: 15px 0;">
                {{ $lead->mensagem }}
            </div>
            
            <p>Enquanto isso, você também pode entrar em contato diretamente através dos nossos canais:</p>
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="https://wa.me/5551982695830" class="button">
                    💬 WhatsApp
                </a>
                <a href="mailto:adm.sindicofk@gmail.com" class="button" style="background: #0B5345;">
                    ✉️ E-mail
                </a>
            </div>
            
            <p style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #dee2e6;">
                <strong>Por que escolher a Síndico FK?</strong>
            </p>
            <ul style="color: #666;">
                <li>✅ Gestão profissional e transparente</li>
                <li>✅ Experiência comprovada em condomínios</li>
                <li>✅ Atendimento personalizado</li>
                <li>✅ Tecnologia e inovação</li>
            </ul>
        </div>
        
        <div class="footer">
            <p style="margin: 5px 0;"><strong>Síndico FK</strong></p>
            <p style="margin: 5px 0;">Gestão Profissional de Condomínios</p>
            <p style="margin: 5px 0;">
                📧 <a href="mailto:adm.sindicofk@gmail.com">adm.sindicofk@gmail.com</a> | 
                📞 <a href="https://wa.me/5551982695830">(51) 98269-5830</a>
            </p>
            <p style="margin: 15px 0 5px 0; font-size: 12px; color: #999;">
                Este é um e-mail automático. Por favor, não responda diretamente.
            </p>
        </div>
    </div>
</body>
</html>
