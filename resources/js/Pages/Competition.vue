<script setup>
import BoxJogosUltimos from "@/Components/Competition/BoxJogosUltimos.vue";
import { useFormatarData } from "@/composables/useFormatarData";
import LayoutPadrao from "@/Layouts/LayoutPadrao.vue";
import axios from "axios";
import { defineProps, ref } from "vue";

const props = defineProps({
    paises: Object,
});

const paisSelecionado = ref("");
const competicaoSelecionada = ref("");

const campeonatos = ref([]);
const dadosRodadasCampeonato = ref({
    currentMatchday: {},
    nextMatchday: {},
});
const dadosCampeonatoRecebido = ref(false);

const loadingCompeticao = ref(false);
const loadingRodadasCampeonato = ref(false);

const erroConsultaApi = ref(false);

const selecionarPais = (pais) => {
    paisSelecionado.value = pais;
    campeonatos.value = [];
    competicaoSelecionada.value = "";
    dadosRodadasCampeonato.value.currentMatchday = {};
    dadosRodadasCampeonato.value.nextMatchday = {};
    dadosCampeonatoRecebido.value = false;
    carregarCampeonatos();
};

const selecionarCompeticao = (campeonato) => {
    competicaoSelecionada.value = campeonato;
    dadosCampeonatoRecebido.value = false;
    dadosCampeonato();
};
const errorCarregarCampeonato = () => {
    campeonatos.value = [];
    competicaoSelecionada.value = "";
    dadosCampeonatoRecebido.value = false;
    erroConsultaApi.value = true;
};

const recarregarPagina = () => {
    window.location.reload();
};

const statusJogo = {
    SCHEDULED: "Agendado",
    FINISHED: "Finalizado",
    CANCELED: "Cancelado",
    POSTPONED: "Adiado",
    IN_PLAY: "Em andamento",
    PAUSED: "Pausado",
    TIMED: "Em breve",
    SUSPENDED: "Suspenso",
};

const carregarCampeonatos = () => {
    loadingCompeticao.value = true;
    axios
        .get(`/campeonatos/${paisSelecionado.value.id}/competitionsCountrie`)
        .then((response) => {
            campeonatos.value = response.data.competitions;
        })
        .catch((error) => {
            console.log(error);
            errorCarregarCampeonato();
        })
        .finally(() => {
            loadingCompeticao.value = false;
        });
};

const dadosCampeonato = () => {
    loadingRodadasCampeonato.value = true;
    axios
        .get(`/campeonatos/${competicaoSelecionada.value.code}/dataCompetition`)
        .then((response) => {
            dadosRodadasCampeonato.value = response.data;
        })
        .catch((error) => {
            console.log(error);
            errorCarregarCampeonato();
        })
        .finally(() => {
            dadosCampeonatoRecebido.value = true;
            loadingRodadasCampeonato.value = false;
        });
};
</script>

