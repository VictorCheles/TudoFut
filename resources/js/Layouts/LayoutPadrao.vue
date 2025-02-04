<script setup>
import OverlayLoading from "@/Components/OverlayLoading.vue";
import { Link, router, usePage } from "@inertiajs/vue3";
import { onMounted, ref } from "vue";

const loading = ref(false);

onMounted(() => {
    router.on("start", () => {
        loading.value = true;
    });
    router.on("finish", () => {
        loading.value = false;
    });
});

const page = usePage();
</script>
<template>
    <header class="p-3 mb-3 border-bottom bg-navbar">
        <div class="container">
            <div
                class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start"
            >
                <a
                    href="/"
                    class="d-flex align-items-center mb-2 mb-lg-0 link-body-emphasis text-decoration-none"
                >
                    <img
                        src="images/logo_tudofut.svg"
                        alt="Bootstrap"
                        width="auto"
                        height="50"
                    />
                </a>

                <ul
                    class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0 tex"
                >
                    <li class="nav-item">
                        <Link
                            class="nav-link text-white"
                            :class="page.url === '/' ? 'border-bottom' : ''"
                            href="/"
                        >
                            <i
                                class="bi bi-trophy-fill ico-navbar"
                                style="color: orange"
                            ></i>
                            Campeonatos
                        </Link>
                    </li>
                    <li class="nav-item">
                        <Link
                            class="nav-link text-white"
                            :class="
                                page.url === '/times' ? 'border-bottom' : ''
                            "
                            href="/teams"
                        >
                            <i class="bi bi-dribbble" style="color: black"></i>
                            Pesquise o seu time
                        </Link>
                    </li>
                </ul>
            </div>
        </div>
    </header>
    <OverlayLoading :show="loading" />
    <main class="flex-grow bg-cinza_claro">
        <slot />
    </main>
</template>

<style>
.bg-navbar {
    background: linear-gradient(to right, #87dc6b, #045701);
    border-radius: 0 0 80px 0;
}
.nav-link:hover {
    color: #0a6e1e !important;
}
</style>
