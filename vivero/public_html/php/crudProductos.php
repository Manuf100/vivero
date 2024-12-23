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
        // Lectura de productos
        if (isset($_GET['id'])) {
            // Obtener un Producto específico
            $id = $_GET['id'];
            $query = "SELECT id, nombre, precio, cantidad, descripcion, Categoria, cant_vendida, total FROM productos WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // Cambia esto para obtener solo un objeto
                $producto = $result->fetch_assoc();
                echo json_encode($producto); // Devuelve solo un objeto
            } else {
                echo json_encode(["error" => "Usuario no encontrado"]);
            }

            $stmt->close();
        } else {
            // Obtener todos los Productos
            $query = "SELECT id, nombre, precio, cantidad, descripcion, Categoria, cant_vendida, total FROM productos";
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
        $id=$_POST['id'];
        $nombre = $_POST['nombre'];
        $precio = $_POST['precio'];
        $cantidad = $_POST['cantidad'];
        $descripcion = $_POST['descripcion'];
        $img = $_POST['img'];
        $categoria = $_POST['categoria'];

        // Verificar si el id o nombre ya existe
        $query = "SELECT id, nombre FROM productos WHERE id = ? or nombre= ?";
        $stmt2 = $conn->prepare($query);
        $stmt2->bind_param("ss", $id, $nombre);
        $stmt2->execute();
        $result = $stmt2->get_result();

        if ($result->num_rows > 0) {
            echo json_encode(['success' => false, 'message' => 'El id o el nombre ya existe']);

        } else {
            // Insertar nuevo producto
            $query = "INSERT INTO productos (id, nombre, precio, cantidad, descripcion, img, categoria) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sssssss", $id, $nombre, $precio, $cantidad, $descripcion, $img, $categoria);

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
            // Actualizar un producto existente
            parse_str(file_get_contents("php://input"), $putVars);
        
            // Verificar si se proporcionaron los datos necesarios
            if (isset($putVars['id'], $putVars['nombre'], $putVars['precio'], $putVars['cantidad'], $putVars['descripcion'], $putVars['categoria'])) {
                $id = $putVars['id'];
                $nombre = $putVars['nombre'];
                $precio = $putVars['precio'];
                $cantidad = $putVars['cantidad'];
                $descripcion = $putVars['descripcion'];
                $categoria = $putVars['categoria'];

                // Verificar si el id o nombre ya existen en otros productos, excluyendo el producto actual
                $query = "SELECT id, nombre FROM productos WHERE (id = ? OR nombre = ?) AND id != ?";
                $stmt2 = $conn->prepare($query);
                $stmt2->bind_param("sss", $id, $nombre, $id);
                $stmt2->execute();
                $result = $stmt2->get_result();



                if ($result->num_rows > 0) {
                    echo json_encode(['success' => false, 'message' => 'El id o el nombre ya existe']);

                } else {
                    $query = "UPDATE productos SET nombre = ?, precio = ?, cantidad = ?, descripcion = ?, categoria = ? WHERE id = ?";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("sssssi", $nombre, $precio, $cantidad, $descripcion, $categoria, $id);
            
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
        
                $query = "DELETE FROM productos WHERE id = ?";
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
