CREATE DATABASE IF NOT EXISTS restaurant_ordering_system;

USE restaurant_ordering_system;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

SET FOREIGN_KEY_CHECKS = 0;


-- ==========================================
-- DROP EXISTING TABLES
-- ==========================================

DROP TABLE IF EXISTS payments;
DROP TABLE IF EXISTS order_items;
DROP TABLE IF EXISTS orders;
DROP TABLE IF EXISTS cart_items;
DROP TABLE IF EXISTS carts;
DROP TABLE IF EXISTS menu_items;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS coupons;
DROP TABLE IF EXISTS contact_messages;
DROP TABLE IF EXISTS restaurant_settings;
DROP TABLE IF EXISTS users;


-- ==========================================
-- USERS
-- ==========================================

CREATE TABLE users (
    id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','customer') NOT NULL DEFAULT 'customer',
    status ENUM('active','inactive') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- ==========================================
-- DEFAULT ADMIN ACCOUNT
-- ==========================================

INSERT INTO users
(name, email, phone, password, role, status)
VALUES
(
    'Administrator',
    'admin@urbanbites.com',
    '03000000000',
    '$2y$12$bli5zcaSLwd8VusUfwYpX.e22MKskxtlQWu.h4ZWkjXbYpInuB4He',
    'admin',
    'active'
);


-- ==========================================
-- CATEGORIES
-- ==========================================

CREATE TABLE categories (
    id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description TEXT DEFAULT NULL,
    image VARCHAR(255) DEFAULT NULL,
    status TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- ==========================================
-- CATEGORY DATA
-- ==========================================

INSERT INTO categories
(id, name, description, image, status)
VALUES
(1, 'Pizza', 'Fresh Pizza', 'pizza.jpg', 1),
(2, 'Burger', 'Tasty Burger', 'burger.jpg', 1),
(3, 'Pasta', 'Creamy Pasta', 'pasta.jpg', 1),
(4, 'BBQ', 'Delicious BBQ', 'bbq.jpg', 1),
(5, 'Drinks', 'Cold Drinks', 'drinks.jpg', 1),
(6, 'Dessert', 'Sweet Dessert', 'dessert.jpg', 1);


-- ==========================================
-- MENU ITEMS
-- ==========================================

CREATE TABLE menu_items (
    id INT NOT NULL AUTO_INCREMENT,
    category_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    description TEXT DEFAULT NULL,
    price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    image VARCHAR(255) DEFAULT NULL,
    status TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY category_id (category_id),

    CONSTRAINT fk_menu_category
        FOREIGN KEY (category_id)
        REFERENCES categories(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- ==========================================
-- MENU DATA
-- ==========================================

INSERT INTO menu_items
(id, category_id, name, description, price, image, status)
VALUES

(
    1,
    6,
    'Chocolate Cake',
    'Soft chocolate sponge topped with rich chocolate cream.',
    9.99,
    'menu-1.jpg',
    1
),

(
    2,
    3,
    'Cheese Pasta',
    'Rich cheese sauce pasta with fresh vegetables.',
    13.49,
    'menu-2.jpg',
    1
),

(
    3,
    5,
    'Chocolate Shake',
    'Rich creamy chocolate shake topped with whipped cream.',
    10.99,
    'menu-3.jpg',
    1
),

(
    4,
    1,
    'Chicken Pizza',
    'Fresh oven-baked pizza topped with spicy grilled chicken.',
    13.49,
    'menu-4.jpg',
    1
),

(
    5,
    2,
    'Chicken Burger',
    'Juicy grilled chicken patty with melted cheese and fresh salad.',
    8.99,
    'menu-5.jpg',
    1
),

(
    6,
    4,
    'Grilled Steak',
    'Tender grilled steak served with fresh vegetables.',
    18.99,
    'menu-6.jpg',
    1
),

(
    7,
    6,
    'Signature Cupcake',
    'Chocolate cupcake with whipped cream topping.',
    4.99,
    'menu-7.jpg',
    1
),

(
    8,
    3,
    'Shrimp Spaghetti',
    'Delicious shrimp pasta with a rich tomato-based sauce.',
    14.99,
    'menu-8.jpg',
    1
),

(
    9,
    1,
    'Cheese Pizza',
    'Classic pizza topped with melted mozzarella cheese.',
    14.99,
    'menu-9.jpg',
    1
),

(
    10,
    2,
    'Mega Burger & Fries',
    'Juicy beef burger served with crispy fries.',
    12.99,
    'menu-10.jpg',
    1
),

(
    11,
    5,
    'Fresh Drinks',
    'Refreshing beverages to quench your thirst.',
    5.49,
    'menu-11.jpg',
    1
),

(
    12,
    4,
    'Special Grilled Tikka',
    'Juicy grilled chicken tikka served with crispy fries.',
    12.99,
    'menu-12.jpg',
    1
);


-- ==========================================
-- COUPONS
-- ==========================================

CREATE TABLE coupons (
    id INT NOT NULL AUTO_INCREMENT,
    code VARCHAR(50) NOT NULL,
    discount_type ENUM('fixed','percentage') NOT NULL DEFAULT 'percentage',
    discount_value DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    minimum_order DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    expiry_date DATE NOT NULL,
    status TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY code (code)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- ==========================================
-- CARTS
-- ==========================================

CREATE TABLE carts (
    id INT NOT NULL AUTO_INCREMENT,
    user_id INT NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY user_id (user_id),

    CONSTRAINT fk_cart_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- ==========================================
-- CART ITEMS
-- ==========================================

CREATE TABLE cart_items (
    id INT NOT NULL AUTO_INCREMENT,
    cart_id INT NOT NULL,
    menu_item_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    subtotal DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    PRIMARY KEY (id),
    KEY cart_id (cart_id),
    KEY menu_item_id (menu_item_id),

    CONSTRAINT fk_cartitem_cart
        FOREIGN KEY (cart_id)
        REFERENCES carts(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    CONSTRAINT fk_cartitem_menu
        FOREIGN KEY (menu_item_id)
        REFERENCES menu_items(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- ==========================================
-- ORDERS
-- ==========================================

CREATE TABLE orders (
    id INT NOT NULL AUTO_INCREMENT,
    user_id INT NOT NULL,
    customer_name VARCHAR(100) NOT NULL,
    customer_email VARCHAR(150) NOT NULL,
    customer_phone VARCHAR(30) NOT NULL,
    delivery_address TEXT DEFAULT NULL,
    city VARCHAR(100) DEFAULT NULL,
    order_notes TEXT DEFAULT NULL,
    coupon_id INT DEFAULT NULL,
    order_number VARCHAR(20) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    discount DECIMAL(10,2) NOT NULL,
    delivery_charges DECIMAL(10,2) NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    payment_method ENUM('cash','card') NOT NULL,
    payment_status ENUM('pending','paid','failed') NOT NULL,
    order_status ENUM(
        'new',
        'preparing',
        'on_the_way',
        'delivered',
        'cancelled'
    ) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (id),
    UNIQUE KEY order_number (order_number),
    KEY user_id (user_id),
    KEY coupon_id (coupon_id),

    CONSTRAINT fk_order_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    CONSTRAINT fk_order_coupon
        FOREIGN KEY (coupon_id)
        REFERENCES coupons(id)
        ON DELETE SET NULL
        ON UPDATE CASCADE

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- ==========================================
-- ORDER ITEMS
-- ==========================================

CREATE TABLE order_items (
    id INT NOT NULL AUTO_INCREMENT,
    order_id INT NOT NULL,
    menu_item_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    subtotal DECIMAL(10,2) NOT NULL DEFAULT 0.00,

    PRIMARY KEY (id),
    KEY order_id (order_id),
    KEY menu_item_id (menu_item_id),

    CONSTRAINT fk_orderitem_order
        FOREIGN KEY (order_id)
        REFERENCES orders(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    CONSTRAINT fk_orderitem_menu
        FOREIGN KEY (menu_item_id)
        REFERENCES menu_items(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- ==========================================
-- PAYMENTS
-- ==========================================

CREATE TABLE payments (
    id INT NOT NULL AUTO_INCREMENT,
    order_id INT NOT NULL,
    payment_method ENUM('cash','card') NOT NULL DEFAULT 'cash',
    amount DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    payment_status ENUM('pending','paid','failed') NOT NULL DEFAULT 'pending',
    transaction_id VARCHAR(100) DEFAULT NULL,
    paid_at DATETIME DEFAULT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (id),
    KEY order_id (order_id),

    CONSTRAINT fk_payment_order
        FOREIGN KEY (order_id)
        REFERENCES orders(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- ==========================================
-- CONTACT MESSAGES
-- ==========================================

CREATE TABLE contact_messages (
    id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    subject VARCHAR(150) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    status ENUM('Unread','Read') NOT NULL DEFAULT 'Unread',

    PRIMARY KEY (id)

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- ==========================================
-- RESTAURANT SETTINGS
-- ==========================================

CREATE TABLE restaurant_settings (
    id INT NOT NULL AUTO_INCREMENT,
    resturant_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(100) NOT NULL,
    address TEXT NOT NULL,
    opening_time TIME NOT NULL,
    closing_time TIME NOT NULL,
    delivery_charges DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    logo VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (id)

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- ==========================================
-- RESET AUTO INCREMENT
-- ==========================================

ALTER TABLE users AUTO_INCREMENT = 2;
ALTER TABLE categories AUTO_INCREMENT = 7;
ALTER TABLE menu_items AUTO_INCREMENT = 13;
ALTER TABLE coupons AUTO_INCREMENT = 1;
ALTER TABLE carts AUTO_INCREMENT = 1;
ALTER TABLE cart_items AUTO_INCREMENT = 1;
ALTER TABLE orders AUTO_INCREMENT = 1;
ALTER TABLE order_items AUTO_INCREMENT = 1;
ALTER TABLE payments AUTO_INCREMENT = 1;
ALTER TABLE contact_messages AUTO_INCREMENT = 1;
ALTER TABLE restaurant_settings AUTO_INCREMENT = 1;


SET FOREIGN_KEY_CHECKS = 1;