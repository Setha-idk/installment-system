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
        
        <!-- Products Grid -->
        <div id="products-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <!-- Products will be inserted here -->
        </div>
    </div>

    <template id="product-template">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="h-48 bg-gray-200 flex items-center justify-center">
                <span class="text-gray-500">No Image</span>
            </div>
            <div class="p-4">
                <h2 class="text-xl font-semibold product-name"></h2>
                <p class="text-gray-600 mt-2 product-description"></p>
                <div class="mt-4 flex justify-between items-center">
                    <span class="text-lg font-bold product-price"></span>
                    <span class="text-sm text-gray-500">Stock: <span class="product-stock"></span></span>
                </div>
            </div>
        </div>
    </template>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetchProducts();
        });

        async function fetchProducts() {
            try {
                const response = await fetch('http://127.0.0.1:8000/api/products');
                const data = await response.json();
                displayProducts(data);
            } catch (error) {
                console.error('Error fetching products:', error);
            }
        }

        function displayProducts(products) {
            const grid = document.getElementById('products-grid');
            const template = document.getElementById('product-template');

            products.forEach(product => {
                const clone = template.content.cloneNode(true);
                
                // Set product details
                clone.querySelector('.product-name').textContent = product.name;
                clone.querySelector('.product-description').textContent = product.description;
                clone.querySelector('.product-price').textContent = `$${product.price}`;
                clone.querySelector('.product-stock').textContent = product.stock;

                grid.appendChild(clone);
            });
        }
    </script>
</body>
</html>