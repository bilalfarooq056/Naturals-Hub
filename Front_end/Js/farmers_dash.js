// ===== Global Declaration of editingProduct =====
let editingProduct = null;  // Declare globally


// ===== Toggle Form Visibility =====
function toggleForm(show = null) {
    const formContainer = document.getElementById('formContainer');
    if (show === true) {
        formContainer.style.display = 'block';
    } else if (show === false) {
        formContainer.style.display = 'none';
    } else {
        formContainer.style.display = formContainer.style.display === 'none' ? 'block' : 'none';
    }
}

// ===== Dropdown Menu Toggle =====
function toggleMenu() {
    const dropdown = document.getElementById("dropdown");
    dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
}

window.onclick = function(event) {
    const dropdown = document.getElementById("dropdown");
    const profileIcon = document.querySelector('.profile-icon');
    if (!profileIcon.contains(event.target) && !dropdown.contains(event.target)) {
        dropdown.style.display = "none";
    }
};

// ===== Escape HTML Utility =====
function escapeHTML(str) {
    return String(str)
        .replace(/&/g, "&amp;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;");
}

// ===== Add Product Logic =====

// ===== Add Product Logic =====
const addProductForm = document.getElementById('addProductForm');
addProductForm.addEventListener('submit', function (e) {
    e.preventDefault();
    const formData = new FormData(addProductForm);

    // Set default unit
    formData.set('unit', 'kg');

    // Ensure description exists
    let description = formData.get('productDescription');
    if (!description || description.trim() === '') {
        description = 'No description provided';
        formData.set('productDescription', description);
    }

    fetch('handlers/addProducthandler.php', {
        method: 'POST',
        body: formData
    })
    .then(res => {
        // Log the raw response status and text to check the returned response
        console.log('Raw response:', res);

        // Check if the response is okay
        if (!res.ok) {
            throw new Error("Network error or bad response");
        }

        // Attempt to parse the response as JSON
        return res.json();
    })
    .then(data => {
        console.log('Server response:', data);

        if (data.status === "success") {
            alert("Product added successfully!");

            const productHTML = `
                <div class="product-item" data-product-id="${Date.now()}">
                    <div class="product-details">
                        <h4>${formData.get('productName')} <span class="editing-label">New</span></h4>
                        <p>Category: ${formData.get('category')}</p>
                        <p>Description: ${description}</p>
                        <p>Price: PKR ${formData.get('price')} / ${formData.get('unit')}</p>
                        <p>Quantity: ${formData.get('quantity')} ${formData.get('unit')}</p>
                        <p>Unit: ${formData.get('unit')}</p>
                        <p>Available From: ${formData.get('Avalibility_Date')}</p>
                        <button class="edit-btn">Edit</button>
                        <button class="remove-btn">Remove</button>
                    </div>
                </div>
            `;

            const productList = document.getElementById('productList');
            let productItem;
            if (editingProduct) {
                productItem = editingProduct;
                productItem.innerHTML = productHTML;
                editingProduct = null;  // Reset after editing
            } else {
                productItem = document.createElement('div');
                productItem.classList.add('product-item');
                productItem.innerHTML = productHTML;
                productList.appendChild(productItem);
            }

            attachProductEventListeners();
            addProductForm.reset();
            toggleForm(false);
        } else {
            console.log("Error in server response:", data);
            alert("Error: " + data.message);
        }
    })
    .catch(error => {
        console.error('Error adding product:', error);
        alert("An error occurred while adding the product. Check the console for details.");
    });
});


