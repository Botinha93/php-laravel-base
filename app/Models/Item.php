<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Rules\JsonArrayRule;

class Item extends Model
{
    use HasFactory;
    protected $guarded = ['id','created_at','updated_at'];
    protected $hidden = ['idMarca', 'idModelo', 'idCategoria', 'idSubcategoria', 'idTemplate'];
    protected $casts = [
        'tags' => 'array',
        'idTemplate' => 'array',
        'idMarca' => 'array',
        'idModelo' => 'array',
    ];
    public static function ValidateUpdate (){ 
        return [
            'tipo'=> 'numeric|integer|between:1,3',
            'idMarca'=> [new JsonArrayRule],
            'idModelo'=> [new JsonArrayRule],
            'idCategoria'=> 'numeric|integer',
            'idTemplate'=> [new JsonArrayRule],
            'idSubcategoria'=> 'numeric|integer',
            'codigo_catmas'=> 'bail',
            'desc_catmas'=> 'bail',
            'admType'=> 'bail',
            'descricao'=> 'bail',
            'imagem'=> 'bail',
            'tags' => [new JsonArrayRule],
        ];
    }
    public static function ValidateNew (){ 
        return [
            'tipo'=> 'required|numeric|integer|between:1,3',
            'idMarca'=> [ new JsonArrayRule],
            'idModelo'=> [ new JsonArrayRule],
            'idCategoria'=> 'required|numeric|integer',
            'idTemplate'=> [new JsonArrayRule],
            'idSubcategoria'=> 'numeric|integer',
            'admType'=> 'required',
            'codigo_catmas'=> 'required',
            'desc_catmas'=> 'required',
            'descricao'=> 'required',
            'imagem'=> 'bail',
            'tags' => ['required', new JsonArrayRule],
        ];
    
    }
    public static $Searcheable = [
        'tipo',
        'idMarca',
        'admType',
        'idCategoria',
        'idSubcategoria',
        'codigo_catmas',
        'desc_catmas',
        'tags',
        'descricao',
        'id',
        'idTemplate',
    ];
    protected $with = ['Categoria' , 'Subcategoria'];
    public function Modelo()
    {
        return $this->belongsTo(Modelo::class, 'idModelo');
    }
    public function Marca()
    {
        return $this->belongsTo(Marca::class, 'idMarca');
    }
    public function Categoria()
    {
        return $this->belongsTo(Categoria::class, 'idCategoria');
    }
    public function Template()
    {
        return $this->belongsTo(Template::class, 'idTemplate');
    }
    public function Subcategoria()
    {
        return $this->belongsTo(Categoria::class, 'idSubcategoria');
    }

}
