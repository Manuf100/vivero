document.addEventListener('DOMContentLoaded', function() {
    var sectionUsuarios = document.getElementById('verU');

    // Cargar usuarios
    fetch('http://localhost/vivero/public_html/php/crudUsuarios.php', {})
    .then(response => response.json())
    .then(data => {
        let tabla = '<table><tr><th>ID</th><th>Nombre</th><th>Apellido</th><th>Tipo</th><th>Mail</th><th>Acciones</th></tr>';
        data.forEach(usuario => {
            tabla += `
                <tr>
                    <td>${usuario.id}</td>
                    <td>${usuario.nombre}</td>
                    <td>${usuario.apellido}</td>
                    <td>${usuario.tipo}</td>
                    <td>${usuario.mail}</td>
                    <td>
                       <button onclick="abrirFormularioEditar(${usuario.id})">Editar</button>

                        <button onclick="eliminarUsuario(${usuario.id})">Eliminar</button>
                    </td>
                </tr>
            `;
        });
        tabla += '</table>';
        sectionUsuarios.innerHTML = tabla;
    })
    .catch(error => {
        console.error('Error: ', error);
    });

    // Agregar usuario
    document.getElementById('formAgregarUsuario').addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(this);

        fetch('http://localhost/vivero/public_html/php/crudUsuarios.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const alertaContainer = document.getElementById('alertaContainer');
                alertaContainer.style.display="block";
                const alertaContainerCorrecto = document.getElementById('alertaContainer2');
                alertaContainerCorrecto.style.display="none";
                alertaContainer.innerHTML = `
                    <label class="alert">
                        Usuario agregado con exito.
                    </div>
                `;
                setTimeout(function () {
                    location.reload();
                }, 2000); // Recarga la página después de 2 segundos
            } else {
                const alertaContainer = document.getElementById('alertaContainer2');
                alertaContainer.style.display="block";
                alertaContainer.innerHTML = `
                    <label class="alert">
                        Ya existe ese mail.
                    </div>
                `;
            }  
        })
        .catch(error => {
            const alertaContainer = document.getElementById('alertaContainer2');
                alertaContainer.style.display="block";
                alertaContainer.innerHTML = `
                    <label class="alert">
                        Ya existe ese mail.
                    </div>
                `;
        });
    });

    // Función para eliminar usuario
    window.eliminarUsuario = function(id) {
        if (confirm('¿Estás seguro de que deseas eliminar este usuario?')) {
            fetch('http://localhost/vivero/public_html/php/crudUsuarios.php', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({ id: id })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Usuario eliminado con éxito');
                    location.reload();
                } else {
                    alert('Error al eliminar el usuario: ' + data.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    };

    // Abrir formulario de edición y cargar datos del usuario
    window.abrirFormularioEditar = function(id) {
        // Obtener los datos del usuario
        fetch(`http://localhost/vivero/public_html/php/crudUsuarios.php?id=${id}`)
        .then(response => response.json())
        .then(usuario => {
            if (usuario && !usuario.error) { // Verifica que usuario no tenga un error
                document.getElementById('nombre').value = usuario.nombre;
                document.getElementById('apellido').value = usuario.apellido;
                document.getElementById('tipo').value = usuario.tipo;
                document.getElementById('mail').value = usuario.mail;
                document.getElementById('formEditarUsuario').dataset.id = usuario.id; // Guardar el ID en el formulario
                document.getElementById('formularioEditar').style.display = 'block'; // Mostrar el formulario
            } else {
                alert('Error al cargar los datos del usuario: ' + (usuario.error || 'Desconocido.'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    };


    // Evento para el formulario de edición
    document.getElementById('formEditarUsuario').addEventListener('submit', function(event) {
        event.preventDefault();

        const id = this.dataset.id; // Obtener el ID desde el formulario
        const nombre = document.getElementById('nombre').value;
        const apellido = document.getElementById('apellido').value;
        const tipo = document.getElementById('tipoE').value;
        const mail = document.getElementById('mail').value;

        fetch('http://localhost/vivero/public_html/php/crudUsuarios.php', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({
                id: id,
                nombre: nombre,
                apellido: apellido,
                tipo: tipo,
                mail: mail
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const alertaContainer = document.getElementById('alertaContainer3');
                alertaContainer.style.display="block";
                const alertaContainerCorrecto = document.getElementById('alertaContainer4');
                alertaContainerCorrecto.style.display="none";
                alertaContainer.innerHTML = `
                    <label class="alert">
                        Usuario editado con exito.
                    </div>
                `;
                setTimeout(function () {
                    location.reload();
                }, 2000); // Recarga la página después de 2 segundos
            } else {
                const alertaContainer = document.getElementById('alertaContainer4');
                alertaContainer.style.display="block";
                alertaContainer.innerHTML = `
                    <label class="alert">
                        Error ya existe ese mail.
                    </div>
                `;
            }  
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });
});
