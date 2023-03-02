<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permissions extends Model
{
    use HasFactory;
    protected $guarded = ['id','created_at','updated_at'];
    protected $casts = [
        'endpoint_permissions' => 'array',
    ];
    public static function ValidateUpdate (){ 
        return [
            'roles'=> 'string',
            'endpoint_permissions'=> 'array',
    ];}
    public static function ValidateNew (){ 
        return [
            'roles'=> 'string',
            'endpoint_permissions'=> 'array',
    ];}
    public static $Searcheable = [
        'roles','endpoint_permissions',
    ];
}
