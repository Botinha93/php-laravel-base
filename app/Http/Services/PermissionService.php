<?php

namespace App\Http\Services;

use App\Http\Services\Keycloak\AdminKeycloack;
use App\Models\Permissions;
use App\Http\Controllers\PermissionsController;
use App\Models\User;

class PermissionService{

    private static $instances = [];
    private $permissions = array();
    private $rolesU = array();

    protected function __construct() { 
        global $UserInfo;
        if(array_key_exists(env('KEYCLOAK_CLIENT'), $UserInfo['resource_access'])){
            foreach ($UserInfo['resource_access'][env('KEYCLOAK_CLIENT')]['roles'] as $role) {
                $rolePerm = Permissions::where('roles', $role)->first();
                if($rolePerm){
                    $this->permissions = array_merge($this->permissions, $rolePerm['endpoint_permissions']);
                }else{
                    AdminKeycloack::init(env('KEYCLOAK_ADMIN'),env('KEYCLOAK_ADMIN_PASS'),env('KEYCLOAK_URL'), env('KEYCLOAK_REALM'));
                    $roles = AdminKeycloack::getClientRoles(env('KEYCLOAK_CLIENT_ID'));
                    if($roles['status']['code'] == 200){
                        for($i=0; $i<count($roles['result']);$i++ ) {
                            if($roles['result'][$i]['name'] == $role ) {
                                array_push($this->rolesU,$roles['result'][$i]['name']);
                                $newPermission = $roles['result'][$i];
                                if(array_key_exists('roles', $newPermission ['attributes'])){
                                    $CLEAN = str_replace(' ', '', $newPermission['attributes']['roles'][0]);
                                    $endpoints = explode(';', $CLEAN);
                                    $this->permissions = array_merge($this->permissions, $endpoints);
                                    $save = new Permissions();
                                    $save['roles'] = $role;
                                    $save['endpoint_permissions'] = $endpoints;
                                    $save->save();
                                }
                            }
                        }
                    }
                }
            }
        }
            $user = User::where('idKeycloak', '=', $UserInfo['sub'])->first();
            if(empty($user)){
                $user = new User();
                $user['idKeycloak'] = $UserInfo['sub'];
                $user['usuario'] = $UserInfo['preferred_username'];
                $user['Unidade'] = $UserInfo['groups'][0];
                $user['permissions'] = [1];
                $user->save();
            }
            $this->rolesU = $user['permissions'];
            foreach ($this->rolesU as $value) {
                $rolePerm = Permissions::where('id', $value)->first();
                if($rolePerm){
                    $this->permissions = array_merge($this->permissions, $rolePerm['endpoint_permissions']);
            }
            
        }
        
    }


    protected function __clone() { }

    public function __wakeup()
    {
        throw new \Exception("Classe é um singletom");
    }

    protected static function getInstance(): PermissionService
    {
        $cls = static::class;
        if (!isset(self::$instances[$cls])) {
            self::$instances[$cls] = new static();
        }
        return self::$instances[$cls];
    }
    public static function __callStatic($name, $arguments)
    {
        return call_user_func(array(Self::getInstance(),$name ), $arguments);
    }

    private function recursivePermission($obj,$permssion, $pos){
        if($pos < (Count($permssion)-1)){
            if(!isset($obj[$permssion[$pos]]))
                $obj[$permssion[$pos]] = array();
            $obj[$permssion[$pos]] = $this->recursivePermission($obj[$permssion[$pos]],$permssion, ($pos+1));
        }else{
            if(!isset($obj))
                $obj = array();
            array_push($obj, $permssion[$pos]);
        }
        return $obj;
    }
    public function buildMenu(){
        $UserMenu = array();
        foreach($this->permissions as $perm){
            $breakdown = explode('.',$perm);
            if($breakdown[0] != ""){
                if(!isset($UserMenu[$breakdown[0]]))
                    $UserMenu[$breakdown[0]] = array();
                $UserMenu[$breakdown[0]] = $this->recursivePermission($UserMenu[$breakdown[0]], $breakdown,1);
            }
        }
        $return['roles'] = $this->rolesU;
        $return['menu'] = $UserMenu;
        return $return;
    }
    public function getPermissions(){
        return array_values(array_merge( $this->permissions, PermissionsController::$exclusion));
    }
}
?>