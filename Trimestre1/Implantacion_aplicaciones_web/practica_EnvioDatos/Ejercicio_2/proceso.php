<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: subir_foto.php");
    exit;
}

$carpeta_destino = "fotos/";
if (!is_dir($carpeta_destino)) {
    mkdir($carpeta_destino, 0755, true);
}

$archivo = $_FILES['foto'] ?? null;

if (!$archivo || $archivo['error'] !== UPLOAD_ERR_OK) {
    die("Error al subir el archivo.");
}

$tipos_permitidos = ['image/jpg', 'image/png', 'image/gif'];
if (!in_array($archivo['type'], $tipos_permitidos)) {
    die("Formato no permitido. Solo JPG, PNG o GIF.");
}

$tamano_max = 2 * 1024 * 1024;
if ($archivo['size'] > $tamano_max) {
    die("El archivo es demasiado grande. Máximo 2MB.");
}

$nombre_servidor = "foto_usuario" . strrchr($archivo['name'], '.');

if (!move_uploaded_file($archivo['tmp_name'], $carpeta_destino . $nombre_servidor)) {
    die("Error al guardar el archivo en el servidor.");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Foto Subida</title>
    <link rel="stylesheet" href="subir_foto.css">
</head>
<body>

<h2>Foto subida correctamente</h2>
<p><img src="<?= $carpeta_destino . $nombre_servidor ?>" width="300"></p>

</body>
</html>
