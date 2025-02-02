<template>
    <header class="p-3 mb-3 border-bottom bg-navbar">
        <div class="container">
        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
            <a href="/" class="d-flex align-items-center mb-2 mb-lg-0 link-body-emphasis text-decoration-none">
                <img src="images/logo_tudofut.svg" alt="Bootstrap" width="auto" height="50">
            </a>

            <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0 tex">
                <li class="nav-item">
                    <a class="nav-link text-white" href="">
                        <i class="bi bi-trophy-fill ico-navbar" style="color: orange"></i>
                        Campeonatos
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="">
                        <i class="bi bi-dribbble" style="color: black"></i>
                        Pesquise o seu time
                    </a>
                </li>
            </ul>
        </div>
        </div>
    </header>
    <main class="flex-grow bg-cinza_claro">
        <slot />
    </main>
</template>

<style>
.bg-navbar {
    background-color: #95b58a !important;
}
.nav-link:hover {
    color: #0a6e1e !important;
}

.card-exibe-resulta-competicao {
    background-color: #95b58a !important;
    color: white !important;
    width: 300px;
    height: 150px;
}
</style>
