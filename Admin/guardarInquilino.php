<?php

include("conection.php");

$nombre   = $_POST["nombre"];
$apellido = $_POST["apellido"];
$dni      = $_POST["dni"];
$email    = $_POST["email"];
$password = $_POST["password"];

/* Guardar inquilino */

$sql = "INSERT INTO inquilinos
(nombre, apellido, dni, email)
VALUES (?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "ssss",
    $nombre,
    $apellido,
    $dni,
    $email
);

$stmt->execute();

$idInquilino = $conn->insert_id;

/* Guardar usuario */

$sqlUsuario = "INSERT INTO usuarios
(Nombre, Apellido, Email, Usuario, Password, Rol, idInquilino)
VALUES (?, ?, ?, ?, ?, 'Inquilino', ?)";

$stmt2 = $conn->prepare($sqlUsuario);

$stmt2->bind_param(
    "sssssi",
    $nombre,
    $apellido,
    $email,
    $dni,
    $password,
    $idInquilino
);

$stmt2->execute();

/* Volver a la lista */

header("Location: inquilinos.php");
exit();