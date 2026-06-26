<?php
/* Este if se pregunta si el usuario envió el formulario */
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usuario = $_POST["usuario"];
    $clave = $_POST["clave"];

    /* Ejemplo temporal de validación
     Después acá va la consulta a la base de datos */

    if ($usuario === "inquilino" && $clave === "1234") {

        header("Location: panelInq.php");
        exit();

    } else {
        $error = "Usuario o contraseña incorrecto";

    }

}

?>


<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">

<title>Login Inquilino</title>
<!-- la fuente de google fonts -->
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600&display=swap" rel="stylesheet">

<link rel="stylesheet" href="../css/estilo.css?v=1">

</head>


<body>

<div class="login-contenedor">

    <!-- el boton para volver al index -->
    <a class="login-flecha" href="../index.php">
    ←
    </a>

    <div class="login-banner"> <!-- el logo y slogan -->

    <img src="../img/inmofix-02.png" alt="logo">

    <h2>
    Gestión inmobiliaria inteligente
    </h2>

    </div>


    <div class="login-formulario">
        <h3>
        Acceso Inquilino
        </h3>

        <?php if (!empty($error)): ?>


            <p class="login-error">

                <?= $error ?>

            </p>


        <?php endif; ?>


        <form method="POST">

            <label>
                Usuario
            </label>

            <input 
                type="text"
                name="usuario"
                placeholder="ingrese su usuario"
                required>

            <label>
                Contraseña
            </label>

            <input 
                type="password"
                name="clave"
                placeholder="Ingrese su contraseña"
                required>

            <a class="login-olvide" href="recuperar.php">
                Olvidé mi contraseña
            </a>

            <button class="login-btn" type="submit">
                Ingresar
            </button>

        </form>


    </div>

</div>

</body>

</html>