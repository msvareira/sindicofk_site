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
        .info-box {
            background: #f8f9fa;
            border-left: 4px solid #0B5345;
            padding: 15px;
            margin: 20px 0;
        }
        .info-row {
            margin: 10px 0;
        }
        .label {
            font-weight: bold;
            color: #0B5345;
            display: inline-block;
            min-width: 120px;
        }
        .value {
            color: #333;
        }
        .message-box {
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 15px;
            margin: 20px 0;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 14px;
            color: #666;
            border-top: 1px solid #dee2e6;
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
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔔 Novo Lead Recebido</h1>
            <p style="margin: 5px 0 0 0;">Síndico FK CRM</p>
        </div>
        
        <div class="content">
            <p>Um novo lead foi registrado através do formulário do site.</p>
            
            <div class="protocol">
                Protocolo: {{ $protocolo }}
            </div>
            
            <div class="info-box">
                <h3 style="margin-top: 0; color: #0B5345;">Informações do Lead</h3>
                
                <div class="info-row">
                    <span class="label">Nome:</span>
                    <span class="value">{{ $lead->nome }}</span>
                </div>
                
                <div class="info-row">
                    <span class="label">E-mail:</span>
                    <span class="value"><a href="mailto:{{ $lead->email }}">{{ $lead->email }}</a></span>
                </div>
                
                <div class="info-row">
                    <span class="label">Telefone:</span>
                    <span class="value"><a href="tel:{{ $lead->telefone }}">{{ $lead->telefone }}</a></span>
                </div>
                
                @if($lead->condominio)
                <div class="info-row">
                    <span class="label">Condomínio:</span>
                    <span class="value">{{ $lead->condominio }}</span>
                </div>
                @endif
                
                <div class="info-row">
                    <span class="label">Tipo de Contato:</span>
                    <span class="value">{{ ucfirst($lead->tipo_contato ?? 'N/A') }}</span>
                </div>
                
                <div class="info-row">
                    <span class="label">Status:</span>
                    <span class="value">{{ ucfirst($lead->status) }}</span>
                </div>
                
                <div class="info-row">
                    <span class="label">Data/Hora:</span>
                    <span class="value">{{ $lead->created_at->format('d/m/Y H:i') }}</span>
                </div>
            </div>
            
            <h3 style="color: #0B5345;">Mensagem:</h3>
            <div class="message-box">
                {{ $lead->mensagem }}
            </div>
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="https://wa.me/55{{ preg_replace('/\D/', '', $lead->telefone) }}" 
                   style="display: inline-block; background: #25D366; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; font-weight: bold;">
                    💬 Contatar via WhatsApp
                </a>
            </div>
        </div>
        
        <div class="footer">
            <p style="margin: 5px 0;"><strong>Síndico FK</strong></p>
            <p style="margin: 5px 0;">Gestão Profissional de Condomínios</p>
            <p style="margin: 5px 0;">
                <a href="mailto:adm.sindicofk@gmail.com">adm.sindicofk@gmail.com</a> | 
                <a href="https://wa.me/5551982695830">WhatsApp</a>
            </p>
        </div>
    </div>
</body>
</html>
