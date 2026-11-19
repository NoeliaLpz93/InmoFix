<?php

include("conection.php");

$id = $_GET["id"];

if(isset($_POST["guardar"])){

    $password = password_hash(
        $_POST["password"],
        PASSWORD_DEFAULT
    );

    $sql = "UPDATE usuarios
            SET Password = ?
            WHERE IdUsuario = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si",$password,$id);
    $stmt->execute();

    header("Location: loginAdmin.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Nueva contraseña</title>
    <link rel="stylesheet" href="../Css/estilo.css">
</head>
<body>

<div class="login-contenedor">

    <div class="login-tarjeta">

        <div class="login-formulario">

            <h2>Nueva contraseña</h2>

            <form method="POST">

                <input
                    type="password"
                    name="password"
                    placeholder="Nueva contraseña"
                    required>

                <button
                    class="login-btn"
                    type="submit"
                    name="guardar">

                    Guardar contraseña

                </button>

            </form>

        </div>

    </div>

</div>

</body>
</html>