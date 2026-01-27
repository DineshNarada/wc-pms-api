# Assessment V4

## WooCommerce REST API Integration - Product Fetching

### Objective

Provide hands-on experience with REST APIs by integrating the WooCommerce REST API to fetch  
and display product data using authenticated API requests.

This assignment focuses on understanding:

- REST API fundamentals  
- Authentication using API keys  
- JSON data handling  
- External system integration with WooCommerce  

---

## Core Requirements

### 1. WooCommerce REST API Authentication

- Generate WooCommerce REST API keys:
  - Consumer Key
  - Consumer Secret
- Use key-based authentication to access the WooCommerce REST API
- Authentication must be implemented securely
- (Keys must not be hard-coded directly in publicly accessible files)

---

### 2. Product Data Fetching

Fetch product data using the WooCommerce REST API endpoints.
Required product data to retrieve:

- Product name
- Price
- Stock status
- Product image (featured image)

---

### 3. Custom Product Listing Page

- Create a custom product listing page (completely outside WordPress) that displays the  
  fetched data.
- Products must be rendered dynamically using API responses
- The page can be:
  - A standalone PHP page or
  - A static HTML page with JavaScript
- The page must:
  - Handle empty responses
  - Handle API errors gracefully
  - Display a user-friendly error message when the API fails
  - It communicates with WooCommerce only via REST API
  - Authentication is handled using API keys

---

### 4. REST API Request Handling

- All communication must be done via HTTP requests
- Data must be processed as JSON
- No direct database queries are allowed
- No WordPress functions may be used to fetch products directly

---

### Important Note

API keys must be treated as confidential credentials.  
They must not be exposed directly in frontend JavaScript in production environments.

---

### 5. Performance & Best Practices

- Implement basic response optimization:
  - Limit number of products per request
  - Use pagination where applicable
- API responses should be structured and reusable
- Code must be clean, readable, and well-organized

---

## Restrictions

❌ No WooCommerce shortcodes  
❌ No direct database access  
❌ No WooCommerce product loops (WC_Query, WP_Query)  
❌ No third-party plugins for API handling
