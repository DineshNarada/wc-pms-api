// script to fetch and display products with pagination to frontend/products.html

const API_ENDPOINT = '../api/fetch-products.php';
const INVENTORY_FRAGMENT_ENDPOINT = '../api/fetch-inventory-fragment.php';
let currentPage = 1;
let totalPages = 1;
let inventoryRefreshInterval = null;

/**
 * Escape HTML to prevent XSS
 * @param {string} text 
 * @returns {string}
 */
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

/**
 * Open lightbox modal to view image
 * @param {string} imageUrl 
 * @param {string} altText 
 */
function openLightbox(imageUrl, altText) {
    const lightbox = document.getElementById('lightbox');
    const lightboxImage = document.getElementById('lightbox-image');
    const lightboxAlt = document.getElementById('lightbox-alt');
    
    lightboxImage.src = imageUrl;
    lightboxImage.alt = altText;
    lightboxAlt.textContent = altText;
    lightbox.style.display = 'flex';
}

/**
 * Close lightbox modal
 */
function closeLightbox() {
    const lightbox = document.getElementById('lightbox');
    lightbox.style.display = 'none';
}

/**
 * Display a network error message in the content div
 * @param {Response} response 
 * @param {string} responseText 
 */
function displayNetworkError(response, responseText) {
    const content = document.getElementById('content');
    console.error('Network Error Response:', {
        status: response.status,
        statusText: response.statusText,
        contentType: response.headers.get('content-type'),
        body: responseText
    });
    
    let errorMsg = `Failed to fetch products (HTTP ${response.status})`;
    if (responseText && responseText.length > 0) {
        // Try to extract meaningful error from response HTML
        const match = responseText.match(/<title>(.*?)<\/title>/);
        if (match) {
            errorMsg = `Server Error: ${match[1]}`;
        }
    }
    
    content.innerHTML = `
        <div class="error-message">
            <strong>Error:</strong> ${escapeHtml(errorMsg)}<br>
            <small>Check browser console for details.</small>
        </div>
    `;
}

/**
 * Load products from the API
 * @param {number} page 
 */
async function loadProducts(page = 1) {
    const content = document.getElementById('content');
    content.innerHTML = '<div class="loading">Loading products...</div>';

    try {
        const response = await fetch(`${API_ENDPOINT}?page=${page}&per_page=12`);
        const responseText = await response.text();

        // Check if response is valid JSON
        if (!response.ok || !responseText.trim().startsWith('{')) {
            displayNetworkError(response, responseText);
            return;
        }

        const data = JSON.parse(responseText);

        if (!data.success) {
            content.innerHTML = `
                <div class="error-message">
                    <strong>Error:</strong> ${escapeHtml(data.error || 'Unknown error')}
                </div>
            `;
            return;
        }

        currentPage = data.currentPage;
        totalPages = data.totalPages;

        if (data.products.length === 0) {
            content.innerHTML = `
                <div class="empty-state">
                    <h2>No Products Found</h2>
                    <p>It seems there are no products available at the moment.</p>
                </div>
            `;
            return;
        }

        let html = '<div class="products-grid">';

            data.products.forEach(product => {
            const price = parseFloat(product.price);
            const formattedPrice = isNaN(price) ? 'N/A' : price.toFixed(2);
            const stockClass = product.stock_status === 'instock' ? 'stock-instock' : 'stock-outofstock';
            const stockQuantity = product.stock_quantity || 0;
            const stockText = product.stock_status === 'instock' 
                ? `✓ In Stock (${stockQuantity} ${stockQuantity === 1 ? 'item' : 'items'})` 
                : '✗ Out of Stock';
            const salePrice = product.sale_price ? `<span class="original">${parseFloat(product.regular_price).toFixed(2)}</span>` : '';

            html += `
                <div class="product-card" data-product-id="${product.id}">
                    <img src="${product.featured_image}" alt="${escapeHtml(product.name)}" class="product-image" onclick="openLightbox('${product.featured_image}', '${escapeHtml(product.name)}')"
                        onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22200%22 height=%22200%22%3E%3Crect fill=%22%23ddd%22 width=%22200%22 height=%22200%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 dominant-baseline=%22middle%22 text-anchor=%22middle%22 font-family=%22Arial%22 font-size=%2214%22 fill=%22%23999%22%3ENo Image%3C/text%3E%3C/svg%3E'">
                    <div class="product-info">
                        <h3 class="product-name">${escapeHtml(product.name)}</h3>
                        <div class="product-price">${salePrice}${formattedPrice}</div>
                        <span class="product-stock ${stockClass}">${stockText}</span>
                    </div>
                </div>
            `;
        });

        html += '</div>';

        // Pagination controls
        html += '<div class="pagination">';

        if (currentPage > 1) {
            html += `<button onclick="loadProducts(1)">« First</button>`;
            html += `<button onclick="loadProducts(${currentPage - 1})">‹ Previous</button>`;
        }

        for (let i = Math.max(1, currentPage - 2); i <= Math.min(totalPages, currentPage + 2); i++) {
            const activeClass = i === currentPage ? 'active' : '';
            html += `<button onclick="loadProducts(${i})" class="${activeClass}">${i}</button>`;
        }

        if (currentPage < totalPages) {
            html += `<button onclick="loadProducts(${currentPage + 1})">Next ›</button>`;
            html += `<button onclick="loadProducts(${totalPages})">Last »</button>`;
        }

        html += '</div>';

        if (totalPages > 1) {
            html += `<div class="pagination-info">Page ${currentPage} of ${totalPages} (${data.total} total products)</div>`;
        }

        content.innerHTML = html;

    } catch (error) {
        console.error('Fetch error:', error);
        content.innerHTML = `
            <div class="error-message">
                <strong>Error:</strong> Failed to fetch products. Please check your connection and try again.
            </div>
        `;
    }
}

