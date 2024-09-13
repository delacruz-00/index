<?php
// Inicia la sesión para manejar la persistencia de datos
session_start();

// Inicializa arrays para usuarios y mascotas si no existen
if (!isset($_SESSION['usuarios'])) {
    $_SESSION['usuarios'] = [];
}
if (!isset($_SESSION['mascotas'])) {
    $_SESSION['mascotas'] = [];
}

// Función para agregar un usuario
function agregarUsuario($nombre, $cedula, $edad) {
    $nuevoUsuario = [
        'nombre' => $nombre,
        'cedula' => $cedula,
        'edad' => $edad
    ];
    $_SESSION['usuarios'][] = $nuevoUsuario;
}

// Función para eliminar un usuario por cédula
function eliminarUsuario($cedula) {
    foreach ($_SESSION['usuarios'] as $index => $usuario) {
        if ($usuario['cedula'] == $cedula) {
            unset($_SESSION['usuarios'][$index]);
            $_SESSION['usuarios'] = array_values($_SESSION['usuarios']); // Reindexa el array
            break;
        }
    }
}

// Función para agregar una mascota
function agregarMascota($nombre, $id, $raza) {
    $nuevaMascota = [
        'nombre' => $nombre,
        'id' => $id,
        'raza' => $raza
    ];
    $_SESSION['mascotas'][] = $nuevaMascota;
}

// Función para eliminar una mascota por ID
function eliminarMascota($id) {
    foreach ($_SESSION['mascotas'] as $index => $mascota) {
        if ($mascota['id'] == $id) {
            unset($_SESSION['mascotas'][$index]);
            $_SESSION['mascotas'] = array_values($_SESSION['mascotas']); // Reindexa el array
            break;
        }
    }
}

// Manejo del formulario de usuarios
if (isset($_POST['submit_usuario'])) {
    $nombre = $_POST['nombre'];
    $cedula = $_POST['cedula'];
    $edad = $_POST['edad'];
    if (is_numeric($edad)) {
        agregarUsuario($nombre, $cedula, $edad);
    }
}

// Manejo del formulario de eliminación de usuario
if (isset($_POST['eliminar_usuario'])) {
    $cedula = $_POST['cedula_eliminar'];
    eliminarUsuario($cedula);
}

// Manejo del formulario de mascotas
if (isset($_POST['submit_mascota'])) {
    $nombre = $_POST['nombre_mascota'];
    $id = $_POST['id_mascota'];
    $raza = $_POST['raza_mascota'];
    agregarMascota($nombre, $id, $raza);
}

// Manejo del formulario de eliminación de mascota
if (isset($_POST['eliminar_mascota'])) {
    $id = $_POST['id_eliminar'];
    eliminarMascota($id);
}

// Ordena usuarios por edad de mayor a menor
usort($_SESSION['usuarios'], function($a, $b) {
    return $b['edad'] - $a['edad'];
});
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Usuarios y Mascotas</title>
    <style>
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        table, th, td { border: 1px solid black; }
        th, td { padding: 8px; text-align: left; }
        .button { padding: 5px 10px; margin: 5px; }
    </style>
</head>
<body>
    <h1>Formulario de Usuarios</h1>
    <form method="post">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required>
        <label for="cedula">Cédula:</label>
        <input type="text" id="cedula" name="cedula" required>
        <label for="edad">Edad:</label>
        <input type="number" id="edad" name="edad" required>
        <input type="submit" name="submit_usuario" value="Agregar Usuario">
    </form>

    <h2>Lista de Usuarios</h2>
    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Cédula</th>
                <th>Edad</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($_SESSION['usuarios'] as $usuario): ?>
            <tr>
                <td><?php echo htmlspecialchars($usuario['nombre']); ?></td>
                <td><?php echo htmlspecialchars($usuario['cedula']); ?></td>
                <td><?php echo htmlspecialchars($usuario['edad']); ?></td>
                <td>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="cedula_eliminar" value="<?php echo htmlspecialchars($usuario['cedula']); ?>">
                        <input type="submit" name="eliminar_usuario" value="Eliminar" class="button">
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h1>Formulario de Mascotas</h1>
    <form method="post">
        <label for="nombre_mascota">Nombre:</label>
        <input type="text" id="nombre_mascota" name="nombre_mascota" required>
        <label for="id_mascota">ID:</label>
        <input type="text" id="id_mascota" name="id_mascota" required>
        <label for="raza_mascota">Raza:</label>
        <input type="text" id="raza_mascota" name="raza_mascota" required>
        <input type="submit" name="submit_mascota" value="Agregar Mascota">
    </form>

    <h2>Lista de Mascotas por Raza</h2>
    <?php
    $razas = array_unique(array_column($_SESSION['mascotas'], 'raza'));
    foreach ($razas as $raza):
    ?>
    <h3><?php echo htmlspecialchars($raza); ?></h3>
    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>ID</th>
                <th>Raza</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($_SESSION['mascotas'] as $mascota): ?>
                <?php if ($mascota['raza'] == $raza): ?>
                <tr>
                    <td><?php echo htmlspecialchars($mascota['nombre']); ?></td>
                    <td><?php echo htmlspecialchars($mascota['id']); ?></td>
                    <td><?php echo htmlspecialchars($mascota['raza']); ?></td>
                    <td>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="id_eliminar" value="<?php echo htmlspecialchars($mascota['id']); ?>">
                            <input type="submit" name="eliminar_mascota" value="Eliminar" class="button">
                        </form>
                    </td>
                </tr>
                <?php endif; ?>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endforeach; ?>
</body>
</html