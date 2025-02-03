<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ApiClientService;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class CompetitionController extends Controller
{
    private $apiClientService;

    public function __construct(ApiClientService $apiClientService)
    {
        $this->apiClientService = $apiClientService;
    }

    public function index()
    {
        return Inertia::render('Competition', [
            'paises' => $this->getPaises()
        ]);
    }

    public function getCompetitionsCountrie($idCountrie)
    {
        try {
            return Cache::remember('dados_competicoes_'.$idCountrie, now()->addHours(config('cache.tempo_cache')), function () use ($idCountrie) {
                return $this->apiClientService->getCompetitions($idCountrie);
            });
        } catch (\Throwable $th) {
            Log::error("Erro ao buscar dados da API: " . $th->getMessage());
            return response()->json(['error' => 'Ocorreu um erro na consulta dos dados'], 500);
        }
    }

    public function getDataCompetition($code)
    {
        try {
            $competicoesGerais = $this->apiClientService->getDataCompetition($code);
            $rodadaAtual = $competicoesGerais['seasons'][0]['currentMatchday'];

            $dados = [
                'currentMatchday' => $this->apiClientService->getMatchesForMatchday($code, $rodadaAtual),
                'nextMatchday' => $this->apiClientService->getMatchesForMatchday($code, $rodadaAtual + 1),
            ];

            return $dados;
        } catch (\Throwable $th) {
            Log::error("Erro ao buscar dados da API: " . $th->getMessage());
            return response()->json(['error' => 'Ocorreu um erro na consulta dos dados'], 500);
        }
    }

    private function getPaises()
    {
        return Cache::remember('dados_paises', now()->addHours(config('cache.tempo_cache')), function () {
            return $this->apiClientService->filterCountries(
                $this->apiClientService->getCountries()['areas']?? []
            );
        });
    }
}
