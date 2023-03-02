<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class permuta extends Model
{
    use HasFactory;
    protected $guarded = ['id','created_at','updated_at'];
    public static function ValidateUpdate (){ 
        return [
            'materialID'=> 'required|numeric|integer',
            'troca'=> 'required',
            'tamanhoPossuido'=> 'required',
            'usuario'=> 'required',
    ];}
    public static function ValidateNew (){ 
        return [
            'materialID'=> 'numeric|integer',
            'troca'=> 'required',
            'tamanhoPossuido'=> 'required',
            'usuario'=> 'required',
    ];}
    public static $Searcheable = [
        'materialID','troca','usuario'
    ];
    protected $with = ['Material'];
    public function Material()
    {
        return $this->belongsTo(Material::class, 'materialID')->with('Item');
    }
}
