<?php
require "conection.php";

$id = $_GET["id"];

$sql = "SELECT r.*, 
               u.Nombre AS NombreInquilino,
               u.Apellido AS ApellidoInquilino,
               i.Direccion AS DireccionInmueble
        FROM Reclamos r
        INNER JOIN Usuarios u ON r.IdInquilino = u.IdUsuario
        INNER JOIN Inmuebles i ON r.IdInmueble = i.IdInmueble
        WHERE r.IdReclamo = $id";

$reclamo = $conn->query($sql)->fetch_assoc();
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

    <h2>Detalle del Reclamo #<?= $reclamo["IdReclamo"] ?></h2>

    <p><strong>Estado:</strong> <?= $reclamo["Estado"] ?></p>
    <p><strong>Prioridad:</strong> <?= $reclamo["Prioridad"] ?></p>
    <p><strong>Fecha de creación:</strong> <?= $reclamo["FechaCreacion"] ?></p>
    <p><strong>Fecha de resolución:</strong> <?= $reclamo["FechaResolucion"] ?: "Sin resolver" ?></p>
    <p><strong>Inquilino:</strong> <?= $reclamo["NombreInquilino"] . " " . $reclamo["ApellidoInquilino"] ?></p>
    <p><strong>Inmueble:</strong> <?= $reclamo["DireccionInmueble"] ?></p>
    <p><strong>Categoría:</strong> <?= $reclamo["Categoria"] ?></p>
    <p><strong>Descripción:</strong> <?= $reclamo["Descripcion"] ?></p>

    <div class="acciones-detalle">
        <a href="reclamo_estado.php?id=<?= $reclamo['IdReclamo'] ?>" class="btn btn-naranja">
            Cambiar Estado
        </a>

        <a href="reclamo_eliminar.php?id=<?= $reclamo['IdReclamo'] ?>"
           class="btn btn-rojo"
           onclick="return confirm('¿Seguro que deseas eliminar este reclamo?');">
            Eliminar Reclamo
        </a>

        <a href="reclamos.php" class="btn btn-gris">Volver</a>
    </div>

</div>

</body>
</html>
