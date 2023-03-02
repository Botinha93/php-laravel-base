<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models;
use App\Models\User;
use App\Http\Services\grupos;
use App\Http\Services\StatusCode;
use App\Models\Permissions;
use Illuminate\Support\Facades\Route;
use App\Models\Notificações;
use App\Http\Services\Search;

use function PHPUnit\Framework\isEmpty;

class PermissionsController extends GenericController
{
    public static $exclusion = [
        "keycloak.ValidaUsuario",
        "keycloak.permissions",
        "keycloak.menu",
        "keycloak.grupos.todos",
        "keycloak.grupos.byNome",
        "keycloak.grupos.usuarios",
        "keycloak.roles.get",
        "keycloak.roles.delete",
        "keycloak.roles.roleToUser",
        "keycloak.roles.users",
        "keycloak.perfil.new",
        "keycloak.perfil.getAllInGroup",
        "keycloak.perfil.getPerfilOfGroup",
        "keycloak.perfil.deleteRole",
        "keycloak.perfil.addUsuario",
        "keycloak.perfil.deleteUser",
        "keycloak.perfil.putUser",
        "logs.get",
        "logs.getUsuario",
        "logs.getTipo",
        "logs.getRota",
        "logs.getUnidade",
        "logs.getAcoes",
        "logs.show",
        "pagina.show",
        "pagina.index",
        'catmas.show',
        'catmas.index',
        'calor.cron'
    ];
    public static $inclusions = [
        "material.store.notifica",
        "material.agrupamento",
        "historico.descarte.descarga",
        "historico.movimentaçoes",
        "material.listarDisponibilizado",
        "ver.dados",
        "relatorio.geral",
        "relatorio.auditoria",
        "visualizar.painel",
        "visualizar.usuarios"
    ];
    public function __construct()
    {
        $this->Model = "App\\Models\\Permissions";
    }
    public function index(Request $request)
    {
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
        unset($index['result'][0]);
        $arr = array();
        foreach($index['result'] as $v){
            array_push($arr,$v);
        }
        $index['result'] = $arr ;
        $index['result'] = array_values($index['result']);
        return response()->json($index);
    }
    public function listarTodas(){
        $routeCollection = Route::getRoutes();
        
        $arr = array();
        foreach ($routeCollection as $value) {
            if(!empty($value) && !str_contains($value->getName(), 'public') && !str_contains($value->getName(), 'ignition'))
            array_push($arr, $value->getName());
        }
        return response()->json(array_values(array_filter(array_unique(array_merge(array_diff($arr, PermissionsController::$exclusion),PermissionsController::$inclusions)))));
        
    }
    public function addToUser(Request $request){
        $user = User::where('idKeycloak', '=', $request->get('user'))->first();
        if(empty($user)){
            $user = new User();
            $user['idKeycloak'] = $request->get('user');
        }
        $user['permissions'] = $request->get('permissions');
        $user->save();
    }
    public function tempPermissions(Request $request){
        $user = User::where('idKeycloak', '=', $request->get('user'))->first();
        if(empty($user)){
            $user = new User();
            $user['idKeycloak'] = $request->get('user');
            $user['permissions'] = [1];
        }
        $user['tempPermissions'] = $request->get('tempPermissions');
        $user['justificativa'] = $request->get('justificativa');
        $user['inicio'] = $request->get('inicio');
        $user['fim'] = $request->get('fim');
        $user->save();
        Notificações::Notifica('tempPermissions',$request->get('usuario'),$user['Unidade'],'Usuario ' . $user['usuario'] . ' requisita permissoes temporarias');
    }
    public function listar(Request $request){
        $unidades = array();
        $Array = explode('.', $request->header('token'));
        $publicKey = "-----BEGIN PUBLIC KEY-----\n" . env('KEYCLOAK_PKEY') . "\n-----END PUBLIC KEY-----";
        $payload = utf8_decode($Array[0] . '.' . $Array[1]);
        $signature = base64_decode(strtr($Array[2], '-_', '+/'));
        global $UserInfo;
        $UserInfo =  json_decode(base64_decode($Array[1]), true);
        foreach ($UserInfo['groups'] as $value) {
            if(is_numeric($value)){
                array_push($unidades, $value);
                $filhos = grupos::arvore($value);
                if(array_count_values($filhos) > 0)
                    $unidades = array_merge($unidades, $filhos);
            }
        }
        $user = User::whereJsonLength('tempPermissions','>', 0)->whereIn('Unidade', $unidades)->get();
        return response()->json($user);
    }
    public function tempPermissionsAprova($id){
        $user = User::where('idKeycloak', '=', $id)->first();
        $user['aprovado'] = true;
        $user->save();
    }
    public function tempPermissionsNega($id){
        $user = User::where('idKeycloak', '=', $id)->first();
        $user['tempPermissions'] = array();
        $user['justificativa'] = "";
        $user['inicio'] = null;
        $user['fim'] = null;
        $user->save();
    }
}
