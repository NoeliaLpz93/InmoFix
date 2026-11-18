<?php

require "verificar_admin.php";
require "conection.php";

$id = (int) $_GET["id"];

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
                   SET Direccion = ?,
                       Tipo = ?,
                       Estado = ?,
                       Descripcion = ?,
                       imagen = ?
                   WHERE IdInmueble = ?";

        $stmt = $conn->prepare($update);
        $stmt->bind_param(
            "sssssi",
            $direccion,
            $tipo,
            $estado,
            $descripcion,
            $nombreImg,
            $id
        );

    } else {

        // SI NO SE SUBE IMAGEN, SE MANTIENE LA ANTERIOR
        $update = "UPDATE inmuebles
                   SET Direccion = ?,
                       Tipo = ?,
                       Estado = ?,
                       Descripcion = ?
                   WHERE IdInmueble = ?";

        $stmt = $conn->prepare($update);
        $stmt->bind_param(
            "ssssi",
            $direccion,
            $tipo,
            $estado,
            $descripcion,
            $id
        );
    }


    if ($stmt->execute()) {
        header("Location: inmueble_detalles.php?id=$id");
        exit();
    } else {
        echo "Error al modificar el inmueble: " . $stmt->error;
    }

    $stmt->close();
}

?>