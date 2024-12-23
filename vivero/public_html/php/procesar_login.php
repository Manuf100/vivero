<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "vivero";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Error de conexión a la base de datos.']));
}

// Depurar datos recibidos
error_log(print_r($_POST, true));

if (isset($_POST['usuario']) && isset($_POST['password'])) {
    $usuario = $_POST['usuario'];
    $contraseña = $_POST['password'];

    $sql = "SELECT id, tipo FROM usuarios WHERE mail = ? AND contraseña = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $usuario, $contraseña);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode(['success' => true, 'id_usuario' => $row['id'], 'tipo_cliente'=>$row['tipo']]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Usuario o contraseña incorrectos']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Faltan datos de usuario o contraseña']);
}

$conn->close();
