<script setup>
import { useFormatarData } from "@/composables/useFormatarData";
import LayoutPadrao from "@/Layouts/LayoutPadrao.vue";
import axios from "axios";
import { computed, ref } from "vue";

const props = defineProps({
    teams: Object,
});


const nameTeam = ref("");
const teams = ref([]);
const dropdownAberto = ref(false);

const loadingSearchTeam = ref(false);
const dadosTeam = ref({});
const error = ref({
    status: false,
    mensagem: 'Um erro inesperado ocorreu, por favor tente novamente em 1 minuto.'
});

const filteredTeams = computed(() => {
    alertError(false);
    if (!nameTeam.value) {
        abrirDropdown(false);
        return [];
    }

    const termo = nameTeam.value.toLowerCase();

    const resultado = Object.entries(props.teams)
        .filter(([id, team]) => team.name.toLowerCase().includes(termo))
        .map(([id, team]) => ({
            id: team.id,
            name: team.name,
            crest: team.crest
        }));

    abrirDropdown(resultado.length > 0);
    return resultado;
});

const consultarDadosTime = async (id) => {
    loadingSearchTeam.value = true;
    try {
        const response = await axios.get(`/teams/search/id/${id}`);
        dadosTeam.value = response.data;
        console.log(dadosTeam.value.nextMatchs);
        loadingSearchTeam.value = false;
    } catch (error) {
        alertError(true);
        loadingSearchTeam.value = false;
        console.log(error);
    }
}

const selecionarTime = (team) => {
    consultarDadosTime(team.id);
    abrirDropdown(false);
}

const abrirDropdown = (isOpen) => {
    dropdownAberto.value = isOpen;
}

const alertError = (status) => {
    error.value.status = status;
}

