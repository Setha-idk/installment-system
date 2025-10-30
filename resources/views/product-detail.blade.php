<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title id="page-title">Product Details</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Optional: Keep the page content centered and readable */
        .product-container {
            max-width: 900px;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="container mx-auto px-4 py-10 product-container">
        <div id="loading" class="text-center text-gray-500 text-lg">Loading product details...</div>
        <div id="product-detail" class="bg-white rounded-xl shadow-lg p-6 hidden">
            
            <div class="md:flex md:space-x-8">
                <div class="md:w-1/2">
                    <img id="product-image" class="w-full h-80 object-cover rounded-lg shadow-md mb-6 md:mb-0" 
                         src="" alt="Product Image">
                </div>

                <div class="md:w-1/2">
                    <h1 id="product-name" class="text-4xl font-extrabold text-gray-800 mb-2"></h1>
                    <p class="text-sm font-medium text-gray-500 mb-4">Category: <span id="product-category">N/A</span></p>

                    <p id="product-description" class="text-gray-700 mb-6 border-b pb-4"></p>
                    
                    <div class="flex items-center justify-between mb-6">
                        <span class="text-4xl font-bold text-indigo-600" id="product-price"></span>
                        <span class="text-lg text-gray-600 bg-gray-200 px-3 py-1 rounded-full">Stock: <span id="product-stock"></span></span>
                    </div>

                    <h3 class="text-xl font-semibold text-gray-800 mb-3">Available Payment Plans</h3>
                    <ul id="plans-list" class="space-y-3">
                        <li class="text-gray-500">No plans available.</li>
                    </ul>
                </div>
            </div>

        </div>
        <div id="error-message" class="text-center text-red-600 text-lg hidden"></div>
    </div>

    <template id="plan-template">
        <li class="bg-indigo-50 p-3 rounded-lg flex justify-between items-center border border-indigo-200">
            <div class="flex flex-col">
                <span class="font-semibold text-indigo-800 plan-name">Plan Name</span>
                <span class="text-sm text-indigo-600 plan-description">Description</span>
            </div>
            <div class="text-right">
                <span class="text-md font-bold text-indigo-700 plan-installments">3 Installments</span>
                <span class="block text-xs text-indigo-500 plan-rate">@ 0.00% APR</span>
            </div>
        </li>
    </template>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Get the Product Identifier (e.g., UUID) from the URL query parameters or hash
            const params = new URLSearchParams(window.location.search || window.location.hash.slice(1));
            const productIdentifier = params.get('uuid'); // Accepts both ?uuid=xxx and #uuid=xxx formats

            if (productIdentifier) {
                fetchProductDetail(productIdentifier);
            } else {
                displayError("Product ID not provided in the URL.");
            }
        });

        async function fetchProductDetail(identifier) {
            const endpoint = `http://127.0.0.1:8000/api/products/${identifier}`;
            
            try {
                const response = await fetch(endpoint);
                const result = await response.json();

                if (!response.ok) {
                    throw new Error(result.message || `HTTP error! Status: ${response.status}`);
                }

                // The product data is wrapped in a 'data' object
                displayProduct(result.data); 

            } catch (error) {
                console.error('Error fetching product details:', error);
                displayError("Product not found or API error.");
            } finally {
                document.getElementById('loading').classList.add('hidden');
            }
        }

        function displayProduct(product) {
            document.getElementById('product-detail').classList.remove('hidden');
            document.getElementById('page-title').textContent = product.name;

            // 1. Core Details
            document.getElementById('product-name').textContent = product.name;
            document.getElementById('product-description').textContent = product.description;
            document.getElementById('product-stock').textContent = product.stock;

            // 2. Formatting Price
            const price = parseFloat(product.price);
            document.getElementById('product-price').textContent = new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'USD'
            }).format(price);

            // 3. Category Detail (Uses the nested object from the API)
            document.getElementById('product-category').textContent = product.category ? product.category.name : 'Uncategorized';
            
            // 4. Image
            const imgElement = document.getElementById('product-image');
            if (product.image && product.image.url) {
                // Add the base URL if the image path is relative
                const imageUrl = product.image.url.startsWith('http') 
                    ? product.image.url 
                    : `http://127.0.0.1:8000${product.image.url}`;
                imgElement.src = imageUrl;
                imgElement.alt = `Image of ${product.name}`;
            } else {
                imgElement.src = 'https://via.placeholder.com/600x400?text=No+Image+Available';
                imgElement.alt = 'No image available';
            }

            // 5. Installment Plans
            displayPlans(product.plans);
        }

        function displayPlans(plans) {
            const list = document.getElementById('plans-list');
            const template = document.getElementById('plan-template');
            
            // Clear default 'No plans available' message if plans exist
            if (plans && plans.length > 0) {
                list.innerHTML = ''; 

                plans.forEach(plan => {
                    const clone = template.content.cloneNode(true);
                    
                    clone.querySelector('.plan-name').textContent = plan.name;
                    clone.querySelector('.plan-description').textContent = plan.description;
                    clone.querySelector('.plan-installments').textContent = `${plan.installments_count} Installments`;
                    clone.querySelector('.plan-rate').textContent = `@ ${plan.interest_rate.toFixed(2)}% APR`;

                    list.appendChild(clone);
                });
            } else {
                list.innerHTML = '<li class="text-gray-500">No special installment plans are currently offered for this product.</li>';
            }
        }

        function displayError(message) {
            document.getElementById('loading').classList.add('hidden');
            const errorDiv = document.getElementById('error-message');
            errorDiv.textContent = message;
            errorDiv.classList.remove('hidden');
        }
    </script>
</body>
</html>