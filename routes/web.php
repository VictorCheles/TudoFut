<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\CompetitionController;

Route::get('/', [CompetitionController::class, 'index']);
Route::get('/times', [CompetitionController::class, 'index']);

//grupo de rotas de campeonatos
Route::prefix('campeonatos')->group(function () {
    Route::get('/{idCountrie}/competitionsCountrie', [CompetitionController::class, 'getCompetitionsCountrie']);
    Route::get('/{code}/dataCompetition', [CompetitionController::class, 'getDataCompetition']);
});
