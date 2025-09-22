// js/carrito.js

function cargarNumeroCarrito() {
    fetch('clases/obtener_carrito.php', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.ok) {
            let elemento = document.getElementById("num_cart");
            if (elemento) {
                elemento.innerHTML = data.numero;
            }
        }
    })
    .catch(error => {
        console.error('Error al cargar el carrito:', error);
    });
}

document.addEventListener('DOMContentLoaded', function() {
    cargarNumeroCarrito();


    
});