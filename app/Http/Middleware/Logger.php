<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Log;
use Illuminate\Support\Str;

class Logger
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(Str::contains($request->route()->getName(), ['index', 'show', 'keycloak', 
        "tag.store",
        "tag.update",
        "marca.store",
        "marca.update",
        "modelo.store",
        "modelo.update",
        "template.store",
        "template.update",
        "campos.store",
        "campos.update",
        "campos.destroy",
        "categoria.store",
        "categoria.update",
        "item.store",
        "recibo.store",
        "recibo.update",
        "material.store",
        "permuta.store",
        "permuta.update",
        "permuta.destroy",
        "pagina.store",
        "pagina.update",
        "pagina.destroy",
        "recibo.pdf",
        "solicitacao.store",
        "solicitacao.update",
        "keycloak",
        "logs.get",
        "logs.getUsuario",
        "logs.getTipo",
        "logs.getRota",
        "logs.getUnidade",
        "logs.getAcoes",
        "log.getAlertas",
        "public.grupo.arvore",
        "public.grupo.todos",
        "public.material.listardisponibilizado",
        ])){
            return $next($request);
        }
        if($request->route()->getName() != 'notificacoes.todas'){
            global $UserInfo;
            $response = $next($request);
            $tipo = 3;
            if(str_contains($request->path() , 'api/public/')){
                $tipo=2;
            }else if(str_contains($request->path() , 'api/keycloak/')){
                $tipo=1;
            }
            $groups = "";
            if($tipo!=2){
                foreach($UserInfo['groups'] as $group)
                    if(is_numeric($group))
                        if($groups == ""){
                            $groups = $group;
                        }else
                            $groups = $groups . ',' . $group;
            }
            $newlog = new Log([
                'tipo' => $tipo,
                'rota' => empty($request->route()->getName())? $request->path() : $request->route()->getName(),
                'request' => empty($request->all())? $request->path() : $request->all(),
                'resultado' => $response->content(),
                'usuario' => $tipo==2? "acesso publico" : $UserInfo['preferred_username'],
                'usuario_nome' => $tipo==2? "acesso publico" : $UserInfo['name'],
                'unidades' => $groups,
            ]);
            $newlog->save();
        }else{
            $response = $next($request);
        }
        return $response;
    }
}
