<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Alerta extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id','created_at','updated_at'];

    public static function ValidateUpdate (){ 
        return [
        'materialID'=> 'required',
        'tipo'=> 'bail',
        'observacao'=> 'bail',
        'formula'=> 'bail',
        'comparativo'=> 'bail',
        'informado'=> 'bail',
        'unidade'=> 'bail',
        'usuario'=> 'bail',
    ];}
    public static function ValidateNew (){ 
        return [
            'materialID'=> 'required',
            'tipo'=> 'required',
            'observacao'=> 'required',
            'formula'=> 'required',
            'comparativo'=> 'required',
            'informado'=> 'required',
            'unidade'=> 'required',
            'usuario'=> 'bail',
    ];}
    public static $Searcheable = [
        'unidade',
        'materialID',
        'usuario',
        'tipo',
        'observacao',
        'formula',
        'comparativo',
        'informado',
        'ativo',

    ];
    protected $with = ['Material'];
    public function Material()
    {
        return $this->belongsTo(Material::class, 'materialID')->with('Item');
    }
}
