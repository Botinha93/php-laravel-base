<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Catmas extends Model
{
    use HasFactory;
    protected $guarded = ['id','created_at','updated_at'];
    public static function ValidateUpdate (){ 
        return [
        'cod_catman'=> 'bail',
        'desc_catman'=> 'bail',
    ];}
    public static function ValidateNew (){ 
        return [
        'cod_catman'=> 'required',
        'desc_catman'=> 'required',
    ];}
    public static $Searcheable = [
        'cod_catman','desc_catman',
    ];
}
