<?php
include("conection.php");

$mensaje = "";

if(isset($_POST["buscar"])){

    $email = $_POST["email"];

    $sql = "SELECT IdUsuario
            FROM usuarios
            WHERE Email = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s",$email);
    $stmt->execute();

    $resultado = $stmt->get_result();

    if($resultado->num_rows > 0){

        $usuario = $resultado->fetch_assoc();

        header("Location: nuevaPassword.php?id=".$usuario["IdUsuario"]);
        exit();

    }else{
        $mensaje = "No existe una cuenta con ese email";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Recuperar contraseña</title>
    <link rel="stylesheet" href="../Css/estilo.css">
</head>
<body>

<div class="login-contenedor">

    <div class="login-tarjeta">

        <div class="login-formulario">

            <h2>Recuperar contraseña</h2>

            <form method="POST">

                <input
                    type="email"
                    name="email"
                    placeholder="Ingrese su email"
                    required>

                <button
                    class="login-btn"
                    type="submit"
                    name="buscar">

                    Continuar

                </button>

            </form>

            <p><?= $mensaje ?></p>

        </div>

    </div>

</div>

</body>
</html>