<?php

require "verificar_admin.php";
require "conection.php";

// Validar el ID recibido por GET
$id = filter_var($_GET["id"] ?? null, FILTER_VALIDATE_INT, [
    "options" => ["min_range" => 1]
]);

$reclamo = null;

if ($id !== false && $id !== null) {

    // Obtener toda la información del reclamo
    $sql = "SELECT r.*,
                   q.nombre AS NombreInquilino,
                   q.apellido AS ApellidoInquilino,
                   i.Direccion AS DireccionInmueble
            FROM Reclamos r
            INNER JOIN inquilinos q ON r.IdInquilino = q.id
            INNER JOIN Inmuebles i ON r.IdInmueble = i.IdInmueble
            WHERE r.IdReclamo = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $reclamo = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>InmoFix - Detalle del reclamo</title>

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/estilo.css?v=1">
</head>

<body>

    <?php include "nav.php"; ?>

    <div class="contenedor detalle-contenedor">

        <h2>Detalle del reclamo</h2>

        <div class="detalle-info-card">

            <?php if ($reclamo): ?>

                <div class="detalle-info-header">
                    <h3>Reclamo #<?= (int) $reclamo["IdReclamo"] ?></h3>

                    <span class="estado-inmueble">
                        <?= htmlspecialchars($reclamo["Estado"], ENT_QUOTES, 'UTF-8') ?>
                    </span>
                </div>

                <div class="detalle-dato">
                    <span class="detalle-label">Prioridad</span>
                    <p><?= htmlspecialchars($reclamo["Prioridad"], ENT_QUOTES, 'UTF-8') ?></p>
                </div>

                <div class="detalle-dato">
                    <span class="detalle-label">Fecha de creación</span>
                    <p><?= htmlspecialchars($reclamo["FechaCreacion"], ENT_QUOTES, 'UTF-8') ?></p>
                </div>

                <div class="detalle-dato">
                    <span class="detalle-label">Fecha de resolución</span>
                    <p><?= htmlspecialchars($reclamo["FechaResolucion"] ?: "Sin resolver", ENT_QUOTES, 'UTF-8') ?></p>
                </div>

                <div class="detalle-dato">
                    <span class="detalle-label">Inquilino</span>
                    <p><?= htmlspecialchars($reclamo["NombreInquilino"] . " " . $reclamo["ApellidoInquilino"], ENT_QUOTES, 'UTF-8') ?></p>
                </div>

                <div class="detalle-dato">
                    <span class="detalle-label">Inmueble</span>
                    <p><?= htmlspecialchars($reclamo["DireccionInmueble"], ENT_QUOTES, 'UTF-8') ?></p>
                </div>

                <div class="detalle-dato">
                    <span class="detalle-label">Categoría</span>
                    <p><?= htmlspecialchars($reclamo["Categoria"], ENT_QUOTES, 'UTF-8') ?></p>
                </div>

                <div class="detalle-dato">
                    <span class="detalle-label">Descripción</span>
                    <p><?= htmlspecialchars($reclamo["Descripcion"], ENT_QUOTES, 'UTF-8') ?></p>
                </div>

                <div class="detalle-acciones">

                    <a href="reclamos_eliminar.php?id=<?= (int) $reclamo["IdReclamo"] ?>"
                       class="btn btn-rojo"
                       onclick="return confirm('¿Seguro que deseas eliminar este reclamo?');">
                        <i class="fa-solid fa-trash"></i>
                        Eliminar reclamo
                    </a>

                </div>

            <?php else: ?>

                <div class="detalle-info-header">
                    <h3>Reclamo no encontrado</h3>
                </div>

                <p class="login-error">
                    No existe un reclamo con ese identificador.
                </p>

            <?php endif; ?>

        </div>

        <div class="detalle-volver">
            <a href="Reclamos.php" class="btn btn-volver">
                <i class="fa-solid fa-arrow-left"></i>
                Volver a reclamos
            </a>
        </div>

    </div>

</body>
</html>