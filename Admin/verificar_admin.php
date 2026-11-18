<?php

session_start();

if (
    !isset($_SESSION["idUsuario"]) ||
    !isset($_SESSION["rol"]) ||
    $_SESSION["rol"] !== "Administrador"
) {
    header("Location: loginAdmin.php");
    exit();
}

?>