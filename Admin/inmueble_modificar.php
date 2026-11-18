<?php

require "verificar_admin.php";
require "conection.php";

$id = (int) ($_GET["id"] ?? 0);

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $direccion = trim($_POST["direccion"] ?? "");
    $tipo = $_POST["tipo"] ?? "";
    $estado = $_POST["estado"] ?? "";
    $descripcion = trim($_POST["descripcion"] ?? "");

    // VERIFICAR SI SE SUBIÓ UNA NUEVA IMAGEN
    if (isset($_FILES["imagen"]) && $_FILES["imagen"]["error"] !== UPLOAD_ERR_NO_FILE) {

        // COMPROBAR ERRORES DE SUBIDA
        if ($_FILES["imagen"]["error"] !== UPLOAD_ERR_OK) {
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

        // GUARDAR IMAGEN
        $directorio = __DIR__ . "/../uploads/";
        $ruta = $directorio . $nombreImg;

        if (!move_uploaded_file($imagen["tmp_name"], $ruta)) {
            die("Error: no se pudo guardar la imagen.");
        }

        // ACTUALIZAR INMUEBLE CON LA NUEVA IMAGEN
        $update = "UPDATE inmuebles
                   SET Direccion = ?,
                       Tipo = ?,
                       Estado = ?,
                       Descripcion = ?,
                       imagen = ?
                   WHERE IdInmueble = ?";

        $stmt = $conn->prepare($update);

        if (!$stmt) {
            unlink($ruta);
            die("Error al preparar la consulta.");
        }

        $stmt->bind_param(
            "sssssi",
            $direccion,
            $tipo,
            $estado,
            $descripcion,
            $nombreImg,
            $id
        );

        if (!$stmt->execute()) {
            unlink($ruta);
            echo "Error al modificar el inmueble: " . $stmt->error;
            $stmt->close();
            exit();
        }

    } else {

        // SI NO SE SUBE IMAGEN, SE MANTIENE LA ANTERIOR
        $update = "UPDATE inmuebles
                   SET Direccion = ?,
                       Tipo = ?,
                       Estado = ?,
                       Descripcion = ?
                   WHERE IdInmueble = ?";

        $stmt = $conn->prepare($update);

        if (!$stmt) {
            die("Error al preparar la consulta.");
        }

        $stmt->bind_param(
            "ssssi",
            $direccion,
            $tipo,
            $estado,
            $descripcion,
            $id
        );

        if (!$stmt->execute()) {
            echo "Error al modificar el inmueble: " . $stmt->error;
            $stmt->close();
            exit();
        }
    }

    $stmt->close();

    header("Location: inmueble_detalles.php?id=$id");
    exit();
}

?>