<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modelo extends Model
{
    use HasFactory;
    protected $guarded = ['id','created_at','updated_at'];
    public static function ValidateUpdate (){ 
        return [
        'nome'=> 'bail',
    ];}
    public static function ValidateNew (){ 
        return [
        'nome'=> 'required',
    ];}
    public static $Searcheable = [
        'nome',
    ];
}
