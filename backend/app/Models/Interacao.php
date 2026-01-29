<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Interacao extends Model
{
    use HasFactory;

    protected $table = 'interacoes';

    protected $fillable = [
        'lead_id',
        'tipo',
        'assunto',
        'descricao',
        'data_interacao',
        'usuario_id',
        'metadata',
    ];

    protected $casts = [
        'data_interacao' => 'datetime',
        'metadata' => 'array',
    ];

    /**
     * Relacionamento com lead
     */
    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    /**
     * Scope para filtrar por tipo
     */
    public function scopeTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    /**
     * Scope para filtrar por período
     */
    public function scopePeriodo($query, $dataInicio, $dataFim)
    {
        return $query->whereBetween('data_interacao', [$dataInicio, $dataFim]);
    }
}
