function fetchProducts() {
    fetch('vendor_handlers/fetch_products.php')
        .then(response => response.json())
        .then(data => {
            const productList = document.getElementById('productList');
            productList.innerHTML = '';

            if (data.error) {
                productList.innerHTML = `<p>${data.error}</p>`;
                return;
            }

            if (data.length > 0) {
                data.forEach(product => {
                    const productItem = document.createElement('div');
                    productItem.classList.add('product-item');

                    const farmName = product.farm_name || product.business_name || "Unknown";

                    productItem.innerHTML = `
                        <div class="product-image">
                            <img src="default-product.jpg" alt="${product.product_name}">
                        </div>
                        <div class="product-details">
                            <h4>${product.product_name}</h4>
                            <p>Price: Rs${product.price_per_unit}/${product.unit}</p>
                            <p>Available: ${product.quantity} ${product.unit}</p>
                            <p>Farm Name: ${farmName}</p>
                            <input type="number" id="qty-${product.product_id}" placeholder="Qty" min="1" value="1" style="width:60px; margin-right:10px;" />
                            <button onclick="addToCart(${product.product_id})">Add to Cart</button>
                        </div>
                    `;
                    productList.appendChild(productItem);
                });
            } else {
                productList.innerHTML = '<p>No products available.</p>';
            }
        })
        .catch(error => {
            console.error('Error fetching products:', error);
        });
}

function addToCart(productId) {
    const qtyInput = document.getElementById(`qty-${productId}`);
    const quantity = parseInt(qtyInput.value);

    // Validate the quantity
    if (isNaN(quantity) || quantity <= 0) {
        alert('Please enter a valid quantity.');
        return;
    }

    const formData = new URLSearchParams();
    formData.append('product_id', productId);
    formData.append('quantity', quantity);

    fetch('vendor_handlers/add_to_cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: formData.toString()
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        console.log('Response:', data);

        // Check if the response contains success or error
        if (data.success) {
            alert(data.success);
        } else if (data.error) {
            alert('Error: ' + data.error);
        } else {
            alert('Unexpected response received.');
        }
    })
    .catch(error => {
        console.error('Error adding to cart:', error);
        alert('Error occurred while adding product to cart.');
    });
}


window.onload = fetchProducts;
