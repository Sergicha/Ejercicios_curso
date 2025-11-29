<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Subir Foto</title>
    <link rel="stylesheet" href="subir_foto.css">
</head>
<body>

<h2>Subir una Fotografía</h2>

<form action="procesar_foto.php" method="POST" enctype="multipart/form-data">
    <div class="fila">
        <label>Selecciona una foto:</label>
        <input type="file" name="foto" accept="image/*" required>
    </div>
    <input type="submit" value="Subir foto">
</form>

</body>
</html>
