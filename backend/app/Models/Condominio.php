<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Condominio extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nome',
        'cnpj',
        'endereco',
        'bairro',
        'cidade',
        'estado',
        'cep',
        'numero_unidades',
        'tipo',
        'telefone',
        'email',
        'status',
        'data_inicio_contrato',
        'data_fim_contrato',
        'observacoes',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
        'data_inicio_contrato' => 'date',
        'data_fim_contrato' => 'date',
    ];

    /**
     * Scope para filtrar por status
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope para clientes ativos
     */
    public function scopeAtivos($query)
    {
        return $query->where('status', 'cliente');
    }

    /**
     * Scope para prospectos
     */
    public function scopeProspectos($query)
    {
        return $query->where('status', 'prospecto');
    }
}
