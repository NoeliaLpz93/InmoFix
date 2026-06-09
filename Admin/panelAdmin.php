<?php
session_start();

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
    "⚠️ Hay $contratos_por_vencer contratos próximos a vencer.",
    "⚠️ Hay $reclamos_urgentes reclamos urgentes sin resolver.",
    "⚠️ Hay $pagos_vencidos pagos vencidos pendientes."
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel del Administrador</title>
    <link rel="stylesheet" href="../css/estilo.css?v=1">
</head>

<body>

    <!-- NAVBAR -->
    <nav class="nav-admin">
        <a href="panel.php">Home</a>
        <a href="inquilinos.php">Inquilinos</a>
        <a href="propiedades.php">Propiedades</a>
        <a href="pagos.php">Pagos</a>
        <a href="reclamos.php">Reclamos</a>
        <a class="salir" href="salir.php">Salir</a>
    </nav>

    <div class="contenedor">

        <h2>Bienvenido, <?= $nombre ?></h2>

        <!-- ALERTAS -->
        <?php foreach ($alertas as $a): ?>
            <div class="alerta"><?= $a ?></div>
        <?php endforeach; ?>

        <!-- CARDS -->
        <div class="card">
            <h3>Contratos</h3>
            <p><strong>Por vencer:</strong> <?= $contratos_por_vencer ?></p>
        </div>

        <div class="card">
            <h3>Reclamos</h3>
            <p><strong>Urgentes:</strong> <?= $reclamos_urgentes ?></p>
        </div>

        <div class="card">
            <h3>Pagos</h3>
            <p><strong>Vencidos:</strong> <?= $pagos_vencidos ?></p>
        </div>

    </div>
</body>
</html>