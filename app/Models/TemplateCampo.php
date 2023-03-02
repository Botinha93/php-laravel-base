<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemplateCampo extends Model
{
    use HasFactory;
    protected $guarded = ['id','created_at','updated_at'];

    public static function ValidateUpdate (){ 
        return [
        'idTemplate'=> 'numeric|integer',
        'imput'=> 'numeric|integer|between:1,5',
        'nome'=> 'bail',
        'op'=> 'bail',
        'op_aux'=> 'bail',
        'obrigatorio'=> 'boolean',
        'ajuda'=> 'bail',
    ];}
    public static function ValidateNew (){ 
        return [
        'idTemplate'=> 'required|numeric|integer',
        'imput'=> 'required|numeric|integer|between:1,5',
        'nome'=> 'required',
        'op'=> 'required',
        'op_aux'=> 'bail',
        'obrigatorio'=> 'required|boolean',
        'ajuda'=> 'bail',
    ];}
    public static $Searcheable = [
        'nome',
        'imput',
        'obrigatorio',
        'idTemplate'
    ];


}
