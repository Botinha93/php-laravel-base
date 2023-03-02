<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Log;
use App\Http\Services\StatusCode;
use App\Http\Services\grupos;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function filho($unidade)
    {

        $Log = StatusCode::$GENERICO_200->setResult(grupos::filho($unidade));
        return response()->json($Log,$Log['status']['code']);
    }
    public function todas()
    {
        $Log = StatusCode::$GENERICO_200->setResult(grupos::todas());
        return response()->json($Log,$Log['status']['code']);
    }
    public function arvore($unidade)
    {
        $Log = StatusCode::$GENERICO_200->setResult(grupos::arvore($unidade));
        return response()->json($Log,$Log['status']['code']);
    }
    public function arvoreEscalonada($unidade)
    {
        $Log = StatusCode::$GENERICO_200->setResult(grupos::arvoreEscalonada($unidade));
        return response()->json($Log,$Log['status']['code']);
    }
    public function pai($unidade)
    {
        $Log = StatusCode::$GENERICO_200->setResult(grupos::pai($unidade));
        return response()->json($Log,$Log['status']['code']);
    }
    public function arvoreRaiz()
    {
        $Log = StatusCode::$GENERICO_200->setResult(grupos::arvoreRaiz());
        return response()->json($Log,$Log['status']['code']);
    }
    public function adm()
    {
        $Log = StatusCode::$GENERICO_200->setResult(["Frota", "Operacional", "Bélico", "Tecnologia", "informatica", "teste"]);
        return response()->json($Log,$Log['status']['code']);
    }
}
