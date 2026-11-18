<?php

require "verificar_admin.php";
require "conection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $apellido = $_POST["apellido"];
    $nombre = $_POST["nombre"];
    $dni = $_POST["dni"];
    $telefono = $_POST["telefono"];
    $email = $_POST["email"];


    // GUARDAR INQUILINO EN LA BASE DE DATOS
    $sql = "INSERT INTO inquilinos
            (apellido, nombre, dni, telefono, email)
            VALUES (?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $apellido, $nombre, $dni, $telefono, $email);

    if ($stmt->execute()) {
        header("Location: inquilinos.php");
        exit();
    } else {
        echo "Error al guardar el inquilino: " . $stmt->error;
    }

    $stmt->close();
}

?>