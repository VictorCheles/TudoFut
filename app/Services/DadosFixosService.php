<?php

namespace App\Services;

use App\Models\Time;
use App\Models\Pais;
use App\Models\Competicao;

class DadosFixosService
{

    /**
     * Retorna todos os países.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\App\Models\Pais[]
     */
    public function getPaises()
    {
        return Pais::orderBy('name')->get();
    }


    /**
     * Retorna todas as competi es.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\App\Models\Competicao[]
     */
    public function getCompeticoes()
    {
        return Competicao::orderBy('name')->get();
    }


    /**
     * Retorna todos os times.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\App\Models\Time[]
     */
    public function getTimes()
    {
        return Time::orderBy('name')->get();
    }
}
