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

    /**
     * Create a new controller instance.
     *
     * @param  ApiClientService $apiClientService
     * @param  DadosFixosService $dadosFixosService
     * @return void
     */
    public function __construct(ApiClientService $apiClientService, DadosFixosService $dadosFixosService)
    {
        $this->apiClientService = $apiClientService;
        $this->dadosFixosService = $dadosFixosService;

    }

    /**
     * Exibe a tela de listagem de times.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        return Inertia::render('Teams', [
            'teams' => $this->dadosFixosService->getTimes()
        ]);
    }

    /**
     * Recupera os dados de um time especifico.
     *
     * @param int $id
     * @return array|\Illuminate\Http\JsonResponse
     */
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
