<?php
require "conection.php";

$id = $_GET["id"];

$sql = "DELETE FROM inmuebles WHERE id = $id";
$conn->query($sql);

header("Location: inmuebles.php");
exit;
