<?php

require "conection.php";

$id = $_GET["id"];


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $apellido = $_POST["apellido"];
    $nombre = $_POST["nombre"];
    $dni = $_POST["dni"];
    $telefono = $_POST["telefono"];
    $email = $_POST["email"];


    // MODIFICAR INQUILINO
    $update = "UPDATE inquilinos
               SET apellido='$apellido',
                   nombre='$nombre',
                   dni='$dni',
                   telefono='$telefono',
                   email='$email'
               WHERE id=$id";

    $conn->query($update);


    header("Location: inquilinos.php");
    exit;
}

?>