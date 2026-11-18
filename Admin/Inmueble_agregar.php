<?php

require "verificar_admin.php";
require "conection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // RECIBIR DATOS
    $direccion = trim($_POST["direccion"] ?? "");
    $tipo = $_POST["tipo"] ?? "";
    $estado = $_POST["estado"] ?? "";
    $descripcion = trim($_POST["descripcion"] ?? "");

    // VALIDAR QUE SE HAYA SUBIDO UNA IMAGEN
    if (!isset($_FILES["imagen"]) || $_FILES["imagen"]["error"] !== UPLOAD_ERR_OK) {
        die("Error: no se pudo subir la imagen.");
    }

    $imagen = $_FILES["imagen"];

    // VALIDAR TAMAÑO (MÁXIMO 5 MB)
    $tamanoMaximo = 5 * 1024 * 1024;

    if ($imagen["size"] > $tamanoMaximo) {
        die("Error: la imagen no puede superar los 5 MB.");
    }

    // VALIDAR EXTENSIÓN
    $extensionesPermitidas = ["jpg", "jpeg", "png", "webp"];

    $extension = strtolower(pathinfo($imagen["name"], PATHINFO_EXTENSION));

    if (!in_array($extension, $extensionesPermitidas, true)) {
        die("Error: formato de imagen no permitido.");
    }

    // VALIDAR TIPO MIME REAL
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $tipoMime = $finfo->file($imagen["tmp_name"]);

    $tiposPermitidos = [
        "jpg" => "image/jpeg",
        "jpeg" => "image/jpeg",
        "png" => "image/png",
        "webp" => "image/webp"
    ];

    if (!isset($tiposPermitidos[$extension]) ||
        $tipoMime !== $tiposPermitidos[$extension]) {
        die("Error: el archivo no es una imagen válida.");
    }

    // GENERAR NOMBRE ÚNICO
    $nombreImg = uniqid("inmueble_", true) . "." . $extension;

    // RUTA DE DESTINO
    $directorio = __DIR__ . "/../uploads/";
    $ruta = $directorio . $nombreImg;

    // MOVER IMAGEN AL DIRECTORIO
    if (!move_uploaded_file($imagen["tmp_name"], $ruta)) {
        die("Error: no se pudo guardar la imagen.");
    }

    // GUARDAR INMUEBLE EN LA BASE DE DATOS
    $sql = "INSERT INTO inmuebles
            (Direccion, Tipo, Estado, Descripcion, imagen)
            VALUES (?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        unlink($ruta);
        die("Error al preparar la consulta.");
    }

    $stmt->bind_param(
        "sssss",
        $direccion,
        $tipo,
        $estado,
        $descripcion,
        $nombreImg
    );

    if ($stmt->execute()) {

        $stmt->close();

        header("Location: Inmuebles.php");
        exit();

    } else {

        // ELIMINAR LA IMAGEN SI NO SE PUDO GUARDAR EL INMUEBLE
        unlink($ruta);

        echo "Error al guardar el inmueble: " . $stmt->error;
    }

    $stmt->close();
}

?>