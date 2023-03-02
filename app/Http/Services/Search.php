<?php

namespace App\Http\Services;

use Illuminate\Http\Request;
use App\Http\Services\StatusCode;
use App\Http\Services\grupos;
use Illuminate\Support\Facades\Schema;

class Search extends GenericSingleton{

    private $operators = [
        'L' => 'like',
        'SW' => 'like',
        'EW' => 'like',
        'NL' => 'not like',
        'E' => '=',
        'NE' => '<>',
        'GT' => '>',
        'LT' => '<',
    ];


    private function andor(Request $request, $RQuerry, $Model)
    {
        $jsonquerry = json_decode(base64_decode($request->querry), true);
        foreach ($jsonquerry as $json) {
            $RQuerry->orWhere(function($query) use ($json, $Model) {
                $querryArray = array();
                foreach ($json as $key => $value) {
                    if(in_array($key, $Model::$Searcheable)){
                        switch ($value['operator']) {
                            case "L" :
                                $value['value'] = '%' . $value['value'] . '%';
                                break;
                            case "NL":
                                $value['value'] = '%' . $value['value'] . '%';
                                break;
                            case "SW":
                                $value['value'] = '%' . $value['value'];
                                break;
                            case "EW":
                                $value['value'] = $value['value'] . '%';
                                break;
                            case "AR":
                                $query->orWhere($key,'like',' ' . $value['value'] .',');
                                $query->orWhere($key,'like',' ' . $value['value'] .']');
                                $query->orWhere($key,'like','[' . $value['value'] .']');
                                $query->orWhere($key,'like','[' . $value['value'] .',');
                                break;
                            case "IN":
                                $query->whereIn($key, $value['value']);
                                break;
                        }
                        if(key_exists($value['operator'], $this->operators)){
                            $query->where($key,$this->operators[$value['operator']],$value['value']);
                        }else if($value['operator'] != "AR" || $value['operator'] != "IN"){
                            return StatusCode::$GENERICO_501->setResult('Operação "' . $value['operator'] . '" não soportada');
                        }
                    }else{
                        return StatusCode::$GENERICO_501->setResult('Chave "' . $key . '" não faz parte do esquema de pesquisa');
                    }
                    if(count($Model::$constraints) != ""){
                        $query->union($Model::$constraints);
                    }
                    
                }
            });
        }
        if(isset($request->fields)){
            $fields = explode(',',$request->fields);
            $RQuerry->addSelect($fields);
        }
            
        return StatusCode::$GENERICO_200->setResult($RQuerry);
    }

    public function searcher($request, $Model)
    {

        if(isset($request->querry))
                try{
                    if(isset($request->withtrashed)){
                        $querry = $this->andor($request,$Model::withTrashed(), $Model);
                    }else
                        $querry = $this->andor($request,$Model::query(), $Model);
                    return ($querry);
                }catch(\Exception $e){
                    return (StatusCode::$GENERICO_400->setResult($e->getMessage()));
                }
            else{
                try{
                    if(isset($request->withtrashed)){
                        $querry = $Model::withTrashed();
                    }else{
                        $querry = $Model::query();
                    }
                    if(isset($request->fields)){
                        $fields = explode(',',$request->fields);
                        $querry->addSelect($fields);
                    }
                    return (StatusCode::$GENERICO_200->setResult($querry));
                }catch(\Exception $e){
                    return (StatusCode::$GENERICO_400->setResult($e->getMessage()));
                }
            }
        }
        
    }
