<?php
session_start();

require "conection.php";

$nombre = "Administrador";

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