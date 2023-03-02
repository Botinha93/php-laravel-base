<?php

namespace App\Http\Services\Keycloak;
use App\Models\User;
use App\Models\Permissions;
use App\Http\Services\HttpReqProcessor;
use App\Http\Services\StatusCode;
use \HTTP_Request2;

use function PHPUnit\Framework\isEmpty;

    class AdminKeycloack{
        static function init(string $username, string $password, string $keycloakURL, string $realm){
            HttpReqProcessor::getToken($username, $password, $keycloakURL, $realm);
        }
        static function getGroups(){
            $response = HttpReqProcessor::initRequest('/groups', HTTP_Request2::METHOD_GET);
            if ($response->getStatus() == 200) {
                return new StatusCode(200, "Grupos Retornados", json_decode($response->getBody(), true));
            }else{
                return StatusCode::$GENERICO->setResult( $response->getStatus() . ' ' . $response->getReasonPhrase());
            }
            
        }
        static function getGroup($name){
            
            $response = HttpReqProcessor::initRequest('/groups?search=' . $name , HTTP_Request2::METHOD_GET);
            if ($response->getStatus() == 200) {
                $decoded = json_decode($response->getBody(), true);
                if(empty($decoded)){
                    return new StatusCode(404, "Nenhum Grupo Encontrado");
                }
                return new StatusCode(200, "Grupo Encontrado", $decoded );
            }else{
                return StatusCode::$GENERICO->setResult( $response->getStatus() . ' ' . $response->getReasonPhrase());
            }
            
        }
        static function getGroupID($id){
            $response = HttpReqProcessor::initRequest('/groups/' . $id , HTTP_Request2::METHOD_GET);
            if ($response->getStatus() == 200) {
                $decoded = json_decode($response->getBody(), true);
                if(empty($decoded)){
                    return new StatusCode(404, "Nenhum Grupo Encontrado");
                }
                return new StatusCode(200, "Grupo Encontrado", $decoded );
            }else{
                return StatusCode::$GENERICO->setResult( $response->getStatus() . ' ' . $response->getReasonPhrase());
            }
        }
        static function getUserID($name){
            
            $response = HttpReqProcessor::initRequest('/users/' . $name , HTTP_Request2::METHOD_GET);
            if ($response->getStatus() == 200) {
                return new StatusCode(200, "Usuario Encontrado", json_decode($response->getBody(), true));
            }else{
                return StatusCode::$GENERICO->setResult( $response->getStatus() . ' ' . $response->getReasonPhrase());
            }
            
        }
        static function getUser($name){
            
            $response = HttpReqProcessor::initRequest('/users?username=' . $name , HTTP_Request2::METHOD_GET);
            if ($response->getStatus() == 200) {
                return new StatusCode(200, "Usuario Encontrado", json_decode($response->getBody(), true));
            }else{
                return StatusCode::$GENERICO->setResult( $response->getStatus() . ' ' . $response->getReasonPhrase());
            }
            
        }
        static function addUserToGroup($userID, $groupID){
            HttpReqProcessor::initRequest('/users/' .  $userID . "/groups/" .$groupID, HTTP_Request2::METHOD_PUT);
        }
        static function addNewGroup($name){
            $GroupRepresentation = [];
            $GroupRepresentation['name'] = $name;
            HttpReqProcessor::initRequest('/groups', HTTP_Request2::METHOD_POST, json_encode($GroupRepresentation));
        }

        static function registerUser($userRepresentation){
            $response = HttpReqProcessor::initRequest('/users', HTTP_Request2::METHOD_POST, json_encode($userRepresentation));
        }
        
        static function getGroupMembers($id){
            
            $response = HttpReqProcessor::initRequest('/groups/' . $id . '/members' , HTTP_Request2::METHOD_GET);
            $responseG = json_decode(HttpReqProcessor::initRequest('/groups/' . $id, HTTP_Request2::METHOD_GET)->getBody(), true);
            if ($response->getStatus() == 200) {
                $users = array();
                $permissions = array();
                foreach(json_decode($response->getBody(), true) as $value){
                    $user = User::where('idKeycloak', '=', $value['id'])->get();
                    if($user->isEmpty()){
                        $user = new User();
                        $user['idKeycloak'] = $value['id'];
                        $user['permissions'] = [1];
                        $user['usuario'] = $value['username'];
                        $user['Unidade'] = $responseG['name'];
                        $user->save();
                    }
                    $value['permissions'] = array();
                    foreach ($user->first()['permissions'] as $perm) {
                        if(array_key_exists($perm, $permissions)){
                            array_push($value['permissions'],$permissions[$perm]);
                        }else{
                            $permiss = Permissions::find($perm);
                            if($user){
                                $permissions[$perm]=$permiss;
                                array_push($value['permissions'],$permissions[$perm]);
                            }
                        }
                    }
                    array_push($users, $value);
                }
                return new StatusCode(200, "Usuarios Retornados", $users);
            }else{
                return StatusCode::$GENERICO->setResult( $response->getStatus() . ' ' . $response->getReasonPhrase());
            }
            
        }
        static function getClientRoles($id){
            
            $response = HttpReqProcessor::initRequest('/clients/' . $id . '/roles/?briefRepresentation&=false' , HTTP_Request2::METHOD_GET);
            if ($response->getStatus() == 200) {
                return new StatusCode(200, "Roles Retornados", json_decode($response->getBody(), true));
            }else{
                return StatusCode::$GENERICO->setResult( $response->getStatus() . ' ' . $response->getReasonPhrase());
            }
            
        }
        static function getClientRolesUsers($id, $role){
            
            $response = HttpReqProcessor::initRequest('/clients/' . $id . '/roles/' . $role . '/users' , HTTP_Request2::METHOD_GET);
            if ($response->getStatus() == 200) {
                return new StatusCode(200, "Users do role retornados", json_decode($response->getBody(), true));
            }else{
                return StatusCode::$GENERICO->setResult( $response->getStatus() . ' ' . $response->getReasonPhrase());
            }
            
        }
        static function PostClientRoles($id, $ClientID, $Body){
            
            $response = HttpReqProcessor::initRequest('/users/' . $id . '/role-mappings/clients/' . $ClientID  , HTTP_Request2::METHOD_POST, json_encode($Body));
            if ($response->getStatus() == 204) {
                return new StatusCode(200, "Roles Adicionados", json_decode($response->getBody(), true));
            }else{
                return StatusCode::$GENERICO->setResult( $response->getStatus() . ' ' . $response->getReasonPhrase());
            }
            
        }
        static function DeletClientRoles($id, $ClientID, $Body){
            $response = HttpReqProcessor::initRequest('/users/' . $id . '/role-mappings/clients/' . $ClientID , HTTP_Request2::METHOD_DELETE, json_encode($Body));
            if ($response->getStatus() == 204) {
                return new StatusCode(200, "Roles Removidos", json_decode($response->getBody(), true));
            }else{
                return StatusCode::$GENERICO->setResult( $response->getStatus() . ' ' . $response->getReasonPhrase() . ' ' . json_encode($Body));
            }
        }

        static function AddPerfil($idgrupo, $perfil){
            $basegroups = AdminKeycloack::getGroupID($idgrupo);
            if($basegroups['status']['code'] == 200){
                if(is_numeric($basegroups['result']['name']) || $basegroups['result']['name'] == 'APP_GLOBAL'){
                    $Body = '{"name":"APP_' . env("KEYCLOAK_CLIENT") . '"}';
                    $response = HttpReqProcessor::initRequest('/groups/' . $basegroups['result']['id'] . '/children/' , HTTP_Request2::METHOD_POST, $Body);
                    if ($response->getStatus() == 201 || $response->getStatus() == 409){
                        $basegroups = AdminKeycloack::getGroupID($idgrupo);
                        $subgrupo = array();
                        foreach($basegroups['result']['subGroups'] as $thisclient){
                            if($thisclient['name'] == 'APP_' . env("KEYCLOAK_CLIENT") ){
                                $subgrupo = $thisclient;
                                break;
                            }
                        }
                        $Body = '{"name":"'.$perfil.'"}';
                        $response = HttpReqProcessor::initRequest('/groups/' . $subgrupo['id'] . '/children/' , HTTP_Request2::METHOD_POST, $Body);
                        if ($response->getStatus() == 201) {
                            return new StatusCode(200, "Grupo Criado", json_decode($response->getBody(), true));
                        }else if ($response->getStatus() == 409){
                            return new StatusCode(409, "Grupo ja existe", json_decode($response->getBody(), true));
                        }else{
                            return StatusCode::$GENERICO->setResult( $response->getStatus() . ' ' . $response->getReasonPhrase() . ' ' . $Body);
                        }
                    }
                }else{
                    return StatusCode::$GENERICO_400->setResult("Unidade invalida (não numerica)");
                }
            }else{
                return $basegroups;
            }
        }
        static function GetPerfilouPerfis($idgrupo, $perfil = ""){
            $mensagem = "Perfis";
            $basegroups = AdminKeycloack::getGroupID($idgrupo);
            if($basegroups['status']['code'] == 200){
                if(is_numeric($basegroups['result']['name']) || $basegroups['result']['name'] == 'APP_GLOBAL'){
                        foreach($basegroups['result']['subGroups'] as $thisclient){
                            if($thisclient['name'] == 'APP_' . env("KEYCLOAK_CLIENT") ){
                                if($perfil == ""){
                                    $subgrupo = $thisclient;
                                }else{
                                    foreach($thisclient['subGroups'] as $sub){
                                        if($sub['name'] == $perfil){
                                            $subgrupo = $sub;
                                            $mensagem = "Perfil";
                                            break;
                                        }
                                    }
                                }
                            }
                        }
                        if (!empty($subgrupo)) {
                            return new StatusCode(200, $mensagem . " Retornado", $subgrupo);
                        }else {
                            return new StatusCode(404, $mensagem . " Não Encontrado");
                        }
                    }else{
                        return StatusCode::$GENERICO_400->setResult("Unidade invalida (não numerica)");
                    }
            }else{
                return $basegroups;
            }
        }
        static function PutPerfilRoles($id, $ClientID, $Body){
            
            $response = HttpReqProcessor::initRequest('/groups/' . $id . '/role-mappings/clients/' . $ClientID  , HTTP_Request2::METHOD_POST, json_encode($Body));
            if ($response->getStatus() == 200) {
                return new StatusCode(200, "Roles Adicionados", json_decode($response->getBody(), true));
            }else{
                return StatusCode::$GENERICO->setResult( $response->getStatus() . ' ' . $response->getReasonPhrase());
            }
            
        }
        static function DelePerfilRoles($id, $ClientID, $Body){
            $response = HttpReqProcessor::initRequest('/users/' . $id . '/role-mappings/clients/' . $ClientID , HTTP_Request2::METHOD_DELETE, json_encode($Body));
            if ($response->getStatus() == 200) {
                return new StatusCode(200, "Roles Removidos", json_decode($response->getBody(), true));
            }else{
                return StatusCode::$GENERICO->setResult( $response->getStatus() . ' ' . $response->getReasonPhrase() . ' ' . json_encode($Body));
            }
        }
        static function PutUserInPerfil($id, $GroupID){
            $response = HttpReqProcessor::initRequest('/users/' . $id . '/groups/' . $GroupID  , HTTP_Request2::METHOD_PUT);
            if ($response->getStatus() == 204) {
                return new StatusCode(200, "User Adicionado ao Perfil", json_decode($response->getBody(), true));
            }else{
                return StatusCode::$GENERICO->setResult( $response->getStatus() . ' ' . $response->getReasonPhrase());
            }
        }
        static function RemoveUserInPerfil($id, $GroupID){
            $response = HttpReqProcessor::initRequest('/users/' . $id . '/groups/' . $GroupID , HTTP_Request2::METHOD_DELETE);
            if ($response->getStatus() == 204) {
                return new StatusCode(200, "User Removido do Perfil", json_decode($response->getBody(), true));
            }else{
                return StatusCode::$GENERICO->setResult( $response->getStatus() . ' ' . $response->getReasonPhrase());
            }
        }
    }
?>