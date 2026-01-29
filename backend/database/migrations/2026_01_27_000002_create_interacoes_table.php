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
        Schema::create('interacoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained()->onDelete('cascade');
            $table->enum('tipo', ['email', 'telefone', 'whatsapp', 'reuniao', 'visita', 'nota', 'outro'])->default('nota');
            $table->string('assunto')->nullable();
            $table->text('descricao');
            $table->timestamp('data_interacao')->useCurrent();
            $table->unsignedBigInteger('usuario_id')->nullable(); // Futuro: ID do usuário que fez a interação
            $table->json('metadata')->nullable(); // Dados adicionais
            $table->timestamps();

            // Índices
            $table->index('lead_id');
            $table->index('tipo');
            $table->index('data_interacao');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interacoes');
    }
};
