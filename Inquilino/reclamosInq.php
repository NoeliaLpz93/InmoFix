<?php
// Inicia o reanuda la sesión del usuario
session_start();

// Verifica que el usuario haya iniciado sesión y tenga el rol de Inquilino
if (
    !isset($_SESSION["idUsuario"], $_SESSION["idInquilino"]) ||
    $_SESSION["rol"] !== "Inquilino"
) {
    header("Location: loginInq.php");
    exit();
}

// Conecta a la base de datos
require_once "../Admin/conection.php";

$idInquilino = (int) $_SESSION["idInquilino"];

// Recupera mensajes flash (éxito o error) de una acción anterior y los limpia
$mensajeExito = $_SESSION["flash_exito"] ?? "";
$mensajeError = $_SESSION["flash_error"] ?? "";
unset($_SESSION["flash_exito"], $_SESSION["flash_error"]);

// Busca el último contrato del inquilino para saber qué inmueble tiene asignado
$idInmuebleActual = 0;
$direccionInmuebleActual = "";

$sqlContrato = "SELECT c.IdInmueble, i.Direccion
                FROM contratos c
                INNER JOIN inmuebles i ON c.IdInmueble = i.IdInmueble
                WHERE c.IdInquilino = ?
                ORDER BY c.IdContrato DESC
                LIMIT 1";
$stmtContrato = $conn->prepare($sqlContrato);
$stmtContrato->bind_param("i", $idInquilino);
$stmtContrato->execute();
$resContrato = $stmtContrato->get_result();
if ($filaContrato = $resContrato->fetch_assoc()) {
    $idInmuebleActual = (int) $filaContrato["IdInmueble"];
    $direccionInmuebleActual = (string) $filaContrato["Direccion"];
}
$stmtContrato->close();

// Procesa el envío de un nuevo reclamo (patrón PRG para evitar reenvíos al actualizar)
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $categoria = trim($_POST["categoria"] ?? "");
    $prioridad = trim($_POST["prioridad"] ?? "Media");
    $descripcion = trim($_POST["descripcion"] ?? "");

    // Valida que la prioridad sea una de las permitidas
    $prioridadesValidas = ["Baja", "Media", "Alta"];
    if (!in_array($prioridad, $prioridadesValidas, true)) {
        $prioridad = "Media";
    }

    // Valida contrato activo y campos obligatorios
    if ($idInmuebleActual <= 0) {
        $_SESSION["flash_error"] = "No tenés un inmueble asignado en un contrato para asociar el reclamo.";
    } elseif ($categoria === "" || $descripcion === "") {
        $_SESSION["flash_error"] = "Completá la categoría y la descripción del reclamo.";
    } else {
        $estadoInicial = "Pendiente";
        $fechaCreacion = date("Y-m-d H:i:s");

        // Inserta el reclamo usando consulta preparada (evita SQL Injection)
        $sqlInsert = "INSERT INTO reclamos
                        (IdInquilino, IdInmueble, Categoria, Descripcion, Estado, FechaCreacion, FechaResolucion, Prioridad)
                      VALUES (?, ?, ?, ?, ?, ?, NULL, ?)";
        $stmtInsert = $conn->prepare($sqlInsert);
        $stmtInsert->bind_param(
            "iisssss",
            $idInquilino,
            $idInmuebleActual,
            $categoria,
            $descripcion,
            $estadoInicial,
            $fechaCreacion,
            $prioridad
        );

        if ($stmtInsert->execute()) {
            $_SESSION["flash_exito"] = "Tu reclamo fue enviado y ya figura para revisión de administración.";
        } else {
            $_SESSION["flash_error"] = "No se pudo registrar el reclamo. Intentá nuevamente.";
        }
        $stmtInsert->close();
    }

    // Redirige a la misma página después del POST
    header("Location: reclamosInq.php");
    exit();
}

// Consulta todos los reclamos del inquilino, del más nuevo al más viejo
$reclamos = [];
$sqlLista = "SELECT
                r.IdReclamo,
                r.Categoria,
                r.Descripcion,
                r.Estado,
                r.FechaCreacion,
                r.FechaResolucion,
                r.Prioridad,
                i.Direccion
             FROM reclamos r
             LEFT JOIN inmuebles i ON r.IdInmueble = i.IdInmueble
             WHERE r.IdInquilino = ?
             ORDER BY r.FechaCreacion DESC, r.IdReclamo DESC";

$stmtLista = $conn->prepare($sqlLista);
$stmtLista->bind_param("i", $idInquilino);
$stmtLista->execute();
$resLista = $stmtLista->get_result();

while ($fila = $resLista->fetch_assoc()) {
    $reclamos[] = $fila;
}
$stmtLista->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Reclamos</title>
    <!-- Tipografía íconos -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <!-- Estilos generales -->
    <link rel="stylesheet" href="../Css/estilo.css?v=1">
    <link rel="stylesheet" href="../Css/cssinquilinos.css?v=7">
</head>

