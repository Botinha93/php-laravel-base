<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pagina extends Model
{
    use HasFactory;
    protected $guarded = ['id','created_at','updated_at'];
    public static function ValidateUpdate (){ 
        return [
            'unidade'=> 'bail',
            'html'=> 'bail',
    ];}
    public static function ValidateNew (){ 
        return [
            'unidade'=> 'required',
            'html'=> 'required',
    ];}
    public static $Searcheable = [
        'unidade',
    ];
}
