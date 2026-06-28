<?php
require "conection.php";

$id = $_GET["id"];

$sql = "DELETE FROM Reclamos WHERE IdReclamo = $id";
$conn->query($sql);

header("Location: reclamos.php");
exit;
