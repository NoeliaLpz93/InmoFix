<?php
require "conection.php";

$id = $_GET["id"];

// Obtener datos actuales
$sql = "SELECT * FROM inmuebles WHERE id = $id";
$inmueble = $conn->query($sql)->fetch_assoc();

if ($_POST) {

    $direccion = $_POST["direccion"];
    $tipo = $_POST["tipo"];
    $estado = $_POST["estado"];
    $descripcion = $_POST["descripcion"];

    // Si se sube una nueva imagen
    if (!empty($_FILES["imagen"]["name"])) {

        $nombreImg = $_FILES["imagen"]["name"];
        $ruta = "../uploads/" . $nombreImg;

        move_uploaded_file($_FILES["imagen"]["tmp_name"], $ruta);

        $update = "UPDATE inmuebles 
                   SET direccion='$direccion',
                       tipo='$tipo',
                       estado='$estado',
                       descripcion='$descripcion',
                       imagen='$nombreImg'
                   WHERE id=$id";

    } else {
        // Si NO sube imagen, se mantiene la anterior
        $update = "UPDATE inmuebles 
                   SET direccion='$direccion',
                       tipo='$tipo',
                       estado='$estado',
                       descripcion='$descripcion'
                   WHERE id=$id";
    }

    $conn->query($update);

    header("Location: inmueble_detalles.php?id=$id");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Modificar Inmueble</title>
    <link rel="stylesheet" href="../css/estilo.css?v=1">
</head>
<body>

<?php include "nav.php"; ?>

<div class="contenedor">

    <h2>Modificar Inmueble</h2>

    <form method="POST" enctype="multipart/form-data">

        <label>Dirección:</label>
        <input type="text" name="direccion" value="<?= $inmueble['direccion'] ?>" required><br>

        <label>Tipo:</label>
        <input type="text" name="tipo" value="<?= $inmueble['tipo'] ?>" required><br>

        <label>Estado:</label>
        <select name="estado">
            <option value="Disponible" <?= $inmueble['estado']=="Disponible" ? "selected" : "" ?>>Disponible</option>
            <option value="Alquilado" <?= $inmueble['estado']=="Alquilado" ? "selected" : "" ?>>Alquilado</option>
            <option value="Suspendido" <?= $inmueble['estado']=="Suspendido" ? "selected" : "" ?>>Suspendido</option>
        </select><br>

        <label>Descripción:</label>
        <textarea name="descripcion"><?= $inmueble['descripcion'] ?></textarea><br>

        <label>Imagen actual:</label><br>
        <img src="../uploads/<?= $inmueble['imagen'] ?>" width="250" style="border-radius:10px;"><br><br>

        <label>Subir nueva imagen (opcional):</label>
        <input type="file" name="imagen"><br><br>

        <button type="submit">Guardar cambios</button>
    </form>

    <br>
    <a href="inmueble_detalles.php?id=<?= $id ?>" class="btn btn-gris">Volver</a>

</div>

</body>
</html>
