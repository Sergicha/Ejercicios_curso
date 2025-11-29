<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: formulario.php");
    exit;
}

$nombre    = trim($_POST['nombre'] ?? '');
$apellidos = trim($_POST['apellidos'] ?? '');
$edad      = trim($_POST['edad'] ?? '');
$correo    = trim($_POST['correo'] ?? '');
$provincia = trim($_POST['provincia'] ?? '');
$fecha     = trim($_POST['fecha'] ?? '');
$fijo      = trim($_POST['fijo'] ?? '');
$movil     = trim($_POST['movil'] ?? '');
$hijos     = $_POST['hijos'] ?? '';

$errores = [];

if (empty($nombre) || !preg_match('/^[\p{L}\s]+$/u', $nombre) || strlen($nombre) > 50) {
    $errores[] = "Debe completarlo y solo puede tener letras y un máximo de 50.";
}
if (empty($apellidos) || !preg_match('/^[\p{L}\s]+$/u', $apellidos) || strlen($apellidos) > 50) {
    $errores[] = "Debe completarlo y solo puede tener letras y un máximo de 50.";
}
if (!is_numeric($edad) || $edad < 1 || $edad > 99) {
    $errores[] = "La edad debe estar entre 1 y 99.";
}
if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    $errores[] = "Este correo no es válido.";
}
if (empty($provincia)) {
    $errores[] = "Seleccione una provincia.";
}
if (empty($fecha)) {
    $errores[] = "La fecha de nacimiento es obligatoria.";
} elseif (!strtotime($fecha)) {
    $errores[] = "Esta fecha no es válida.";
} else {
    $fecha_formateada = date("d/m/y", strtotime($fecha));
}
if (!empty($fijo) && !preg_match('/^[89]\d{8}$/', $fijo)) {
    $errores[] = "El teléfono fijo debe tener 9 dígitos y comenzar con 8 o 9.";
}
if (!empty($movil) && !preg_match('/^[67]\d{8}$/', $movil)) {
    $errores[] = "El teléfono móvil debe tener 9 dígitos y comenzar con 6 o 7.";
}
if (empty($hijos)) {
    $errores[] = "Elija una de las dos opciones.";
}

if (!empty($errores)) {
    $mensaje = urlencode(implode("<br>", $errores));
    header("Location: formulario.php?error=$mensaje");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Datos Procesados</title>
    <link rel="stylesheet" href="procesos.css">
</head>
<body>

<div class="card">
    <h2>Datos del usuario</h2>
    <p><strong>Nombre:</strong> <?= htmlspecialchars($nombre) ?> <?= htmlspecialchars($apellidos) ?></p>
    <p><strong>Edad:</strong> <?= htmlspecialchars($edad) ?></p>
    <p><strong>Correo:</strong> <?= htmlspecialchars($correo) ?></p>
    <p><strong>Provincia:</strong> <?= htmlspecialchars($provincia) ?></p>
    <p><strong>Fecha de nacimiento:</strong> <?= htmlspecialchars($fecha_formateada) ?></p>
    <p><strong>Teléfono fijo:</strong> <?= htmlspecialchars($fijo ?: 'No indicado') ?></p>
    <p><strong>Teléfono móvil:</strong> <?= htmlspecialchars($movil ?: 'No indicado') ?></p>
    <p><strong>¿Tiene hijos?:</strong> <?= htmlspecialchars($hijos) ?></p>

    <h2>Carta de presentación</h2>
    <p>Estimado <?= htmlspecialchars($nombre) ?>,</p>
    <p>Tengo el placer de comunicarle que en <strong><?= htmlspecialchars($provincia) ?></strong> estamos ofreciendo una oferta de productos que le pueden interesar:</p>

    <?php if ($edad >= 20 && $edad <= 30): ?>
        <p>Una televisión de plasma de 65 pulgadas.</p>
    <?php elseif ($edad >= 31 && $edad <= 60): ?>
        <p>Un viaje al lugar que decida con todos los gastos pagados.</p>
    <?php elseif ($edad > 60): ?>
        <p>Un bono para visitar gratuitamente cualquier parque de atracciones.</p>
    <?php else: ?>
        <p>Ninguna oferta, que escriban sus padres.</p>
    <?php endif; ?>

    <?php if ($hijos === "si"): ?>
        <p>Además, por tener hijos, le ofrecemos un descuento especial en consolas.</p>
    <?php endif; ?>
</div>

</body>
</html>
