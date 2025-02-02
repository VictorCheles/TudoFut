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
                'status' => isset($response) ? $response->status() : 500,
                'data' => null
            ];
        }
    }

    public function getCountries()
    {
        $response = $this->fetchFromApi('/areas');
        return $response;
    }

    public function getCompetitions($countryId = null)
    {
        $params = $countryId ? ['areas' => $countryId] : [];

        $response = $this->fetchFromApi("/competitions", $params);
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

        return collect($response['matches'])->groupBy(function ($match) {
            return $match['status'];
        });
    }

    public function getTeams($offset = 0)
    {
        try{
            return Cache::remember("dados_team_for_offset_$offset", now()->addHours(config('cache.tempo_cache')), function () use ($offset) {
                return $this->fetchFromApi("/teams",[
                    'limit' => 500,
                    'offset' => $offset,
                ]);
            });
        }
        catch (\Throwable $th) {
            Log::error("Erro ao buscar dados da API: " . $th->getMessage());
            return response()->json(['error' => 'Ocorreu um erro na consulta dos dados'], 500);
        }

    }

    public function getTeamData($id)
    {
        dd('ID:'.$id);
        try{
            $response = $this->fetchFromApi("/teams/$id");
            return $response;
        }
        catch (\Throwable $th) {
            Log::error("Erro ao buscar dados dos times via API: " . $th->getMessage());
            return response()->json(['error' => 'Ocorreu um erro na consulta dos dados'], 500);
        }
    }

    public function getTeamForName($name)
    {
        try {
            $teamResponse = [];
            foreach (range(0, 8000, 500) as $offset) {
                $response = $this->getTeams($offset);
                $teams = isset($response['teams']) ? collect($response['teams']) : [];

                if(!empty($teams) && $filteredTeams = $teams->filter(fn($team) => stripos($team['name'], $name))){
                    if(!empty($filteredTeams)){
                        $teamResponse = $teamResponse + $filteredTeams->toArray();
                    }
                }
            }

            if (!$teamResponse) {
                return response()->json(['error' => 'Time não encontrado'], 404);
            }
            return $teamResponse ;
        } catch (\Throwable $th) {
            Log::error("Erro ao buscar dados da API: " . $th->getMessage());
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
}
