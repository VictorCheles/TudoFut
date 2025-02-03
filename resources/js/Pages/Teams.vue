<script setup>
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

const filteredTeams = computed(() => {
    if (!nameTeam.value) {
        abrirDropdown(false);
        return [];
    }

    const termo = nameTeam.value.toLowerCase();

    const resultado = Object.entries(props.teams)
        .filter(([id, team]) => team.name.toLowerCase().includes(termo))
        .map(([id, team]) => ({
            id,
            name: team.name,
            crest: team.crest
        }));

    abrirDropdown(resultado.length > 0);
    return resultado;
});

const consultarTimesPorNome = async () => {
    loadingSearchTeam.value = true;
    abrirDropdown(true);
    try {
        const response = await axios.post("/teams/search/name", {
            name: nameTeam.value
        });
        teams.value = response.data;
        loadingSearchTeam.value = false;

        console.log(response);
    } catch (error) {
        console.log(error);
    }
}

const consultarDadosTime = async (id) => {
    try {
        const response = await axios.get(`/teams/search/id/${id}`);
        console.log(response);
    } catch (error) {
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

</script>
<template>
    <LayoutPadrao>
        <div class="container">
            <div
                class="card-body p-2 bg-light bg-gradient p-3 rounded text-dark"
            >
                <h5 class="card-title">Digite o nome do time que deseja buscar:</h5>
                <div class="row mt-3">
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-success" id="basic-addon1">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" class="form-control" placeholder="Pesquisar por time" aria-label="Username" aria-describedby="basic-addon1" v-model="nameTeam">
                        <button class="btn btn-outline-primary" type="button" id="button-addon1" @click="consultarTimesPorNome">
                            Pesquisar
                        </button>
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
            <div>

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