</script>
<template>
    <LayoutPadrao>
        <div class="container">
            <div
                class="card-body p-2 bg-light bg-gradient rounded text-dark"
            >
                <h5 class="card-title">Digite o nome do time que deseja buscar:</h5>
                <div class="row mt-3">
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-success" id="basic-addon1">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" class="form-control" placeholder="Pesquisar por time" aria-label="Username" aria-describedby="basic-addon1" v-model="nameTeam">
                        <ul class="dropdown-menu mt-1" :class="{ 'show': dropdownAberto }">
                            <li v-if="filteredTeams.length === 0">
                                <a class="dropdown-item">Nenhum time encontrado</a>
                            </li>
                            <li v-for="team in filteredTeams" :key="team.id">
                                <a class="dropdown-item" href="#" @click="selecionarTime(team)">
                                    <img :src="team.crest" class="pais-bandeira mx-1" alt="Bandeira" width="24" height="24" />
                                    {{ team.name }}
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="alert alert-warning text-center fw-bold" role="alert" :hidden="!error.status">
               <p>{{error.mensagem}}</p>
            </div>
            <div class="alert alert-primary text-center fw-bold" role="alert" :hidden="!loadingSearchTeam">
               <p>Aguarde... Dados sendo carregados</p>
                <div class="spinner-border text-success" role="status">
                    <span class="visually-hidden">Carregando...</span>
                </div>
            </div>
            <div class="card-body p-2 bg-light bg-gradient rounded text-dark mt-3" v-if="dadosTeam.team?.name">
               <div class="row">
                    <div id="bandeira" class="col-12 col-lg-4 text-center">
                        <img
                            :src="dadosTeam.team.crest"
                            class="pais-bandeira mx-3 bg-white p-1"
                            alt="Bandeira"
                            width="150"
                            v-if="dadosTeam.team.crest"
                        />
                    </div>
                    <div class="col-12 col-lg-8 mt-lg-0 mt-2">
                        <h5 class="card-title mb-3 fw-bold">
                            {{ dadosTeam.team.name }}
                        </h5>
                        <p class="card-text" id="competitions-running">
                            <strong>Competições em andamento:</strong>
                            <ul>
                                <li v-for="competition in dadosTeam.team.runningCompetitions" :key="competition.id">
                                    <span id="bandeira"><img :src="competition.emblem" class="pais-bandeira mx-1" alt="Bandeira" width="24" height="24" v-if="competition.emblem"/></span>{{ competition.name }}
                                </li>
                            </ul>
                        </p>
                    </div>
               </div>
               <div class="card-body p-2 rounded text-dark mt-3">
                   <h5 class="card-title mb-3 fw-bold text-primary p-2 border rounded text-center">
                        PRÓXIMOS JOGOS
                   </h5>
                   <div class="card-exibe-resulta-competicao d-flex flex-wrap justify-content-center">
                        <table class="table table-striped w-50">
                            <thead>
                                <tr>
                                    <th scope="col" style="text-align: end;">Casa</th>
                                    <th scope="col" style="text-align: center;">X</th>
                                    <th scope="col">Fora</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template v-for="game in dadosTeam.nextMatchs" :key="game.id" v-if="Object.keys(dadosTeam.nextMatchs).length > 0">
                                    <tr>
                                        <td colspan="3" class="text-center fw-bold bg-light">
                                            {{ useFormatarData(game.utcDate) }}
                                        </td>
                                    </tr>
                                    <tr class="text-center">
                                        <td class="text-end">
                                            {{ game.homeTeam.name }}
                                            <img :src="game.homeTeam.crest" class="pais-bandeira mx-1" alt="Bandeira" width="24" height="24" v-if="game.homeTeam.crest"/>
                                        </td>
                                        <td class="text-center fw-bold">X</td>
                                        <td class="text-start">
                                            <img :src="game.awayTeam.crest" class="pais-bandeira mx-1" alt="Bandeira" width="24" height="24" v-if="game.awayTeam.crest"/>
                                            {{ game.awayTeam.name }}
                                        </td>
                                    </tr>
                                </template>
                                <template v-else>
                                    <tr>
                                        <td colspan="3" class="text-center fw-bold bg-light">
                                            Nenhum jogo encontrado
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                   </div>

               </div>
               <div class="card-body p-2 rounded text-dark mt-3">
                   <h5 class="card-title mb-3 fw-bold text-primary p-2 border rounded text-center">
                        JOGOS ANTERIORES
                   </h5>
                   <div class="card-exibe-resulta-competicao d-flex flex-wrap justify-content-center">
                        <table class="table table-striped w-50">
                            <thead>
                                <tr>
                                    <th scope="col" style="text-align: end;">Casa</th>
                                    <th scope="col" style="text-align: center;">X</th>
                                    <th scope="col">Fora</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template v-for="game in dadosTeam.previousMatchs" :key="game.id" v-if="Object.keys(dadosTeam.previousMatchs.length > 0)">
                                    <tr>
                                        <td colspan="3" class="text-center fw-bold bg-light">
                                            {{ useFormatarData(game.utcDate) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-end">{{ game.homeTeam.name }} <img :src="game.homeTeam.crest" class="pais-bandeira mx-1" alt="Bandeira" width="24" height="24" v-if="game.homeTeam.crest"/>
                                            <span class="fw-bold mx-2 h5" :class="{ 'text-success': game.score.fullTime.home > game.score.fullTime.away }">{{ game.score.fullTime.home }}</span>
                                            <i class="bi bi-trophy-fill ico-navbar card-img-top " style="color: orange" v-if="game.score.winner == 'HOME_TEAM'" ></i>
                                        </td>
                                        <td class="text-center fw-bold">X</td>
                                        <td>
                                            <i class="bi bi-trophy-fill ico-navbar card-img-top" style="color: orange" v-if="game.score.winner == 'AWAY_TEAM'" ></i>
                                            <span class="fw-bold mx-2 h5" :class="{ 'text-danger': game.score.fullTime.home < game.score.fullTime.away }">{{ game.score.fullTime.home }}</span>
                                            <img :src="game.awayTeam.crest" class="pais-bandeira mx-1" alt="Bandeira" width="24" height="24" v-if="game.awayTeam.crest"/> {{ game.awayTeam.name }}
                                        </td>
                                    </tr>
                                </template>
                                <template v-else>
                                    <tr>
                                        <td colspan="3" class="text-center fw-bold bg-light">
                                            Nenhum jogo encontrado
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                   </div>

               </div>
            </div>
        </div>
    </LayoutPadrao>
</template>

<style scoped>
.dropdown-menu {
    display: none;
    position: absolute;
    top: 100%; /* Faz o dropdown aparecer logo abaixo do input */
    left: 0;
    width: 100%;
    z-index: 1000;
}

.dropdown-menu.show {
    display: block;
}
</style>
