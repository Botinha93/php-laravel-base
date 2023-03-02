<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificaçõesController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\PermissionsController;
use App\Http\Services\AsyncOperation;

Route::get('/teste/{x}', [NotificaçõesController::class, 'cometTeste'])->name('teste');
Route::get('grupo/pai/{unidade}', [GroupController::class, 'pai'])->name('grupo.pai');
Route::get('grupo/filho/{unidade}', [GroupController::class, 'filho'])->name('grupo.filho');
Route::get('grupo/arvore/{unidade}', [GroupController::class, 'arvore'])->name('grupo.arvore');
Route::get('grupo/arvoreescalonada/{unidade}', [GroupController::class, 'arvoreEscalonada'])->name('grupo.arvore');
Route::get('grupo/arvoreraiz/', [GroupController::class, 'arvoreRaiz'])->name('grupo.arvore');
Route::get('grupo/todos/', [GroupController::class, 'todas'])->name('grupo.todos');
Route::get('adm', [GroupController::class, 'adm'])->name('grupo.todos');
Route::get('perfil/listarTodas/', [PermissionsController::class, 'listarTodas'])->name('perfil.listarTodas');

Route::get('threads', [AsyncOperation::class, 'runThreads'])->name('run.internal.op');


