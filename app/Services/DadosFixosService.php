<?php

namespace App\Services;

use App\Models\Time;
use App\Models\Pais;
use App\Models\Competicao;

class DadosFixosService
{
    /**
     * Retorna todos os países.
     */
    public function getPaises()
    {
        return Pais::orderBy('name')->get();
    }

    /**
     * Retorna todas as competições.
     */
    public function getCompeticoes()
    {
        return Competicao::orderBy('name')->get();
    }

    /**
     * Retorna todos os times.
     */
    public function getTimes()
    {
        return Time::orderBy('name')->get();
    }
}
