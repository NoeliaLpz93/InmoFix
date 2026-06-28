<?php
require "../conection.php";

$id = $_GET["id"];

$sql = "DELETE FROM inquilinos WHERE id = $id";
$conn->query($sql);

header("Location: inquilinos.php");
exit;
