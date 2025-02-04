<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaisesController;
use App\Http\Controllers\CampeonatosController;

//grupo de rotas de campeonatos
Route::prefix('campeonatos')->group(function () {
    Route::get('/{idCountrie}/competitionsCountrie', [CampeonatosController::class, 'index']);
    Route::get('/{code}/dataCompetition', [CampeonatosController::class, 'getDataCompetition']);
});
