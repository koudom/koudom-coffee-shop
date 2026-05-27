<?php
require_once __DIR__ . '/env.php';
require_once __DIR__ . '/helpers.php';

loadEnv();

$dbDriver = app_env('DB_DRIVER', 'sqlite');
$pdo = null;

function getDB() {
    global $pdo, $dbDriver;

    if ($pdo !== null) {
        return $pdo;
    }

    $driver = strtolower(app_env('DB_DRIVER', 'sqlite'));

    if ($driver === 'mysql') {
        try {
            $host = app_env('DB_MYSQL_HOST', 'localhost');
            $port = app_env('DB_MYSQL_PORT', '3306');
            $user = app_env('DB_MYSQL_USER', 'root');
            $password = app_env('DB_MYSQL_PASSWORD', '');
            $database = app_env('DB_MYSQL_DATABASE', 'coffee_shop');

            $dsn = "mysql:host=$host;port=$port;dbname=$database;charset=utf8mb4";

            $pdo = new PDO(
                $dsn,
                $user,
                $password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]
            );

            $dbDriver = 'mysql';
            return $pdo;
        } catch (PDOException $e) {
            error_log("MySQL connection failed: " . $e->getMessage());
            error_log("Falling back to SQLite...");
            $_ENV['DB_DRIVER'] = 'sqlite';
            return getDBSQLite();
        }
    } else {
        return getDBSQLite();
    }
}

function getDBSQLite() {
    global $pdo;

    $dbPath = app_env('DB_SQLITE_PATH', __DIR__ . '/../database/coffee_shop.db');
    $dbDir = dirname($dbPath);

    if (!is_dir($dbDir)) {
        mkdir($dbDir, 0755, true);
    }

    try {
        $pdo = new PDO(
            "sqlite:" . $dbPath,
            null,
            null,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]
        );

        $pdo->exec("PRAGMA journal_mode=WAL");
        return $pdo;
    } catch (PDOException $e) {
        die("Database connection failed: " . $e->getMessage());
    }
}

function initDB() {
    $pdo = getDB();
    $driver = strtolower(app_env('DB_DRIVER', 'sqlite'));

    if ($driver === 'mysql') {
        initDBMySQL($pdo);
    } else {
        initDBSQLite($pdo);
    }
}

function initDBMySQL($pdo) {
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS products (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            category ENUM('hot', 'iced') NOT NULL,
            price DECIMAL(10, 2) NOT NULL,
            description TEXT NOT NULL,
            image_url VARCHAR(500) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS orders (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            total_amount DECIMAL(10, 2) NOT NULL,
            status VARCHAR(50) NOT NULL DEFAULT 'pending',
            payment_method VARCHAR(50),
            shipping_address TEXT NOT NULL,
            shipping_city VARCHAR(255) NOT NULL,
            shipping_zip VARCHAR(20) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS order_items (
            id INT AUTO_INCREMENT PRIMARY KEY,
            order_id INT NOT NULL,
            product_id INT NOT NULL,
            quantity INT NOT NULL,
            price_at_purchase DECIMAL(10, 2) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
            FOREIGN KEY (product_id) REFERENCES products(id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");

    $count = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
    if ($count == 0) {
        insertProducts($pdo);
    }
}

function initDBSQLite($pdo) {
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            email TEXT NOT NULL UNIQUE,
            password TEXT NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ");

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS products (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            category TEXT NOT NULL CHECK(category IN ('hot', 'iced')),
            price REAL NOT NULL,
            description TEXT NOT NULL,
            image_url TEXT NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ");

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS orders (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER NOT NULL,
            total_amount REAL NOT NULL,
            status TEXT NOT NULL DEFAULT 'pending',
            payment_method TEXT,
            shipping_address TEXT NOT NULL,
            shipping_city TEXT NOT NULL,
            shipping_zip TEXT NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id)
        )
    ");

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS order_items (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            order_id INTEGER NOT NULL,
            product_id INTEGER NOT NULL,
            quantity INTEGER NOT NULL,
            price_at_purchase REAL NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (order_id) REFERENCES orders(id),
            FOREIGN KEY (product_id) REFERENCES products(id)
        )
    ");

    $count = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
    if ($count == 0) {
        insertProducts($pdo);
    }
}

function insertProducts($pdo) {
    $products = [
        ['Espresso', 'hot', 3.50, 'Bold, intense single shot pulled to perfection.', 'https://images.unsplash.com/photo-1510591509098-f4fdc6d0ff04?w=400'],
        ['Cappuccino', 'hot', 4.50, 'Espresso topped with velvety steamed milk foam.', 'https://images.unsplash.com/photo-1572442388796-11668a67e53d?w=400'],
        ['Caramel Latte', 'hot', 5.00, 'Smooth latte kissed with house-made caramel drizzle.', 'https://images.unsplash.com/photo-1461023058943-07fcbe16d735?w=400'],
        ['Flat White', 'hot', 4.75, 'Micro-foam milk over a double ristretto. Rich and silky.', 'https://images.unsplash.com/photo-1517701604599-bb29b565090c?w=400'],
        ['Iced Americano', 'iced', 4.00, 'Double espresso over ice with cold water. Crisp and clean.', 'https://images.unsplash.com/photo-1517959105821-eaf2591984ca?w=400'],
        ['Cold Brew', 'iced', 5.50, '12-hour slow steep. Naturally sweet and incredibly smooth.', 'https://images.unsplash.com/photo-1541167760496-1628856ab772?w=400'],
        ['Iced Latte', 'iced', 5.00, 'Espresso and cold milk over ice. Light and refreshing.', 'https://images.unsplash.com/photo-1511920170033-f8396924c348?w=400'],
        ['Frappuccino', 'iced', 6.00, 'Blended coffee, ice, and cream. Sweet, cold perfection.', 'https://images.unsplash.com/photo-1570197788417-0e82375c9371?w=400'],
    ];

    $stmt = $pdo->prepare("
        INSERT INTO products (name, category, price, description, image_url)
        VALUES (?, ?, ?, ?, ?)
    ");

    foreach ($products as $product) {
        $stmt->execute($product);
    }
}

session_start();
