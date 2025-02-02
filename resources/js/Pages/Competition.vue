<script setup>
import LayoutPadrao from '@/Layouts/LayoutPadrao.vue';
import { defineProps, ref } from 'vue';
import axios from 'axios';
import BoxJogosUltimos from '@/Components/Competition/BoxJogosUltimos.vue';

const props = defineProps({
    paises: Object,
})

const paisSelecionado = ref('');
const competicaoSelecionada = ref('');
const campeonatos = ref([]);
const loadingCompeticao = ref(false);

const selecionarPais = (pais) => {
  paisSelecionado.value = pais;
  campeonatos.value = [];
  competicaoSelecionada.value = '';
  carregarCampeonatos();
};

const selecionarCompeticao = (campeonato) => {
    competicaoSelecionada.value = campeonato;
    dadosCampeonato();
}

const carregarCampeonatos = () => {
    loadingCompeticao.value = true;
    axios.get(`/campeonatos/${paisSelecionado.value.id}/competitionsCountrie`)
        .then(response => {
            console.log(response.data);
            campeonatos.value = response.data.competitions;
        })
        .catch(error => {
            console.log(error);
        })
        .finally(() => {
            loadingCompeticao.value = false;
        });
}

const dadosCampeonato = () => {
    axios.get(`/campeonatos/${competicaoSelecionada.value.code}/dataCompetition`)
        .then(response => {
            console.log(response.data);
        })
        .catch(error => {
            console.log(error);
        })
}


</script>

<template>
    <LayoutPadrao>
        <div class="container bg-grey">
            <div class="card-body p-2 bg-light bg-gradient p-3 rounded text-dark">
                <h5 class="card-title">
                    Selecione um campeonato:
                </h5>
                <div class="row mt-3">
                    <div class="col-12 col-lg-3 mt-3 mt-lg-0">
                        <div class="dropdown">
                            <p>Selecione o país do campeonato:</p>
                            <button class="btn btn-outline-success dropdown-toggle w-75 w-lg-50" type="button" data-bs-toggle="dropdown">
                                {{ paisSelecionado ? paisSelecionado.name : "Selecione um país" }}
                            </button>
                            <ul class="dropdown-menu menu-select-pais">
                                <li v-for="pais in paises" :key="pais.id">
                                    <a class="dropdown-item" href="#" @click="selecionarPais(pais)">
                                        <img :src="pais.flag" class="pais-bandeira" alt="Bandeira" width="24" height="24" v-if="pais.flag"> {{ pais.name }}
                                    </a>
                                </li>
                            </ul>
                            <img :src="paisSelecionado.flag" class="pais-bandeira mx-3" alt="Bandeira" width="24" height="24" v-if="paisSelecionado.flag">
                        </div>
                    </div>
                    <div class="col-12 col-lg-5 mt-3 mt-lg-0">
                        <div class="dropdown">
                            <p>Selecione o campeonato:
                                <i class="text-danger font-monospace">{{ paisSelecionado.name ? '': "(Selecione um país)" }}</i>
                                <span v-if="loadingCompeticao" >
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Carregando campeonatos...
                                </span>
                            </p>
                            <button class="btn dropdown-toggle w-75" :class="`btn-outline-${loadingCompeticao || !paisSelecionado ? 'secondary' : 'success'}`" type="button" data-bs-toggle="dropdown" :disabled="!paisSelecionado && !loadingCompeticao">
                                {{ competicaoSelecionada ? competicaoSelecionada.name : "Selecione um campeonato" }}
                            </button>
                            <ul class="dropdown-menu menu-select-pais">
                                <li v-for="campeonato in campeonatos" :key="campeonato.id">
                                    <a class="dropdown-item" href="#" @click="selecionarCompeticao(campeonato)">
                                        <img :src="campeonato.emblem" class="pais-bandeira" alt="Bandeira" width="24" height="24"> {{ campeonato.name }}
                                    </a>
                                </li>
                            </ul>
                            <img :src="competicaoSelecionada.emblem" class="pais-bandeira mx-3" alt="Bandeira" width="24" height="24" v-if="competicaoSelecionada.emblem">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card my-3" style="width: 90vw">
                <div class="card-body card-dados-jogos">
                    <h5 class="card-title">
                        <i class="bi bi-trophy-fill ico-navbar card-img-top ml-3" style="color: orange"></i>
                        Proxima Rodada
                    </h5>
                    <div class="scroll-container">
                        <div class="d-flex flex-nowrap" v-if="paisSelecionado && competicaoSelecionada">
                            <BoxJogosUltimos v-for="n in 20" :key="n" />
                        </div>
                        <div class="text-center text-secondary font-monospace w-100" v-else>
                            <p>Selecione um país e uma competição para visualizar a próxima rodada.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body card-dados-jogos">
                    <h5 class="card-title">
                        <i class="bi bi-star ico-navbar card-img-top ml-3" style="color: green"></i>
                        Últimos Jogos
                    </h5>
                    <div class="scroll-container">
                        <div class="d-flex flex-nowrap" v-if="paisSelecionado && competicaoSelecionada">
                            <BoxJogosUltimos v-for="n in 20" :key="n" />
                        </div>
                        <div class="text-center text-secondary font-monospace w-100" v-else>
                            <p>Selecione um país e uma competição para visualizar os últimos jogos.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </LayoutPadrao>
</template>

<style scoped>
.scroll-container {
  overflow-x: auto;
  white-space: nowrap;
  display: flex;
  padding: 10px;
}

.menu-select-pais {
    max-height: 200px;
    overflow-y: auto;
    width: 100%;
}

.card-dados-jogos{
    min-height: 250px;
}
</style>
