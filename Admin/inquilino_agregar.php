<?php

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
            VALUES
            ('$apellido', '$nombre', '$dni', '$telefono', '$email')";

    $conn->query($sql);

    header("Location: inquilinos.php");
    exit;
}

?>