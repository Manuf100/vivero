<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json'); // Definir que la respuesta es JSON

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "vivero";

// Configuración de errores
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', 'error_log.txt');

// Conexión a la base de datos
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Error de conexión a la base de datos.']));
}

// Recibe los datos enviados por POST
if (isset($_POST['mail']) && isset($_POST['password']) && isset($_POST['nombre']) && isset($_POST['apellido'])) {
    $nombre = $_POST['nombre'];
    $password = $_POST['password'];
    $mail = $_POST['mail'];
    $apellido = $_POST['apellido'];

    // Comprobar si el usuario ya existe
    $sql = "SELECT * FROM usuarios WHERE mail = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $mail); // Corregido a $mail
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Si ya existe, enviar un mensaje de error
        echo json_encode(['success' => false, 'message' => 'El usuario ya existe.']);
    } else {
        // Insertar el nuevo usuario en la base de datos
        $sql = "INSERT INTO usuarios (nombre, apellido, contraseña, mail) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssss', $nombre, $apellido, $password, $mail); // Corregí $contraseña a $password
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo json_encode(['success' => true, 'message' => 'Usuario creado con éxito.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'No se pudo crear el usuario.']);
        }
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Faltan datos para crear el usuario.']);
}

$conn->close();
?>