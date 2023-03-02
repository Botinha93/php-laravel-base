<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    use HasFactory;
    protected $guarded = ['id','created_at','updated_at'];

    public static function ValidateUpdate (){ 
        return [
        'tipo'=> 'bail',
        'nome'=> 'bail',
        'descricao'=> 'bail',
    ];}
    public static function ValidateNew (){ 
        return [
        'tipo'=> 'required|numeric|integer|between:1,3',
        'nome'=> 'required',
        'descricao'=> 'required',
    ];}
    public static $Searcheable = [
        'nome',
        'descricao',
        'tipo',
    ];
    protected $with = ['Campos'];
    public function Campos()
    {
        return $this->hasMany(TemplateCampo::class, 'idTemplate');
    }
}
