<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Rules\JsonArrayRule;

class Calor extends Model
{
    use HasFactory;
    protected $guarded = [];  
    protected $primaryKey = 'codigo_catmas';
    protected $casts = [
        'tags' => 'array',
        'contagem_unidades' => 'json',
    ];
    public static function ValidateNew (){ 
        return [
        "contagem_unidades"=> 'required',
        "desc_catmas"=> 'required',
        "codigo_catmas"=> 'required',
        "descricao"=> 'required',
        "tags"=> 'required',
        "idSubcategoria"=> 'required',
        "idCategoria" => 'required',
    ];}
    public static $Searcheable = [
        "contagem_unidades",
        "desc_catmas",
        "codigo_catmas",
        "descricao",
        "tags",
        "idSubcategoria",
        "idCategoria"
    ];
}
