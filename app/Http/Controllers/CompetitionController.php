<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ApiClientService;
use Inertia\Inertia;

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
        return $this->apiClientService->getCompetitions($idCountrie);
    }

    public function getDataCompetition($code)
    {
        $competicoesGerais = $this->apiClientService->getDataCompetition($code);
        return $this->apiClientService->getMatchesForMatchday($code, $competicoesGerais['seasons'][0]['currentMatchday']);
        ;
    }

    private function getPaises()
    {
        return $this->apiClientService->filterCountries(
            $this->apiClientService->getCountries()['areas']
        );
    }
}
