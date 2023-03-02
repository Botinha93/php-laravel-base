<?php

namespace App\Http\Services;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notificações;
use App\Models\User;
use App\Models\Alerta;
use App\Models\Material;
use Thread;

class AsyncOperation extends Controller{

    public function runThreads(Request $request){
        error_log($this->getIp() . ' e e ' . request()->ip());
        //if($this->getIp() == request()->ip()){
            //dispatch(new BackgroundCron());
            (new AsyncOperationNotifica())->run();
            (new AsyncOperationPermission())->run();
        //}
    } 
    public function getIp(){
        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key){
            if (array_key_exists($key, $_SERVER) === true){
                foreach (explode(',', $_SERVER[$key]) as $ip){
                    $ip = trim($ip); // just to be safe
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false){
                        return $ip;
                    }
                }
            }
        }
        return request()->ip(); 
    }
}

class AsyncOperationNotifica {

    public function run() {
        $itens = Alerta::where('ativo', true)->get();
        foreach ($itens as $value) {
            $mat = Material::findOrFail($value['materialID']);
            $valuetc = null;
            if($value['tipo'] == 1){
                $valuetc = $this->comparativo($mat['quantidade'], $value['comparativo'],$value['formula'] == 2? 4:$value['formula']);
            }else if($value['tipo'] == 2){
                $valuetc = $this->comparativo($value['updated_at']->addDays($value['comparativo']), now(),$value['formula']);
            }else if($value['tipo'] == 3){
                $valuetc = $this->comparativo($value['updated_at']->addDays($value['comparativo']), now(),$value['formula']);
            }else{
                $valuetc = $this->comparativo($value['updated_at']->addDays($value['comparativo']),now(),$value['formula']);
            }
            Notificações::Notifica('alerta','',$value['unidade'],'Alerta ' . $value['informado'] . ' para o material ' . $value['materialID']);
        }
    
    }
    function comparativo($v1, $v2, $tipo){
        if($tipo == 1){
            return $v1 > $v2;
        }
        else if($tipo == 2){
            return $v1 == $v2 || $v1 < $v2;
        }else if($tipo == 4){
            return $v1 == $v2;
        }
        return $v1 < $v2;
    }
}

class AsyncOperationPermission {
    
    public function run() {
        $itens = User::where('inicio', '<>', NULL)->get();
        foreach ($itens as $user) {
            error_log(json_encode($user));
            if($user['inicio'] <= now()){
                if($user['fim'] <= now()){
                    $permis = $user['permissions'];
                    foreach ($user['tempPermissions'] as $tempvalue) {
                        for ($i=0; $i < count($user['permissions']); $i++) { 
                            if($tempvalue == $user['permissions'][$i]){
                                unset($permis[$i]);
                            }
                        }
                    }
                    $user['permissions'] = array_unique($permis, SORT_REGULAR);
                    $user['aprovado'] = false;
                    $user['inicio'] = null;
                    $user['fim'] = null;
                    $user['tempPermissions'] = array();
                    $user['justificativa'] = "";
                    $user->save();
                }else if ($user['aprovado']){
                    $user['permissions'] = array_merge($user['permissions'],$user['tempPermissions']);
                    $user->save();
                }
            }
        }
    }
}
