<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solicitacao extends Model
{
    use HasFactory;

    protected $guarded = ['id','created_at','updated_at'];

    public static function ValidateUpdate (){ 
        return [
        'materialID'=> 'required',
        'quantidade' => 'required',
        'usuario' => 'required',
        'unidade'=> 'required',
    ];}
    public static function ValidateNew (){ 
        return [
            'materialID'=> 'required',
            'quantidade' => 'required',
            'usuario' => 'required',
            'unidade'=> 'required',
    ];}
    public static $Searcheable = [
        'materialID',
        'usuario',
        'unidade',
    ];
}
