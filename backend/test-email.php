#!/usr/bin/env php
<?php

/**
 * Script de teste para verificar o envio de emails
 * 
 * Execute com: php test-email.php
 */

define('LARAVEL_START', microtime(true));

// Carregar o autoloader do Composer
require __DIR__.'/vendor/autoload.php';

// Carregar a aplicação Laravel
$app = require_once __DIR__.'/bootstrap/app.php';

// Iniciar o kernel
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Lead;
use App\Mail\NewLeadNotification;
use App\Mail\LeadConfirmation;
use Illuminate\Support\Facades\Mail;

echo "=================================\n";
echo "Teste de Envio de E-mail\n";
echo "=================================\n\n";

// Criar um lead de teste
$leadData = [
    'nome' => 'João da Silva (Teste)',
    'email' => 'msvareira@gmail.com',
    'telefone' => '(51) 98269-5830',
    'condominio' => 'Condomínio Teste',
    'tipo_contato' => 'orcamento',
    'mensagem' => 'Esta é uma mensagem de teste para verificar o funcionamento do sistema de emails.',
    'origem' => 'teste',
    'status' => 'novo',
    'prioridade' => 2,
    'data_primeiro_contato' => now(),
];

echo "Criando lead de teste...\n";
$lead = Lead::create($leadData);
echo "✓ Lead criado com ID: {$lead->id}\n\n";

// Testar envio de email para administrador
echo "Enviando email para administrador...\n";
try {
    $adminEmail = config('mail.admin_notification_email');
    echo "Email do admin: {$adminEmail}\n";
    
    Mail::to($adminEmail)->send(new NewLeadNotification($lead));
    echo "✓ Email de notificação enviado com sucesso!\n\n";
} catch (\Exception $e) {
    echo "✗ Erro ao enviar email de notificação: {$e->getMessage()}\n\n";
}

// Testar envio de email de confirmação
echo "Enviando email de confirmação para o lead...\n";
try {
    Mail::to($lead->email)->send(new LeadConfirmation($lead));
    echo "✓ Email de confirmação enviado com sucesso!\n\n";
} catch (\Exception $e) {
    echo "✗ Erro ao enviar email de confirmação: {$e->getMessage()}\n\n";
}

echo "=================================\n";
echo "Configurações de Email:\n";
echo "=================================\n";
echo "Mailer: " . config('mail.default') . "\n";
echo "Host: " . config('mail.mailers.smtp.host') . "\n";
echo "Port: " . config('mail.mailers.smtp.port') . "\n";
echo "From: " . config('mail.from.address') . "\n";
echo "Admin Email: " . config('mail.admin_notification_email') . "\n";
echo "=================================\n\n";

// Deletar o lead de teste
echo "Limpando dados de teste...\n";
$lead->delete();
echo "✓ Lead de teste removido\n\n";

echo "Teste concluído!\n";
