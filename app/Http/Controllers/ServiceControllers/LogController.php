<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Log;
use App\Http\Services\grupos;
use App\Http\Services\StatusCode;
use App\Http\Services\Search;

class LogController extends GenericController
{
    public function __construct()
    {
        $this->Model = "App\\Models\\Log";
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function unidade($unidade)
    {
        $Log = StatusCode::$GENERICO_200->setResult(Log::where('unidades', 'LIKE', '%'.$unidade.',%')->whereIn('unidades', $this->grupos())->paginate(10));
        return response()->json($Log,$Log['status']['code']);
    }
    public function rota($rota)
    {
        $Log = StatusCode::$GENERICO_200->setResult(Log::where('rota', $rota)->whereIn('unidades', $this->grupos())->paginate(10));
        return response()->json($Log,$Log['status']['code']);
    }
    public function tipo($tipo)
    {
        $Log = StatusCode::$GENERICO_200->setResult(Log::where('tipo', $tipo)->whereIn('unidades', $this->grupos())->paginate(10));
        return response()->json($Log,$Log['status']['code']);
    }
    public function usuario($usuario)
    {
        $Log = StatusCode::$GENERICO_200->setResult(Log::where('usuario', $usuario)->whereIn('unidades', $this->grupos())->paginate(10));
        return response()->json($Log,$Log['status']['code']);
    }
    public function todos(Request $request)
    {
        if(!$request->querry){
            $Log = StatusCode::$GENERICO_200->setResult(Log::whereIn('unidades', $this->grupos())->paginate(10));
            return response()->json($Log,$Log['status']['code']);
        }else{
            $index = Search::getInstance()->searcher($request, $this->Model);
            if($index['status']['code'] == 200)
                if(isset($request->max)){
                    $index['result']= $index['result']->paginate($request->max);
                }else{
                    $index['result'] = $index['result']->get();
                }
            else{
                return response()->json($index);
            }
            return response()->json($index, $index['status']['code']);
        }
    }
    public function acoes()
    {
        $Log = StatusCode::$GENERICO_200->setResult(Log::whereIn('rota',['material.disponibilizar','material.vincular','material.vinculardisponibiliza','material.vinculardisponibilizado',"material.update"])->whereIn('unidades', $this->grupos())->orderBy('created_at','desc')->paginate(10));
        return response()->json($Log,$Log['status']['code']);
    }
    public function alertas()
    {
        $Log = StatusCode::$GENERICO_200->setResult(Log::whereIn('rota',['alerta.finalizar','alerta.store','alerta.update','alerta.destroy'])->whereIn('unidades', $this->grupos())->orderBy('created_at','desc')->paginate(10));
        return response()->json($Log,$Log['status']['code']);
    }
    private function grupos(){
        $unidades = array();
        global $UserInfo;
        foreach ($UserInfo['groups'] as $value) {
            if(is_numeric($value)){
                array_push($unidades, $value);
                $filhos = grupos::arvore($value);
                if(array_count_values($filhos) > 0)
                    $unidades = array_merge($unidades, $filhos);
            }
        }
        return $unidades;
    }
    
}
