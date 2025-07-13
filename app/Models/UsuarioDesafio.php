<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsuarioDesafio extends Model
{
    use HasFactory;

    protected $fillable = [
        'usuario_id',
        'desafio_id',
        'status',
        'iniciado_em',
        'concluido_em',
        'pontos_ganhos',
    ];

    public function usuario() {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function desafio() {
        return $this->belongsTo(Desafio::class, 'desafio_id');
    }
}
