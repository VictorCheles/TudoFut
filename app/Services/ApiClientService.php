<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class ApiClientService
{
    protected $baseUrl;
    private $paisesPermitidos = ['INT', 'EUR', 'DEU', 'NLD', 'BRA', 'ESP', 'FRA', 'ENG', 'POR', 'ITA', 'SAM'];
    private $statusJogosAtivos = ['SCHEDULED','IN_PLAY','PAUSED','LIVE','TIMED'];
    private $statusJogosFinalizados = ['FINISHED'];

    public function __construct()
    {
        $this->baseUrl = config('services.api_cliente.url');
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
                'status' => isset($response) ? $response->status() : 500,
                'data' => null
            ];
        }
    }

    public function getCompetitions($countryId = null)
    {
        $params = $countryId ? ['areas' => $countryId] : [];

        $chaveCache = 'dados_competicoes_por_pais_'.$countryId;
        $response = Cache::remember($chaveCache, now()->addHours(config('cache.tempo_cache')), function () use ($params, $chaveCache) {
            $response = $this->fetchFromApi("/competitions", $params);

            if(isset($response['error']) && $response['error']){
                Cache::forget($chaveCache);
            }

            return $response;

        });
        return $response;
    }

    public function getDataCompetition($code)
    {
        $response = $this->fetchFromApi("/competitions/$code");
        return $response;
    }
    public function getMatchesForMatchday($code, $matchday)
    {
        try{
            $response = $this->fetchFromApi("/competitions/$code/matches",[
                'matchday' => $matchday
            ]);

            return collect($response['matches'])->groupBy(function ($match) {
                return $match['status'];
            });
        }catch(\Throwable $th) {
            Log::error("Erro ao buscar dados dos jogos via API: " . $th->getMessage());
            return response()->json(['error' => 'Ocorreu um erro na consulta dos dados'], 500);
        }

    }

    public function getTeamData($id)
    {
        try{
            $response = $this->fetchFromApi("/teams/$id");
            return $response;
        }
        catch (\Throwable $th) {
            Log::error("Erro ao buscar dados dos times via API: " . $th->getMessage());
            return response()->json(['error' => 'Ocorreu um erro na consulta dos dados'], 500);
        }
    }

    public function filterCountries($paises)
    {
       $filtrados = array_filter($paises, function ($pais) {
            return in_array($pais['countryCode'], $this->paisesPermitidos);
        });
       return $filtrados;
    }

    public function getMatchForTeam($id)
    {
        $response = $this->fetchFromApi("/teams/$id/matches");
        return $response;
    }
}
