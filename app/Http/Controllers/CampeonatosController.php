<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ApiClienteService;


class CampeonatosController extends Controller
{
    protected $apiService;

    public function __construct(ApiClienteService $apiService)
    {
        $this->apiService = $apiService;
    }

    public function index($idCountrie)
    {
        try {
            $competicoes = $this->apiService->getCompetitions($idCountrie);

            if(!$competicoes) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Erro ao buscar campeonatos'
                ], 500);
            }

            return response()->json([
                'status' => 'success',
                'data' => $competicoes['competitions']
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erro ao buscar campeonatos'
            ], 500);
        }

    }

    public function getDataCompetition($code)
    {
        $competicao = $this->apiService->getDataCompetition($code);

        if(!$competicao) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erro ao buscar dados da competição'
            ], 500);
        }

        $rodadas = [
            'current' => $competicao['seasons'][0]['currentMatchday'] ?? null,
            'next' => $competicao['seasons'][1]['currentMatchday'] ?? null
        ];

        return response()->json([
            'status' => 'success',
            'data' => [
                'name' => $competicao['name'],
                'matchdays' => [
                    'current' => $this->apiService->getMatchesForMatchday($code,$rodadas['current']),
                    'next' => $this->apiService->getMatchesForMatchday($code,$rodadas['next'])
                ]
            ]
        ]);
    }
}
