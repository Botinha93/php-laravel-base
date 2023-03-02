<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\GenericRequest;
use App\Http\Services\StatusCode;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Services\Search;
use Exception;


class GenericController extends Controller
{
    public $Model = "";
    public $DeleteConstraints = [];

    public function __construct()
    {
        if($this->Model == ""){
            throw new Exception('Modelo não iniciado, defina a variavel Model', 500);
        }
    }

    public function index(Request $request)
    {
        foreach ($this->Model::$relationships as $key => $value) {
            if($request->has($key)){
                $querry = $request->querry;
                $request->merge(['querry' => $request->item]);
                $item = Search::getInstance()->searcher($request,$value['model']);
                $request->merge(['querry' => $querry]);
                $result = Search::getInstance()->searcher($request, $this->Model);
                $result['result'] = $item['result']->joinSub($result['result'], 'JOINING', function ($join) use($value) {
                    $join->on($value['idPai'], '=', $value['idFilho']);
                });
            }
        }
        if(!isset($result ))
            $result = Search::getInstance()->searcher($request, $this->Model);
        
        $result = Search::getInstance()->searcher($request, $this->Model);
        if($result['status']['code'] == 200)
            if(isset($request->max)){
                $result['result']= $result['result']->paginate($request->max);
            }else{
                $result['result'] = $result['result']->get();
            }
        return response()->json($result, $result['status']['code']);
    }
    public function store(Request $request)
    {
        if($request->has('items')){
            $returned = [];
            foreach ($request->input('items') as $value) {
                $saved = $this->storeBase($value);
                if($saved['status']['code'] != 200){
                    array_push($returned, $saved['status']);
                }else
                    array_push($returned, $saved['result']);
            }
            $result = StatusCode::$GENERICO_206->setResult($returned);
        }else{
            $result = StatusCode::$GENERICO_206->setResult($this->storeBase($request->all()));
        }
        return response()->json($result);
    }
    public function storeBase($request)
    {
        $result = GenericRequest::requestProcessor(GenericRequest::rules($request,$this->Model::ValidateNew()),$this->Model);
        if($result != false){
            if((is_array($result) && key_exists("errorInfo",$result)) || property_exists($result,"errorInfo")){
                return StatusCode::$GENERICO_400->setResult($result);
            }else if(is_string($result)){
                return StatusCode::$GENERICO_400->setResult(json_decode($result));
            }
            return StatusCode::$GENERICO_200->setResult($result);
        }else
            return StatusCode::$GENERICO_400;
    }

    public function show($id)
    {
        try{
            $generico = $this->Model::findOrFail($id);
            return response()->json(StatusCode::$GENERICO_200->setResult($generico));
        }catch(ModelNotFoundException $e)
        {
            return response()->json(StatusCode::$GENERICO_404,StatusCode::$GENERICO_404['status']['code']);
        }
    }

    public function update(Request $request, $id)
    {
        if($request->has('idsUpdate')){
            $returned = $this->updateMultiple($request, $id);
        }else{
            $returned = $this->updateBase($request, $id);
        }
        return response()->json($returned,$returned['status']['code']);
    }

    public function updateMultiple(Request $request)
    {
        foreach ($request->input('idsUpdate') as $value) {
            $returned = $this->updateBase($request, $value);
            if(!$returned['status']['code'] == 200){
                $returned['result']['falha'] = 'Erro ao atualizar id: ' . $value;
                return response()->json($returned,$returned['status']['code']);
            }
        }
        return response()->json(StatusCode::$GENERICO_200,StatusCode::$GENERICO_200['status']['code']);

    }
    public function updateBase(Request $request, $id)
    {
        try
        {
            $generico = $this->Model::findOrFail($id);
            $result = GenericRequest::requestProcessor(GenericRequest::rules($request->all(),$this->Model::ValidateUpdate()),$this->Model,$generico);
            if($result != false){
                if((is_array($result) && key_exists("errorInfo",$result)) || property_exists($result,"errorInfo")){
                    return StatusCode::$GENERICO_400->setResult($result);
                }else if(is_string($result)){
                    return StatusCode::$GENERICO_400->setResult($result);
                }
                return StatusCode::$GENERICO_200->setResult($result);
            }else
                return StatusCode::$GENERICO_400;
        }
        catch(ModelNotFoundException $e)
        {
            return StatusCode::$GENERICO_404->setResult($e);
        }

    }

    public function destroy($id)
    {
        if(count($this->DeleteConstraints)>0){
            foreach ($this->DeleteConstraints as $key => $value) {
                if($key::where($value, $id)->count() > 0){
                    return response()->json(new StatusCode(502, "Item não pode ser deletado pois possue relaçoes existentes " . $value . " em " . $key));
                }
            }
        }
        try
        {
            $generico = $this->Model::findOrFail($id);
            if($generico->delete())
                return response()->json(StatusCode::$GENERICO_200->setResult($generico::all()));
            else
                return response()->json(new StatusCode(502, "Erro ao deletar item"),502);
        }
        catch(ModelNotFoundException $e)
        {
            return response()->json(StatusCode::$GENERICO_404,StatusCode::$GENERICO_404['status']['code']);
        }

    }
}
