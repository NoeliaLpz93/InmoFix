<?php
require "conection.php";

$busqueda = $_GET["q"] ?? "";

// Reclamos + datos del inquilino + datos del inmueble
$sql = "SELECT r.*, 
               u.Nombre AS NombreInquilino,
               u.Apellido AS ApellidoInquilino,
               i.Direccion AS DireccionInmueble
        FROM Reclamos r
        INNER JOIN Usuarios u ON r.IdInquilino = u.IdUsuario
        INNER JOIN Inmuebles i ON r.IdInmueble = i.IdInmueble
        WHERE u.Nombre LIKE '%$busqueda%'
        OR u.Apellido LIKE '%$busqueda%'
        OR i.Direccion LIKE '%$busqueda%'";

$resultado = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reclamos</title>
    <link rel="stylesheet" href="../css/estilo.css?v=1">
</head>

<body>

<?php include "nav.php"; ?>

<div class="contenedor">

    <h2>Reclamos</h2>

    <!-- BUSCADOR -->
    <form method="GET" class="buscador">
        <input type="text" name="q" placeholder="Buscar por nombre o dirección" value="<?= $busqueda ?>">
        <button type="submit">Buscar</button>
    </form>

    <!-- TABLA -->
    <table class="tabla">
        <thead>
            <tr>
                <th>N° Reclamo</th>
                <th>Estado</th>
                <th>Prioridad</th>
                <th>Fecha</th>
                <th>Inquilino</th>
                <th>Inmueble</th>
                <th>Categoría</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>
            <?php while ($fila = $resultado->fetch_assoc()) { ?>

                <?php
                // Colores según estado
                $colorEstado = [
                    "Pendiente" => "estado-pendiente",
                    "En Proceso" => "estado-proceso",
                    "Resuelto" => "estado-resuelto"
                ];

                // Colores según prioridad
                $colorPrioridad = [
                    "Alta" => "prioridad-alta",
                    "Media" => "prioridad-media",
                    "Baja" => "prioridad-baja"
                ];
                ?>

                <tr>
                    <td>#<?= $fila["IdReclamo"] ?></td>

                    <td class="<?= $colorEstado[$fila["Estado"]] ?>">
                        <?= $fila["Estado"] ?>
                    </td>

                    <td class="<?= $colorPrioridad[$fila["Prioridad"]] ?>">
                        <?= $fila["Prioridad"] ?>
                    </td>

                    <td><?= $fila["FechaCreacion"] ?></td>

                    <td><?= $fila["NombreInquilino"] . " " . $fila["ApellidoInquilino"] ?></td>

                    <td><?= $fila["DireccionInmueble"] ?></td>

                    <td><?= $fila["Categoria"] ?></td>

                    <td>
                        <a href="reclamo_detalles.php?id=<?= $fila['IdReclamo'] ?>" class="btn">Detalle</a>
                        <a href="reclamo_estado.php?id=<?= $fila['IdReclamo'] ?>" class="btn btn-naranja">Cambiar Estado</a>
                    </td>
                </tr>

            <?php } ?>
        </tbody>
    </table>

</div>
</body>
</html>
