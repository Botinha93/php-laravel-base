<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notificações extends Model
{
    use HasFactory;
    protected $guarded = ['id','created_at','updated_at'];

    public static function ValidateUpdate (){ 
        return [
        'rota'=> 'bail',
        'usuario'=> 'bail',
        'unidades'=> 'bail',
    ];}
    public static function ValidateNew (){ 
        return [
            'rota'=> 'required',
            'usuario'=> 'required',
            'unidades'=> 'required',
    ];}
    public static $Searcheable = [
        'rota','usuario','unidades'
    ];
    public static function Notifica($rota,$usuario,$unidades,$mensagen)
    {
        $notifica = new Notificações();
        $notifica['rota'] = $rota;
        $notifica['usuario'] =$usuario;
        $notifica['unidades'] = $unidades;
        $notifica['mensagem'] = $mensagen;
        $notifica->save();
    }
}
