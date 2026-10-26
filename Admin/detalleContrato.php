<?php
require "conection.php";

// El id llega por GET desde contratos.php. Se valida que sea un entero positivo.
$id = filter_var($_GET["id"] ?? null, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]]);

$contrato = null;

if ($id !== false && $id !== null) {

    $sql = "SELECT c.IdContrato, c.FechaInicio, c.FechaFin, c.ValorInicial,
                   c.TipoAjuste, c.PorcentajeAjuste, c.Estado,
                   q.nombre AS NombreInquilino, q.apellido AS ApellidoInquilino, q.dni,
                   i.Direccion AS DireccionInmueble
            FROM contratos c
            INNER JOIN inquilinos q ON c.IdInquilino = q.id
            INNER JOIN inmuebles  i ON c.IdInmueble  = i.IdInmueble
            WHERE c.IdContrato = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $contrato = $stmt->get_result()->fetch_assoc(); // null si no existe
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>InmoFix - Detalle del contrato</title>

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../Css/estilo.css?v=1">
</head>
<body>

    <?php include "nav.php"; ?>

    <div class="contenedor detalle-contenedor">

        <h2>Detalle del contrato</h2>

        <div class="detalle-info-card">

        <?php if ($contrato): ?>

            <div class="detalle-info-header">
                <h3>Contrato #<?= (int) $contrato["IdContrato"] ?></h3>
                <span class="estado-inmueble"><?= htmlspecialchars($contrato["Estado"]) ?></span>
            </div>

            <div class="detalle-dato">
                <span class="detalle-label">Inquilino</span>
                <p><?= htmlspecialchars($contrato["NombreInquilino"] . " " . $contrato["ApellidoInquilino"]) ?> (DNI <?= htmlspecialchars($contrato["dni"]) ?>)</p>
            </div>

            <div class="detalle-dato">
                <span class="detalle-label">Inmueble</span>
                <p><?= htmlspecialchars($contrato["DireccionInmueble"]) ?></p>
            </div>

            <div class="detalle-dato">
                <span class="detalle-label">Fecha de inicio</span>
                <p><?= date("d/m/Y", strtotime($contrato["FechaInicio"])) ?></p>
            </div>

            <div class="detalle-dato">
                <span class="detalle-label">Vencimiento</span>
                <p><?= date("d/m/Y", strtotime($contrato["FechaFin"])) ?></p>
            </div>

            <div class="detalle-dato">
                <span class="detalle-label">Valor inicial</span>
                <p>$<?= number_format((float) $contrato["ValorInicial"], 2, ",", ".") ?></p>
            </div>

            <div class="detalle-dato">
                <span class="detalle-label">Ajuste</span>
                <p><?= htmlspecialchars($contrato["TipoAjuste"]) ?> — <?= number_format((float) $contrato["PorcentajeAjuste"], 2, ",", ".") ?>%</p>
            </div>

            <div class="detalle-acciones">

                <a href="editarContrato.php?id=<?= (int) $contrato["IdContrato"] ?>" class="btn">
                    <i class="fa-solid fa-pen"></i>
                    Editar
                </a>

                <?php if ($contrato["Estado"] !== "Finalizado"): ?>
                    <a href="finalizarContrato.php?id=<?= (int) $contrato["IdContrato"] ?>" class="btn btn-rojo">
                        <i class="fa-solid fa-flag-checkered"></i>
                        Finalizar
                    </a>
                <?php endif; ?>

            </div>

        <?php else: ?>

            <div class="detalle-info-header">
                <h3>Contrato no encontrado</h3>
            </div>
            <p class="login-error">No existe un contrato con ese identificador.</p>

        <?php endif; ?>

        </div>

        <!-- VOLVER -->
        <div class="detalle-volver">
            <a href="contratos.php" class="btn btn-volver">
                <i class="fa-solid fa-arrow-left"></i>
                Volver a contratos
            </a>
        </div>

    </div>

</body>
</html>