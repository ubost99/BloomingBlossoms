Blooming Blossoms
Blooming Blossoms is a floral e-commerce website where users can browse and purchase beautiful floral arrangements. This project showcases an online shop for floral products with features like product management, a cart system, and a user-friendly interface. The project doesn't handle actual payments but allows users to add items to a cart, view products, and customize their theme.

Features
User Authentication: Users can register, log in, and manage their accounts.

Product Management: Admins can add, edit, and delete products.

Cart System: Users can add products to their cart and proceed with checkout (without payment).

Theme Customization: Users can choose between different themes (Default, Dark, Floral).

Responsive Design: The website is mobile-friendly and adjusts to different screen sizes.

Admin Dashboard: Admins can manage products and users through a secure dashboard.

Technologies Used
Frontend: HTML, CSS, JavaScript (Bootstrap for responsive design).

Backend: PHP for server-side logic.

Database: MySQL for storing user data, product information, and cart data.

File Uploads: Handling product image uploads for product management.

Prerequisites
Before you start, ensure the following requirements are met:
1. Web Server:
- Apache or Nginx (preferably Apache).
- PHP 7.4 or higher.
2. Database:
- MySQL 5.7 or higher.
3. Software Requirements:
- PHP extensions: mysqli, fileinfo, mbstring, and curl.
4. FTP/SFTP Access:
- Access to the server where you wish to install the site (for uploading files).

Steps to Set Up
Clone the Repository:

bash
Copy
git clone https://github.com/yourusername/blooming-blossoms.git
cd blooming-blossoms

This table stores user information, including authentication data and theme preferences.

Create SQL database and the following tables:

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,      -- Unique user ID
    name VARCHAR(255) NOT NULL,             -- User's full name
    email VARCHAR(255) NOT NULL UNIQUE,     -- User's email address
    password VARCHAR(255) NOT NULL,         -- User's hashed password
    theme VARCHAR(50) DEFAULT 'default.css',-- User's selected theme (default is light)
    is_admin TINYINT(1) DEFAULT 0,          -- Indicates if the user is an admin (1 = admin, 0 = regular)
    is_active TINYINT(1) DEFAULT 1          -- Indicates if the user's account is active (1 = active, 0 = disabled)
);

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,       -- Unique product ID
    name VARCHAR(255) NOT NULL,              -- Product name
    description TEXT NOT NULL,               -- Product description
    price DECIMAL(10, 2) NOT NULL,           -- Product price
    image VARCHAR(255) NOT NULL              -- Product image URL
);

Running the Application:

Open the site in your browser (e.g., https://localhost/blooming-blossoms).

Use the registration page to create an account and explore the features.

Contributing
Contributions are welcome! If you'd like to contribute to the project, feel free to submit a pull request.
