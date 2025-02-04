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

/**
 * ApiClientService constructor.
 *
 * Initializes the base URL for the API client using the configuration
 * provided in the services configuration file.
 */

    public function __construct()
    {
        $this->baseUrl = config('services.api_cliente.url');
    }

/**
 * Fetches data from the specified API endpoint.
 *
 * Sends a GET request to the provided API endpoint with the given parameters.
 * Uses the API client configuration to include the necessary authentication token.
 * If the API request fails, logs the error and returns an error response with details.
 *
 * @param string $endpoint The API endpoint to request data from.
 * @param array $params Optional parameters to include in the request.
 *
 * @return \Illuminate\Support\Collection A collection of the API response data,
 * or an array containing error information if the request fails.
 *
 * @throws \Exception If the API request fails.
 */

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
            return response()->json([
                'error' => true,
                'message' => $e->getMessage(),
                'status' => isset($response) ? $response->status() : 500,
                'data' => null
            ], 500);
        }
    }

/**
 * Retrieves a list of competitions, optionally filtered by country.
 *
 * Fetches competition data from the API, caching the response to reduce
 * the number of API requests. If a country ID is provided, the fetched
 * competitions are limited to that country.
 *
 * @param string|null $countryId Optional country ID to filter competitions.
 *
 * @return \Illuminate\Support\Collection|array Cached API response containing the
 * competition data or an error array if the request fails.
 */

    public function getCompetitions($countryId = null)
    {
        try {
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
        } catch (\Throwable $th) {
            Log::error("Erro ao buscar dados da API: " . $th->getMessage());
            return response()->json(['error' => 'Ocorreu um erro na consulta dos dados'], 500);
        }
    }

    /**
     * Fetches a competition's data by its unique code.
     *
     * The response will contain the competition's data.
     *
     * @param string $code Unique identifier of the competition.
     *
     * @return array API response containing the competition data or an error array if the request fails.
     */
    public function getDataCompetition($code)
    {
        $response = $this->fetchFromApi("/competitions/$code");
        return $response;
    }
/**
 * Retrieves matches for a specific competition and matchday.
 *
 * Fetches match data from the API for the given competition code
 * and matchday, then groups the matches by their status.
 *
 * @param string $code Unique identifier of the competition.
 * @param int $matchday The matchday number to retrieve matches for.
 *
 * @return \Illuminate\Support\Collection|\Illuminate\Http\JsonResponse A collection grouped by match status or
 * a JSON response with error details if the request fails.
 */

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

    /**
     * Retrieves team data by team ID.
     *
     * Fetches detailed information about a specific team using its unique
     * identifier from the API. If the request fails, logs the error and
     * returns a JSON response with error details.
     *
     * @param int $id Unique identifier of the team.
     *
     * @return array|\Illuminate\Http\JsonResponse API response containing the team data
     * or a JSON response with error details if the request fails.
     */

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

    /**
     * Filters the provided list of countries.
     *
     * Iterates over the given countries and returns only those that are
     * permitted based on their country codes. The list of allowed country
     * codes is defined in the instance's $paisesPermitidos property.
     *
     * @param array $paises List of countries to filter, each represented as
     * an associative array with a 'countryCode' key.
     *
     * @return array Filtered list of countries that are permitted.
     */

    public function filterCountries($paises)
    {
       $filtrados = array_filter($paises, function ($pais) {
            return in_array($pais['countryCode'], $this->paisesPermitidos);
        });
       return $filtrados;
    }

    /**
     * Retrieves matches for a specific team.
     *
     * Fetches a list of matches played by a team identified by its unique
     * identifier from the API. If the request fails, logs the error and
     * returns a JSON response with error details.
     *
     * @param int $id Unique identifier of the team.
     *
     * @return array|\Illuminate\Http\JsonResponse API response containing the team's matches
     * or a JSON response with error details if the request fails.
     */
    public function getMatchForTeam($id)
    {
        $response = $this->fetchFromApi("/teams/$id/matches");
        return $response;
    }
}
