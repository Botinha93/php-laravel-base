<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Http\Services\Keycloak\KeycloakUser;
use App\Http\Services\StatusCode;


class AuthenticateKeycloakToken
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
        if(str_contains($request->path() , 'api/public/') || str_contains($request->path() , 'view/') || str_contains($request->path() , 'js/')){
            return $next($request);
        }else{
            $tdecode = KeycloakUser::tokenDecoder($request->header('token'));
            $host = request()->headers->get('Host');
            if ($tdecode["status"]['code'] == 200) {
                $response =  KeycloakUser::validaUsuario($tdecode["result"]['iss'], $request->header('token'));
                if ($response["status"]['code'] == 200 || $response["status"]['code'] == 422) {
                    return $next($request);
                }else{
                    return response()->json($response, $response["status"]['code']);
                }
            } else {
                return response()->json($tdecode, $tdecode["status"]['code']);
            }
            return response()->json($tdecode, $tdecode["status"]['code']);
        }
    }
}
