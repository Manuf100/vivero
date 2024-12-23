let id_usuario; // Declarar variable para el ID del usuario
let tipo_usuario;

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('login-form').onsubmit = function(e) {
        e.preventDefault();   
        var usuario = document.getElementById('usuario').value;
        var password = document.getElementById('password').value;

        // Crear objeto FormData para enviar los datos al servidor
        var formData = new FormData();
        formData.append('usuario', usuario);
        formData.append('password', password);

        // Hacer la petición al servidor
        fetch('http://localhost/vivero/public_html/php/procesar_login.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Guardar el ID del usuario en la variable
                localStorage.setItem('id_usuario', data.id_usuario);
                localStorage.setItem('tipo_cliente', data.tipo_cliente);
                // Si el inicio de sesión es exitoso, redirige a menu.html
                window.location.href = 'index.html';
            } else {
                // Si las credenciales no son correctas, mostrar mensaje de error
                document.getElementById('error-message').style.display = 'block';
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    };

    document.getElementById('recover-form').onsubmit = function(e) {
        e.preventDefault();   
        var usuarioRecup = document.getElementById('usuario-recup').value;
        var newPassword = document.getElementById('new-password').value;
    
        // Crear objeto FormData para enviar los datos al servidor
        var formData = new FormData();
        formData.append('usuario', usuarioRecup);
        formData.append('new-password', newPassword);
    
        // Hacer la petición al servidor
        fetch('http://localhost/vivero/public_html/php/cambiar_contrasena.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('error-message-recup').style.display = 'block';
                document.getElementById('error-message-recup2').style.display = 'none';
            } else {
                document.getElementById('error-message-recup2').style.display = 'block';
                document.getElementById('error-message-recup').style.display = 'none';
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    };
    document.getElementById('create-form').onsubmit = function(e) {
        e.preventDefault();   
        var mail = document.getElementById('usuario-crear').value; 
        var password = document.getElementById('password-crear').value;
        var nombre = document.getElementById('nombre').value;
        var apellido = document.getElementById('apellido').value;
    
        // Crear objeto FormData para enviar los datos al servidor
        var formData = new FormData();
        formData.append('mail', mail); 
        formData.append('password', password);
        formData.append('nombre', nombre);
        formData.append('apellido', apellido);
    
        // Hacer la petición al servidor
        fetch('http://localhost/vivero/public_html/php/crear_usuario.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('error-message-crear2').style.display = 'block';
                document.getElementById('error-message-crear').style.display = 'none'; // Oculta mensaje de error
            } else {
                document.getElementById('error-message-crear').style.display = 'block';
                document.getElementById('error-message-crear2').style.display = 'none'; // Oculta mensaje de éxito
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    };
    
    
});

// Funciones para mostrar los diferentes formularios
function mostrarRecuperacion() {
    document.getElementById('container').style.display = 'none';
    document.getElementById('container-recup').style.display = 'block';
    document.getElementById('container-crear').style.display = 'none';
}

function mostrarSesion() {
    document.getElementById('container').style.display = 'block';
    document.getElementById('container-recup').style.display = 'none';
    document.getElementById('container-crear').style.display = 'none';
}

function mostrarCrear() {
    document.getElementById('container').style.display = 'none';
    document.getElementById('container-recup').style.display = 'none';
    document.getElementById('container-crear').style.display = 'block';
}
