<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DesafioController;

// Rota protegida por Sanctum (se estiver usando autenticação)
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

  
});

Route::prefix('desafios')->group(function () {      
    Route::get('/all', [DesafioController::class, 'index']);            
    Route::get('/{id}', [DesafioController::class, 'indexId']);      
    Route::post('/', [DesafioController::class, 'store']);          
    Route::put('/{id}', [DesafioController::class, 'update']);       
      
});
