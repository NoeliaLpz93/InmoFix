<?php

require "conection.php";

$id = $_GET["id"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $direccion = $_POST["direccion"];
    $tipo = $_POST["tipo"];
    $estado = $_POST["estado"];
    $descripcion = $_POST["descripcion"];


    // SI SE SUBE UNA NUEVA IMAGEN
    if (!empty($_FILES["imagen"]["name"])) {

        $nombreImg = $_FILES["imagen"]["name"];
        $ruta = "../uploads/" . $nombreImg;

        move_uploaded_file(
            $_FILES["imagen"]["tmp_name"],
            $ruta
        );

        $update = "UPDATE inmuebles
                   SET Direccion='$direccion',
                       Tipo='$tipo',
                       Estado='$estado',
                       Descripcion='$descripcion',
                       imagen='$nombreImg'
                   WHERE IdInmueble=$id";

    } else {

        // SI NO SE SUBE IMAGEN, SE MANTIENE LA ANTERIOR
        $update = "UPDATE inmuebles
                   SET Direccion='$direccion',
                       Tipo='$tipo',
                       Estado='$estado',
                       Descripcion='$descripcion'
                   WHERE IdInmueble=$id";
    }


    $conn->query($update);

    header("Location: inmueble_detalles.php?id=$id");
    exit;
}

?>