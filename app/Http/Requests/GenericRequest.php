<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class GenericRequest
{
    static public function rules($request, $validate)
    {
        $validator = Validator::make($request, $validate);
        if ($validator->fails()) {
            return json_encode($validator->errors());
        }
        return $validator->validated();
    }
    static public function requestProcessor($validated, $class, $obj = null)
    {
        $data = array();
        if(is_array($validated)){
            if(is_null($obj)){
                $obj = new $class();
                foreach($validated as $key=>$val)
                {
                    $obj[$key]=$val;
                }
            }else{
                foreach($validated as $key=>$val)
                {
                    $obj[$key]=$val;
                }
            }
            try{
                $obj->save();
                return $obj;
            }catch(\Exception $e){
                return $e;
            }
        }else{
            return $validated;
        }
    }
}
