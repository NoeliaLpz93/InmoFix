<?php

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
            VALUES
            ('$direccion', '$tipo', '$estado', '$descripcion', '$nombreImg')";

    $conn->query($sql);

    header("Location: Inmuebles.php");
    exit;
}
?>