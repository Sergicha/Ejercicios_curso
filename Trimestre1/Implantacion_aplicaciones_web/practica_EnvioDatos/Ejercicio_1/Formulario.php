<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Formulario básico</title>
    <link rel="stylesheet" href="formulario.css">
</head>
<body>

<h2>Formulario de Registro</h2>

<form action="procesos.php" method="POST">

    <div class="fila">
        <label>Nombre:</label>
        <input type="text" name="nombre" maxlength="50" required>

        <label>Apellidos:</label>
        <input type="text" name="apellidos" maxlength="50" required>
    </div>

    <div class="fila">
        <label>Dirección:</label>
        <input type="text" name="direccion" required>

        <label>Edad:</label>
        <input type="number" name="edad" min="1" max="99" required>
    </div>

    <div class="fila">
        <label>Correo electrónico:</label>
        <input type="email" name="correo" required>
    </div>

    <div class="fila">
        <label>Provincia:</label>
        <select name="provincia" required>
            <option value="">Seleccione</option>
            <option>Madrid</option>
            <option>Barcelona</option>
            <option>Valencia</option>
            <option>Sevilla</option>
        </select>

        <label>Fecha de nacimiento:</label>
        <input type="date" name="fecha" placeholder="DD/MM/AA" title="Formato DD/MM/AA" required>
    </div>

    <div class="fila">
        <label>Teléfono fijo:</label>
        <input type="text" name="fijo">

        <label>Teléfono móvil:</label>
        <input type="text" name="movil">
    </div>

    <div class="fila">
        <label>¿Tiene hijos?</label>
        <input type="radio" name="hijos" value="si" required> Sí
        <input type="radio" name="hijos" value="no" required> No
    </div>

    <div class="fila">
        <input type="submit" value="Aceptar">
    </div>

</form>

</body>
</html>
