<?php
session_start();

if (
    !isset($_SESSION["idUsuario"], $_SESSION["idInquilino"]) ||
    $_SESSION["rol"] !== "Inquilino"
) {
    header("Location: loginInq.php");
    exit();
}

require_once "../Admin/conection.php";

// Datos desde BD
// Datos del inquilino
$idInquilino = (int) $_SESSION["idInquilino"];

$nombre = ($_SESSION["nombre"] ?? "") . " " . ($_SESSION["apellido"] ?? "");

// Valores iniciales
$contrato_estado = "Sin contrato";
$contrato_vencimiento = "No disponible";
$inmueble = "Sin inmueble asignado";
$reclamos_pendientes = 0;
$reclamos_proceso = 0;
$proximo_pago = "No disponible";
$dias_restantes_contrato = null;

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

    if (!empty($datos["FechaFin"])) {
        $timestampFin = strtotime($datos["FechaFin"]);
        $contrato_vencimiento = date("d/m/Y", $timestampFin);

        $hoy = new DateTime(date("Y-m-d"));
        $fin = new DateTime(date("Y-m-d", $timestampFin));
        $dias_restantes_contrato = (int) $hoy->diff($fin)->format("%r%a");
    }

    $inmueble = $datos["Direccion"];
}

$stmt->close();

// Buscar reclamos pendientes y en proceso del inquilino
$sqlReclamos = "SELECT
                    SUM(CASE WHEN Estado = 'Pendiente' THEN 1 ELSE 0 END) AS pendientes,
                    SUM(CASE WHEN Estado = 'En Proceso' THEN 1 ELSE 0 END) AS en_proceso
                FROM reclamos
                WHERE IdInquilino = ?";

$stmtRec = $conn->prepare($sqlReclamos);
$stmtRec->bind_param("i", $idInquilino);
$stmtRec->execute();

$resRec = $stmtRec->get_result();

if ($filaRec = $resRec->fetch_assoc()) {
    $reclamos_pendientes = (int) ($filaRec["pendientes"] ?? 0);
    $reclamos_proceso = (int) ($filaRec["en_proceso"] ?? 0);
}

$stmtRec->close();

// Notificaciones de la bd
$notificaciones = [];

if ($dias_restantes_contrato !== null) {
    if ($dias_restantes_contrato < 0) {
        $notificaciones[] = "⚠️ Tu contrato venció el " . $contrato_vencimiento;
    } elseif ($dias_restantes_contrato === 0) {
        $notificaciones[] = "⚠️ Tu alquiler vence hoy";
    } elseif ($dias_restantes_contrato <= 30) {
        $textoDia = $dias_restantes_contrato === 1 ? "día" : "días";
        $notificaciones[] = "⚠️ Tu alquiler vence en " . $dias_restantes_contrato . " " . $textoDia;
    }
}

if ($reclamos_proceso > 0) {
    $textoRec = $reclamos_proceso === 1
        ? "⚠️ Tenés 1 reclamo en proceso"
        : "⚠️ Tenés " . $reclamos_proceso . " reclamos en proceso";
    $notificaciones[] = $textoRec;
}

if ($reclamos_pendientes > 0) {
    $textoPend = $reclamos_pendientes === 1
        ? "⚠️ Tenés 1 reclamo pendiente de revisión"
        : "⚠️ Tenés " . $reclamos_pendientes . " reclamos pendientes de revisión";
    $notificaciones[] = $textoPend;
}

if (empty($notificaciones)) {
    $notificaciones[] = "No tenés avisos urgentes por el momento.";
}
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
    <link rel="stylesheet" href="../Css/cssinquilinos.css?v=9">
</head>

<body>

    <!-- HEADER -->
    <header class="panel-header">

    <div></div>

    <div class="panel-logo">
        <img src="../img/inmofix-02.png" alt="Logo INMOFIX">
    </div>

    <div class="panel-perfil-wrap">
        <button type="button" class="panel-perfil" aria-label="Menú de usuario">
            <i class="fa-solid fa-user"></i>
        </button>

        <div class="panel-perfil-dropdown">
            <a href="../Admin/logout.php" class="panel-perfil-item">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span>Cerrar sesión</span>
            </a>
        </div>
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

                <p><strong>Estado:</strong> <?= htmlspecialchars($contrato_estado) ?></p>

                <p><strong>Vence:</strong> <?= htmlspecialchars($contrato_vencimiento) ?></p>

            </div>

            <div class="card">

                <h3>Mis Reclamos</h3>

                <p><strong>Pendientes:</strong> <?= (int) $reclamos_pendientes ?></p>

                <p><strong>En proceso:</strong> <?= (int) $reclamos_proceso ?></p>

            </div>

            <div class="card">

                <h3>Mis Pagos</h3>

                <p><strong>Próximo vencimiento:</strong> <?= htmlspecialchars($proximo_pago) ?></p>

            </div>

            <div class="card">

                <h3>Mi Inmueble</h3>

                <p><?= htmlspecialchars($inmueble) ?></p>

            </div>

        </div>

        <div class="card notificaciones">

            <h3>Notificaciones</h3>

            <?php foreach ($notificaciones as $n): ?>

                <p><?= htmlspecialchars($n) ?></p>

            <?php endforeach; ?>

        </div>

    </div>

</body>
</html>