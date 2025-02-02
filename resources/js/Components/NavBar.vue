<template>
    <header class="p-3 mb-3 border-bottom bg-navbar">
        <div class="container">
            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
                <a href="/" class="d-flex align-items-center mb-2 mb-lg-0 link-body-emphasis text-decoration-none">
                    <img src="{{ asset('images/logo_tudofut.svg') }}" alt="Bootstrap" width="auto" height="50">
                </a>

                <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0 tex">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="campeonatos.php">
                            <i class="bi bi-trophy-fill ico-navbar" style="color: orange"></i>
                            Campeonatos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="times.php">
                            <i class="bi bi-dribbble" style="color: black"></i>
                            Pesquise o seu time
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </header>
</template>
