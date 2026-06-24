<?php
session_start(); //INICIA UNA SESIÓN

// DESACTIVADO PARA QUE PUEDAS VERLO SIN LOGIN
// if (!isset($_SESSION["admin"])) {
//     header("Location: login.php");
//     exit();
// }

// Datos de ejemplo (luego vienen desde BD)
$nombre = "Administrador";

$contratos_por_vencer = 3;
$reclamos_urgentes = 2;
$pagos_vencidos = 4;

$alertas = [

    [
        "titulo" => "Vencimiento de contrato",
        "detalle" => "Hay $contratos_por_vencer contratos próximos a vencer."
    ],

    [
        "titulo" => "Reclamos urgentes",
        "detalle" => "Hay $reclamos_urgentes reclamos sin resolver."
    ],

    [
        "titulo" => "Pagos vencidos",
        "detalle" => "Hay $pagos_vencidos pagos pendientes."
    ]

];
?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <title>Panel del Administrador</title>

    <!-- la letra -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- iconos -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <!-- CSS -->
    <link rel="stylesheet" href="../Css/estilo.css?v=1">

</head>

<body>

    <!-- HEADER -->

    <header class="panel-header">

        <div></div>

        <div class="panel-logo">
            <img src="../img/inmofix-02.png" alt="Logo INMOFIX">
        </div>

        <div class="panel-perfil">
            <i class="fa-solid fa-user"></i>
        </div>

    </header>

    <!-- NAVBAR -->

    <nav class="panel-navbar">

        <a class="panel-activo" href="panelAdmin.php">
            <i class="fa-solid fa-house"></i>
            Inicio
        </a>

        <a href="inquilinos.php">
            <i class="fa-solid fa-users"></i>
            Inquilinos
        </a>

        <a href="inmuebles.php">
            <i class="fa-solid fa-folder"></i>
            Inmuebles
        </a>

        <a href="contratos.php">
            <i class="fa-solid fa-file-contract"></i>
            Contratos
        </a>

        <a href="reclamos.php">
            <i class="fa-solid fa-comments"></i>
            Reclamos
        </a>

        <a href="pagos.php">
            <i class="fa-solid fa-dollar-sign"></i>
            Pagos
        </a>

    </nav>

    <!-- CONTENIDO -->

    <main class="panel-main">

        <h1 class="panel-bienvenida">
            Bienvenido, <?= $nombre ?>
        </h1>

        <div class="panel-alertas">

            <?php foreach ($alertas as $alerta): ?>

                <div class="panel-alerta">

                    <div class="panel-icono">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>

                    <div class="panel-texto">

                        <strong>
                            <?= $alerta["titulo"] ?>
                        </strong>

                        <p>
                            <?= $alerta["detalle"] ?>
                        </p>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>

    </main>

</body>

</html>