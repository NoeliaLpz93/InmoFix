<?php
require "../conection.php";

$busqueda = $_GET["q"] ?? "";

$sql = "SELECT * FROM inmuebles 
        WHERE direccion LIKE '%$busqueda%'";

$resultado = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inmuebles</title>
    <link rel="stylesheet" href="../css/estilo.css?v=1">
</head>

<body>

<?php include "nav.php"; ?>

<div class="contenedor">

    <h2>Inmuebles</h2>

    <!-- BOTONES SUPERIORES -->
    <div class="acciones-superior">
        <a href="inmueble_cargar.php" class="btn">Agregar inmueble</a>

        <form method="GET" class="buscador">
            <input type="text" name="q" placeholder="Buscar por dirección" value="<?= $busqueda ?>">
            <button type="submit">Buscar</button>
        </form>

        <a href="inmueble_eliminar.php" class="btn btn-rojo">Eliminar inmueble</a>
    </div>

    <!-- TARJETAS -->
    <div class="grid-inmuebles">
        <?php while ($fila = $resultado->fetch_assoc()) { ?>
            <div class="card-inmueble">

                <img src="../uploads/<?= $fila['imagen'] ?>" class="foto-inmueble">

                <div class="info">
                    <h3><?= $fila["direccion"] ?></h3>
                    <p><?= $fila["tipo"] ?></p>
                </div>

                <a href="inmueble_detalles.php?id=<?= $fila['id'] ?>" class="btn-detalles">
                    Detalles
                </a>

            </div>
        <?php } ?>
    </div>

</div>
</body>
</html>
