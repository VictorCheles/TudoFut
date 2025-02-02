<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Services\ApiClientService;

class TeamController extends Controller
{
    private $apiClientService;

    public function __construct(ApiClientService $apiClientService)
    {
        $this->apiClientService = $apiClientService;
    }

    public function index()
    {
        return Inertia::render('Teams');
    }

    public function getNameTeams(Request $request)
    {
        $name = $request->input('name');
        $teams = $this->apiClientService->getTeamForName($name);
        return $teams;
    }

    public function getDadosTeam($id)
    {
        $team = $this->apiClientService->getTeamData($id);
        return $team;
    }
}
