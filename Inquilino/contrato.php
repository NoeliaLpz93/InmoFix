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

$idInquilino = (int) $_SESSION["idInquilino"];
$contratos = [];

$sql = "SELECT
            c.IdContrato,
            c.IdInmueble,
            c.FechaInicio,
            c.FechaFin,
            c.ValorInicial,
            c.TipoAjuste,
            c.PorcentajeAjuste,
            c.Estado,
            i.Direccion,
            i.Tipo,
            i.imagen
        FROM contratos c
        INNER JOIN inmuebles i
            ON c.IdInmueble = i.IdInmueble
        WHERE c.IdInquilino = ?
        ORDER BY c.IdContrato DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idInquilino);
$stmt->execute();

$resultado = $stmt->get_result();
while ($fila = $resultado->fetch_assoc()) {
    $contratos[] = $fila;
}

$stmt->close();

$contratoActual = $contratos[0] ?? null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Contrato</title>
    <!-- Letra -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Iconos -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <!-- CSS -->
    <link rel="stylesheet" href="../Css/estilo.css?v=1">
    <link rel="stylesheet" href="../Css/cssinquilinos.css?v=5">
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

        <a href="panelInq.php">
            <i class="fa-solid fa-house"></i> Home
        </a>

        <a href="inmuebles.php">
            <i class="fa-solid fa-building"></i> Inmuebles disponibles
        </a>

        <a class="activo" href="contrato.php">
            <i class="fa-solid fa-file-contract"></i> Contrato
        </a>

        <a href="reclamosInq.php">
            <i class="fa-solid fa-comments"></i> reclamos
        </a>

    </nav>

    <div class="inq-contrato-wrap">

        <div class="inq-contrato-top">
            <h2 class="inq-contrato-titulo">Mi Contrato</h2>
            <?php if ($contratoActual): ?>
                <button type="button" onclick="window.print()" class="inq-btn-imprimir">
                    <i class="fa-solid fa-print"></i> Imprimir resumen
                </button>
            <?php endif; ?>
        </div>

        <?php if (!$contratoActual): ?>
            <div class="inq-ficha-doc">
                <p class="inq-ficha-sub">Todavía no tenés un contrato registrado en el sistema.</p>
            </div>
        <?php else: ?>
            <?php
            $fechaInicioFmt = !empty($contratoActual["FechaInicio"])
                ? date("d/m/Y", strtotime($contratoActual["FechaInicio"]))
                : "No disponible";

            $fechaFinFmt = !empty($contratoActual["FechaFin"])
                ? date("d/m/Y", strtotime($contratoActual["FechaFin"]))
                : "No disponible";

            $valorFmt = "$ " . number_format((float)($contratoActual["ValorInicial"] ?? 0), 2, ",", ".");
            $ajusteFmt = number_format((float)($contratoActual["PorcentajeAjuste"] ?? 0), 2, ",", ".") . "%";

            $archivoImg = trim((string)($contratoActual["imagen"] ?? ""));
            $rutaImg = $archivoImg !== ""
                ? "../uploads/" . rawurlencode($archivoImg)
                : "../img/inmofix-02.png";
            ?>

            <article class="inq-ficha-doc">

                <div class="inq-ficha-encabezado">
                    <div>
                        <strong>Contrato #<?= (int)($contratoActual["IdContrato"] ?? 0) ?></strong>
                        <p class="inq-ficha-sub">
                            Resumen de vigencia, inmueble asociado y condiciones económicas
                        </p>
                    </div>
                    <span class="inq-badge-estado">
                        <?= htmlspecialchars($contratoActual["Estado"] ?? "Activo") ?>
                    </span>
                </div>

                <section class="inq-ficha-seccion">
                    <h3 class="inq-ficha-seccion-titulo">Estado y Vigencia</h3>
                    <dl class="inq-ficha-lista">
                        <div class="inq-ficha-fila">
                            <dt>Estado</dt>
                            <dd><?= htmlspecialchars($contratoActual["Estado"] ?? "-") ?></dd>
                        </div>
                        <div class="inq-ficha-fila">
                            <dt>Fecha de inicio</dt>
                            <dd><?= htmlspecialchars($fechaInicioFmt) ?></dd>
                        </div>
                        <div class="inq-ficha-fila">
                            <dt>Fecha de vencimiento</dt>
                            <dd><?= htmlspecialchars($fechaFinFmt) ?></dd>
                        </div>
                    </dl>
                </section>

                <section class="inq-ficha-seccion">
                    <h3 class="inq-ficha-seccion-titulo">Inmueble Asociado</h3>
                    <div class="inq-ficha-inmueble-layout">
                        <img
                            src="<?= htmlspecialchars($rutaImg) ?>"
                            alt="<?= htmlspecialchars($contratoActual["Direccion"] ?? "Inmueble") ?>"
                            class="inq-ficha-foto"
                            onerror="this.onerror=null; this.src='../img/inmofix-02.png';">

                        <dl class="inq-ficha-lista">
                            <div class="inq-ficha-fila">
                                <dt>Dirección</dt>
                                <dd><?= htmlspecialchars($contratoActual["Direccion"] ?? "-") ?></dd>
                            </div>
                            <div class="inq-ficha-fila">
                                <dt>Tipo</dt>
                                <dd><?= htmlspecialchars($contratoActual["Tipo"] ?? "-") ?></dd>
                            </div>
                        </dl>
                    </div>
                </section>

                <section class="inq-ficha-seccion">
                    <h3 class="inq-ficha-seccion-titulo">Condiciones y Monto</h3>
                    <dl class="inq-ficha-lista">
                        <div class="inq-ficha-fila">
                            <dt>Valor inicial</dt>
                            <dd><?= htmlspecialchars($valorFmt) ?></dd>
                        </div>
                        <div class="inq-ficha-fila">
                            <dt>Tipo de ajuste</dt>
                            <dd><?= htmlspecialchars($contratoActual["TipoAjuste"] ?? "-") ?></dd>
                        </div>
                        <div class="inq-ficha-fila">
                            <dt>Porcentaje de ajuste</dt>
                            <dd><?= htmlspecialchars($ajusteFmt) ?></dd>
                        </div>
                    </dl>
                </section>

            </article>

            <?php if (count($contratos) > 1): ?>
                <section class="inq-ficha-doc inq-historial-box">
                    <h3 class="inq-ficha-seccion-titulo">Historial de contratos</h3>
                    <?php foreach ($contratos as $c): ?>
                        <div class="inq-ficha-fila">
                            <dt>Contrato #<?= (int)$c["IdContrato"] ?> · <?= htmlspecialchars($c["Direccion"] ?? "") ?></dt>
                            <dd>
                                <?= htmlspecialchars($c["Estado"] ?? "") ?> ·
                                <?= htmlspecialchars(date("d/m/Y", strtotime($c["FechaInicio"]))) ?>
                                al <?= htmlspecialchars(date("d/m/Y", strtotime($c["FechaFin"]))) ?>
                            </dd>
                        </div>
                    <?php endforeach; ?>
                </section>
            <?php endif; ?>
        <?php endif; ?>

    </div>

</body>
</html>