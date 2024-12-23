<?php
header('Content-Type: application/json');

// Configuración de conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "vivero";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die(json_encode(['error' => 'Conexión fallida: ' . $conn->connect_error]));
}

// Obtener el nombre del producto desde la solicitud GET
$nombreProducto = $_GET['nombreProducto'] ?? '';

if ($nombreProducto) {
    // Preparar y ejecutar la consulta
    $stmt = $conn->prepare("SELECT Id FROM Productos WHERE Nombre LIKE ?");
    if ($stmt === false) {
        die(json_encode(['error' => 'Error en la preparación de la consulta: ' . $conn->error]));
    }

    $searchTerm = "%$nombreProducto%";
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result === false) {
        die(json_encode(['error' => 'Error en la ejecución de la consulta: ' . $stmt->error]));
    }

    $productos = [];
    while ($row = $result->fetch_assoc()) {
        $productos[] = $row;
    }

    echo json_encode($productos);
} else {
    echo json_encode([]);
}

// Cerrar conexión
$conn->close();
?>
