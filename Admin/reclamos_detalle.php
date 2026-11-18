<?php

require "verificar_admin.php";
require "conection.php";

$id = $_GET["id"];
// Consulta SQL para obtener toda la información del reclamo.
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
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Detalle del Reclamo</title>
    <link rel="stylesheet" href="../css/estilo.css?v=1">
</head>
<body>

<?php include "nav.php"; ?>

<div class="contenedor">
<!-- Mostramos el número del reclamo -->
    <h2>Detalle del Reclamo #<?= (int) $reclamo["IdReclamo"] ?></h2>

    <p><strong>Estado:</strong> <?= htmlspecialchars($reclamo["Estado"], ENT_QUOTES, 'UTF-8') ?></p>

    <p><strong>Prioridad:</strong> <?= htmlspecialchars($reclamo["Prioridad"], ENT_QUOTES, 'UTF-8') ?></p>

    <p><strong>Fecha de creación:</strong> <?= htmlspecialchars($reclamo["FechaCreacion"], ENT_QUOTES, 'UTF-8') ?></p>

    <p><strong>Fecha de resolución:</strong> <?= htmlspecialchars($reclamo["FechaResolucion"] ?: "Sin resolver", ENT_QUOTES, 'UTF-8') ?></p>

    <p><strong>Inquilino:</strong> <?= htmlspecialchars($reclamo["NombreInquilino"] . " " . $reclamo["ApellidoInquilino"], ENT_QUOTES, 'UTF-8') ?></p>

    <p><strong>Inmueble:</strong> <?= htmlspecialchars($reclamo["DireccionInmueble"], ENT_QUOTES, 'UTF-8') ?></p>

    <p><strong>Categoría:</strong> <?= htmlspecialchars($reclamo["Categoria"], ENT_QUOTES, 'UTF-8') ?></p>

    <p><strong>Descripción:</strong> <?= htmlspecialchars($reclamo["Descripcion"], ENT_QUOTES, 'UTF-8') ?></p>
 <!-- modificamos el estado del reclamo -->
    <div class="acciones-detalle">
        <a href="reclamos_estado.php?id=<?= (int) $reclamo['IdReclamo'] ?>" class="btn btn-naranja">
            Cambiar Estado
        </a>
<!-- eliminamos el estado del reclamo -->
        <a href="reclamos_eliminar.php?id=<?= (int) $reclamo['IdReclamo'] ?>"
           class="btn btn-rojo"
           onclick="return confirm('¿Seguro que deseas eliminar este reclamo?');">
            Eliminar Reclamo
        </a>
 <!-- Regresamos al listado de reclamos -->
        <a href="reclamos.php" class="btn btn-gris">Volver</a>
    </div>

</div>

</body>
</html>
