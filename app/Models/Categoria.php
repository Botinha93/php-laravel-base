<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;

    protected $guarded = ['id','created_at','updated_at'];

    public static $constraints = "";

    public static function ValidateUpdate (){ 
        return [
        'usuario'=> 'required',
        'nome'=> 'required',
        'subordinada'=> 'bail',
        'administrador'=> 'required',
    ];}
    public static function ValidateNew (){ 
        return [
        'usuario'=> 'required',
        'nome'=> 'required',
        'subordinada'=> 'bail',
        'administrador'=> 'required',
    ];}
    public static $Searcheable = [
        'nome',
        'usuario',
        'created_at',
        'updated_at',
    ];
    protected $with = ['subordinada'];
    public function subordinada()
    {
        return $this->hasMany(Categoria::class, 'subordinada')->without('subordinada');
    }
}
