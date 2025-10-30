<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-8">Products</h1>
        
        <div id="products-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            </div>
    </div>

    <template id="product-template">
        <a href="#" class="product-link block bg-white rounded-lg shadow-md overflow-hidden transition duration-300 ease-in-out hover:shadow-xl">
            <img class="h-48 w-full object-cover product-image" src="" alt="Product Image Placeholder">
            
            <div class="p-4">
                <h2 class="text-xl font-semibold product-name"></h2>
                <p class="text-gray-600 mt-2 product-description"></p>
                <div class="mt-4 flex justify-between items-center">
                    <span class="text-lg font-bold product-price"></span>
                    <span class="text-sm text-gray-500">Stock: <span class="product-stock"></span></span>
                </div>
            </div>
        </a>
    </template>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetchProducts();
        });

        async function fetchProducts() {
            try {
                // Fetch products from the public API endpoint
                const response = await fetch('http://127.0.0.1:8000/api/products');
                
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }

                const data = await response.json();
                
                // Use data.data to access the products array from the resource collection
                displayProducts(data.data); 

            } catch (error) {
                console.error('Error fetching products:', error);
                document.getElementById('products-grid').innerHTML = '<p class="text-red-600">Failed to load products. Check console for API errors.</p>';
            }
        }

        function displayProducts(products) {
            const grid = document.getElementById('products-grid');
            const template = document.getElementById('product-template');

            products.forEach(product => {
                const clone = template.content.cloneNode(true);
                
                // 💡 FIX: Link to the Laravel route URL (/product-detail)
                const detailUrl = `/product-detail?uuid=${product.uuid}`; // Removed .blade.php
                clone.querySelector('.product-link').href = detailUrl;

                // Set product details
                clone.querySelector('.product-name').textContent = product.name;
                clone.querySelector('.product-description').textContent = product.description;
                
                // Formatting the price
                clone.querySelector('.product-price').textContent = new Intl.NumberFormat('en-US', {
                    style: 'currency',
                    currency: 'USD'
                }).format(product.price);
                
                clone.querySelector('.product-stock').textContent = product.stock;

                // Handle the Image URL
                const imgElement = clone.querySelector('.product-image');

                if (product.image && product.image.url) {
                    imgElement.src = product.image.url;
                    imgElement.alt = product.name;
                } else {
                    // Create a placeholder if no image URL is available
                    const placeholderDiv = document.createElement('div');
                    placeholderDiv.className = 'h-48 bg-gray-200 flex items-center justify-center';
                    placeholderDiv.innerHTML = '<span class="text-gray-500">No Image Available</span>';
                    
                    const productLink = clone.querySelector('.product-link');
                    // Replace the <img> element with the placeholder div
                    productLink.replaceChild(placeholderDiv, imgElement);
                }

                grid.appendChild(clone);
            });
        }
    </script>
</body>
</html>