<template>
    <LayoutPadrao>
        <div class="container">
            <div
                class="card-body p-2 bg-light bg-gradient p-3 rounded text-dark"
            >
                <h5 class="card-title">Selecione um campeonato:</h5>
                <div class="row mt-3">
                    <div class="col-12 col-lg-3 mt-3 mt-lg-0">
                        <div class="dropdown">
                            <p>Selecione o país do campeonato:</p>
                            <button
                                class="btn btn-outline-success dropdown-toggle w-75 w-lg-50"
                                type="button"
                                data-bs-toggle="dropdown"
                                :disabled="Object.keys(paises).length === 0"
                            >
                                {{
                                    paisSelecionado
                                        ? paisSelecionado.name
                                        : "Selecione um país"
                                }}
                            </button>
                            <ul
                                class="dropdown-menu menu-select-pais w-75 w-lg-50"
                            >
                                <li v-for="pais in paises" :key="pais.id">
                                    <a
                                        class="dropdown-item"
                                        href="#"
                                        @click="selecionarPais(pais)"
                                    >
                                        <img
                                            :src="pais.flag"
                                            class="pais-bandeira"
                                            alt="Bandeira"
                                            width="24"
                                            height="24"
                                            v-if="pais.flag"
                                        />
                                        {{ pais.name }}
                                    </a>
                                </li>
                            </ul>
                            <img
                                :src="paisSelecionado.flag"
                                class="pais-bandeira mx-3"
                                alt="Bandeira"
                                width="24"
                                height="24"
                                v-if="paisSelecionado.flag"
                            />
                        </div>
                    </div>
                    <div class="col-12 col-lg-4 mt-3 mt-lg-0">
                        <div class="dropdown">
                            <p>
                                Selecione o campeonato:
                                <i class="text-danger font-monospace">{{
                                    paisSelecionado.name
                                        ? ""
                                        : "(Selecione um país)"
                                }}</i>
                                <span v-if="loadingCompeticao">
                                    <span
                                        class="spinner-border spinner-border-sm"
                                        role="status"
                                        aria-hidden="true"
                                    ></span>
                                    Carregando campeonatos...
                                </span>
                            </p>
                            <button
                                class="btn dropdown-toggle w-75"
                                :class="`btn-outline-${
                                    loadingCompeticao || !paisSelecionado
                                        ? 'secondary'
                                        : 'success'
                                }`"
                                type="button"
                                data-bs-toggle="dropdown"
                                :disabled="
                                    !paisSelecionado && !loadingCompeticao
                                "
                            >
                                {{
                                    competicaoSelecionada
                                        ? competicaoSelecionada.name
                                        : "Selecione um campeonato"
                                }}
                            </button>
                            <ul class="dropdown-menu menu-select-pais w-75">
                                <li
                                    v-for="campeonato in campeonatos"
                                    :key="campeonato.id"
                                >
                                    <a
                                        class="dropdown-item"
                                        href="#"
                                        @click="
                                            selecionarCompeticao(campeonato)
                                        "
                                    >
                                        <img
                                            :src="campeonato.emblem"
                                            class="pais-bandeira"
                                            alt="Bandeira"
                                            width="24"
                                            height="24"
                                        />
                                        {{ campeonato.name }}
                                    </a>
                                </li>
                            </ul>
                            <img
                                :src="competicaoSelecionada.emblem"
                                class="pais-bandeira mx-3"
                                alt="Bandeira"
                                width="24"
                                height="24"
                                v-if="competicaoSelecionada.emblem"
                            />
                        </div>
                    </div>
                    <div
                        class="col-12 col-lg-4 mt-3 mt-lg-0 border rounded p-2 bg-success bg-gradient text-white"
                        v-if="competicaoSelecionada"
                    >
                        <div class="row container">
                            <div class="col-8 text-start">
                                <p>
                                    <span class="fw-bold">País:</span>
                                    {{ paisSelecionado.name }}
                                </p>
                                <p>
                                    <span class="fw-bold">Campeonato:</span>
                                    {{ competicaoSelecionada.name }}
                                </p>
                                <p>
                                    <span class="fw-bold">Temporada:</span>
                                    {{
                                        competicaoSelecionada.currentSeason
                                            .currentMatchday ?? "N/A"
                                    }}
                                </p>
                                <p>
                                    <span class="fw-bold">Início do Campeonato:</span>
                                    {{
                                        useFormatarData(
                                            competicaoSelecionada.currentSeason
                                                .startDate
                                        )
                                    }}
                                </p>
                                <p>
                                    <span class="fw-bold">Fim do Campeonato:</span>
                                    {{
                                        useFormatarData(
                                            competicaoSelecionada.currentSeason.endDate
                                        )
                                    }}
                                </p>
                            </div>
                            <div class="col-4">
                                <img
                                    :src="paisSelecionado.flag"
                                    class="pais-bandeira m-3 bg-white p-1"
                                    alt="Bandeira"
                                    width="80"
                                    v-if="paisSelecionado.flag"
                                />
                                <img
                                    :src="competicaoSelecionada.emblem"
                                    class="pais-bandeira mx-3 bg-white p-1"
                                    alt="Bandeira"
                                    width="80"
                                    v-if="competicaoSelecionada.emblem"
                                />
                            </div>
                        </div>
                    </div>
                    <div
                        class="alert alert-danger my-3 text-center"
                        role="alert"
                        v-if="
                            erroConsultaApi || Object.keys(paises).length === 0
                        "
                    >
                        <p>Ocorreu um erro na consulta dos dados.</p>
                        <button
                            id="recarregar-pagina"
                            class="btn btn-danger btn-sm"
                            @click="recarregarPagina"
                        >
                            Recarregar Página
                        </button>
                    </div>
                </div>
            </div>

            <div class="card my-3">
                <div class="card-body card-dados-jogos">
                    <h5 class="card-title">
                        <i
                            class="bi bi-trophy-fill ico-navbar card-img-top ml-3"
                            style="color: orange"
                        ></i>
                        Próximos Jogos
                    </h5>
                    <div class="scroll-container">
                        <div
                            v-if="
                                Object.keys(
                                    dadosRodadasCampeonato.currentMatchday
                                ).length > 0 ||
                                Object.keys(dadosRodadasCampeonato.nextMatchday)
                                    .length > 0
                            "
                            v-for="rodada in dadosRodadasCampeonato"
                            class="d-inline-flex flex-nowrap"
                        >
                            <div
                                v-for="(jogosAtuais, status) in rodada"
                                :key="jogosAtuais"
                                class="d-flex flex-nowrap"
                            >
                                <BoxJogosUltimos
                                    v-for="jogo in jogosAtuais"
                                    :key="jogo.id"
                                    v-if="status != 'FINISHED'"
                                    :jogo="jogo"
                                    :status="statusJogo[status]"
                                />
                            </div>
                        </div>
                        <div
                            class="text-center text-secondary font-monospace w-100"
                            v-else
                        >
                            <p
                                v-if="
                                    !loadingRodadasCampeonato &&
                                    !dadosCampeonatoRecebido
                                "
                            >
                                Selecione um país e uma competição para
                                visualizar a próxima rodada.
                            </p>
                            <p
                                v-else-if="
                                    !loadingRodadasCampeonato &&
                                    dadosCampeonatoRecebido
                                "
                            >
                                Nenhum jogo encontrado para o campeonato
                                selecionado.
                            </p>
                            <p v-else>
                                Carregando dados...
                                <span
                                    class="spinner-border spinner-border-sm"
                                    role="status"
                                    aria-hidden="true"
                                ></span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body card-dados-jogos">
                    <h5 class="card-title">
                        <i
                            class="bi bi-star ico-navbar card-img-top ml-3"
                            style="color: green"
                        ></i>
                        Últimos Jogos
                    </h5>
                    <div class="scroll-container">
                        <div
                            v-if="
                                Object.keys(
                                    dadosRodadasCampeonato.currentMatchday
                                ).length > 0
                            "
                            v-for="rodada in dadosRodadasCampeonato"
                            class="d-inline-flex flex-nowrap"
                        >
                            <div
                                v-for="(jogosAtuais, status) in rodada"
                                :key="jogosAtuais"
                                class="d-flex flex-nowrap"
                            >
                                <BoxJogosUltimos
                                    v-for="jogo in jogosAtuais"
                                    :key="jogo.id"
                                    v-if="status === 'FINISHED'"
                                    :jogo="jogo"
                                    :status="statusJogo[status]"
                                />
                            </div>
                        </div>
                        <div
                            v-else
                            class="text-center text-secondary font-monospace w-100"
                        >
                            <p
                                v-if="
                                    !dadosCampeonatoRecebido &&
                                    !loadingRodadasCampeonato
                                "
                            >
                                Selecione um país e uma competição para
                                visualizar a próxima rodada.
                            </p>
                            <p
                                v-else-if="
                                    !loadingRodadasCampeonato &&
                                    dadosCampeonatoRecebido
                                "
                            >
                                Nenhum jogo encontrado para o campeonato
                                selecionado.
                            </p>

                            <p v-else>
                                Carregando dados...
                                <span
                                    class="spinner-border spinner-border-sm"
                                    role="status"
                                    aria-hidden="true"
                                ></span>
                            </p>
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
}

.card-dados-jogos {
    min-height: 250px;
}
</style>
