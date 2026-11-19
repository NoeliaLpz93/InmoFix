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

$idInmueble = isset($_GET["id"]) ? (int) $_GET["id"] : 0;

if ($idInmueble <= 0) {
    header("Location: inmuebles.php");
    exit();
}

$sql = "SELECT IdInmueble, Direccion, Tipo, Estado, Descripcion, imagen
        FROM inmuebles
        WHERE IdInmueble = ?
        LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idInmueble);
$stmt->execute();
$resultado = $stmt->get_result();
$inmueble = $resultado->fetch_assoc();
$stmt->close();

if (!$inmueble) {
    header("Location: inmuebles.php");
    exit();
}

// Resolver ruta de la imagen desde ../uploads/
$rutaImagen = "../img/inmofix-02.png";
if (!empty($inmueble["imagen"])) {
    $nombreArchivo = basename($inmueble["imagen"]);
    $rutaFisica = __DIR__ . "/../uploads/" . $nombreArchivo;
    if (file_exists($rutaFisica)) {
        $rutaImagen = "../uploads/" . rawurlencode($nombreArchivo);
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle del Inmueble - InmoFix</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="../Css/estilo.css?v=1">
    <link rel="stylesheet" href="../Css/cssinquilinos.css?v=11">
</head>
<body>

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

    <nav class="nav-inquilino">
        <a href="panelInq.php"><i class="fa-solid fa-house"></i> Home</a>
        <a class="activo" href="inmuebles.php"><i class="fa-solid fa-building"></i> Inmuebles disponibles</a>
        <a href="contrato.php"><i class="fa-solid fa-file-contract"></i> Contrato</a>
        <a href="reclamosInq.php"><i class="fa-solid fa-comments"></i> reclamos</a>
    </nav>

    <div class="inq-detalle-wrap">
        <h2 class="inq-detalle-titulo"><?= htmlspecialchars($inmueble["Direccion"]) ?></h2>

        <article class="inq-detalle-card">
            <div class="inq-detalle-img-box">
                <img
                    src="<?= htmlspecialchars($rutaImagen) ?>"
                    alt="<?= htmlspecialchars($inmueble["Direccion"]) ?>"
                >
            </div>

            <div class="inq-detalle-info">
                <p><strong>Tipo:</strong> <?= htmlspecialchars($inmueble["Tipo"] ?? "-") ?></p>
                <p><strong>Estado:</strong> <?= htmlspecialchars($inmueble["Estado"] ?? "-") ?></p>
                <p class="inq-detalle-desc">
                    <strong>Descripción:</strong><br>
                    <?= nl2br(htmlspecialchars($inmueble["Descripcion"] ?? "Sin descripción disponible.")) ?>
                </p>

                <div class="inq-detalle-acciones">
                    <a href="inmuebles.php" class="inq-btn-volver">
                        <i class="fa-solid fa-arrow-left"></i> Volver a inmuebles
                    </a>
                </div>
            </div>
        </article>
    </div>

</body>
</html>