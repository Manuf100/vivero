<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

// Manejar solicitud OPTIONS (preflight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Conectar a la base de datos
$conexion = new mysqli("localhost", "root", "", "vivero");

// Verificar la conexión
if ($conexion->connect_error) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error de conexión a la base de datos']);
    exit;
}

// Obtener y decodificar los datos recibidos en formato JSON
$data = json_decode(file_get_contents("php://input"), true);
$monto = isset($data['monto']) ? floatval($data['monto']) : 0;
$tipo = isset($data['tipo']) ? $data['tipo'] : '';

// Validar monto y tipo de incremento
if ($monto <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Monto inválido']);
    exit;
}
if ($tipo !== 'porcentaje' && $tipo !== 'valor fijo') {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Tipo de aumento inválido']);
    exit;
}

// Preparar y ejecutar la consulta SQL según el tipo de aumento
if ($tipo === 'porcentaje') {
    $query = "UPDATE productos SET precio = precio * (1 + ? / 100)";
} elseif ($tipo === 'valor fijo') {
    $query = "UPDATE productos SET precio = precio + ?";
}

$stmt = $conexion->prepare($query);

if (!$stmt) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error al preparar la consulta']);
    exit;
}

$stmt->bind_param("d", $monto);

// Ejecutar la consulta y verificar el resultado
if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Precios aumentados exitosamente']);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error al actualizar los precios']);
}

// Cerrar la conexión y la declaración
$stmt->close();
$conexion->close();
?>
