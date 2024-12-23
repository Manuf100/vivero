document.addEventListener('DOMContentLoaded', function() {
    var sectionProductos = document.getElementById('verP');

    // Cargar usuarios
    fetch('http://localhost/vivero/public_html/php/crudProductos.php', {})
    .then(response => response.json())
    .then(data => {
        let tabla = '<table><tr><th>ID</th><th>Nombre</th><th>Precio</th><th>Cantidad</th><th>Descripción</th><th>Categoría</th><th>Cantidad vendida</th><th>Total Vendido</th><th>Acciones</th></tr>';
        data.forEach(producto => {
            tabla += `
                <tr>
                    <td>${producto.id}</td>
                    <td>${producto.nombre}</td>
                    <td>${producto.precio}</td>
                    <td>${producto.cantidad}</td>
                    <td>${producto.descripcion}</td>
                    <td>${producto.Categoria}</td>
                    <td>${producto.cant_vendida}</td>
                    <td>${producto.total}</td>
                    <td>
                        <button onclick="abrirFormularioEditarProducto(${producto.id})">Editar</button>
                        <button onclick="eliminarProducto(${producto.id})">Eliminar</button>
                    </td>
                </tr>
            `;
        });
        tabla += '</table>';
        sectionProductos.innerHTML = tabla;
    })
    .catch(error => {
        console.error('Error: ', error);
    });

    // Agregar usuario
    document.getElementById('formAgregarProducto').addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(this);

        fetch('http://localhost/vivero/public_html/php/crudProductos.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const alertaContainer = document.getElementById('alertaContainer5');
                alertaContainer.style.display="block";
                const alertaContainerCorrecto = document.getElementById('alertaContainer6');
                alertaContainerCorrecto.style.display="none";
                alertaContainer.innerHTML = `
                    <label class="alert">
                        Producto agregado con exito.
                    </div>
                `;
                setTimeout(function () {
                    location.reload();
                }, 2000); // Recarga la página después de 2 segundos
            } else {
                const alertaContainerCorrecto = document.getElementById('alertaContainer6');
                alertaContainerCorrecto.style.display="block";
                alertaContainerCorrecto.innerHTML = `
                        <label class="alert">
                            Error ya existe el id o el nombre.
                        </div>
                    `;
            }  
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });

    // Función para eliminar usuario
    window.eliminarProducto = function(id) {
        if (confirm('¿Estás seguro de que deseas eliminar este producto?')) {
            fetch('http://localhost/vivero/public_html/php/crudProductos.php', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({ id: id })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Producto eliminado con éxito');
                    location.reload();
                } else {
                    const alertaContainer3 = document.getElementById('alertaContainer6');
                    alertaContainer3.style.display="block";
                    alertaContainer3.innerHTML = `
                        <label class="alert">
                            Error.
                        </div>
                    `;
                }  
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    };

    // Abrir formulario de edición y cargar datos del producto
    window.abrirFormularioEditarProducto = function(id) {
        // Obtener los datos del producto
        fetch(`http://localhost/vivero/public_html/php/crudProductos.php?id=${id}`)
        .then(response => response.json())
        .then(producto => {
            if (producto && !producto.error) { // Verifica que producto no tenga un error
                document.getElementById('idP').value = producto.id;
                document.getElementById('nombreP').value = producto.nombre;
                document.getElementById('precioP').value = producto.precio;
                document.getElementById('cantidadP').value = producto.cantidad;
                document.getElementById('descripcionP').value = producto.descripcion;
                document.getElementById('categoriaP').value = producto.Categoria; // Seleccionar la categoría correcta
                document.getElementById('formEditarProducto').dataset.id = producto.id; // Guardar el ID en el dataset del formulario
                document.getElementById('formularioEditarProducto').style.display = 'block'; // Mostrar el formulario
            } else {
                alert('Error al cargar los datos del producto: ' + (producto.error || 'Desconocido.'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    };

    // Función para actualizar el producto
    document.getElementById('formEditarProducto').addEventListener('submit', function(event) {
        event.preventDefault();
        const id = document.getElementById('formEditarProducto').dataset.id; // Obtener el ID desde el dataset del formulario
        const nombre = document.getElementById('nombreP').value;
        const precio = document.getElementById('precioP').value;
        const cantidad = document.getElementById('cantidadP').value;
        const descripcion = document.getElementById('descripcionP').value;
        const categoria = document.getElementById('categoriaP').value;

        fetch('http://localhost/vivero/public_html/php/crudProductos.php', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({
                id: id,
                nombre: nombre,
                precio: precio,
                cantidad: cantidad,
                descripcion: descripcion,
                categoria: categoria
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const alertaContainer = document.getElementById('alertaContainer7');
                alertaContainer.style.display="block";
                const alertaContainerCorrecto = document.getElementById('alertaContainer8');
                alertaContainerCorrecto.style.display="none";
                alertaContainer.innerHTML = `
                    <label class="alert">
                        Producto editado con exito.
                    </div>
                `;
                setTimeout(function () {
                    location.reload();
                }, 2000); // Recarga la página después de 2 segundos
            } else {
                const alertaContainer = document.getElementById('alertaContainer8');
                alertaContainer.style.display="block";
                alertaContainer.innerHTML = `
                    <label class="alert">
                        Error.
                    </div>
                `;
            }  
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });
});
