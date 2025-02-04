<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Services\ApiClientService;
use App\Services\DadosFixosService;
use Illuminate\Support\Facades\Log;

class TeamController extends Controller
{
    private $apiClientService;
    private $dadosFixosService;

    public function __construct(ApiClientService $apiClientService, DadosFixosService $dadosFixosService)
    {
        $this->apiClientService = $apiClientService;
        $this->dadosFixosService = $dadosFixosService;

    }

    public function index()
    {
        return Inertia::render('Teams', [
            'teams' => $this->dadosFixosService->getTimes()
        ]);
    }

    public function getDadosTeam($id)
    {
        try {

            $team = $this->apiClientService->getTeamData($id);

            $codeCompetitions = isset($team['runningCompetitions']) ? collect($team['runningCompetitions'])->pluck('code') : [];

            $matchs = $this->apiClientService->getMatchForTeam($id);

            $retorno = [
                'team' => $team,
                'nextMatchs' => collect($matchs['matches'])->filter(function ($match){
                    return $match['status'] != 'FINISHED';
                }),
                'previousMatchs' => collect($matchs['matches'])->filter(function ($match){
                    return $match['status'] == 'FINISHED';
                })
            ];

            return $retorno;

        } catch (\Throwable $th) {
            Log::error("Erro ao buscar dados da API: " . $th->getMessage());
            return response()->json(['error' => 'Ocorreu um erro na consulta dos dados'], 500);
        }
    }
}
