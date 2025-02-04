<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ApiClientService;
use App\Services\DadosFixosService;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class CompetitionController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        return Inertia::render('Competition', [
            'paises' => $this->getPaises()
        ]);
    }

    /**
     * Return a list of competitions for a given country.
     *
     * This method queries the API and caches the response for a given amount of time.
     * If an error occurs, log the error and return a JSON response with a 500 status and an error message.
     *
     * @param int $idCountrie ID of the country.
     *
     * @return array List of competitions or a JSON response with error details.
     */
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

    /**
     * Retrieves the current and next matchday data for a given competition.
     *
     * This method fetches general competition data and retrieves matches for the
     * current and next matchdays using the competition code. If an error occurs
     * during the API request, it logs the error and returns a JSON response with
     * a 500 status and an error message.
     *
     * @param string $code Unique identifier of the competition.
     *
     * @return array JSON response containing matchday data or an error message if the request fails.
     */

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

    /**
     * Filtra os países com base nos países permitidos na API.
     *
     * @return array Lista de países filtrada.
     */
    private function getPaises()
    {
        return $this->apiClientService->filterCountries(
            $this->dadosFixosService->getPaises()->toArray() ?? []
        );
    }
}
