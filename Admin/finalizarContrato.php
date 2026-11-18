<?php

require "verificar_admin.php";
require "conection.php";

$ESTADO_FINALIZADO = "Finalizado";

$id = filter_var($_GET["id"] ?? null, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]]);

$contrato = null;

if ($id !== false && $id !== null) {

    $sql = "SELECT c.IdContrato, c.IdInmueble, c.Estado,
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

$yaFinalizado = $contrato && $contrato["Estado"] === $ESTADO_FINALIZADO;
$error = null;

// La acción solo se ejecuta al confirmar mediante POST.
if ($contrato && !$yaFinalizado && $_SERVER["REQUEST_METHOD"] === "POST") {

    try {

        // INICIAR TRANSACCIÓN
        $conn->begin_transaction();

        // FINALIZAR EL CONTRATO
        $upd = $conn->prepare(
            "UPDATE contratos
             SET Estado = ?
             WHERE IdContrato = ? AND Estado = 'Activo'"
        );

        $upd->bind_param("si", $ESTADO_FINALIZADO, $id);
        $upd->execute();

        if ($upd->affected_rows != 1) {
            throw new Exception("No se pudo finalizar el contrato.");
        }

        $upd->close();

        // VERIFICAR SI EL INMUEBLE TIENE OTRO CONTRATO ACTIVO
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

        // SI NO TIENE OTROS CONTRATOS ACTIVOS, LIBERAR EL INMUEBLE
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

        // CONFIRMAR TRANSACCIÓN
        $conn->commit();

        header("Location: contratos.php?ok=finalizado");
        exit;

    } catch (Throwable $e) {

        $conn->rollback();

        $error = "No se pudo finalizar el contrato. Intentá nuevamente.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>InmoFix - Finalizar contrato</title>

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../Css/estilo.css?v=1">
</head>
<body>

    <?php include "nav.php"; ?>

    <div class="contenedor detalle-contenedor">

        <h2>Finalizar contrato</h2>

        <div class="detalle-info-card">

        <?php if (!$contrato): ?>

            <div class="detalle-info-header">
                <h3>Contrato no encontrado</h3>
            </div>
            <p class="login-error">No existe un contrato con ese identificador.</p>

        <?php elseif ($yaFinalizado): ?>

            <div class="detalle-info-header">
                <h3>Contrato #<?= (int) $contrato["IdContrato"] ?></h3>
                <span class="estado-inmueble"><?= htmlspecialchars($contrato["Estado"]) ?></span>
            </div>
            <p class="login-error">Este contrato ya está finalizado.</p>

        <?php else: ?>

            <div class="detalle-info-header">
                <h3>Contrato #<?= (int) $contrato["IdContrato"] ?></h3>
                <span class="estado-inmueble"><?= htmlspecialchars($contrato["Estado"]) ?></span>
            </div>

            <div class="detalle-dato">
                <span class="detalle-label">Inquilino</span>
                <p><?= htmlspecialchars($contrato["NombreInquilino"] . " " . $contrato["ApellidoInquilino"]) ?></p>
            </div>

            <div class="detalle-dato">
                <span class="detalle-label">Inmueble</span>
                <p><?= htmlspecialchars($contrato["DireccionInmueble"]) ?></p>
            </div>

            <?php if ($error): ?>
                <p class="login-error"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>

            <p>¿Seguro que querés finalizar este contrato?</p>

            <form method="POST" class="detalle-acciones">
                <button type="submit" class="btn btn-rojo">
                    <i class="fa-solid fa-flag-checkered"></i>
                    Confirmar
                </button>
                <a href="contratos.php" class="btn btn-accion-secundaria">Cancelar</a>
            </form>

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