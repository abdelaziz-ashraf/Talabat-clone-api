# **Talabat API**

This project is a Talabat API clone designed to manage customers, delivery personnel, and vendors. It allows customers to place orders, manage their profiles, and interact with vendors, while vendors can manage their products, categories, and orders. 

## **Features**

### **For Customers:**
- **Authentication**: Customers can register, log in, and verify their account via code.
- **Profile Management**: Customers can view, update, or delete their accounts.
- **Address Management**: Customers can manage their addresses, set one as active, and add, update, or delete addresses.
- **Product Browsing**: Customers can view products from various vendors and categories.
- **Cart Management**: Customers can add items to the cart, view their cart, change item quantities, and proceed to checkout.
- **Order Management**: Customers can view their past orders and track their current order status.

### **For Vendors:**
- **Authentication**: Vendors can register, log in, and set their password.
- **Profile Management**: Vendors can manage their profiles and menu.
- **Address Management**: Vendors can add, update, or delete their addresses, and toggle the active status of their addresses.
- **Category and Product Management**: Vendors can manage categories and products, including adding, updating, and deleting them.
- **Order Management**: Vendors can view orders and change their status (e.g., processing, completed).

## **Postman**

[Published Postman Documentation](https://documenter.getpostman.com/view/30672560/2sAYJ4gzY8)

## **ToDo**

1. Assign delivery using location (the closer one).
2. Track delivery's path on the map.
3. Add payment gateway to pay using Credit Cart not only on delivery only.


## **Installation**

1. Clone the repository:
    ```bash
    git clone https://github.com/your-repository/talabat-clone-api.git
    ```
2. Navigate to the project directory:
    ```bash
    cd talabat-clone-api
    ```
3. Install dependencies:
    ```bash
    composer install
    ```
4. Set up the environment file:
    ```bash
    cp .env.example .env
    ```
5. Generate the application key:
    ```bash
    php artisan key:generate
    ```
6. Run the migrations:
    ```bash
    php artisan migrate
    ```
7. Serve the application:
    ```bash
    php artisan serve
    ```

## **Usage**

The API endpoints are structured under the following prefixes:
- `/customers`: For customer-related actions (authentication, profile, addresses, products, carts, orders).
- `/vendors`: For vendor-related actions (authentication, profile, products, orders).

Ensure that authentication tokens are provided when accessing protected routes.


## **Contributing**

1. Fork the repository.
2. Create a new branch for your feature or fix.
3. Submit a pull request detailing your changes.

