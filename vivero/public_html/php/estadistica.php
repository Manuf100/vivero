<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "vivero");

// Verificar la conexión
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Consulta SQL
$sql = "SELECT id, nombre, cant_vendida, total FROM productos ORDER BY cant_vendida DESC, total DESC, id LIMIT 5;";
$resultado = $conexion->query($sql);

// Crear un array para almacenar los resultados
$productos = array();

// Verificar si se encontraron resultados
if ($resultado->num_rows > 0) {
    // Obtener cada fila y agregarla al array
    while($fila = $resultado->fetch_assoc()) {
        $productos[] = $fila;
    }
}

// Establecer el encabezado para JSON
header('Content-Type: application/json');

// Devolver los datos en formato JSON
echo json_encode($productos);

// Cerrar la conexión
$conexion->close();
?>
