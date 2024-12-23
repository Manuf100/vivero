<?php
     header("Access-Control-Allow-Origin: *");
     header('Content-Type: application/json');
     header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    $conexion=new mysqli("localhost","root","","vivero");
    if($conexion->connect_error){
        die("Conexxion fallida: ".$conexion->connect_error);
    }

    $sql = "SELECT YEAR(fecha) AS año, SUM(total) AS total FROM pedidos WHERE YEAR(fecha) IN (YEAR(CURRENT_DATE), YEAR(CURRENT_DATE) - 1) GROUP BY YEAR(fecha) ORDER BY YEAR(fecha);";
    $resultado = $conexion->query($sql);

    $ventas=array();
    if($resultado->num_rows>0){
        while($fila=$resultado->fetch_assoc()){
            $ventas[]=$fila;
        }
    }
    echo json_encode(($ventas));
    $conexion->close();
?>