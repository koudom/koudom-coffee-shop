<?php
require_once __DIR__ . '/includes/config.php';
initDB();

requireAuth();

$pdo = getDB();

ob_start();
?>
<a href="dashboard.php" class="btn-back">← Back to Shop</a>
<?php
$navRight = ob_get_clean();
?>
<!DOCTYPE html>
<html lang="en">
<?php renderHead('Shopping Cart — Brew & Bean'); ?>
<body>
    <?php renderNavbar(['right' => $navRight]); ?>

    <main class="cart-page page-enter">
        <div class="cart-container">
            <h1 class="cart-title">Your Cart</h1>
            <div id="cartContent"></div>
        </div>
    </main>

    <script>
        const pdo_products = <?php
            $stmt = $pdo->prepare("SELECT id, name, price, image_url FROM products");
            $stmt->execute();
            echo json_encode($stmt->fetchAll());
        ?>;

        const productMap = {};
        pdo_products.forEach(p => {
            productMap[p.id] = p;
        });

        let cart = JSON.parse(sessionStorage.getItem('cbs_cart') || '[]');
        const cartData = {};
        cart.forEach(item => {
            cartData[item.productId] = item.quantity;
        });

        function renderCart() {
            const container = document.getElementById('cartContent');
            const productIds = Object.keys(cartData);

            if (productIds.length === 0) {
                container.innerHTML = `
                    <div class="empty-cart">
                        <div class="empty-cart-icon">🛒</div>
                        <h2>Your cart is empty</h2>
                        <p>Looks like you haven't added any coffee yet. Let's fix that!</p>
                        <a href="dashboard.php" class="btn btn-primary">Continue Shopping</a>
                    </div>
                `;
                return;
            }

            let total = 0;
            let itemsHTML = '';

            productIds.forEach(id => {
                const product = productMap[id];
                if (!product) return;

                const qty = cartData[id];
                const subtotal = product.price * qty;
                total += subtotal;

                itemsHTML += `
                    <div class="cart-item">
                        <img src="${product.image_url}" alt="${product.name}" class="cart-item-image">
                        <div class="cart-item-details">
                            <h3>${product.name}</h3>
                            <p class="cart-item-price">$${product.price.toFixed(2)} each</p>
                        </div>
                        <div class="cart-item-quantity">
                            <button class="qty-btn" data-product-id="${id}" data-action="decrease">−</button>
                            <input type="number" class="qty-input" value="${qty}" min="1" data-product-id="${id}">
                            <button class="qty-btn" data-product-id="${id}" data-action="increase">+</button>
                        </div>
                        <div class="cart-item-subtotal">
                            $${subtotal.toFixed(2)}
                        </div>
                        <button class="btn-remove" data-product-id="${id}" title="Remove item">×</button>
                    </div>
                `;
            });

            const tax = total * 0.08;
            const finalTotal = total + tax;

            container.innerHTML = `
                <div class="cart-content">
                    <div class="cart-items">${itemsHTML}</div>
                    <div class="cart-summary">
                        <h2>Order Summary</h2>
                        <div class="summary-row">
                            <span>Subtotal</span>
                            <span>$${total.toFixed(2)}</span>
                        </div>
                        <div class="summary-row">
                            <span>Shipping</span>
                            <span>$0.00</span>
                        </div>
                        <div class="summary-row">
                            <span>Tax</span>
                            <span>$${tax.toFixed(2)}</span>
                        </div>
                        <div class="summary-row summary-total">
                            <span>Total</span>
                            <span>$${finalTotal.toFixed(2)}</span>
                        </div>
                        <form id="checkoutForm" action="checkout.php" method="POST">
                            <input type="hidden" name="cart" id="cartData" value="">
                            <button type="submit" class="btn btn-primary btn-large">Proceed to Checkout</button>
                        </form>
                        <a href="dashboard.php" class="btn btn-secondary btn-large">Continue Shopping</a>
                    </div>
                </div>
            `;

            attachEventListeners();
        }

        function updateCart(productId, quantity) {
            if (quantity > 0) {
                cartData[productId] = quantity;
            } else {
                delete cartData[productId];
            }
            const updatedCart = Object.entries(cartData).map(([pid, qty]) => ({
                productId: parseInt(pid),
                quantity: qty
            }));
            sessionStorage.setItem('cbs_cart', JSON.stringify(updatedCart));
            renderCart();
        }

        function attachEventListeners() {
            document.querySelectorAll('.qty-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const productId = this.dataset.productId;
                    const input = document.querySelector(`.qty-input[data-product-id="${productId}"]`);
                    let qty = parseInt(input.value);

                    if (this.dataset.action === 'increase') qty++;
                    else if (qty > 1) qty--;

                    updateCart(productId, qty);
                });
            });

            document.querySelectorAll('.btn-remove').forEach(btn => {
                btn.addEventListener('click', function() {
                    const productId = this.dataset.productId;
                    updateCart(productId, 0);
                });
            });

            document.getElementById('checkoutForm')?.addEventListener('submit', function() {
                document.getElementById('cartData').value = JSON.stringify(cartData);
            });
        }

        renderCart();
    </script>
</body>
</html>
