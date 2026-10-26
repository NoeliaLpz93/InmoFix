<?php
require "conection.php";

// Estado que usa el sistema para un contrato cerrado (ver también finalizarContrato.php)
$ESTADO_FINALIZADO = "Finalizado";

$id = filter_var($_GET["id"] ?? null, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]]);

$contrato = null;

if ($id !== false && $id !== null) {

    $sql = "SELECT c.IdContrato, c.FechaInicio, c.FechaFin, c.Estado,
                   q.nombre AS NombreInquilino, q.apellido AS ApellidoInquilino,
                   i.Direccion AS DireccionInmueble
            FROM contratos c
            INNER JOIN inquilinos q ON c.IdInquilino = q.id
            INNER JOIN inmuebles  i ON c.IdInmueble  = i.IdInmueble
            WHERE c.IdContrato = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $contrato = $stmt->get_result()->fetch_assoc();
}

$errores = [];
$estadoForm = $contrato["Estado"] ?? "";
$fechaFinForm = $contrato["FechaFin"] ?? "";

if ($contrato) {

    // Estados que se pueden elegir. Si el contrato ya tiene otro valor en la
    // base, se conserva como opción para no pisarlo sin querer.
    $estadosPermitidos = array_values(array_unique([$contrato["Estado"], "Activo", $ESTADO_FINALIZADO]));

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $estadoForm   = $_POST["estado"] ?? "";
        $fechaFinForm = $_POST["fechaFin"] ?? "";

        // --- VALIDACIONES EN EL SERVIDOR ---
        if (!in_array($estadoForm, $estadosPermitidos, true)) {
            $errores[] = "El estado seleccionado no es válido.";
        }

        $fecha = DateTime::createFromFormat("Y-m-d", $fechaFinForm);
        if (!$fecha || $fecha->format("Y-m-d") !== $fechaFinForm) {
            $errores[] = "La fecha de vencimiento no es válida.";
        } elseif ($fechaFinForm < $contrato["FechaInicio"]) {
            $errores[] = "El vencimiento no puede ser anterior a la fecha de inicio (" . date("d/m/Y", strtotime($contrato["FechaInicio"])) . ").";
        }

        // --- GUARDAR ---
        if (!$errores) {
            try {
                $upd = $conn->prepare("UPDATE contratos SET Estado = ?, FechaFin = ? WHERE IdContrato = ?");
                $upd->bind_param("ssi", $estadoForm, $fechaFinForm, $id);
                $upd->execute();

                header("Location: contratos.php?ok=editado");
                exit;
            } catch (mysqli_sql_exception $e) {
                $errores[] = "No se pudo guardar el contrato. Intentá nuevamente.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>InmoFix - Editar contrato</title>

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../Css/estilo.css?v=1">
</head>
<body>

    <?php include "nav.php"; ?>

    <div class="contenedor detalle-contenedor">

        <h2>Editar contrato</h2>

        <div class="detalle-info-card">

        <?php if ($contrato): ?>

            <div class="detalle-info-header">
                <h3>Contrato #<?= (int) $contrato["IdContrato"] ?></h3>
            </div>

            <?php foreach ($errores as $error): ?>
                <p class="login-error"><?= htmlspecialchars($error) ?></p>
            <?php endforeach; ?>

            <form method="POST" class="formulario-inquilino">

                <div class="campos-inquilino">

                    <div class="campo-inmueble campo-inquilino-completo">
                        <label>Inquilino</label>
                        <input type="text" readonly value="<?= htmlspecialchars($contrato["NombreInquilino"] . " " . $contrato["ApellidoInquilino"]) ?>">
                    </div>

                    <div class="campo-inmueble campo-inquilino-completo">
                        <label>Inmueble</label>
                        <input type="text" readonly value="<?= htmlspecialchars($contrato["DireccionInmueble"]) ?>">
                    </div>

                    <div class="campo-inmueble">
                        <label for="estado">Estado</label>
                        <select id="estado" name="estado" required>
                            <?php foreach ($estadosPermitidos as $e): ?>
                                <option value="<?= htmlspecialchars($e) ?>" <?= $e === $estadoForm ? "selected" : "" ?>>
                                    <?= htmlspecialchars($e) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="campo-inmueble">
                        <label for="fechaFin">Vencimiento</label>
                        <input type="date" id="fechaFin" name="fechaFin" required value="<?= htmlspecialchars($fechaFinForm) ?>">
                    </div>

                </div>

                <div class="detalle-acciones">
                    <button type="submit" class="btn">
                        <i class="fa-solid fa-check"></i>
                        Guardar cambios
                    </button>

                    <a href="contratos.php" class="btn btn-accion-secundaria">Cancelar</a>
                </div>

            </form>

        <?php else: ?>

            <div class="detalle-info-header">
                <h3>Contrato no encontrado</h3>
            </div>
            <p class="login-error">No existe un contrato con ese identificador.</p>

        <?php endif; ?>

        </div>

        <div class="detalle-volver">
            <a href="contratos.php" class="btn btn-volver">
                <i class="fa-solid fa-arrow-left"></i>
                Volver a contratos
            </a>
        </div>

    </div>

</body>
</html>