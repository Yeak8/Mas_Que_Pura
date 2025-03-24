// Productos
const productos = [
    {
        nombre: "Filtro Purificador Sobre Tarja - Anti Cloro",
        precio: 1800,
        descuento: "40% OFF",
        meses: "3, 6, 9 o 12 meses sin intereses",
        id: "producto1"
    },
    // ... más productos
];

function renderProductos() {
    const container = document.getElementById('productosContainer');
    
    productos.forEach(producto => {
        const productHTML = `
            <div class="producto-card">
                <h3>${producto.nombre}</h3>
                <span class="descuento">${producto.descuento}</span>
                <p class="precio">$${producto.precio}</p>
                <p>${producto.meses}</p>
                <div id="paypal-button-${producto.id}"></div>
            </div>
        `;
        container.innerHTML += productHTML;
        
        // Integración PayPal
        paypal.Buttons({
            createOrder: function(data, actions) {
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: producto.precio
                        }
                    }]
                });
            },
            onApprove: function(data, actions) {
                return actions.order.capture().then(function(details) {
                    alert('Pago completado por ' + details.payer.name.given_name);
                });
            }
        }).render(`#paypal-button-${producto.id}`);
    });
}

// Login Modal
const modal = document.getElementById('loginModal');
const loginBtn = document.getElementById('loginBtn');
const span = document.getElementsByClassName('close')[0];

loginBtn.onclick = () => modal.style.display = "block";
span.onclick = () => modal.style.display = "none";
window.onclick = (event) => {
    if (event.target == modal) modal.style.display = "none";
}

// Inicialización
document.addEventListener('DOMContentLoaded', () => {
    renderProductos();
});