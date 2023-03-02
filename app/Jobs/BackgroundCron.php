<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\Notificações;
use App\Models\User;
use App\Models\Alerta;
use App\Models\Material;

class BackgroundCron implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->runAlerta();
        $this->runPermission();
    }

    public function runPermission() {
        $itens = User::where('inicio', '<>', NULL)->get();
        foreach ($itens as $user) {
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
    public function runAlerta() {
        $itens = Alerta::where('ativo', true)->get();
        foreach ($itens as $value) {
            $mat = Material::findOrFail($value['materialID']);
            $valuetc = null;
            if($value['tipo'] == 1){
                $valuetc = $this->comparativo($mat['quantidade'], $value['comparativo'],$value['formula']);
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
        }
        return $v1 < $v2;
    }
}

