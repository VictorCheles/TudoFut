<script setup>
import LayoutPadrao from "@/Layouts/LayoutPadrao.vue";
import axios from "axios";
import { ref } from "vue";


const nameTeam = ref("");
const teams = ref([]);
const dropdownAberto = ref(false);

const consultarTimesPorNome = async () => {
    try {
        abrirDropdown(true);
        const response = await axios.post("/teams/search/name", {
            name: nameTeam.value
        });
        teams.value = response.data;
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
                            <li v-for="team in teams" :key="team.id">
                                <a class="dropdown-item" href="#" @click="selecionarTime(team)">
                                    <img :src="team.crest" class="pais-bandeira" alt="Bandeira" width="24" height="24"
                                        v-if="team.crest" />
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
