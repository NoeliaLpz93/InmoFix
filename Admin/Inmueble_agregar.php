<?php

require "verificar_admin.php";
require "conection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $direccion = $_POST["direccion"];
    $tipo = $_POST["tipo"];
    $estado = $_POST["estado"];
    $descripcion = $_POST["descripcion"];

    // SUBIR IMAGEN
    $nombreImg = $_FILES["imagen"]["name"];
    $ruta = "../uploads/" . $nombreImg;

    move_uploaded_file(
        $_FILES["imagen"]["tmp_name"],
        $ruta
    );

    // GUARDAR INMUEBLE EN LA BASE DE DATOS
    $sql = "INSERT INTO inmuebles
            (Direccion, Tipo, Estado, Descripcion, imagen)
            VALUES (?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "sssss",
        $direccion,
        $tipo,
        $estado,
        $descripcion,
        $nombreImg
    );

    if ($stmt->execute()) {
        header("Location: Inmuebles.php");
        exit();
    } else {
        echo "Error al guardar el inmueble: " . $stmt->error;
    }

    $stmt->close();
}

?>