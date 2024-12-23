document.addEventListener('DOMContentLoaded', function() {
    const titulo = document.getElementById("titulo");
    titulo.innerHTML = 'TOTAL DE VENTAS (' + (new Date().getFullYear() - 1) + ' vs ' + (new Date().getFullYear()) + ')';

    // Gráfico de Ventas por Año
    fetch('http://localhost/vivero/public_html/php/estadisticaVentas.php')
        .then(response => response.json())
        .then(data => {
            const labels = data.map(venta => venta.año);
            const valores = data.map(venta => venta.total);
            const ctx = document.getElementById('graficoVentasAnuales').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Total de Ventas',
                        data: valores,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        })
        .catch(error => console.error('Error al cargar las ventas:', error));

    // Gráfico de Ventas por Usuario
    fetch('http://localhost/vivero/public_html/php/estadisticaUsuario.php')
        .then(response => response.json())
        .then(data => {
            const labels = data.map(usuario => usuario.mail);
            const valores = data.map(usuario => usuario.total);
            const ctx = document.getElementById('graficoVentasUsuarios').getContext('2d');
            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Total de Ventas por Usuario',
                        data: valores,
                        backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'],
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true
                }
            });
        })
        .catch(error => console.error('Error al cargar las ventas por usuario:', error));
        // Gráfico de Ventas por Usuario
    fetch('http://localhost/vivero/public_html/php/estadisticaUsuario.php')
        .then(response => response.json())
        .then(data => {
            const labels = data.map(usuario => usuario.mail);
            const valores = data.map(usuario => usuario.total);
            const ctx = document.getElementById('graficoVentasUsuarios').getContext('2d');
            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Total de Ventas por Usuario',
                        data: valores,
                        backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'],
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true
                }
            });
        })
        .catch(error => console.error('Error al cargar las ventas por usuario:', error));
    // Gráfico de Ventas por Usuario2
    fetch('http://localhost/vivero/public_html/php/estadisticaUsuario.php')
        .then(response => response.json())
        .then(data => {
            const labels = data.map(usuario => usuario.mail);
            const valores = data.map(usuario => usuario.cantidad_pedidos);
            const ctx = document.getElementById('graficoVentasUsuarios2').getContext('2d');
            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Total de Ventas por Usuario (cantidad)',
                        data: valores,
                        backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'],
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true
                }
            });
        })
        .catch(error => console.error('Error al cargar las ventas por usuario:', error));
    // Gráfico de Productos Más Vendidos
    fetch('http://localhost/vivero/public_html/php/estadistica.php')
        .then(response => response.json())
        .then(data => {
            const labels = data.map(producto => producto.nombre);
            const valores = data.map(producto => producto.cant_vendida);
            const ctx = document.getElementById('graficoProductos').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Cantidad Vendida',
                        data: valores,
                        backgroundColor: 'rgba(153, 102, 255, 0.2)',
                        borderColor: 'rgba(153, 102, 255, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        })
        .catch(error => console.error('Error al cargar los productos más vendidos:', error));

    // Tabla de Productos Más Vendidos
    fetch('http://localhost/vivero/public_html/php/estadistica.php')
        .then(response => response.json())
        .then(data => {
            const tabla = document.querySelector("#productosTabla tbody");
            data.forEach(producto => {
                const fila = document.createElement("tr");
                fila.innerHTML = `
                    <td>${producto.id}</td>
                    <td>${producto.nombre}</td>
                    <td>${producto.cant_vendida}</td>
                    <td>${producto.total}</td>
                `;
                tabla.appendChild(fila);
            });
        })
        .catch(error => console.error('Error al cargar los productos:', error));

    // Tabla de Ventas por Usuario
    fetch('http://localhost/vivero/public_html/php/estadisticaUsuario.php')
        .then(response => response.json())
        .then(data => {
            const tablaUsuarios = document.querySelector("#ventasUTabla tbody");
            data.forEach(usuario => {
                const fila = document.createElement("tr");
                fila.innerHTML = `
                    <td>${usuario.mail}</td>
                    <td>${usuario.cantidad_pedidos}</td>
                    <td>${usuario.total}</td>
                `;
                tablaUsuarios.appendChild(fila);
            });
        })
        .catch(error => console.error('Error al cargar las ventas por usuario:', error));

    // Tabla de Ventas por Año
    fetch('http://localhost/vivero/public_html/php/estadisticaVentas.php')
        .then(response => response.json())
        .then(data => {
            const tabla = document.querySelector("#ventaTabla tbody");
            data.forEach(venta => {
                const fila = document.createElement("tr");
                fila.innerHTML = `
                    <td>${venta.año}</td>
                    <td>${venta.total}</td>
                `;
                tabla.appendChild(fila);
            });
        })
        .catch(error => console.error('Error al cargar las ventas:', error));
});
