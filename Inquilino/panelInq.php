<?php
session_start();

if (
    !isset($_SESSION["idUsuario"], $_SESSION["idInquilino"]) ||
    $_SESSION["rol"] !== "Inquilino"
) {
    header("Location: loginInq.php");
    exit();
}

require_once "../conection.php";

// Datos desde BD
// Datos del inquilino
$idInquilino = (int) $_SESSION["idInquilino"];

$nombre = ($_SESSION["nombre"] ?? "") . " " . ($_SESSION["apellido"] ?? "");

// Valores iniciales
$contrato_estado = "Sin contrato";
$contrato_vencimiento = "No disponible";
$inmueble = "Sin inmueble asignado";

// Buscar contrato e inmueble
$sql = "SELECT
            c.Estado,
            c.FechaFin,
            i.Direccion
        FROM contratos c
        INNER JOIN inmuebles i
            ON c.IdInmueble = i.IdInmueble
        WHERE c.IdInquilino = ?
        ORDER BY c.IdContrato DESC
        LIMIT 1";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idInquilino);
$stmt->execute();

$resultado = $stmt->get_result();

if ($datos = $resultado->fetch_assoc()) {

    $contrato_estado = $datos["Estado"];

    $contrato_vencimiento = date(
        "d/m/Y",
        strtotime($datos["FechaFin"])
    );

    $inmueble = $datos["Direccion"];
}

$stmt->close();

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

        <h2>Bienvenido, <?= htmlspecialchars($nombre) ?></h2>

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