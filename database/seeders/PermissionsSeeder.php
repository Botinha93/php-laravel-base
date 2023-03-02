<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permissions')->insert([
            'roles' => "KEYCLOAK_ADM",
            'endpoint_permissions' => [
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
                "pagina.show",
                "pagina.index",
            ],
        ]);
    }
}
