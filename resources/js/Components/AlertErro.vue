<script setup>
import { defineProps } from "vue";

defineProps({
    erroConsultaApi: Boolean,
    mensagem: String
});

const recarregarPagina = () => {
    window.location.reload();
}
</script>


<template>
    <div
        class="alert alert-danger my-3 text-center"
        role="alert"
        v-if="
            erroConsultaApi
        "
    >
        <p>{{mensagem}}</p>
        <button
            id="recarregar-pagina"
            class="btn btn-danger btn-sm"
            @click="recarregarPagina"
        >
            Recarregar Página
        </button>
    </div>
</template>
