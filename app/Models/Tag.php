<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;
    protected $guarded = ['id','created_at','updated_at'];
    public static function ValidateUpdate (){ 
        return [
        'usuario'=> 'bail',
        'nome'=> 'bail',
    ];}
    public static function ValidateNew (){ 
        return [
        'usuario'=> 'required',
        'nome'=> 'required',
    ];}
    public static $Searcheable = [
        'nome',
        'usuario',
    ];
}
