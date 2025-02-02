<script setup>
import { useFormatarData } from "@/composables/useFormatarData";
import { defineProps } from "vue";

const props = defineProps({
    jogo: Object,
    status: String,
});
</script>

<template>
    <div class="card card-exibe-resulta-competicao mx-3">
        <div class="card-body text-center">
            <p
                class="data-hora-jogo text-dark blockquote h6 border rounded bg-light bg-gradient p-1"
            >
                {{ useFormatarData(props.jogo.utcDate) }} -
                <span
                    class="h6"
                    :class="`text-${
                        props.status == 'Finalizado' ? 'danger' : 'success'
                    }`"
                >
                    {{ props.status }}
                </span>
            </p>
            <div class="h6">
                <p class="my-0">
                    <img
                        :src="props.jogo.homeTeam.crest"
                        class="mr-1"
                        width="24"
                    />
                    {{ props.jogo.homeTeam.name }}
                    <span
                        v-if="props.jogo.score.fullTime.home != null"
                        class="border rounded bg-gradient p-1 text-white"
                        :class="{
                            'bg-success':
                                props.jogo.score.fullTime.home >
                                props.jogo.score.fullTime.away,
                            'bg-danger':
                                props.jogo.score.fullTime.home <
                                props.jogo.score.fullTime.away,
                            'bg-secondary':
                                props.jogo.score.fullTime.home ==
                                props.jogo.score.fullTime.away,
                        }"
                    >
                        {{ props.jogo.score.fullTime.home.toString() }}
                        <i
                            class="bi bi-trophy-fill ico-navbar card-img-top px-1"
                            style="color: orange"
                            v-if="
                                props.status == 'Finalizado' &&
                                props.jogo.score.fullTime.away <
                                    props.jogo.score.fullTime.home
                            "
                        ></i>
                    </span>
                </p>
                <p class="my-0">X</p>
                <p class="my-0">
                    <img
                        :src="props.jogo.awayTeam.crest"
                        class="mr-1"
                        width="24"
                    />
                    {{ props.jogo.awayTeam.name }}
                    <span
                        v-if="props.jogo.score.fullTime.away != null"
                        class="border rounded bg-gradient p-1 text-white"
                        :class="{
                            'bg-success':
                                props.jogo.score.fullTime.away >
                                props.jogo.score.fullTime.home,
                            'bg-danger':
                                props.jogo.score.fullTime.away <
                                props.jogo.score.fullTime.home,
                            'bg-secondary':
                                props.jogo.score.fullTime.away ==
                                props.jogo.score.fullTime.home,
                        }"
                    >
                        {{ props.jogo.score.fullTime.away.toString() }}
                        <i
                            class="bi bi-trophy-fill ico-navbar card-img-top px-1"
                            style="color: orange"
                            v-if="
                                props.status == 'Finalizado' &&
                                props.jogo.score.fullTime.home <
                                    props.jogo.score.fullTime.away
                            "
                        ></i>
                    </span>
                </p>
            </div>
        </div>
    </div>
</template>

<style scoped>
.card-exibe-resulta-competicao {
    width: 400px;
    height: 170px;
    background: rgb(255, 255, 255);
    border-radius: 10px;
    flex-shrink: 0;
    box-shadow: 0px 4px 4px 4px rgba(0, 0, 0, 0.11);
}
</style>
