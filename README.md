# vivero
Trabajo realizado para la materia "lenguajes 4". Ecommrece de un vivero, usando como referencia "vivero mario"

A la base de datos agregar el siguiente procedure:

DELIMITER $$
CREATE PROCEDURE ObtenerPedidosPorUsuario()
BEGIN
    SELECT usuarios.mail, 
           COUNT(*) AS cantidad_pedidos, 
           SUM(pedidos.total) AS total
    FROM usuarios 
    INNER JOIN pedidos 
    ON usuarios.id = pedidos.id_usuario
    GROUP BY usuarios.mail LIMIT 5;
END $$
DELIMITER ;
