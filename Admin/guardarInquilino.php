<?php

require "verificar_admin.php";
require "conection.php";

// VERIFICAR ENVÍO DEL FORMULARIO
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: inquilinos.php");
    exit();
}

// RECIBIR DATOS
$nombre   = trim($_POST["nombre"] ?? "");
$apellido = trim($_POST["apellido"] ?? "");
$dni      = trim($_POST["dni"] ?? "");
$email    = trim($_POST["email"] ?? "");
$passwordIngresada = $_POST["password"] ?? "";

// VALIDAR DATOS
if (
    empty($nombre) ||
    empty($apellido) ||
    empty($dni) ||
    empty($email) ||
    empty($passwordIngresada) ||
    !filter_var($email, FILTER_VALIDATE_EMAIL)
) {
    die("Error: verificá los datos ingresados.");
}

// CIFRAR CONTRASEÑA
$password = password_hash($passwordIngresada, PASSWORD_DEFAULT);

// INICIAR TRANSACCIÓN
$conn->begin_transaction();

try {

    // GUARDAR INQUILINO
    $sql = "INSERT INTO inquilinos
            (nombre, apellido, dni, email)
            VALUES (?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        throw new Exception($conn->error);
    }

    $stmt->bind_param(
        "ssss",
        $nombre,
        $apellido,
        $dni,
        $email
    );

    if (!$stmt->execute()) {
        throw new Exception($stmt->error);
    }

    // OBTENER ID DEL INQUILINO CREADO
    $idInquilino = $conn->insert_id;

    $stmt->close();

    // GUARDAR USUARIO
    $sqlUsuario = "INSERT INTO usuarios
                   (Nombre, Apellido, Email, Usuario, Password, Rol, idInquilino)
                   VALUES (?, ?, ?, ?, ?, 'Inquilino', ?)";

    $stmt2 = $conn->prepare($sqlUsuario);

    if (!$stmt2) {
        throw new Exception($conn->error);
    }

    $stmt2->bind_param(
        "sssssi",
        $nombre,
        $apellido,
        $email,
        $dni,
        $password,
        $idInquilino
    );

    if (!$stmt2->execute()) {
        throw new Exception($stmt2->error);
    }

    $stmt2->close();

    // CONFIRMAR TRANSACCIÓN
    $conn->commit();

    // VOLVER A LA LISTA
    header("Location: inquilinos.php");
    exit();

} catch (Exception $e) {

    // DESHACER CAMBIOS SI OCURRE UN ERROR
    $conn->rollback();

    die("Error al guardar el inquilino: " . $e->getMessage());
}

?>