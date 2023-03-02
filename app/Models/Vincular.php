<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vincular extends Model
{
    use HasFactory;
    protected $guarded = ['id','created_at','updated_at'];
}
