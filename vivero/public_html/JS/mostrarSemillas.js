document.addEventListener('DOMContentLoaded', function() {
    var plantasDiv = document.getElementById('wrapper');

    // Definir formDataVer aquí
    var formDataVer = new FormData(); // O cualquier otra inicialización que necesites

    fetch('http://localhost/vivero/public_html/php/semillas.php')

    .then(response => response.json())
    .then(data => {
        console.log(data);  // Verificar qué datos se están recibiendo
        plantasDiv.innerHTML = '';  // Limpiar el contenido anterior
        
        if (Array.isArray(data)) {
            // Si data es un array
            let cuadro = '';
            data.forEach(planta => {
                cuadro += `
                    <div class="product-card" id="${planta.id}">
                        <div class="product-img">
                            <img class="productImage" src="${planta.img}" height="420" width="327" alt="${planta.nombre}" />
                        </div>
                        <div class="product-info">
                            <div class="product-text">
                                <h1>${planta.nombre}</h1>
                            </div>
                            <div class="product-price-btn">
                                <p><span class="precio">$${planta.precio}</span></p>
                                <button type="button" style="width: 214px" onclick="irAProducto(${planta.id})">MAS INFORMACIÓN</button>
                            </div>
                        </div>
                    </div>`;
            });
            plantasDiv.innerHTML = cuadro; // Mostrar las plantas en el contenedor
        } 
    })
    .catch(error => {
        console.error('Error: ', error);
    });
});
function irAProducto(id) {
    // Lógica para redirigir a la página de información del producto
    window.location.href = `Productos.html?id=${id}`; // Asegúrate de que esta página exista y esté configurada para manejar el ID
}