<body>

    <!-- Encabezado con logo y menú de cierre de sesión -->
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

    <!-- Barra de navegación del inquilino -->
    <nav class="nav-inquilino">
        <a href="panelInq.php">
            <i class="fa-solid fa-house"></i> Home
        </a>

        <a href="inmuebles.php">
            <i class="fa-solid fa-building"></i> Inmuebles disponibles
        </a>

        <a href="contrato.php">
            <i class="fa-solid fa-file-contract"></i> Contrato
        </a>

        <a class="activo" href="reclamosInq.php">
            <i class="fa-solid fa-comments"></i> reclamos
        </a>
    </nav>

    <div class="inq-reclamos-wrap">

        <h2 class="inq-reclamos-titulo">Reclamos</h2>

        <!-- Mensajes de éxito o error -->
        <?php if ($mensajeExito !== ""): ?>
            <div class="inq-alerta inq-alerta-exito">
                <i class="fa-solid fa-circle-check"></i>
                <span><?= htmlspecialchars($mensajeExito) ?></span>
            </div>
        <?php endif; ?>

        <?php if ($mensajeError !== ""): ?>
            <div class="inq-alerta inq-alerta-error">
                <i class="fa-solid fa-circle-exclamation"></i>
                <span><?= htmlspecialchars($mensajeError) ?></span>
            </div>
        <?php endif; ?>

        <!-- Formulario para crear un nuevo reclamo -->
        <section class="inq-reclamo-panel">
            <div class="inq-reclamo-panel-head">Nuevo reclamo</div>

            <div class="inq-reclamo-panel-body">
                <?php if ($direccionInmuebleActual !== ""): ?>
                    <p class="inq-reclamo-inmueble-linea">
                        <strong>Inmueble:</strong> <?= htmlspecialchars($direccionInmuebleActual) ?>
                    </p>
                <?php endif; ?>

                <form method="POST" action="reclamosInq.php">
                    <div class="inq-reclamo-form-grid">
                        <div class="inq-campo-grupo">
                            <label for="categoria">Categoría</label>
                            <select id="categoria" name="categoria" class="inq-control-select" required>
                                <option value="">Seleccioná una categoría</option>
                                <option value="Plomería">Plomería</option>
                                <option value="Electricidad">Electricidad</option>
                                <option value="Humedad">Humedad</option>
                                <option value="Gas">Gas</option>
                                <option value="Otro">Otro</option>
                            </select>
                        </div>

                        <div class="inq-campo-grupo">
                            <label for="prioridad">Prioridad</label>
                            <select id="prioridad" name="prioridad" class="inq-control-select">
                                <option value="Baja">Baja</option>
                                <option value="Media" selected>Media</option>
                                <option value="Alta">Alta</option>
                            </select>
                        </div>
                    </div>

                    <div class="inq-campo-grupo">
                        <label for="descripcion">Descripción del problema</label>
                        <textarea
                            id="descripcion"
                            name="descripcion"
                            rows="4"
                            class="inq-control-textarea"
                            placeholder="Contanos qué está pasando en el inmueble..."
                            required></textarea>
                    </div>

                    <div class="inq-form-acciones">
                        <button type="submit" class="inq-btn-enviar">
                            <i class="fa-solid fa-paper-plane"></i> Enviar reclamo
                        </button>
                    </div>
                </form>
            </div>
        </section>

        <!-- Listado de reclamos cargados por el inquilino -->
        <h3 class="inq-subtitulo-seccion">Mis reclamos realizados</h3>

        <?php if (empty($reclamos)): ?>
            <div class="inq-reclamo-panel">
                <div class="inq-reclamo-panel-body">
                    <p>Todavía no cargaste ningún reclamo.</p>
                </div>
            </div>
        <?php else: ?>
            <div class="inq-reclamos-lista">
                <?php foreach ($reclamos as $rec): ?>
                    <?php
                    // Lee el estado actual guardado en la base de datos
                    $estadoRec = trim($rec["Estado"] ?? "Pendiente");

                    // Detecta si el admin ya lo marcó como solucionado/resuelto
                    $esResuelto = in_array(
                        strtolower($estadoRec),
                        ["solucionado", "resuelto", "cerrado", "finalizado"],
                        true
                    );

                    // Formatea la fecha de creación
                    $fechaCreaFmt = !empty($rec["FechaCreacion"])
                        ? date("d/m/Y H:i", strtotime($rec["FechaCreacion"]))
                        : "-";

                    // Muestra fecha de resolución, "Resuelto" si cambió el estado sin fecha, o "Pendiente de resolución"
                    if (!empty($rec["FechaResolucion"])) {
                        $fechaResFmt = date("d/m/Y H:i", strtotime($rec["FechaResolucion"]));
                    } elseif ($esResuelto) {
                        $fechaResFmt = "Resuelto";
                    } else {
                        $fechaResFmt = "Pendiente de resolución";
                    }
                    ?>
                    <article class="inq-reclamo-item <?= $esResuelto ? "is-resuelto" : "" ?>">
                        <div class="inq-reclamo-item-head">
                            <?= htmlspecialchars($rec["Categoria"] ?? "Reclamo") ?>
                        </div>
                        <div class="inq-reclamo-item-body">
                            <p><strong>Estado:</strong> <?= htmlspecialchars($estadoRec) ?></p>
                            <p><strong>Prioridad:</strong> <?= htmlspecialchars($rec["Prioridad"] ?? "-") ?></p>
                            <?php if (!empty($rec["Direccion"])): ?>
                                <p><strong>Inmueble:</strong> <?= htmlspecialchars($rec["Direccion"]) ?></p>
                            <?php endif; ?>
                            <p><strong>Fecha:</strong> <?= htmlspecialchars($fechaCreaFmt) ?></p>
                            <p><strong>Resolución:</strong> <?= htmlspecialchars($fechaResFmt) ?></p>
                            <p class="inq-reclamo-desc">
                                <?= htmlspecialchars($rec["Descripcion"] ?? "") ?>
                            </p>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>

</body>
</html>