<?php
session_start();

if (!isset($_SESSION['pila'])) {
    $_SESSION['pila'] = [];
}

if (isset($_POST['agregar'])) {
    $dato = trim($_POST['dato']);

    if ($dato === "") {
        $mensaje = "No se puede insertar un dato vacío.";
    } elseif (strlen($dato) > 20) {
        $mensaje = "El dato no puede tener más de 20 caracteres.";
    } else {
        array_push($_SESSION['pila'], $dato);
    }
}

if (isset($_POST['eliminar'])) {
    array_pop($_SESSION['pila']);
}

if (isset($_POST['vaciar'])) {
    $_SESSION['pila'] = [];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="variable_session.css">
</head>
<body>
<div class="card">
    <form method="post">
        <input type="text" name="dato" placeholder="Introduce un dato">
        <button type="submit" name="agregar">Agregar</button>
        <button type="submit" name="eliminar">Eliminar último</button>
        <button type="submit" name="vaciar">Vaciar pila</button>
    </form>

    <?php if (isset($mensaje)) echo "<p class='error'>$mensaje</p>"; ?>

    <h3>Contenido de la pila:</h3>
    <ul>
        <?php foreach (array_reverse($_SESSION['pila']) as $item): ?>
            <li><?= htmlspecialchars($item) ?></li>
        <?php endforeach; ?>
    </ul>
</div>
</body>
</html>
