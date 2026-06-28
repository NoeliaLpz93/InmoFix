<?php
require "../conection.php";

$id = $_GET["id"];

$sql = "SELECT * FROM inmuebles WHERE id = $id";
$inmueble = $conn->query($sql)->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Detalles del Inmueble</title>
    <link rel="stylesheet" href="../css/estilo.css?v=1">
</head>
<body>

<?php include "nav.php"; ?>

<div class="contenedor">

    <h2>Detalles del Inmueble</h2>

    <img src="../uploads/<?= $inmueble['imagen'] ?>" class="foto-detalle">

    <p><strong>Dirección:</strong> <?= $inmueble["direccion"] ?></p>
    <p><strong>Tipo:</strong> <?= $inmueble["tipo"] ?></p>
    <p><strong>Estado:</strong> <?= $inmueble["estado"] ?></p>
    <p><strong>Descripción:</strong> <?= $inmueble["descripcion"] ?></p>

    <div class="acciones-detalle">

        <!-- Boton que MODIFICA el Inmueble-->
        <a href="inmueble_modificar.php?id=<?= $inmueble['id'] ?>" class="btn">
            Modificar inmueble
        </a>

        <!-- Boton de ELIMINAR CON CONFIRMACIÓN  -->
        <a href="inmueble_eliminar.php?id=<?= $inmueble['id'] ?>"
           class="btn btn-rojo"
           onclick="return confirm('¿Seguro que deseas eliminar este inmueble? Esta acción no se puede deshacer.');">
            Eliminar inmueble
        </a>

        <!-- VOLVER -->
        <a href="inmuebles.php" class="btn btn-gris">
            Volver
        </a>

    </div>

</div>

</body>
</html>
