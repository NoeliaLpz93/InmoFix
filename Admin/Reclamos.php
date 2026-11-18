<?php

require "verificar_admin.php";
require "conection.php";

$busqueda = $_GET["q"] ?? "";

// Reclamos + datos del inquilino + datos del inmueble
$sql = "SELECT r.*,
               q.nombre AS NombreInquilino,
               q.apellido AS ApellidoInquilino,
               i.Direccion AS DireccionInmueble
        FROM Reclamos r
        INNER JOIN inquilinos q ON r.IdInquilino = q.id
        INNER JOIN Inmuebles i ON r.IdInmueble = i.IdInmueble
        WHERE q.nombre LIKE ?
        OR q.apellido LIKE ?
        OR i.Direccion LIKE ?";

// Ejecutamos la consulta
$stmt = $conn->prepare($sql);

$termino = "%" . $busqueda . "%";

$stmt->bind_param("sss", $termino, $termino, $termino);
$stmt->execute();

$resultado = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reclamos</title>
    <!-- la letra -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- css -->
    <link rel="stylesheet" href="../css/estilo.css?v=1">
</head>

<body>

<?php include "nav.php"; ?>

<div class="contenedor">

    <h2>Reclamos</h2>
    
<!-- CARGAR RECLAMO --> 
 
<div class="acciones-superior">

    <a href="reclamos_cargar.php" class="btn">
    <i class="fa-solid fa-plus"></i>
     Agregar reclamo
</a>


    <!-- BUSCADOR -->
    <form method="GET" class="buscador">
          <!-- Formulario de búsqueda. -->
        <input type="text" name="q" placeholder="Buscar por nombre o dirección" value="<?= htmlspecialchars($busqueda, ENT_QUOTES, 'UTF-8') ?>">
        <button type="submit">Buscar</button>
    </form>
    </div>

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
                // Asocia cada estado del reclamo con una clase CSS.
                // Luego esa clase cambia el color del texto o del fondo.
                // Colores según estado
                $colorEstado = [
                    "Pendiente" => "estado-pendiente",
                    "En proceso" => "estado-proceso",
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

                    <td class="<?= htmlspecialchars($colorEstado[$fila["Estado"]] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                        <?= htmlspecialchars($fila["Estado"], ENT_QUOTES, 'UTF-8') ?>
                    </td>

                    <td class="<?= htmlspecialchars($colorPrioridad[$fila["Prioridad"]] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                        <?= htmlspecialchars($fila["Prioridad"], ENT_QUOTES, 'UTF-8') ?>
                    </td>

                    <td><?= htmlspecialchars($fila["FechaCreacion"], ENT_QUOTES, 'UTF-8') ?></td>

                    <td><?= htmlspecialchars($fila["NombreInquilino"] . " " . $fila["ApellidoInquilino"], ENT_QUOTES, 'UTF-8') ?></td>

                    <td><?= htmlspecialchars($fila["DireccionInmueble"], ENT_QUOTES, 'UTF-8') ?></td>

                    <td><?= htmlspecialchars($fila["Categoria"], ENT_QUOTES, 'UTF-8') ?></td>

                    <td>
                        <a href="reclamos_detalle.php?id=<?= $fila['IdReclamo'] ?>" class="btn">Detalle</a>
                        <a href="reclamos_estado.php?id=<?= $fila['IdReclamo'] ?>" class="btn btn-naranja">Cambiar Estado</a>
                    </td>
                </tr>

            <?php } ?>
        </tbody>
    </table>

</div>
</body>
</html>
