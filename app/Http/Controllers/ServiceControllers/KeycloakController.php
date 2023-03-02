<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Services\Keycloak\AdminKeycloack;
use App\Http\Services\Keycloak\KeycloakUser;
use App\Http\Services\PermissionService;


class KeycloakController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function usuariosDoGrupo($id)
    {
        if(is_numeric($id)){
            $id = AdminKeycloack::getGroup($id)["result"][0]["id"];
        }
        $response = AdminKeycloack::getGroupMembers($id);
        return response()->json($response,$response['status']['code']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function Grupos()
    {
        $response = AdminKeycloack::getGroups();
        return response()->json($response,$response['status']['code']);
    }
    public function GruposByName($name)
    {
        $response = AdminKeycloack::getGroup($name);
        return response()->json($response,$response['status']['code']);

    }
     /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function Roles()
    {
        return response()->json(AdminKeycloack::getClientRoles(env('KEYCLOAK_CLIENT_ID')));
    }
    public function usuariosDoRole()
    {
        $KeyRoles = $this->RoleProcessor([
            "I_CRIAR_NOVA_NOTA",
            "I_CRIAR_NOTICIAS",
            "I_ADICIONAR_BANNER"]);
        
        $requestBody = array();
        foreach ($KeyRoles as &$KeyRole) {
            
                $users = AdminKeycloack::getClientRolesUsers(env('KEYCLOAK_CLIENT_ID'), $KeyRole["name"]);
                if($users['status']['code'] == 200);
                foreach ($users['result'] as &$user) {
                    if(!array_key_exists($user['id'], $requestBody)){
                        $requestBody[$user["id"]] = $user;
                    }
                }
        }
        return response()->json($requestBody);
    }
    function RoleProcessor($Roles){
        
        $KeyRoles = AdminKeycloack::getClientRoles(env('KEYCLOAK_CLIENT_ID'))["result"];
        $requestBody = array();
        foreach ($Roles as &$Role) {
            foreach ($KeyRoles as &$KeyRole) {
                if($KeyRole["name"] == $Role){
                    array_push($requestBody, $KeyRole);
                }
            }
        }
        return $requestBody;
    }
    
    /**
     * Display a listing of the resource.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function UsuarioAddRole(Request $request)
    {
        $response = AdminKeycloack::PostClientRoles($request->get('id'), env('KEYCLOAK_CLIENT_ID'), $this->RoleProcessor($request->get('roles')));
        return response()->json($response, $response['status']['code']);
    }
    /**
     * Display a listing of the resource.
     * @param  string  $id
     * @param  string  $roles
     * @return \Illuminate\Http\Response
     */
    public function UsuarioRemoveRole($id, $roles)
    {
        $response = AdminKeycloack::DeletClientRoles($id, env('KEYCLOAK_CLIENT_ID'), $this->RoleProcessor(explode(",",$roles)));
        return response()->json($response, $response['status']['code']);
    }
    //perfil
    public function AddPerfil(Request $request)
    {
        $response = AdminKeycloack::AddPerfil($request->get('grupo'), $request->get('perfil'));
        return response()->json($response, $response['status']['code']);
    }
    public function getPerfil($grupo, $perfil)
    {
        $response = AdminKeycloack::GetPerfilouPerfis($grupo, $perfil);
        return response()->json($response, $response['status']['code']);
    }
    public function getPerfis($grupo)
    {
        $response = AdminKeycloack::GetPerfilouPerfis($grupo);
        return response()->json($response, $response['status']['code']);
    }
    public function AddRolePerfil(Request $request)
    {
        $response = AdminKeycloack::PostClientRoles($request->get('idgrupo'), env('KEYCLOAK_CLIENT_ID'), $this->RoleProcessor($request->get('roles')));
        return response()->json($response, $response['status']['code']);
    }
    public function RemoveRolePerfil($idgrupo, $roles)
    {
        $response = AdminKeycloack::DeletClientRoles($idgrupo, env('KEYCLOAK_CLIENT_ID'), $this->RoleProcessor(explode(",",$roles)));
        return response()->json($response, $response['status']['code']);
    }
    public function PutUserInPerfil($idgrupo, $id)
    {
        $response = AdminKeycloack::PutUserInPerfil($id,$idgrupo);
        return response()->json($response, $response['status']['code']);
    }
    public function RemoveUserInPerfil($idgrupo, $id)
    {
        $response = AdminKeycloack::RemoveUserInPerfil($id,$idgrupo);
        return response()->json($response, $response['status']['code']);
    }
    public function ValidaUsuario(Request $request)
    {
        $validatedData = $request->validate([
            'pass' => 'required',
            'user' => 'required',
        ],
        [
           'pass.required'=> 'Password não é opcional', // custom message
           'user.required'=> 'Usuario não é opcional' // custom message
        ]
     );
        $response = KeycloakUser::loginUsuario(env('KEYCLOAK_URL') . '/realms/' . env('KEYCLOAK_REALM'), $request->user,$request->pass, env('KEYCLOAK_CLIENT'));
        if($response['status']['code'] == 200)
            $response = AdminKeycloack::getUser($request->user);
        return response()->json($response, $response['status']['code']);
    }
    public function menu(){
        
        return response()->json(PermissionService::getInstance()->buildMenu());
    }
    public function permissions(){
        return response()->json(PermissionService::getInstance()->getPermissions());
    }
}
