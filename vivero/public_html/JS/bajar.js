document.getElementById('formBajarPrecio').addEventListener('submit', function(event) {
    event.preventDefault();

    const monto = document.querySelector('input[name="monto2"]').value;
    const tipo = document.getElementById('tipo').value.replace('2', ''); // Esto elimina el "2" extra en "porcentaje2" o "valor fijo2"

    // Enviar datos en JSON al archivo PHP
    fetch('http://localhost/vivero/public_html/php/bajarPrecio.php', {
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
            document.getElementById('alertaContainer3').style.display = 'block';
            document.getElementById('alertaContainer3').textContent = data.message;
            document.getElementById('alertaContainer4').style.display = 'none';
        } else {
            document.getElementById('alertaContainer4').style.display = 'block';
            document.getElementById('alertaContainer4').textContent = data.message;
            document.getElementById('alertaContainer3').style.display = 'none';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('alertaContainer4').style.display = 'block';
        document.getElementById('alertaContainer4').textContent = 'Error al enviar la solicitud.';
        document.getElementById('alertaContainer3').style.display = 'none';
    });
});