// Initialize products on page load
document.addEventListener('DOMContentLoaded', () => {
    loadProducts(1);

    // Start inventory refresh every 60 seconds
    startInventoryRefresh(60000);
    
    // Setup lightbox close on background click
    const lightbox = document.getElementById('lightbox');
    if (lightbox) {
        lightbox.addEventListener('click', (e) => {
            if (e.target === lightbox) {
                closeLightbox();
            }
        });
    }
});

/**
 * Close lightbox on Escape key
 */
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        closeLightbox();
    }
});

/**
 * Update inventory for a single product without page refresh
 * @param {number} productId 
 * @param {HTMLElement} stockElement 
 */
async function updateProductInventory(productId, stockElement) {
    try {
        const response = await fetch(`${INVENTORY_FRAGMENT_ENDPOINT}?product_id=${productId}`);
        const data = await response.json();

        if (data.success && stockElement) {
            stockElement.innerHTML = data.stock_html;
        }
    } catch (error) {
        console.error('Inventory update error:', error);
    }
}

/**
 * Update all product inventories on the current page
 */
async function updateAllInventories() {
    const productCards = document.querySelectorAll('[data-product-id]');
    
    productCards.forEach(card => {
        const productId = card.getAttribute('data-product-id');
        const stockSpan = card.querySelector('.product-stock');
        if (productId && stockSpan) {
            updateProductInventory(productId, stockSpan);
        }
    });
}

/**
 * Start auto-refresh for inventory (checks every 30 seconds)
 * @param {number} intervalMs - Interval in milliseconds (default: 30000ms = 30 seconds)
 */
function startInventoryRefresh(intervalMs = 30000) {
    // Clear existing interval if any
    if (inventoryRefreshInterval) {
        clearInterval(inventoryRefreshInterval);
    }
    
    inventoryRefreshInterval = setInterval(updateAllInventories, intervalMs);
    console.log(`Inventory refresh started: every ${intervalMs / 1000} seconds`);
}

/**
 * Stop auto-refresh for inventory
 */
function stopInventoryRefresh() {
    if (inventoryRefreshInterval) {
        clearInterval(inventoryRefreshInterval);
        inventoryRefreshInterval = null;
        console.log('Inventory refresh stopped');
    }
}
