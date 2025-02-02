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

    private function getPaises()
    {
        return $this->apiClientService->filterCountries(
            $this->apiClientService->getCountries()['areas']
        );
    }
}
