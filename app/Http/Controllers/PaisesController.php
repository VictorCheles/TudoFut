<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ApiClienteService;

class PaisesController extends Controller
{
    protected $apiService;

    public function __construct(ApiClienteService $apiService)
    {
        $this->apiService = $apiService;
    }

    public function index()
    {
        try {
            $paises = $this->apiService->getCountries();

            if(!$paises) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Erro ao buscar paises'
                ], 500);
            }

            $paises = $this->apiService->filterCountries($paises['areas']);

            $paises = array_map(function($item) {
                return [
                    'id' => $item['id'],
                    'nome' => $item['name'],
                    'flag' => $item['flag']
                ];
            }, $paises);

            return response()->json([
                'status' => 'success',
                'data' => $paises
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erro ao buscar paises'
            ], 500);
        }
    }
}
