<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recibo extends Model
{
    use HasFactory;
    protected $guarded = ['id','created_at','updated_at'];
    public static function ValidateUpdate (){ 
        return [
        'idMaterial'=> 'numeric|integer',
        'userEntrega'=> 'bail',
        'userRecebe'=> 'bail',
        'unidadeRecebe'=> 'bail',
        'unidadeEntrega'=> 'bail',
    ];}
    public static function ValidateNew (){ 
        return [
            'idMaterial'=> 'required|numeric|integer',
            'userEntrega'=> 'required',
            'userRecebe'=> 'required',
            'unidadeRecebe'=> 'required',
            'unidadeEntrega'=> 'required',
    ];}
    public static $Searcheable = [
            'idMaterial',
            'userEntrega',
            'userRecebe',
            'unidadeRecebe',
            'unidadeEntrega',
    ];
}
