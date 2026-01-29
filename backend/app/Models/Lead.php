<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lead extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nome',
        'email',
        'telefone',
        'condominio',
        'tipo_contato',
        'mensagem',
        'origem',
        'status',
        'prioridade',
        'data_primeiro_contato',
        'data_ultimo_contato',
        'ip_address',
        'user_agent',
        'metadata',
        'atribuido_para',
        'observacoes',
    ];

    protected $casts = [
        'metadata' => 'array',
        'data_primeiro_contato' => 'datetime',
        'data_ultimo_contato' => 'datetime',
    ];

    /**
     * Relacionamento com interações
     */
    public function interacoes()
    {
        return $this->hasMany(Interacao::class);
    }

    /**
     * Scope para filtrar por status
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope para filtrar por origem
     */
    public function scopeOrigem($query, $origem)
    {
        return $query->where('origem', $origem);
    }

    /**
     * Scope para leads novos
     */
    public function scopeNovos($query)
    {
        return $query->where('status', 'novo');
    }

    /**
     * Scope para leads de alta prioridade
     */
    public function scopeAltaPrioridade($query)
    {
        return $query->where('prioridade', 1);
    }

    /**
     * Registrar novo contato
     */
    public function registrarContato()
    {
        $this->data_ultimo_contato = now();
        
        if (!$this->data_primeiro_contato) {
            $this->data_primeiro_contato = now();
        }
        
        $this->save();
    }
}
