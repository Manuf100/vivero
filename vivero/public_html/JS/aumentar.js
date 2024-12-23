document.getElementById('formAumentarPrecio').addEventListener('submit', function(event) {
    event.preventDefault();

    const monto = document.querySelector('input[name="monto"]').value;
    const tipo = document.getElementById('tipo').value;

    // Enviar datos en JSON al archivo PHP
    fetch('http://localhost/vivero/public_html/php/aumentarPrecio.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ monto, tipo })
    })
    .then(response => response.json())
    .then(data => {
        // Mostrar mensajes de éxito o error basados en la respuesta JSON del servidor
        if (data.success) {
            document.getElementById('alertaContainer').style.display = 'block';
            document.getElementById('alertaContainer').textContent = data.message;
            document.getElementById('alertaContainer2').style.display = 'none';
        } else {
            document.getElementById('alertaContainer2').style.display = 'block';
            document.getElementById('alertaContainer2').textContent = data.message;
            document.getElementById('alertaContainer').style.display = 'none';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('alertaContainer2').style.display = 'block';
        document.getElementById('alertaContainer2').textContent = 'Error al enviar la solicitud.';
        document.getElementById('alertaContainer').style.display = 'none';
    });
});