// ===== Attach Edit & Remove Logic =====
function attachProductEventListeners() {

    const productList = document.getElementById('productList');

    productList.addEventListener('click', function (e) {
        const productItem = e.target.closest('.product-item');
        if (!productItem) return;

        const productId = productItem.getAttribute('data-product-id');

        // --- Remove Product ---
        if (e.target.classList.contains('remove-btn')) {
            if (confirm("Are you sure you want to delete this product?")) {
                fetch('handlers/deleteProductHandler.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: new URLSearchParams({ 'product_id': productId })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        productItem.remove();
                    } else {
                        alert(data.message || "Failed to delete.");
                    }
                });
            }
        }

        // --- Edit Product ---
        if (e.target.classList.contains('edit-btn')) {
            const btn = e.target;
            const name = productItem.querySelector('h4').innerText;
            const priceText = productItem.querySelector('p:nth-child(3)').innerText;
            const quantityText = productItem.querySelector('p:nth-child(4)').innerText;

            const price = priceText.match(/PKR\s([\d.]+)/)[1];
            const quantity = quantityText.match(/Quantity:\s([\d.]+)/)[1];

            // Replace with input fields
            productItem.querySelector('h4').outerHTML = `<input type="text" class="edit-name" value="${name}">`;
            productItem.querySelector('p:nth-child(3)').outerHTML = `<input type="number" step="0.01" class="edit-price" value="${price}">`;
            productItem.querySelector('p:nth-child(4)').outerHTML = `<input type="number" step="0.1" class="edit-quantity" value="${quantity}">`;

            // Set the 'edit' product as `editingProduct`
            editingProduct = productItem;

            // Change Edit to Save
            btn.textContent = "Save";
            btn.classList.add("save-btn");
            btn.classList.remove("edit-btn");

            // Rebind Save logic
            btn.addEventListener('click', function () {
                const newName = productItem.querySelector('.edit-name').value;
                const newPrice = productItem.querySelector('.edit-price').value;
                const newQuantity = productItem.querySelector('.edit-quantity').value;

                fetch('handlers/updateProductHandler.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: new URLSearchParams({
                        product_id: productId,
                        product_name: newName,
                        price: newPrice,
                        quantity: newQuantity
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        // Update the UI without reloading
                        alert("Product updated successfully!");
                        location.reload(); // Or you can update the UI directly without reload
                    } else {
                        alert(data.message || "Update failed.");
                    }
                });
            }, { once: true }); // Avoid multiple bindings
        }
    });
}

// Rest of the code remains the same...


function loadProducts() {
    fetch('handlers/farmer_view_products.php')
        .then(response => response.text())
        .then(data => {
            document.getElementById('productList').innerHTML = data;
        })
        .catch(err => console.log("Error fetching products:", err));
}

// ===== Load Order History =====
function loadOrderHistory(userType) {
    const url = userType === 'farmer'
        ? 'handlers/get_order_history_farmer.php'
        : 'handlers/get_order_history_vendor.php';

    fetch(url)
        .then(response => response.json())
        .then(data => {
            const list = document.getElementById('orderHistoryList');
            list.innerHTML = '';

            if (data.error) {
                list.innerHTML = `<p>${data.error}</p>`;
                return;
            }

            if (data.length === 0) {
                list.innerHTML = '<p>No order history found.</p>';
                return;
            }

            data.forEach(order => {
                const card = document.createElement('div');
                card.classList.add('order-card');
                card.innerHTML = `
                    <strong>Order ID:</strong> ${order.order_id}<br>
                    <strong>Date:</strong> ${order.order_date}<br>
                    <strong>Product:</strong> ${order.product_name}<br>
                    <strong>Quantity:</strong> ${order.quantity}<br>
                    <strong>Price:</strong> ₹${order.price}<br>
                    ${userType === 'farmer'
                        ? `<strong>Ordered by:</strong> ${order.ordered_by}<br>`
                        : `<strong>Farmer:</strong> ${order.farmer_name}<br>`}
                    <hr>
                `;
                list.appendChild(card);
            });
        })
        .catch(err => {
            console.error(err);
            document.getElementById('orderHistoryList').innerHTML = `<p>Error loading order history.</p>`;
        });
}

// ===== Sidebar Navigation =====
const links = document.querySelectorAll('.menu-link');
const sections = document.querySelectorAll('.content-section');

links.forEach(link => {
    link.addEventListener('click', (e) => {
        e.preventDefault();
        const targetId = link.getAttribute('data-target');

        sections.forEach(section => section.classList.remove('active'));
        links.forEach(l => l.classList.remove('active'));

        link.classList.add('active');
        document.getElementById(targetId).classList.add('active');

        if (targetId === 'viewProductsSection') {
            loadProducts();
        } else if (targetId === 'orderHistorySection') {
            const userType = document.body.getAttribute('data-person-type') || 'farmer';
            loadOrderHistory(userType);
        }
    });
});

// ===== About Link =====
document.getElementById('aboutLink').addEventListener('click', function () {
    document.getElementById('aboutPage').style.display = 'block';
    document.getElementById('homeContent').style.display = 'none';
});

// ===== Initialize =====
attachProductEventListeners();
