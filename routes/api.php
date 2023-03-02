<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaginaController;
use App\Http\Controllers\PermissionsController;



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


/*
 __________________________________________________________________________
| API Routes!!!!!!                                                         |
|__________________________________________________________________________|
|                                                                          |
|   Here is where you can register API routes for your application. These  |
|   routes are loaded by the RouteServiceProvider within a group which     |
|   is assigned the "api" middleware group. Enjoy building your API!       |
|__________________________________________________________________________|

*/


Route::apiResource('perfil', PermissionsController::class);


Route::post('perfil/addToUser/', [PermissionsController::class, 'addToUser'])->name('perfil.addToUser');
Route::get('perfil/temppermissionsaprova/{id}', [PermissionsController::class, 'tempPermissionsAprova'])->name('perfil.tempPermissionsAprova');
Route::get('perfil/temppermissionsnega/{id}', [PermissionsController::class, 'tempPermissionsNega'])->name('perfil.tempPermissionsNega');
Route::post('perfil/temp/', [PermissionsController::class, 'tempPermissions'])->name('perfil.tempPermissions');
Route::post('perfil/temp/listar', [PermissionsController::class, 'listar'])->name('perfil.tempPermissionsListar');

