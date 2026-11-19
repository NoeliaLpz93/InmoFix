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

$estadoDisponible = "Disponible";
$buscarDireccion = trim($_GET["buscar"] ?? "");
$inmuebles = [];

if ($buscarDireccion !== "") {
    $sql = "SELECT
                IdInmueble,
                Direccion,
                Tipo,
                Estado,
                Descripcion,
                imagen
            FROM inmuebles
            WHERE Estado = ?
              AND Direccion LIKE ?
            ORDER BY IdInmueble DESC";

    $likeDireccion = "%" . $buscarDireccion . "%";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $estadoDisponible, $likeDireccion);
} else {
    $sql = "SELECT
                IdInmueble,
                Direccion,
                Tipo,
                Estado,
                Descripcion,
                imagen
            FROM inmuebles
            WHERE Estado = ?
            ORDER BY IdInmueble DESC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $estadoDisponible);
}

$stmt->execute();
$resultado = $stmt->get_result();

while ($fila = $resultado->fetch_assoc()) {
    $inmuebles[] = $fila;
}

$stmt->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inmuebles Disponibles</title>
    <!-- Letra -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Iconos -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <!-- CSS -->
    <link rel="stylesheet" href="../Css/estilo.css?v=1">
    <link rel="stylesheet" href="../Css/cssinquilinos.css?v=3">
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

        <a href="panelInq.php">
            <i class="fa-solid fa-house"></i> Home
        </a>

        <a class="activo" href="inmuebles.php">
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

        <h2 class="inq-catalogo-titulo">Inmuebles</h2>

        <form method="GET" action="inmuebles.php" class="inq-buscador-bar">
            <input
                type="text"
                name="buscar"
                value="<?= htmlspecialchars($buscarDireccion) ?>"
                placeholder="Buscar por dirección"
                class="inq-buscador-input">
            <button type="submit" class="inq-buscador-btn" aria-label="Buscar">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
        </form>

        <?php if (empty($inmuebles)): ?>
            <div class="inq-vacio-msg">
                No hay inmuebles disponibles que coincidan con la búsqueda.
            </div>
        <?php else: ?>
            <div class="inq-inmuebles-grid">
                <?php foreach ($inmuebles as $item): ?>
                    <?php
                    $archivoImg = trim((string)($item["imagen"] ?? ""));
                    $rutaImg = $archivoImg !== ""
                        ? "../uploads/" . rawurlencode($archivoImg)
                        : "../img/inmofix-02.png";
                    $idInm = (int)($item["IdInmueble"] ?? 0);
                    ?>
                    <article class="inq-inmueble-card">
                        <img
                            src="<?= htmlspecialchars($rutaImg) ?>"
                            alt="<?= htmlspecialchars($item["Direccion"] ?? "Inmueble") ?>"
                            class="inq-inmueble-img"
                            onerror="this.onerror=null; this.src='../img/inmofix-02.png';">

                        <div class="inq-inmueble-body">
                            <h3 class="inq-inmueble-dir">
                                <?= htmlspecialchars($item["Direccion"] ?? "Sin dirección") ?>
                            </h3>
                            <p class="inq-inmueble-tipo">
                                <?= htmlspecialchars($item["Tipo"] ?? "") ?>
                            </p>

                            <div class="inq-inmueble-footer">
                                <a href="detalleInmueble.php?id=<?= $idInm ?>" class="inq-btn-detalles">
                                    <i class="fa-solid fa-circle-info"></i> Detalles
                                </a>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>

</body>
</html>