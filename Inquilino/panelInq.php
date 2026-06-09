<?php
session_start();

//if (!isset($_SESSION["inquilino"])) {
  //  header("Location: login.php");
    //exit();
//}

// Datos de ejemplo (luego vienen desde BD)
$nombre = "Pablo Martinez";
$contrato_estado = "Activo";
$contrato_vencimiento = "15/06/2029";

$proximo_pago = "15/05/2028";

$reclamos_pendientes = 2;
$reclamos_proceso = 1;

$inmueble = "San Martín 123";

$notificaciones = [
    "⚠️ Tu alquiler vence en 7 días",
    "⚠️ Tenés un reclamo en proceso"
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel del Inquilino</title>
    <link rel="stylesheet" href="../css/estilo.css?v=1">
</head>

<body>

    <!-- NAVBAR -->
    <nav class="nav-inquilino">
        <a href="panel.php">Home</a>
        <a href="recibos.php">Recibos</a>
        <a href="contrato.php">Contrato</a>
        <a href="reclamos.php">Reclamos</a>
        <a class="salir" href="salir.php">Salir</a>
    </nav>

    <div class="contenedor">

        <h2>Bienvenido, <?= $nombre ?></h2>

        <div class="card">
            <h3>Mi Contrato</h3>
            <p><strong>Estado:</strong> <?= $contrato_estado ?></p>
            <p><strong>Vence:</strong> <?= $contrato_vencimiento ?></p>
        </div>

        <div class="card">
            <h3>Mis Pagos</h3>
            <p><strong>Próximo vencimiento:</strong> <?= $proximo_pago ?></p>
        </div>

        <div class="card">
            <h3>Mis Reclamos</h3>
            <p><strong>Pendientes:</strong> <?= $reclamos_pendientes ?></p>
            <p><strong>En proceso:</strong> <?= $reclamos_proceso ?></p>
        </div>

        <div class="card">
            <h3>Mi Inmueble</h3>
            <p><?= $inmueble ?></p>
        </div>

        <div class="card">
            <h3>Notificaciones</h3>
            <?php foreach ($notificaciones as $n): ?>
                <p><?= $n ?></p>
            <?php endforeach; ?>
        </div>

        <a class="btn" href="nuevo_reclamo.php">+ Nuevo Reclamo</a>

    </div>
</body>
</html>