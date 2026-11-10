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

        <a href="#" onclick="abrirModal()">
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
<!-- Form oculto para alta de inquilino -->
<div id="modalInquilino" class="modal">

    <div class="modal-contenido">

        <h2>Alta de Inquilino</h2>

        <form action="guardarInquilino.php" method="POST">

            <input
                type="text"
                name="nombre"
                placeholder="Nombre"
                required>

            <input
                type="text"
                name="apellido"
                placeholder="Apellido"
                required>

            <input
                type="text"
                name="dni"
                placeholder="DNI"
                required>

            <input
                type="email"
                name="email"
                placeholder="Email"
                required>

            <input
                type="password"
                name="password"
                placeholder="Contraseña"
                required>

            <button type="submit">
                Crear inquilino
            </button>

        </form>

    </div>

</div>
<script>
function toggleMenu() {
    let menu = document.getElementById("menuPerfil");

    if(menu.style.display == "block"){
        menu.style.display = "none";
    } else {
        menu.style.display = "block";
    }
}

function abrirModal() {
    document.getElementById("modalInquilino").style.display = "block";
}
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