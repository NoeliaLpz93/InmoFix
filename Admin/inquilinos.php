<?php
require "../conexion.php";

$busqueda = $_GET["q"] ?? "";

$sql = "SELECT * FROM inquilinos 
        WHERE nombre LIKE '%$busqueda%' 
        OR apellido LIKE '%$busqueda%'";

$resultado = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inquilinos</title>
    <link rel="stylesheet" href="../css/estilo.css?v=1">
</head>

<body>

<?php include "nav.php"; ?>

<div class="contenedor">

    <h2>Inquilinos</h2>

    <!-- BUSCADOR -->
    <form method="GET" class="buscador">
        <input type="text" name="q" placeholder="Buscar por nombre o apellido" value="<?= $busqueda ?>">
        <button type="submit">Buscar</button>
    </form>

    <!-- TABLA -->
    <table class="tabla">
        <thead>
            <tr>
                <th>ID</th>
                <th>Apellido</th>
                <th>Nombre</th>
                <th>DNI</th>
                <th>Teléfono</th>
                <th>Email</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>
            <?php while ($fila = $resultado->fetch_assoc()) { ?>
                <tr>
                    <td><?= $fila["id"] ?></td>
                    <td><?= $fila["apellido"] ?></td>
                    <td><?= $fila["nombre"] ?></td>
                    <td><?= $fila["dni"] ?></td>
                    <td><?= $fila["telefono"] ?></td>
                    <td><?= $fila["email"] ?></td>

                    <td>
                        <a href="inquilino_modificar.php?id=<?= $fila['id'] ?>" class="btn">Editar</a>
                        <a href="inquilino_eliminar.php?id=<?= $fila['id'] ?>" class="btn btn-rojo">Eliminar</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <!-- BOTÓN CARGAR -->
    <div class="acciones">
        <a href="inquilino_cargar.php" class="btn">Cargar nuevo inquilino</a>
    </div>

</div>
</body>
</html>
