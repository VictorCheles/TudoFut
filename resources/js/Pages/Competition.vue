<script setup>
import LayoutPadrao from '@/Layouts/LayoutPadrao.vue';
import { defineProps, ref } from 'vue';
import axios from 'axios';

const props = defineProps({
    paises: Object,
})

const paisSelecionado = ref('');
const competitions = ref('');

const carregarCampeonatos = () => {
    axios.get(`/campeonatos/${paisSelecionado.value}/competitionsCountrie`)
        .then(response => {
            console.log(response.data);
            competitions.value = response.data.competitions;
        })
        .catch(error => {
            console.log(error);
        });
}


</script>

<template>
    <LayoutPadrao>
        <div class="container">
            <div class="card-body p-2">
                <h5 class="card-title">
                    Selecione um campeonato: <button id="btn-recarregar-pagina" class="btn btn-warning" style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;" hidden>Recarregar Página</button>
                </h5>
                <div class="row">
                    <div class="col-12 col-lg-4">
                        <img src="" id="bandeira" class="my-2" alt="" width="auto" height="20">
                        <select class="form-select" id="paises" aria-label="Default select example" v-model="paisSelecionado" @change="carregarCampeonatos" :disabled="paises.length == 0">
                            <option value="">Selecione um país</option>
                            <option v-for="pais in paises" :key="pais.id" :value="pais.id">{{ pais.name }}</option>
                        </select>
                    </div>
                    <div class="col-12 col-lg-4">
                        <img src="" id="logo-compenato" class="my-2" alt="" width="auto" height="20">
                        <select class="form-select" id="campeonato" aria-label="Default select example" :disabled="competitions.length == 0">
                            <option value="">Selecione um campeonato</option>
                            <option v-for="competition in competitions" :key="competition.id" :value="competition.id">{{ competition.name }}</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="card my-3" style="width: 90vw">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-trophy-fill ico-navbar card-img-top ml-3" style="color: orange"></i>
                        Proxima Rodada
                    </h5>
                    <div>

                    </div>

                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-star ico-navbar card-img-top ml-3" style="color: green"></i>
                        Últimos Jogos
                    </h5>
                    <div class="scroll-container">
                        <div class="d-flex flex-nowrap">
                            <div v-for="n in 20" :key="n" class="card-exibe-resulta-competicao m-2"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </LayoutPadrao>
</template>

<style scoped>
.scroll-container {
  overflow-x: auto;  /* Permite rolagem horizontal */
  white-space: nowrap; /* Impede quebra de linha */
  display: flex; /* Mantém os elementos na mesma linha */
  padding: 10px; /* Adiciona espaço ao redor */
}

.card-exibe-resulta-competicao {
  width: 250px;
  height: 150px;
  background: lightgray;
  border-radius: 10px;
  flex-shrink: 0; /* Evita que os cards diminuam para caberem na tela */
}
</style>
