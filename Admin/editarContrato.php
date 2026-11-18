<?php

require "verificar_admin.php";
require "conection.php";

$ESTADO_FINALIZADO = "Finalizado";

$id = filter_var($_GET["id"] ?? null, FILTER_VALIDATE_INT, [
    "options" => ["min_range" => 1]
]);

$contrato = null;

// Buscar el contrato.
if ($id !== false && $id !== null) {

    $sql = "SELECT c.IdContrato, c.IdInmueble, c.FechaInicio, c.FechaFin, c.Estado,
                   q.nombre AS NombreInquilino, q.apellido AS ApellidoInquilino,
                   i.Direccion AS DireccionInmueble
            FROM contratos c
            INNER JOIN inquilinos q ON c.IdInquilino = q.id
            INNER JOIN inmuebles i ON c.IdInmueble = i.IdInmueble
            WHERE c.IdContrato = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $contrato = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

// Procesar el envío del formulario.
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    header("Content-Type: application/json; charset=UTF-8");

    // Verificar que exista el contrato.
    if (!$contrato) {
        echo json_encode([
            "success" => false,
            "mensaje" => "No se encontró el contrato."
        ]);
        exit;
    }

    // No permitir editar contratos finalizados.
    if ($contrato["Estado"] === $ESTADO_FINALIZADO) {
        echo json_encode([
            "success" => false,
            "mensaje" => "Este contrato ya está finalizado y no se puede modificar."
        ]);
        exit;
    }

    $estadoForm = $_POST["estado"] ?? "";
    $fechaFinForm = $_POST["fechaFin"] ?? "";

    $errores = [];

    // VALIDACIONES
    if (!in_array($estadoForm, ["Activo", $ESTADO_FINALIZADO], true)) {
        $errores[] = "El estado seleccionado no es válido.";
    }

    $fecha = DateTime::createFromFormat("Y-m-d", $fechaFinForm);

    if (!$fecha || $fecha->format("Y-m-d") !== $fechaFinForm) {
        $errores[] = "La fecha de vencimiento no es válida.";
    } elseif ($fechaFinForm < $contrato["FechaInicio"]) {
        $errores[] = "El vencimiento no puede ser anterior a la fecha de inicio.";
    }

    if ($errores) {
        echo json_encode([
            "success" => false,
            "mensaje" => implode(" ", $errores)
        ]);
        exit;
    }

    // GUARDAR CAMBIOS
    try {

        $conn->begin_transaction();

        // Verificar que no exista otro contrato activo para el inmueble.
        if ($estadoForm === "Activo") {

            $sqlVerificar = "SELECT COUNT(*)
                             FROM contratos
                             WHERE IdInmueble = ?
                             AND Estado = 'Activo'
                             AND IdContrato <> ?";

            $stmtVerificar = $conn->prepare($sqlVerificar);
            $stmtVerificar->bind_param("ii", $contrato["IdInmueble"], $id);
            $stmtVerificar->execute();
            $stmtVerificar->bind_result($contratosActivos);
            $stmtVerificar->fetch();
            $stmtVerificar->close();

            if ($contratosActivos > 0) {
                throw new Exception("El inmueble ya tiene otro contrato activo.");
            }
        }

        // Actualizar contrato.
        $upd = $conn->prepare(
            "UPDATE contratos
             SET Estado = ?, FechaFin = ?
             WHERE IdContrato = ? AND Estado = 'Activo'"
        );

        $upd->bind_param("ssi", $estadoForm, $fechaFinForm, $id);
        $upd->execute();
        $upd->close();

        // Si se finalizó, comprobar si el inmueble quedó sin contratos activos.
        if ($estadoForm === $ESTADO_FINALIZADO) {

            $sqlVerificar = "SELECT COUNT(*)
                             FROM contratos
                             WHERE IdInmueble = ?
                             AND Estado = 'Activo'";

            $stmtVerificar = $conn->prepare($sqlVerificar);
            $stmtVerificar->bind_param("i", $contrato["IdInmueble"]);
            $stmtVerificar->execute();
            $stmtVerificar->bind_result($contratosActivos);
            $stmtVerificar->fetch();
            $stmtVerificar->close();

            if ($contratosActivos == 0) {

                $sqlInmueble = "UPDATE inmuebles
                                SET Estado = 'Disponible'
                                WHERE IdInmueble = ?
                                AND Estado = 'Alquilado'";

                $stmtInmueble = $conn->prepare($sqlInmueble);
                $stmtInmueble->bind_param("i", $contrato["IdInmueble"]);
                $stmtInmueble->execute();
                $stmtInmueble->close();
            }
        }

        $conn->commit();

        echo json_encode([
            "success" => true,
            "redirect" => "contratos.php?ok=editado"
        ]);
        exit;

    } catch (Throwable $e) {

        $conn->rollback();

        echo json_encode([
            "success" => false,
            "mensaje" => "No se pudo guardar el contrato. " . $e->getMessage()
        ]);
        exit;
    }
}

// Variables para mostrar el formulario si se abre directamente.
$errores = [];
$estadoForm = $contrato["Estado"] ?? "";
$fechaFinForm = $contrato["FechaFin"] ?? "";

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

            <?php if ($contrato["Estado"] === $ESTADO_FINALIZADO): ?>

                <p class="login-error">Este contrato ya está finalizado y no se puede modificar.</p>

            <?php else: ?>

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
                                <option value="Activo" <?= $estadoForm === "Activo" ? "selected" : "" ?>>Activo</option>
                                <option value="Finalizado" <?= $estadoForm === "Finalizado" ? "selected" : "" ?>>Finalizado</option>
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

            <?php endif; ?>

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