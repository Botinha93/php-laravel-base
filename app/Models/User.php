<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    use HasFactory;
    protected $primaryKey = 'idKeycloak';
    public $incrementing = false;

    // In Laravel 6.0+ make sure to also set $keyType
    protected $keyType = 'string';
    
    protected $guarded = ['created_at','updated_at'];
    protected $casts = [
        'permissions' => 'array',
        'tempPermissions' => 'array',
    ];
    public static function ValidateUpdate (){ 
        return [
        'idKeycloak'=> 'bail',
        'permissions'=> 'bail',
        'tempPermissions'=> 'bail',    
        'justificativa'=> 'bail',
        'inicio'=> 'bail',
        'fim'=> 'bail',
    ];}
    public static function ValidateNew (){ 
        return [
        'idKeycloak'=> 'required',
        'permissions'=> 'bail',
        'tempPermissions'=> 'bail',
        'justificativa'=> 'bail',
        'inicio'=> 'bail',
        'fim'=> 'bail',
    ];}
    public static $Searcheable = [
        'idKeycloak',
        'permissions',
        'tempPermissions',
    ];
}
