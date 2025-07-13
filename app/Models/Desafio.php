<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Desafio extends Model
{
    use HasFactory;

    protected $table = 'desafios';

    protected $fillable = [
        'titulo',
        'descricao',
        'dificuldade',
        'pontos_recompensa',
        'duracao_dias',
    ];

    public function usuarioDesafios() {
        return $this->hasMany(UsuarioDesafio::class);
    }
}
