<?php
require "../conection.php";

if ($_POST) {
    $apellido = $_POST["apellido"];
    $nombre = $_POST["nombre"];
    $dni = $_POST["dni"];
    $telefono = $_POST["telefono"];
    $email = $_POST["email"];

    $sql = "INSERT INTO inquilinos (apellido, nombre, dni, telefono, email)
            VALUES ('$apellido', '$nombre', '$dni', '$telefono', '$email')";

    $conn->query($sql);

    header("Location: inquilinos.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Agregar Inquilino</title>
</head>
<body>

<h2>Agregar nuevo inquilino</h2>

<form method="POST">
    <label>Apellido:</label>
    <input type="text" name="apellido" required><br>

    <label>Nombre:</label>
    <input type="text" name="nombre" required><br>

    <label>DNI:</label>
    <input type="text" name="dni" required><br>

    <label>Teléfono:</label>
    <input type="text" name="telefono"><br>

    <label>Email:</label>
    <input type="email" name="email"><br>

    <button type="submit">Guardar</button>
</form>

</body>
</html>
