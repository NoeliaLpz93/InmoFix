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
    <!-- Letra -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Iconos -->
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
    <nav class="nav-inquilino">

        <a class="activo" href="panelInq.php">
            <i class="fa-solid fa-house"></i> Home
        </a>

        <a href="inmuebles.php">
            <i class="fa-solid fa-building"></i> Inmuebles disponibles
        </a>

        <a href="contrato.php">
            <i class="fa-solid fa-file-contract"></i> Contrato
        </a>

        <a href="reclamosInq.php">
            <i class="fa-solid fa-comments"></i> reclamos
        </a>

    </nav>

    <div class="contenedor">

        <h2>Bienvenido, <?= $nombre ?></h2>

        <div class="cards">

            <div class="card">

                <h3>Mi Contrato</h3>

                <p><strong>Estado:</strong> <?= $contrato_estado ?></p>

                <p><strong>Vence:</strong> <?= $contrato_vencimiento ?></p>

            </div>

            <div class="card">

                <h3>Mis Reclamos</h3>

                <p><strong>Pendientes:</strong> <?= $reclamos_pendientes ?></p>

                <p><strong>En proceso:</strong> <?= $reclamos_proceso ?></p>

            </div>

            <div class="card">

                <h3>Mis Pagos</h3>

                <p><strong>Próximo vencimiento:</strong> <?= $proximo_pago ?></p>

            </div>

            <div class="card">

                <h3>Mi Inmueble</h3>

                <p><?= $inmueble ?></p>

            </div>

        </div>

        <div class="card notificaciones">

            <h3>Notificaciones</h3>

            <?php foreach ($notificaciones as $n): ?>

                <p><?= $n ?></p>

            <?php endforeach; ?>

        </div>

    </div>

</body>
</html>