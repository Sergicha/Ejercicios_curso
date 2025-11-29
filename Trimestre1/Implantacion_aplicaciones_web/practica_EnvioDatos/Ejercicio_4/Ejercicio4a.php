<?php
if (isset($_POST['crear'])) {
    $texto = trim($_POST['texto']);

    if ($texto === "") {
        $mensaje = "No puedes dejar el texto vacío.";
    } else {
        $archivo = fopen("archivo.txt", "w");
        fwrite($archivo, $texto);
        fclose($archivo);
        $mensaje = "Archivo creado correctamente como 'archivo.txt'.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear archivo TXT</title>
    <link rel="stylesheet" href="Ejercicio4a.css">
</head>
<body>
<div class="card">
    <h2>Crear un archivo TXT</h2>
    <form method="post">
        <textarea name="texto" rows="5" cols="40" placeholder="Escribe algo aquí..."></textarea><br>
        <button type="submit" name="crear">Crear archivo</button>
    </form>
    <?php if (isset($mensaje)) echo "<p class='mensaje'>$mensaje</p>"; ?>
</div>
</body>
</html>
