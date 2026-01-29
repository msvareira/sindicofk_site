<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('email');
            $table->string('telefone', 20);
            $table->string('condominio')->nullable();
            $table->enum('tipo_contato', ['orcamento', 'duvida', 'informacao', 'reclamacao', 'outro'])->default('orcamento');
            $table->text('mensagem');
            $table->enum('origem', ['site', 'whatsapp', 'telefone', 'email', 'indicacao', 'outro'])->default('site');
            $table->enum('status', ['novo', 'contatado', 'em_negociacao', 'convertido', 'perdido', 'arquivado'])->default('novo');
            $table->integer('prioridade')->default(3); // 1=Alta, 2=Média, 3=Baixa
            $table->timestamp('data_primeiro_contato')->nullable();
            $table->timestamp('data_ultimo_contato')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->json('metadata')->nullable(); // Dados adicionais flexíveis
            $table->unsignedBigInteger('atribuido_para')->nullable(); // Futuro: ID do usuário responsável
            $table->text('observacoes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Índices para otimização
            $table->index('email');
            $table->index('telefone');
            $table->index('status');
            $table->index('origem');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
