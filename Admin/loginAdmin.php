<?php
include ("conection.php");
/* Este if se pregunta si el usuario envió el formulario */
if ($_SERVER["REQUEST_METHOD"] === "POST") { 
    $usuario = $_POST["usuario"];
    $clave = $_POST["clave"];
/* Si el usuario y contraseña son correctos se dirige al panel de Administrador */
    if ($usuario === "admin" && $clave === "admin123") {
        header("Location: panelAdmin.php");
        exit();
    /* Si no son correctos da error */
    } else {
        $error = "Usuario o contraseña incorrecto";
    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">
<title>Login Administrador</title>

<!-- de nuevo la fuente de google fonts -->
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600&display=swap" rel="stylesheet">

<link rel="stylesheet" href="../css/estilo.css?v=1">

</head>


<body>

<div class="login-contenedor"> <!-- el contenedor principal del log in-->

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
Acceso Administrador
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
required>

<label>
Contraseña
</label>

<input 
type="password"
name="clave"
required>

<a class="login-olvide" href="recuperar.php"> <!-- página que hay que crear -->
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