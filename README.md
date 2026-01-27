# WooCommerce REST API – Product Management System - PHP

This project demonstrates how to fetch and display WooCommerce products using the **WooCommerce REST API** from an **external PHP application** (outside of WordPress).

## Requirements
- PHP 8.0+
- Composer
- Local server (XAMPP / WAMP / Laragon)
- WooCommerce store with REST API enabled

## Installation Steps

1. **Clone or unzip the repository** into your local server directory  
   (e.g. `htdocs` or `www`)

2. **Install Composer**  
   Download and install Composer from: [https://getcomposer.org/download/](https://getcomposer.org/download/)

3. **Install WooCommerce PHP SDK**  
   Open Command Prompt in the project root and run:
   ```bash
   composer require automattic/woocommerce
    ```

4. **Configure Environment Variables**
    - Copy `.env.example` to `.env`
    - Add your WooCommerce API credentials and site URL:
    ```.env
        WC_API_URL=http://localhost/your-site/wp-json/wc/v3/
        WC_CONSUMER_KEY=ck_xxxxxxxxxxxxxxxxxxxxx
        WC_CONSUMER_SECRET=cs_xxxxxxxxxxxxxxxxxxxxx
    ```
5. **Run the project**
 - Start your local server
 - Open the project in your browser: `http://localhost/project-folder/`

## Features ##
- Secure WooCommerce REST API authentication
- Fetch product data using REST API
- Display:
    - Product name
    - Price
    - Stock status
    - Featured image

## Extra Resources ##
[WooCommerce PHP API Library](https://github.com/woocommerce/wc-api-php)

## Notes ##
- API keys should never be hard-coded in public files
- `.env` file should be excluded from Git using `.gitignore`# wc-pms-api
