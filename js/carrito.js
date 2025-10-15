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
/* Funcionamiento del menu retractil */
document.addEventListener('DOMContentLoaded', function() {
    const categoriesToggleDesktop = document.getElementById('categoriesToggleDesktop');
    const categoriesDropdownDesktop = document.getElementById('categoriesDropdownDesktop');

    if (categoriesToggleDesktop && categoriesDropdownDesktop) {
        categoriesToggleDesktop.addEventListener('click', function() {
            categoriesDropdownDesktop.classList.toggle('active');
            categoriesToggleDesktop.classList.toggle('active');
        });

        // Cerrar el menú al hacer clic fuera de él
        document.addEventListener('click', function(event) {
            if (!categoriesToggleDesktop.contains(event.target) && !categoriesDropdownDesktop.contains(event.target)) {
                categoriesDropdownDesktop.classList.remove('active');
                categoriesToggleDesktop.classList.remove('active');
            }
        });

        // Cerrar el menú al hacer clic en un enlace de categoría
        const categoryLinks = categoriesDropdownDesktop.querySelectorAll('a');
        categoryLinks.forEach(link => {
            link.addEventListener('click', function() {
                categoriesDropdownDesktop.classList.remove('active');
                categoriesToggleDesktop.classList.remove('active');
            });
        });
    }
});