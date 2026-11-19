<?php
session_start();

// Verificar que el usuario haya iniciado sesión y sea administrador
if (
    !isset($_SESSION["idUsuario"]) ||
    $_SESSION["rol"] !== "Administrador"
) {
    header("Location: loginAdmin.php");
    exit();
}

require "conection.php";

// Contratos por vencer en los próximos 30 días
$sqlContratos = "SELECT COUNT(*) AS total
                 FROM contratos
                 WHERE FechaFin <= DATE_ADD(CURDATE(), INTERVAL 30 DAY)
                 AND Estado = 'Activo'";
$contratos_por_vencer = $conn->query($sqlContratos)->fetch_assoc()["total"];

// Reclamos urgentes
$sqlReclamos = "SELECT COUNT(*) AS total
                FROM reclamos
                WHERE Prioridad = 'Alta'
                AND Estado = 'Pendiente'";
$reclamos_urgentes = $conn->query($sqlReclamos)->fetch_assoc()["total"];

// Pagos vencidos
$sqlPagos = "SELECT COUNT(*) AS total
             FROM pagos
             WHERE Estado = 'Vencido'";
$pagos_vencidos = $conn->query($sqlPagos)->fetch_assoc()["total"];
// Contratos activos
$sqlActivos = "SELECT COUNT(*) AS total
               FROM contratos
               WHERE Estado = 'Activo'";

$contratos_activos = $conn->query($sqlActivos)->fetch_assoc()["total"];
// Próximos vencimientos
$sqlProximos = "
SELECT FechaFin
FROM contratos
WHERE Estado='Activo'
ORDER BY FechaFin ASC
LIMIT 5";

$proximos = $conn->query($sqlProximos);

// Últimos reclamos
$sqlUltimosReclamos = "
SELECT Estado, Prioridad
FROM reclamos
ORDER BY idReclamo DESC
LIMIT 5";

$ultimosReclamos = $conn->query($sqlUltimosReclamos);


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

    <!-- CSS -->
    <link rel="stylesheet" href="../Css/estilo.css?v=1">

</head>

<body>

    <?php include "nav.php"; ?> <!--Inserta la barra de navegación y header-->

    <!-- CONTENIDO -->

    <main class="panel-main">

        <h1 class="panel-bienvenida">
            Hola, <?= htmlspecialchars($_SESSION["nombre"]) ?>
        </h1>

        <p class="fecha-dashboard">
    <?= date("d/m/Y") ?>
</p>

        <div class="dashboard">
    <div class="card-dashboard">
    <h4>Contratos activos</h4>
    <h2><?= $contratos_activos ?></h2>
    <p>Actualmente vigentes</p>
</div>
    <div class="card-dashboard">
        <h4>Contratos por vencer</h4>
        <h2><?= $contratos_por_vencer ?></h2>
        <p>Próximos 30 días</p>
    </div>

    <div class="card-dashboard">
        <h4>Reclamos urgentes</h4>
        <h2><?= $reclamos_urgentes ?></h2>
        <p>Sin resolver</p>
    </div>

    <div class="card-dashboard">
        <h4>Pagos vencidos</h4>
        <h2><?= $pagos_vencidos ?></h2>
        <p>Pendientes</p>
    </div>

</div>
<div class="dashboard-inferior">

    <div class="panel-seccion">

        <h2>
            <i class="fa-solid fa-calendar-days"></i>
            Próximos vencimientos
        </h2>

        <?php while($fila = $proximos->fetch_assoc()): ?>

            <p>
                <i class="fa-solid fa-calendar"></i>
                <?= $fila["FechaFin"] ?>
            </p>

        <?php endwhile; ?>

    </div>

    <div class="panel-seccion">

        <h2>
            <i class="fa-solid fa-triangle-exclamation"></i>
            Últimos reclamos
        </h2>

        <?php while($fila = $ultimosReclamos->fetch_assoc()): ?>

            <p>
                <i class="fa-solid fa-circle-exclamation"></i>
                <?= $fila["Estado"] ?> - Prioridad <?= $fila["Prioridad"] ?>
            </p>

        <?php endwhile; ?>

    </div>

</div>
    </main>

</body>

</html>