<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\KeycloakController;
use App\Http\Controllers\LogController;



/*
 __________________________________________________________________________
| Serviços base!!!                                                         |
|__________________________________________________________________________|
|                                                                          |
| Mantenha essa area de codigo sem rotas com regras de negocio especificas |
| a está aplicação, estas são rotas de uso geral, mantenha separado por    |
| motivo de organização                                                    |
|__________________________________________________________________________|

*/
Route::group(['prefix'=>'keycloak','as'=>'keycloak.'], function() {
    Route::post('/validausuario', [KeycloakController::class, 'ValidaUsuario'])->name('ValidaUsuario');
    Route::get('/permission', [KeycloakController::class, 'permissions'])->name('permissions');
    Route::get('/permission/menu', [KeycloakController::class, 'menu'])->name('menu');
    Route::group(['prefix'=>'grupos','as'=>'grupos.'], function() {
        Route::get('/', [KeycloakController::class, 'Grupos'])->name('todos');
        Route::get('/{name}', [KeycloakController::class, 'GruposByName'])->name('byNome');
        Route::get('/{id}/users', [KeycloakController::class, 'usuariosDoGrupo'])->name('usuarios');
    });
    Route::group(['prefix'=>'roles','as'=>'roles.'], function() {
        Route::get('/', [KeycloakController::class, 'Roles'])->name('get');
        Route::delete('/user/{id}/{roles}', [KeycloakController::class, 'UsuarioRemoveRole'])->name('delete');
        Route::post('/user/', [KeycloakController::class, 'UsuarioAddRole'])->name('roleToUser');
        Route::get('/user/', [KeycloakController::class, 'usuariosDoRole'])->name('users');
    });
    Route::group(['prefix'=>'perfil','as'=>'perfil.'], function() {
        Route::post('/', [KeycloakController::class, 'AddPerfil'])->name('new');
        Route::get('/{grupo}', [KeycloakController::class, 'getPerfis'])->name('getAllInGroup');
        Route::get('/{grupo}/{perfil}', [KeycloakController::class, 'getPerfil'])->name('getPerfilOfGroup');
        Route::delete('/role/{id}/{roles}', [KeycloakController::class, 'AddRolePerfil'])->name('deleteRole');
        Route::post('/role', [KeycloakController::class, 'UsuarioAddRole'])->name('addUsuario');
        Route::delete('/{idgrupo}/user/{id}', [KeycloakController::class, 'RemoveUserInPerfil'])->name('deleteUser');
        Route::put('/{idgrupo}/user/{id}', [KeycloakController::class, 'PutUserInPerfil'])->name('putUser');
    });
});
Route::group(['prefix'=>'log','as'=>'logs.'], function() {
    Route::get('/', [LogController::class, 'todos'])->name('get');
    Route::get('/usuario/{usuario}', [LogController::class, 'usuario'])->name('getUsuario');
    Route::get('/tipo/{tipo}', [LogController::class, 'tipo'])->name('getTipo');
    Route::get('/rota/{rota}', [LogController::class, 'rota'])->name('getRota');
    Route::get('/unidade/{unidade}', [LogController::class, 'todos'])->name('getUnidade');
    Route::get('/acoes/', [LogController::class, 'acoes'])->name('getAcoes');
    Route::get('/alertas/', [LogController::class, 'alertas'])->name('getAlertas');
});
