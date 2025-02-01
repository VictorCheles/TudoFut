@extends('layouts.app')

@section('title', 'Página Inicial')

@section('content')
    <div class="card mb-4" style="width: 90vw">
        <div class="card-body">
            <h5 class="card-title">
                Selecione um campeonato: <button id="btn-recarregar-pagina" class="btn btn-warning" style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;" hidden>Recarregar Página</button>
            </h5>
            <div class="row">
                <div class="col-12 col-lg-4">
                    <img src="" id="bandeira" class="my-2" alt="" width="auto" height="20">
                    <select class="form-select" id="paises" aria-label="Default select example" disabled>
                        <option selected>Carregando países...</option>
                    </select>
                </div>
                <div class="col-12 col-lg-4">
                    <img src="" id="logo-compenato" class="my-2" alt="" width="auto" height="20">
                    <select class="form-select" id="campeonato" aria-label="Default select example" disabled>
                        <option selected>Aguardando seleção do país</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-3" style="width: 90vw">
        <div class="card-body">
            <h5 class="card-title">
                <i class="bi bi-trophy-fill ico-navbar card-img-top ml-3" style="color: orange"></i>
                Proxima Rodada
            </h5>
            <div>

            </div>

        </div>
    </div>
    <div class="card" style="width: 90vw">
        <div class="card-body">
            <h5 class="card-title">
                <i class="bi bi-star ico-navbar card-img-top ml-3" style="color: green"></i>
                Últimos Jogos
            </h5>
            <div class="container">
                <div class="text-center card-exibe-resulta-competicao d-flex flex-wrap"></div>
            </div>
        </div>
    </div>
@endsection
<script src="{{ asset('js/campeonatos.js') }}"></script>
