# Brew & Bean - Coffee Shop E-commerce Platform

A modern, responsive PHP-based coffee shop e-commerce platform with user authentication, shopping cart, and payment checkout functionality.

## Features

- **User Authentication**: Secure login and registration system
- **Product Catalog**: Browse hot and iced coffee with filters and search
- **Shopping Cart**: Add/remove items, manage quantities with sessionStorage
- **Checkout & Payment**: Complete checkout flow with shipping and payment forms
- **Order Management**: Order history stored in database with status tracking
- **Responsive Design**: Mobile-first design with artisan café aesthetic

## Requirements

- PHP 7.4+
- SQLite3 (included in most PHP installations)
- Modern web browser with JavaScript enabled

## Installation

1. **Clone or download the project**

    ```bash
    git clone <repository-url>
    cd kuyhor_coffee_shop
    ```

2. **Configure environment**
    - Copy `.env.example` to `.env`
    - Default config uses SQLite (no setup needed)
    - See "Database Configuration" section below to switch to MySQL

3. **Database setup**
    - SQLite: Auto-created in `database/` folder on first run
    - MySQL: Create database manually, then update `.env`

4. **Start a local server**

    ```bash
    php -S localhost:8000
    ```

5. **Access the application**
    - Open browser: `http://localhost:8000`

## Database Configuration

The app supports both SQLite and MySQL with automatic fallback.

### Using SQLite (Default)

No additional setup required. Edit `.env`:

```
DB_DRIVER=sqlite
DB_SQLITE_PATH=database/coffee_shop.db
```

- Database created automatically on first run
- Perfect for development and testing

### Using MySQL

1. Create a MySQL database:

    ```sql
    CREATE DATABASE coffee_shop CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
    ```

2. Update `.env`:

    ```
    DB_DRIVER=mysql
    DB_MYSQL_HOST=localhost
    DB_MYSQL_PORT=3306
    DB_MYSQL_USER=root
    DB_MYSQL_PASSWORD=your_password
    DB_MYSQL_DATABASE=coffee_shop
    ```

3. Tables and sample data are created automatically on first run

### Auto-Fallback Feature

If MySQL connection fails, the application automatically falls back to SQLite.

This means:
- Start with SQLite during development
- Switch to MySQL for production
- If MySQL fails, app still works with SQLite as backup

## Project Structure

```
kuyhor_coffee_shop/
├── index.php              # Login page
├── register.php           # User registration
├── dashboard.php          # Product catalog & shopping
├── cart.php              # Shopping cart review
├── checkout.php          # Payment & order completion
├── logout.php            # Session logout
├── includes/
│   └── config.php        # Database setup & configuration
├── assets/
│   └── style.css         # All styling (design system)
├── docs/
│   ├── DB_SWITCH.md      # Quick database switching guide
│   └── SETUP.md          # Database setup guide
├── coffee_shop.db        # SQLite database (auto-created)
└── README.md            # This file
```

## Database Schema

### Users Table

```sql
CREATE TABLE users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    email TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)
```

### Products Table

```sql
CREATE TABLE products (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    category TEXT NOT NULL CHECK(category IN ('hot', 'iced')),
    price REAL NOT NULL,
    description TEXT NOT NULL,
    image_url TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)
```

### Orders Table

```sql
CREATE TABLE orders (
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
```

### Order Items Table

```sql
CREATE TABLE order_items (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    order_id INTEGER NOT NULL,
    product_id INTEGER NOT NULL,
    quantity INTEGER NOT NULL,
    price_at_purchase REAL NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
)
```

## User Flow

### 1. Authentication

- **Registration** (`register.php`): New users create an account with email and password
- **Login** (`index.php`): Existing users sign in with credentials
- Sessions managed with PHP `$_SESSION` superglobal

### 2. Shopping

- **Dashboard** (`dashboard.php`):
    - Browse all coffee products
    - Filter by category (Hot/Iced)
    - Search by product name
    - Add items to cart (stored in `sessionStorage`)
    - Cart badge shows item count

### 3. Cart Management

- **Cart Page** (`cart.php`):
    - Review all items in cart
    - Adjust quantities
    - Remove items
    - View order summary with subtotal, tax, and total
    - Proceed to checkout

### 4. Checkout & Payment

- **Checkout Page** (`checkout.php`):
    - Enter shipping address (street, city, ZIP)
    - Enter payment details (card number, name, expiry, CVC)
    - Review order summary
    - Submit payment (demo accepts all card numbers)
    - Order saved to database on success

### 5. Order Confirmation

- Success page displays order details
- Order stored in `orders` and `order_items` tables
- User can continue shopping

## Cart Management

The shopping cart uses **sessionStorage** to persist items in the browser during the session:

```javascript
// Cart data structure
{
  "productId": quantity,
  "2": 1,
  "5": 2,
  ...
}
```

- Cart data transfers to server when user proceeds to checkout
- Cart clears after order completion

## Payment Processing

**Current Implementation**: Demo payment (accepts any card number)

**For Production**, integrate with:

- **Stripe**: `checkout.php` can be updated to use Stripe API
- **PayPal**: Add PayPal SDK for alternative payment method
- **Square**: For in-person + online payments

Payment form validates:

- All fields required
- Card number minimum 13 digits
- Expiry format: MM/YY
- CVC: 3 digits

## Design System

The app uses a warm artisan café color palette:

```css
--color-espresso: #1c0a00 /* Dark brown */ --color-caramel: #c07d3a /* Warm caramel */
    --color-cream: #f5ecd7 /* Light cream */ --color-foam: #fdf8ef /* Bright white-cream */
    --color-milk: #ffffff /* Pure white */;
```

Typography:

- **Display**: Playfair Display (headings)
- **Body**: DM Sans (text content)

## Security Considerations

### Implemented

- Password hashing with `password_hash()`
- SQL parameterized queries to prevent injection
- HTML escaping with `htmlspecialchars()`
- Session-based authentication

### Recommended for Production

- Add CSRF tokens for forms
- Implement HTTPS/SSL
- Add rate limiting for login attempts
- Validate and sanitize all user inputs
- Add payment card tokenization (don't store raw cards)
- Implement PCI DSS compliance
- Add email verification for registration

## Responsive Breakpoints

- **Mobile**: < 480px
- **Tablet**: 480px - 768px
- **Desktop**: 768px - 1024px
- **Wide**: 1024px+

## Troubleshooting

### Database Issues

- **Error**: "Database connection failed"
    - Ensure `coffee_shop.db` directory is writable
    - Check PHP SQLite3 extension is enabled

### Cart Not Showing

- Clear browser sessionStorage: `sessionStorage.clear()`
- Ensure JavaScript is enabled
- Check browser console for errors

### Payment Not Completing

- Verify form fields are filled correctly
- Check browser developer tools network tab
- Ensure POST data is being sent to `checkout.php`

## Future Enhancements

- [ ] Real payment gateway integration (Stripe/PayPal)
- [ ] Email receipts after purchase
- [ ] Order history page for users
- [ ] Admin dashboard for inventory management
- [ ] Product reviews and ratings
- [ ] Wishlist functionality
- [ ] Coupon/discount codes
- [ ] Order tracking with shipping updates
- [ ] User profile and saved addresses
- [ ] Multiple payment methods (Apple Pay, Google Pay)

## License

Free to use for educational and commercial purposes.

## Author

Created by Khav Oudom
