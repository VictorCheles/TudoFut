<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\CompetitionController;
use App\Http\Controllers\TeamController;

Route::get('/', [CompetitionController::class, 'index'])->name('campeonatos');


Route::prefix('teams')->group(function () {
    Route::get('/', [TeamController::class, 'index'])->name('times');
    Route::post('/search/name', [TeamController::class, 'getNameTeams']);
    Route::get('/search/id/{id}', [TeamController::class, 'getDadosTeam']);
});

Route::prefix('campeonatos')->group(function () {
    Route::get('/{idCountrie}/competitionsCountrie', [CompetitionController::class, 'getCompetitionsCountrie']);
    Route::get('/{code}/dataCompetition', [CompetitionController::class, 'getDataCompetition']);
});
