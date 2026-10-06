<?php
    require "conection.php";

    $busqueda = $_GET["q"] ?? ""; // Guarda el texto buscado. Si no hay búsqueda, queda vacío.

    $sql = "SELECT * FROM inmuebles 
        WHERE direccion LIKE '%$busqueda%'"; // Consulta los inmuebles cuya dirección contenga el texto buscado.

    $resultado = $conn->query($sql); // Ejecuta la consulta y guarda los resultados.
?>


<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">
    <title>Inmuebles</title>

    <!-- la letra -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- css -->
    <link rel="stylesheet" href="../Css/estilo.css?v=1">

</head>

    <body>

    <?php include "nav.php"; ?> <!--Inserta la barra de navegación-->

        <div class="contenedor">

            <h2>Inmuebles</h2>

            <!-- BOTONES SUPERIORES -->
            <div class="acciones-superior">
                <a href="inmueble_agregar.php" class="btn">
                    <i class="fa-solid fa-plus"></i>
                    Agregar inmueble
                </a>

                <form method="GET" class="buscador">
                    <input type="text" name="q" placeholder="Buscar por dirección" value="<?= $busqueda ?>">
                        <button type="submit">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                </form>

                <a href="inmueble_eliminar.php" class="btn btn-rojo">
                    <i class="fa-solid fa-trash"></i>
                    Eliminar inmueble
                </a>
            </div>
    <!-- TARJETAS -->
            <div class="grid-inmuebles">

            <?php while ($fila = $resultado->fetch_assoc()) { ?>
            <!-- Recorre todos los inmuebles obtenidos de la base de datos.-->

            <div class="card-inmueble">

                <img src="../uploads/<?= $fila['imagen'] ?>" class="foto-inmueble">

            <div class="info">
                <h3><?= $fila["Direccion"] ?></h3>
                <p><?= $fila["Tipo"] ?></p>
            </div>

            <a href="inmueble_detalles.php?id=<?= $fila['IdInmueble'] ?>" class="btn-detalles">
                <i class="fa-solid fa-circle-info"></i>
                 Detalles
            </a>

        </div>

    <?php } ?>

    </div>

</body>
</html>
