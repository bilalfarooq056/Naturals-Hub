// DOM Elements
const homeLink = document.getElementById('homeLink');
const farmerLink = document.getElementById('farmerLink');
const vendorLink = document.getElementById('vendorLink');
const homeContent = document.getElementById('homeContent');
const farmerContent = document.getElementById('farmerContent');
const vendorContent = document.getElementById('vendorContent');
const farmerButton = document.getElementById('farmerButton');
const vendorButton = document.getElementById('vendorButton');
const homeButtonFarmer = document.getElementById('homeButtonFarmer');
const homeButtonVendor = document.getElementById('homeButtonVendor');
const addProductForm = document.getElementById('addProductForm');
const productList = document.getElementById('productList');
const availableProducts = document.getElementById('availableProducts');
const vendorOrders = document.getElementById('vendorOrders');
const loginButton = document.getElementById('loginButton');
const logoutButton = document.getElementById('logoutButton');
const loginContent = document.getElementById('loginContent');
const toggleForm = document.getElementById('toggleForm');
let isLogin = true;
let cart = [];

// Navigation
function showHome() {
    homeContent.style.display = 'block';
    farmerContent.style.display = 'none';
    vendorContent.style.display = 'none';
}

function showFarmer() {
    homeContent.style.display = 'none';
    farmerContent.style.display = 'block';
    vendorContent.style.display = 'none';
}

function showVendor() {
    homeContent.style.display = 'none';
    farmerContent.style.display = 'none';
    vendorContent.style.display = 'block';
    renderCart();
}

homeLink.addEventListener('click', showHome);
farmerLink.addEventListener('click', showFarmer);
vendorLink.addEventListener('click', showVendor);
farmerButton.addEventListener('click', showFarmer);
vendorButton.addEventListener('click', showVendor);
homeButtonFarmer.addEventListener('click', showHome);
homeButtonVendor.addEventListener('click', showHome);

// Farmer add/edit/delete
addProductForm.addEventListener('submit', (event) => {
    event.preventDefault();
    const productName = document.getElementById('productName').value;
    const category = document.getElementById('category').value;
    const quantity = document.getElementById('quantity').value;
    const price = document.getElementById('price').value;

    const productItem = document.createElement('div');
    productItem.classList.add('list-item');
    productItem.innerHTML = `
        <span>${productName} - ${quantity}kg - $${price}/kg - Category: ${category}</span>
        <button class="btn edit-btn">Edit</button>
        <button class="btn delete-btn">Delete</button>`;

    productList.appendChild(productItem);

    document.getElementById('productName').value = '';
    document.getElementById('category').value = '';
    document.getElementById('quantity').value = '';
    document.getElementById('price').value = '';

    addProductListeners(productItem);
});

function addProductListeners(item) {
    const editBtn = item.querySelector('.edit-btn');
    const deleteBtn = item.querySelector('.delete-btn');

    editBtn.addEventListener('click', () => {
        const text = item.querySelector('span').textContent;
        const parts = text.split(' - ');
        const name = parts[0];
        const qty = parts[1].replace('kg', '');
        const price = parts[2].replace('$', '').replace('/kg', '');
        const cat = parts[3].replace('Category: ', '');

        document.getElementById('productName').value = name;
        document.getElementById('quantity').value = qty;
        document.getElementById('price').value = price;
        document.getElementById('category').value = cat;

        productList.removeChild(item);
    });

    deleteBtn.addEventListener('click', () => {
        productList.removeChild(item);
    });
}

// Vendor Section
const availableProductsData = [
    { id: 1, farmer: 'Farmer A', product: 'Tomatoes', quantity: 100, price: 2, category: 'Vegetables' },
    { id: 2, farmer: 'Farmer B', product: 'Apples', quantity: 50, price: 3, category: 'Fruits' }
];

function renderAvailableProducts() {
    availableProducts.innerHTML = '';
    availableProductsData.forEach(product => {
        const productItem = document.createElement('div');
        productItem.classList.add('list-item');
        productItem.innerHTML = `
            <span>${product.farmer} - ${product.product} - ${product.quantity}kg - $${product.price}/kg - Category: ${product.category}</span>
            <button class="btn add-to-cart-btn">Add to Cart</button>`;

        const button = productItem.querySelector('button');
        button.addEventListener('click', () => {
            cart.push(product);
            alert(`${product.product} added to cart.`);
            renderCart();
        });

        availableProducts.appendChild(productItem);
    });
}
renderAvailableProducts();

function renderCart() {
    vendorOrders.innerHTML = '';
    cart.forEach((order, index) => {
        const orderItem = document.createElement('div');
        orderItem.classList.add('list-item');
        orderItem.innerHTML = `
            <span>${order.product} - ${order.quantity}kg - $${order.price}/kg - Category: ${order.category}</span>
            <button class="btn remove-from-cart-btn">Remove</button>`;

        orderItem.querySelector('.remove-from-cart-btn').addEventListener('click', () => {
            cart.splice(index, 1);
            alert(`${order.product} removed from cart.`);
            renderCart();
        });

        vendorOrders.appendChild(orderItem);
    });
}



// --------------------------- V1 ------------

// Login/Logout/Toggle
// loginButton.addEventListener('click', (event) => {
//     event.preventDefault();
//     window.location.href = 'SignIn_SignUp_page.html';
// });

// logoutButton.addEventListener('click', () => {
//     alert('Logged out successfully!');
//     loginButton.style.display = 'block';
//     logoutButton.style.display = 'none';
//     window.location.href = 'Home_page.html';
// });

// toggleForm.addEventListener('click', () => {
//     isLogin = !isLogin;
//     toggleForm.textContent = isLogin ? 'Switch to Sign Up' : 'Switch to Login';
// });


// // Role selection function
// function selectRole(role) {
//     // Hide the role selection buttons and text
//     document.getElementById('role-buttons').style.display = 'none';
//     document.getElementById('role-text').style.display = 'none';

//     // Show the form container
//     document.getElementById('farmer-form').style.display = 'none';
//     document.getElementById('vendor-form').style.display = 'none';
//     document.querySelector('.role-selection-box').style.display = 'block';

//     // Show the selected form (Farmer or Vendor)
//     if (role === 'farmer') {
//         document.getElementById('farmer-form').style.display = 'block';
//     } else if (role === 'vendor') {
//         document.getElementById('vendor-form').style.display = 'block';
//     }
// }

// // Go back to role selection
// function goBack() {
//     // Hide the forms and display the role selection buttons again
//     document.getElementById('farmer-form').style.display = 'none';
//     document.getElementById('vendor-form').style.display = 'none';
//     document.getElementById('role-buttons').style.display = 'flex';
//     document.getElementById('role-text').style.display = 'block';
// }

// --------------------- v2 --------------------

// farmer dashboard js --------

