<?php
    // Obtiene únicamente el nombre del archivo PHP que se está ejecutando
    // Se utiliza para identificar qué opción de la barra de navegación debe aparecer activa.
    $paginaActual = basename($_SERVER['PHP_SELF']);
?>

<!-- Font Awesome para los iconos -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<!-- Header -->
<header class="panel-header">

    <div></div>

    <div class="panel-logo">
        <img src="../Img/inmofix-02.png" alt="InmoFix">
    </div>

 <div class="panel-perfil">

    <button class="perfil-btn" onclick="toggleMenu()">
        <i class="fa-solid fa-user"></i>
    </button>

    <div id="menuPerfil" class="menu-perfil">

        <a href="#" onclick="abrirModal(); return false;">
            <i class="fa-solid fa-user-plus"></i>
            Alta de Inquilino
        </a>

        <a href="logout.php">
            <i class="fa-solid fa-right-from-bracket"></i>
            Cerrar sesión
        </a>

    </div>

</div>

</header>
<!-- MODAL ALTA DE INQUILINO -->
<div class="modal-fondo" id="modalInquilino">

    <div class="modal-inmueble">

        <!-- ENCABEZADO -->
        <div class="modal-header">

            <h2>
                <span class="modal-icono-titulo">
                    <i class="fa-solid fa-user-plus"></i>
                </span>
                Alta de Inquilino
            </h2>

            <button
                type="button"
                class="modal-cerrar"
                id="cerrar-modal-alta"
            >
                <i class="fa-solid fa-xmark"></i>
            </button>

        </div>

        <!-- FORMULARIO -->
        <form
            action="guardarInquilino.php"
            method="POST"
            class="formulario-inquilino"
        >

            <div class="campos-inquilino">

                <div class="campo-inquilino">
                    <label for="alta-nombre">Nombre</label>
                    <input
                        type="text"
                        id="alta-nombre"
                        name="nombre"
                        placeholder="Nombre"
                        required
                    >
                </div>

                <div class="campo-inquilino">
                    <label for="alta-apellido">Apellido</label>
                    <input
                        type="text"
                        id="alta-apellido"
                        name="apellido"
                        placeholder="Apellido"
                        required
                    >
                </div>

                <div class="campo-inquilino">
                    <label for="alta-dni">DNI</label>
                    <input
                        type="text"
                        id="alta-dni"
                        name="dni"
                        placeholder="DNI"
                        required
                    >
                </div>

                <div class="campo-inquilino">
                    <label for="alta-email">Email</label>
                    <input
                        type="email"
                        id="alta-email"
                        name="email"
                        placeholder="Correo electrónico"
                        required
                    >
                </div>

                <div class="campo-inquilino campo-inquilino-completo">
                    <label for="alta-password">Contraseña</label>
                    <input
                        type="password"
                        id="alta-password"
                        name="password"
                        placeholder="Contraseña"
                        required
                    >
                </div>

            </div>

            <!-- BOTÓN GUARDAR -->
            <div class="modal-footer">

                <button type="submit" class="btn modal-guardar">
                    <i class="fa-solid fa-check"></i>
                    Crear inquilino
                </button>

            </div>

        </form>

    </div>

</div>

<script>

// ABRIR Y CERRAR MENÚ DE PERFIL

function toggleMenu() {

    let menu = document.getElementById("menuPerfil");

    if (menu.style.display === "block") {
        menu.style.display = "none";
    } else {
        menu.style.display = "block";
    }

}


// ABRIR MODAL

function abrirModal() {

    document.getElementById("modalInquilino")
        .classList.add("modal-visible");

    document.getElementById("menuPerfil").style.display = "none";

}


// CERRAR MODAL CON LA X

document.getElementById("cerrar-modal-alta")
    .addEventListener("click", function () {

        document.getElementById("modalInquilino")
            .classList.remove("modal-visible");

    });


// CERRAR MODAL HACIENDO CLIC FUERA

document.getElementById("modalInquilino")
    .addEventListener("click", function(e) {

        if (e.target === this) {
            this.classList.remove("modal-visible");
        }

    });

</script>
<!-- Barra de navegación
     La clase panel-activo se agrega automáticamente
     a la opción correspondiente a la página actual -->
<nav class="panel-navbar">

    <!-- Compara la página actual con panelAdmin.php
    Si coinciden, se agrega la clase panel-activo,
    Si no coinciden, se deja vacío -->
    <a class="<?= $paginaActual == 'panelAdmin.php' ? 'panel-activo' : '' ?>" href="panelAdmin.php"> 
        <i class="fa-solid fa-house"></i>
        Inicio
    </a>

    <a class="<?= $paginaActual == 'inquilinos.php' ? 'panel-activo' : '' ?>" href="inquilinos.php">
        <i class="fa-solid fa-users"></i>
        Inquilinos
    </a>

    <a class="<?= $paginaActual == 'inmuebles.php' ? 'panel-activo' : '' ?>" href="inmuebles.php">
        <i class="fa-solid fa-folder"></i>
        Inmuebles
    </a>

    <a class="<?= $paginaActual == 'contratos.php' ? 'panel-activo' : '' ?>" href="contratos.php">
        <i class="fa-solid fa-file-contract"></i>
        Contratos
    </a>

    <a class="<?= $paginaActual == 'reclamos.php' ? 'panel-activo' : '' ?>" href="reclamos.php">
        <i class="fa-solid fa-comments"></i>
        Reclamos
    </a>

    <a class="<?= $paginaActual == 'pagos.php' ? 'panel-activo' : '' ?>" href="pagos.php">
        <i class="fa-solid fa-dollar-sign"></i>
        Pagos
    </a>

</nav>