<?php
header('Access-Control-Allow-Origin: *'); // Permite solicitudes desde cualquier origen
header('Content-Type: application/json'); // Definir que la respuesta es JSON

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "vivero";

// Conexión a la base de datos
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Error de conexión a la base de datos.']));
}

// Recibe los datos enviados por POST
if (isset($_POST['usuario']) && isset($_POST['new-password'])) {
    $usuario = $_POST['usuario'];
    $new_password = $_POST['new-password'];

    // Actualizar la contraseña en la base de datos
    $sql = "UPDATE usuarios SET contraseña = ? WHERE mail = ?";
    $stmt = $conn->prepare($sql);

    // Verifica si la preparación de la consulta fue exitosa
    if ($stmt) {
        $stmt->bind_param('ss', $new_password, $usuario);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            // Si la actualización fue exitosa, retorna un mensaje de éxito
            echo json_encode(['success' => true, 'message' => 'Contraseña cambiada con éxito.']);
        } else {
            // Si no se afectaron filas, significa que no se pudo cambiar la contraseña
            echo json_encode(['success' => false, 'message' => 'No se pudo cambiar la contraseña. Asegúrate de que el usuario exista.']);
        }
        
        $stmt->close();
    } else {
        // Si hubo un error al preparar la consulta
        echo json_encode(['success' => false, 'message' => 'Error al preparar la consulta.']);
    }
} else {
    // Si faltan datos
    echo json_encode(['success' => false, 'message' => 'Faltan datos de usuario o contraseña.']);
}

$conn->close();
?>
