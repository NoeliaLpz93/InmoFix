<?php
require "conection.php";

$id = $_GET["id"]; // obtiene el id del inquilino a borrar

$sql = "DELETE FROM inquilinos WHERE id = $id";
$conn->query($sql);

header("Location: inquilinos.php"); //redireccionar
exit;
