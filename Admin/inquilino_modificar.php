<?php
require "conection.php";

$id = $_GET["id"]; //obtener el id del inquilino a modificar

$sql = "SELECT * FROM inquilinos WHERE id = $id"; //buscar todos los datos del inquilino con ese id
$inquilino = $conn->query($sql)->fetch_assoc(); //ejecutar y tomar la primera fila para guardar en la variable

if ($_POST) {
    $apellido = $_POST["apellido"];
    $nombre = $_POST["nombre"];
    $dni = $_POST["dni"];
    $telefono = $_POST["telefono"];
    $email = $_POST["email"];

    $update = "UPDATE inquilinos 
               SET apellido='$apellido', nombre='$nombre', dni='$dni',
                   telefono='$telefono', email='$email'
               WHERE id=$id"; // modificar registro existente

    $conn->query($update);

    header("Location: inquilinos.php"); // redireccionar
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Modificar Inquilino</title>
</head>
<body>

<h2>Modificar Inquilino</h2>

<form method="POST">
    <label>Apellido:</label>
    <input type="text" name="apellido" value="<?= $inquilino['apellido'] ?>" required><br>

    <label>Nombre:</label>
    <input type="text" name="nombre" value="<?= $inquilino['nombre'] ?>" required><br>

    <label>DNI:</label>
    <input type="text" name="dni" value="<?= $inquilino['dni'] ?>" required><br>

    <label>Teléfono:</label>
    <input type="text" name="telefono" value="<?= $inquilino['telefono'] ?>"><br>

    <label>Email:</label>
    <input type="email" name="email" value="<?= $inquilino['email'] ?>"><br>

    <button type="submit">Guardar cambios</button>
</form>

</body>
</html>
