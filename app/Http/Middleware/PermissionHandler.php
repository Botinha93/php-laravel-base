<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Http\Services\StatusCode;
use App\Http\Services\PermissionService;
use App\Http\Services\Keycloak\AdminKeycloack;

class PermissionHandler
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
        AdminKeycloack::init(env('KEYCLOAK_ADMIN'),env('KEYCLOAK_ADMIN_PASS'),env('KEYCLOAK_URL'), env('KEYCLOAK_REALM'));
        global $UserInfo;
        $route = $request->route()->getName();
        if(str_contains($request->path() , 'api/public/') || str_contains($request->path() , 'view/')){
            return $next($request);
        }
        if(!array_key_exists('resource_access', $UserInfo) && !array_key_exists(env('KEYCLOAK_CLIENT'), $UserInfo['resource_access'])){
            return response()->json(StatusCode::$GENERICO_401->setResult("Esta aplicação não foi autorizada ao usuario."), 401);
        }
        if(!in_array($route, PermissionService::getInstance()->getPermissions())){
            return response()->json(StatusCode::$GENERICO_401->setResult("Rota " . $route . " (" . $request->fullUrl() . ") não autorizada para este usuario."), 401);
        }else{
            return $next($request);
        }
        return response()->json(StatusCode::$GENERICO_501->setResult("Rota " . $route . " (" . $request->fullUrl() . ") requer permissionamento porem o mesmo não foi implementado"), 501);
    }
}
