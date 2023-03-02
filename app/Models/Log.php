<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    protected $fillable = [
        'tipo',
        'rota',
        'resultado',
        'usuario',
        'usuario_nome',
        'request',
        'unidades',
    ];
    protected $casts = [
        'resultado' => 'json',
        'request' => 'json',
    ];
    public static $Searcheable = [
        'tipo',
        'rota',
        'resultado',
        'usuario',
        'usuario_nome',
        'request',
        'unidades',
        'created_at',
        'updated_at',
    ];
}
