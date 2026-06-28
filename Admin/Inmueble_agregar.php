<?php
require "../conection.php";

if ($_POST) {

    $direccion = $_POST["direccion"];
    $tipo = $_POST["tipo"];
    $estado = $_POST["estado"];
    $descripcion = $_POST["descripcion"];

    // SUBIR IMAGEN
    $nombreImg = $_FILES["imagen"]["name"];
    $ruta = "../uploads/" . $nombreImg;
    move_uploaded_file($_FILES["imagen"]["tmp_name"], $ruta);

    $sql = "INSERT INTO inmuebles (direccion, tipo, estado, descripcion, imagen)
            VALUES ('$direccion', '$tipo', '$estado', '$descripcion', '$nombreImg')";

    $conn->query($sql);

    header("Location: inmuebles.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Agregar Inmueble</title>
</head>
<body>

<h2>Agregar Inmueble</h2>

<form method="POST" enctype="multipart/form-data">

    <label>Dirección:</label>
    <input type="text" name="direccion" required><br>

    <label>Tipo:</label>
    <input type="text" name="tipo" required><br>

    <label>Estado:</label>
    <select name="estado">
        <option value="Disponible">Disponible</option>
        <option value="Alquilado">Alquilado</option>
        <option value="Suspendido">Suspendido</option>
    </select><br>

    <label>Descripción:</label>
    <textarea name="descripcion"></textarea><br>

    <label>Imagen:</label>
    <input type="file" name="imagen" required><br>

    <button type="submit">Guardar</button>
</form>

</body>
</html>
