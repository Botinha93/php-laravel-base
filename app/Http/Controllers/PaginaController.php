<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tag;

class PaginaController extends GenericController
{
    public $Model = "App\\Models\\Pagina";
    public $DeleteConstraints = [];  
}
