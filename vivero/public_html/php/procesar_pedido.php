<?php
header('Access-Control-Allow-Origin: *'); // Permite solicitudes desde cualquier origen
header('Content-Type: application/json'); // Definir que la respuesta es JSON
// Configuración de conexión a la base de datos
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

// Obtener datos del formulario
$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$email = $_POST['email'];
$direccion = $_POST['direccion'];
$numero = $_POST['numero'];
$ciudad = $_POST['ciudad'];
$provincia = $_POST['provincia'];
$pais = $_POST['pais'];
$codigoPostal = $_POST['codigoPostal'];
$medioPago = $_POST['medioPago'];
$comentarios = $_POST['comentarios'];
$id_usuario=$_POST['id_usuario'];
$total=$_POST['total'];

// Obtener productos del carrito
$productosEnCarrito = json_decode($_POST['productosEnCarrito']);
$cantidadesEnCarrito = json_decode($_POST['cantidadesEnCarrito']);

// Iniciar la transacción
$conn->begin_transaction();

try {
    // Insertar datos del pedido
    $stmt = $conn->prepare("INSERT INTO Pedidos (nombre, apellido, email, direccion, numero, ciudad, provincia, pais, codigoPostal, medioPago, comentarios, total, id_usuario) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssssssss", $nombre, $apellido, $email, $direccion, $numero, $ciudad, $provincia, $pais, $codigoPostal, $medioPago, $comentarios, $total, $id_usuario);
    $stmt->execute();

    // Obtener el ID del pedido recién creado
    $pedidoId = $stmt->insert_id;
    $stmt->close();

    // Descontar la cantidad de productos
    for ($i = 0; $i < count($productosEnCarrito); $i++) {
        $producto = $productosEnCarrito[$i];
        $cantidad = $cantidadesEnCarrito[$i];

        // Descontar cantidad de producto
        $stmt = $conn->prepare("UPDATE Productos SET Cantidad = Cantidad - ? WHERE Nombre = ?");
        $stmt->bind_param("is", $cantidad, $producto);
        $stmt->execute();
        $stmt->close();

        $stmt = $conn->prepare("UPDATE Productos SET cant_vendida = cant_vendida + ? WHERE Nombre = ?");
        $stmt->bind_param("is", $cantidad, $producto);
        $stmt->execute();
        $stmt->close();

        $stmt = $conn->prepare("UPDATE Productos SET total = total + (? * precio) WHERE Nombre = ?");
        $stmt->bind_param("is", $cantidad, $producto);
        $stmt->execute();
        $stmt->close();
    }

    // Confirmar la transacción
    $conn->commit();

    // Generar resumen de compra y guardar en archivo
    $resumen = "Resumen de Compra:\n\n";
    $resumen .= "Nombre: $nombre\n";
    $resumen .= "Apellido: $apellido\n";
    $resumen .= "Email: $email\n";
    $resumen .= "Dirección: $direccion, $numero\n";
    $resumen .= "Ciudad: $ciudad\n";
    $resumen .= "Provincia: $provincia\n";
    $resumen .= "País: $pais\n";
    $resumen .= "Código Postal: $codigoPostal\n";
    $resumen .= "Medio de Pago: $medioPago\n";
    $resumen .= "Comentarios: $comentarios\n\n";
    $resumen .= "Productos:\n";
    for ($i = 0; $i < count($productosEnCarrito); $i++) {
        $resumen .= "{$productosEnCarrito[$i]} - Cantidad: {$cantidadesEnCarrito[$i]}\n";
    }
    $resumen .= "Total: $total";

    $filePath = 'resumen_pedido.txt';
    file_put_contents($filePath, $resumen);

    // Redirigir al usuario a la página de confirmación
    echo "<script>
        window.open('$filePath', '_blank');
        window.location.href = 'Compra.html'; // Redirigir a la página de confirmación
    </script>";
} catch (Exception $e) {
    // Rollback en caso de error
    $conn->rollback();
    echo "Error: " . $e->getMessage();
}

// Cerrar conexión
$conn->close();
?>
