<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;

class ApiClienteService
{
    protected $baseUrl;
    private $paisesPermitidos = ['INT', 'EUR', 'DEU', 'NLD', 'BRA', 'ESP', 'FRA', 'ENG', 'POR', 'ITA', 'SAM'];

    public function __construct()
    {
        $this->baseUrl = config('services.api_cliente.url'); // Configurar no .env
    }

    public function fetchFromApi(string $endpoint, array $params = [])
    {
        try {
            $response = Http::withHeaders([
                'X-Auth-Token' => config('services.api_cliente.token')
            ])->get($this->baseUrl.$endpoint, $params);

            if($response->failed())
            {
                throw new \Exception("Erro ao buscar dados da API. Código: " . $response->status());
            }

            return collect($response->json());
        } catch (\Throwable $e) {
            Log::error("Erro ao buscar dados da API: " . $e->getMessage());
            return [
                'error' => true,
                'message' => $e->getMessage(),
                'status' => $response->status() ?? 500,
                'data' => null
            ];
        }
    }

    public function getCountries()
    {
        $response = $this->fetchFromApi('/areas');
        return $response;
    }

    public function getCompetitions($countryId)
    {
        $response = $this->fetchFromApi("/competitions",[
            'areas' => $countryId
        ]);
        return $response;
    }

    public function getDataCompetition($code)
    {
        $response = $this->fetchFromApi("/competitions/$code");
        return $response;
    }
    public function getMatchesForMatchday($code, $matchday)
    {
        $response = $this->fetchFromApi("/competitions/$code/matches",[
            'matchday' => $matchday
        ]);

        return collect($response['matches'])->map(function ($match) {
            return [
                'homeTeam' => $match['homeTeam']['name'] ?? 'Desconhecido',
                'awayTeam' => $match['awayTeam']['name'] ?? 'Desconhecido',
                'utcDate' => $match['utcDate'] ?? 'Sem data',
                'status' => $match['status'] ?? 'Indefinido'
            ];
        });
    }

    public function filterCountries($paises)
    {
       $filtrados = array_filter($paises, function ($pais) {
            return in_array($pais['countryCode'], $this->paisesPermitidos);
        });
       return $filtrados;
    }
}
