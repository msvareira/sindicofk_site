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
        Schema::create('condominios', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('cnpj', 18)->nullable()->unique();
            $table->string('endereco')->nullable();
            $table->string('bairro')->nullable();
            $table->string('cidade')->nullable();
            $table->string('estado', 2)->nullable();
            $table->string('cep', 9)->nullable();
            $table->integer('numero_unidades')->nullable();
            $table->string('tipo')->nullable(); // residencial, comercial, misto
            $table->string('telefone', 20)->nullable();
            $table->string('email')->nullable();
            $table->enum('status', ['prospecto', 'cliente', 'inativo'])->default('prospecto');
            $table->date('data_inicio_contrato')->nullable();
            $table->date('data_fim_contrato')->nullable();
            $table->text('observacoes')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Índices
            $table->index('cnpj');
            $table->index('status');
            $table->index('cidade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('condominios');
    }
};
