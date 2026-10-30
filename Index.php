
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>InmoFix</title>

    <!-- Fuente Montserrat -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Iconos Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <link rel="stylesheet" href="css/estilo.css?v=2">

</head>

<body>

    <div class="index-contenedor">

        <!-- Logo y eslogan -->
        <div class="index-banner">

            <img src="img/inmofix-02.png" alt="Logo InmoFix">

            <h2>
                Gestión inmobiliaria inteligente
            </h2>

        </div>

        <!-- Selección de usuario -->
        <h1 class="index-titulo">
            ¿Cómo querés ingresar?
        </h1>

        <div class="index-tarjetas">

            <!-- Tarjeta Administrador -->
            <div class="index-card">

                <div class="index-icono">
                    <i class="fa-solid fa-user"></i>
                </div>

                <h3>Administrador</h3>

                <p>
                    Gestioná propiedades, contratos, cobros y
                    reclamos desde un único lugar.
                </p>

                <a class="index-btn" href="Admin/loginAdmin.php">
                    Ingresar como Administrador
                </a>

            </div>

            <!-- Tarjeta Inquilino -->
            <div class="index-card">

                <div class="index-icono">
                    <i class="fa-solid fa-users"></i>
                </div>

                <h3>Inquilino</h3>

                <p>
                    Consultá tu contrato, seguí tus pagos y
                    reportá incidencias de tu vivienda.
                </p>

                <a class="index-btn" href="Inquilino/loginInq.php">
                    Ingresar como Inquilino
                </a>

            </div>

        </div>

    </div>

</body>

</html>
