<?php
if (isset($_POST['generar'])) {
    $nombre = trim($_POST['nombre']);
    $edad = intval($_POST['edad']);
    $provincia = trim($_POST['provincia']);
    $tieneHijos = isset($_POST['hijos']) ? $_POST['hijos'] : 'no';

    if ($nombre === "" || $edad <= 0 || $provincia === "") {
        $mensaje = "Todos los campos son obligatorios y deben ser válidos.";
    } else {
        if ($edad < 18) {
            $oferta = "Ninguna, que escriban sus padres.";
        } elseif ($edad >= 20 && $edad <= 30) {
            $oferta = "Una televisión de plasma de 65 pulgadas.";
        } elseif ($edad >= 31 && $edad <= 60) {
            $oferta = "Un viaje al lugar que decida con todos los gastos pagados.";
        } else {
            $oferta = "Un bono para visitar gratuitamente cualquier parque de atracciones.";
        }

        $extra = ($tieneHijos === "si") ? "Además, le ofrecemos un descuento en consolas." : "";

        $contenido = "
        <!DOCTYPE html>
        <html lang='es'>
        <head>
            <meta charset='UTF-8'>
            <title>Carta personalizada</title>
            <link rel='stylesheet' href='Ejercicio4b.css'>
        </head>
        <body>
        <div class='card'>
            <h2>Carta personalizada</h2>
            <p>Estimado/a <strong>$nombre</strong>,</p>
            <p>Tengo el placer de comunicarle que en <strong>$provincia</strong> estamos ofreciendo una oferta de productos que le pueden interesar.</p>
            <p>La oferta consta de: $oferta</p>
            <p>$extra</p>
            <p>Atentamente,<br><strong>El equipo de promociones</strong></p>
        </div>
        </body>
        </html>";

        $archivo = fopen("carta_usuario.html", "w");
        fwrite($archivo, $contenido);
        fclose($archivo);

        $mensaje = "Carta generada correctamente como <a href='carta_usuario.html' target='_blank'>carta_usuario.html</a>.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Generar Carta de Usuario</title>
    <link rel="stylesheet" href="Ejercicio4b.css">
</head>
<body>
<div class="card">
    <h2>Generar Carta de Usuario</h2>
    <form method="post">
        <label>Nombre:</label><br>
        <input type="text" name="nombre" required><br><br>

        <label>Edad:</label><br>
        <input type="number" name="edad" required min="1" max="120"><br><br>

        <label>Provincia:</label><br>
        <input type="text" name="provincia" required><br><br>

        <label>¿Tiene hijos?</label>
        <br>
        <input type="radio" name="hijos" value="si" required> Sí
        <input type="radio" name="hijos" value="no"> No
        <br>

        <button type="submit" name="generar">Generar carta</button>
    </form>

    <?php if (isset($mensaje)) echo "<p style='color:green;'>$mensaje</p>"; ?>
</div>
</body>
</html>
