<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notificações;
use App\Http\Services\grupos;

class NotificaçõesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function get(Request $request)
    {
        global $UserInfo;
        $unidades = array();
        foreach ($UserInfo['groups'] as $value) {
            if(is_numeric($value)){
                array_push($unidades, $value);
                $filhos = grupos::arvore($value);
                if(array_count_values($filhos) > 0)
                 $unidades= array_merge($unidades, $filhos);
            }
        }
        $returnv['usuario'] = Notificações::where('usuario', $UserInfo['preferred_username'])->get();
        $returnv['unidade'] = Notificações::whereIn('unidades', $unidades)->get();
        return response()->json($returnv, 200);
    }
    public function del($id)
    {
        return response()->json(Notificações::findOrFail($id)->delete(), 200);
    }
}
