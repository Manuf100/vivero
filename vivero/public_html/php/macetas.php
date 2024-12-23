<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

// Conectar a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "vivero";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Comprobar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Consulta para obtener los productos
$query = "SELECT id, nombre, descripcion, precio, img FROM productos WHERE Categoria='Maceta'";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Obtener todas las filas con fetch_all y devolver un array de arrays asociativos
    $datos = $result->fetch_all(MYSQLI_ASSOC);  // MYSQLI_ASSOC devuelve un array asociativo
    echo json_encode($datos);  // Devolver los datos en formato JSON
} else {
    echo json_encode(["error" => "No se encontraron datos"]);
}

$stmt->close();
$conn->close();
?>
