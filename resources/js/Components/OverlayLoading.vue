<template>
    <div v-if="show" class="overlay">
        <div class="overlay-content">
            <div class="spinner-border text-success" role="status">
                <span class="visually-hidden">Carregando...</span>
            </div>

            <p class="mt-3 text-white">
                <img
                    src="images/logo_tudofut.svg"
                    alt="Bootstrap"
                    width="auto"
                    height="50"
                />
            </p>
            <p>Carregando...</p>
        </div>
    </div>
</template>

<script setup>
defineProps({
    show: Boolean,
});
</script>

<style scoped>
.overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.7);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
}

.overlay-content {
    background: rgba(255, 255, 255, 0.8);
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(255, 255, 255, 0.2);
    text-align: center;
}
</style>
