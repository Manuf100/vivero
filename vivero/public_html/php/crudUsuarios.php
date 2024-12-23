<?php
// Configuración de cabeceras para permitir acceso desde cualquier origen y para manejar JSON
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

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

// Obtener el método de la solicitud
$method = $_SERVER['REQUEST_METHOD'];

switch($method) {
    case 'GET':
        // Lectura de usuarios
        if (isset($_GET['id'])) {
            // Obtener un usuario específico
            $id = $_GET['id'];
            $query = "SELECT id, nombre, apellido, tipo, mail FROM usuarios WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // Cambia esto para obtener solo un objeto
                $usuario = $result->fetch_assoc();
                echo json_encode($usuario); // Devuelve solo un objeto
            } else {
                echo json_encode(["error" => "Usuario no encontrado"]);
            }

            $stmt->close();
        } else {
            // Obtener todos los usuarios
            $query = "SELECT id, nombre, apellido, tipo, mail FROM usuarios";
            $stmt = $conn->prepare($query);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $datos = $result->fetch_all(MYSQLI_ASSOC);
                echo json_encode($datos);
            } else {
                echo json_encode(["error" => "No se encontraron datos"]);
            }

            $stmt->close();
        }
        break;
    case 'POST':
        // Crear un nuevo usuario
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $tipo = $_POST['tipo'];
        $mail = $_POST['mail'];
        $contraseña = $_POST['contraseña'];

        // Verificar si el mail ya existe
        $query = "SELECT mail FROM usuarios WHERE mail = ?";
        $stmt2 = $conn->prepare($query);
        $stmt2->bind_param("s", $mail);
        $stmt2->execute();
        $result = $stmt2->get_result();

        if ($result->num_rows > 0) {
            echo json_encode(['success' => false, 'message' => 'Ese mail ya existe']);
        } else {
            // Insertar nuevo usuario
            $query = "INSERT INTO usuarios (nombre, apellido, tipo, mail, contraseña) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sssss", $nombre, $apellido, $tipo, $mail, $contraseña);

            if ($stmt->execute()) {
                echo json_encode(["success" => true]);
            } else {
                echo json_encode(["success" => false, "error" => $stmt->error]);
            }
            $stmt->close();
        }

        $stmt2->close();
        
        break;

        case 'PUT':
            // Actualizar un usuario existente
            parse_str(file_get_contents("php://input"), $putVars);
        
            // Verificar si se proporcionaron los datos necesarios
            if (isset($putVars['id'], $putVars['nombre'], $putVars['apellido'], $putVars['tipo'], $putVars['mail'])) {
                $id = $putVars['id'];
                $nombre = $putVars['nombre'];
                $apellido = $putVars['apellido'];
                $tipo = $putVars['tipo'];
                $mail = $putVars['mail'];

                $query = "SELECT mail FROM usuarios WHERE mail = ? AND id <> ?";
                $stmt2 = $conn->prepare($query);
                $stmt2->bind_param("ss", $mail, $id);
                $stmt2->execute();
                $result = $stmt2->get_result();

                if ($result->num_rows > 0) {
                    echo json_encode(['success' => false, 'message' => 'Ese mail ya existe']);
                } else {
                    $query = "UPDATE usuarios SET nombre = ?, apellido = ?, tipo = ?, mail = ? WHERE id = ?";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("ssssi", $nombre, $apellido, $tipo, $mail, $id);
            
                    if ($stmt->execute()) {
                        echo json_encode(["success" => true]);
                    } else {
                        echo json_encode(["success" => false, "error" => $stmt->error]);
                    }
            
                    $stmt->close();
                }

                $stmt2->close();
        
                
            } else {
                echo json_encode(["success" => false, "error" => "Datos incompletos para la actualización"]);
            }
            break;
        
        

        case 'DELETE':
            parse_str(file_get_contents("php://input"), $deleteVars);
            if (isset($deleteVars['id'])) {
                $id = $deleteVars['id'];
        
                $query = "DELETE FROM usuarios WHERE id = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("i", $id);
        
                if ($stmt->execute()) {
                    echo json_encode(["success" => true]);
                } else {
                    echo json_encode(["success" => false, "error" => $stmt->error]);
                }
        
                $stmt->close();
            } else {
                echo json_encode(["success" => false, "error" => "ID no proporcionado para la eliminación"]);
            }
            break;
        

    default:
        echo json_encode(["error" => "Método no soportado"]);
        break;
}

$conn->close();
?>
