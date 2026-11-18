<?php

require "verificar_admin.php";
require "conection.php";

$id = (int) $_GET["id"];

$sql = "DELETE FROM inquilinos
        WHERE id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: inquilinos.php");
    exit();
} else {
    echo "Error al eliminar el inquilino: " . $stmt->error;
}

$stmt->close();

?>