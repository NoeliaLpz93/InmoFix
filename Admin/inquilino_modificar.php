<?php

require "verificar_admin.php";
require "conection.php";

$id = (int) $_GET["id"];


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $apellido = $_POST["apellido"];
    $nombre = $_POST["nombre"];
    $dni = $_POST["dni"];
    $telefono = $_POST["telefono"];
    $email = $_POST["email"];


    // MODIFICAR INQUILINO
    $update = "UPDATE inquilinos
               SET apellido = ?,
                   nombre = ?,
                   dni = ?,
                   telefono = ?,
                   email = ?
               WHERE id = ?";

    $stmt = $conn->prepare($update);
    $stmt->bind_param("sssssi", $apellido, $nombre, $dni, $telefono, $email, $id);

    if ($stmt->execute()) {
        header("Location: inquilinos.php");
        exit();
    } else {
        echo "Error al modificar el inquilino: " . $stmt->error;
    }

    $stmt->close();
}

?>