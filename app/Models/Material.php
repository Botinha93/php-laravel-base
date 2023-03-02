<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Rules\{MaterialCamposRule, JsonArrayRule};

class Material extends Model
{
    
    use HasFactory;
    protected $guarded = ['id','created_at','updated_at'];
    protected $attributes = [
        'disponibilizado'=>'{"disp": false , "aprovado": false , "quantidade": 0, "patrimonio":""}',
    ];
    protected $casts = [
        'options' => AsArrayObject::class,
        'campos' => 'object',
        'patrimonio_serie' => 'object',
        'disponibilizado' => 'array',
        'marca'=> 'array',
        'modelo'=> 'array',
    ];
    public static function ValidateUpdate(){ 
        return [
        'campos' => 'bail',
        'idItem'=> 'bail',
        'patrimonio_serie'=> 'bail',
        'admType'=> 'bail',
        'dataFabr'=> 'date',
        'dataVal'=> 'bail',
        'estConservacao'=> 'bail',
        'quantidade'=> 'bail',
        'demanda'=> 'bail',
        'unidade'=> 'bail',
        'ativo'=> 'bail', 
        'marca'=> 'bail',
        'modelo'=> 'bail',
        'status_material'=> 'bail',
        'vinculado'=> 'bail', 
        'usuariovinculado'=> 'required_if:vinculado,true', 
    ];}

    public static function ValidateNew (){ 
        return [
            'campos' => 'required',
            'idItem'=> 'required|numeric|integer',
            'patrimonio_serie'=> 'bail',
            'marca'=> 'bail',
            'modelo'=> 'bail',
            'admType'=> 'required',
            'dataFabr'=> 'date',
            'dataVal'=> 'bail',
            'estConservacao'=> 'numeric|integer|between:1,4',
            'quantidade'=> 'bail',
            'demanda'=> 'bail',
            'unidade'=> 'bail',
            'ativo'=> 'bail', 
            'status_material'=> 'bail',
            'vinculado'=> 'boolean', 
            'usuariovinculado'=> 'required_if:vinculado,true', 
    ];}
    public static $Searcheable = [
        'id',
        'tipo',
        'descricao',
        'dataFabr',
        'admType',
        'campos' ,
        'idItem',
        'patrimonio_serie',
        'admType',
        'dataFabr',
        'dataVal',
        'estConservacao',
        'quantidade',
        'demanda',
        'unidade',
        'ativo', 
        'vinculado', 
        'usuariovinculado', 
        'status_material',
        'marca',
        'modelo',
        'movimentando',
    ];
    public function Item()
    {
        return $this->belongsTo(Item::class, 'idItem');
    }

}
