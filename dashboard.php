<?php
require_once __DIR__ . '/includes/config.php';
initDB();

$user = requireAuth();
$pdo = getDB();

$category = $_GET['category'] ?? '';
$search = trim($_GET['search'] ?? '');

$sql = "SELECT * FROM products WHERE 1=1";
$params = [];

if ($category === 'hot' || $category === 'iced') {
    $sql .= " AND category = ?";
    $params[] = $category;
}

if ($search !== '') {
    $sql .= " AND name LIKE ?";
    $params[] = "%$search%";
}

$sql .= " ORDER BY id ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();

ob_start();
?>
<div class="search-bar">
    <span class="search-icon">🔍</span>
    <input
        type="text"
        id="searchInput"
        placeholder="Search coffee..."
        value="<?= e($search) ?>"
    >
</div>
<?php
$navCenter = ob_get_clean();

ob_start();
?>
<a href="cart.php" class="cart-icon" id="cartIcon" title="View cart">
    🛒
    <span class="cart-badge" id="cartBadge">0</span>
</a>
<span class="user-name"><?= e($user['name']) ?></span>
<a href="logout.php" class="btn-logout">Logout</a>
<?php
$navRight = ob_get_clean();
?>
<!DOCTYPE html>
<html lang="en">
<?php renderHead('Brew & Bean — Dashboard'); ?>
<body>
    <?php renderNavbar(['logoIsLink' => false, 'center' => $navCenter, 'right' => $navRight]); ?>

    <main class="dashboard page-enter">
        <!-- Hero -->
        <section class="hero">
            <h1 class="hero-title">Good <?= date('a') === 'am' ? 'morning' : 'afternoon' ?>, <?= e(explode(' ', $user['name'])[0]) ?> ☕</h1>
            <p class="hero-subtitle">What are you craving today?</p>
        </section>

        <!-- Filters -->
        <div class="filters">
            <a href="dashboard.php<?= $search ? '?search=' . urlencode($search) : '' ?>" class="filter-pill <?= $category === '' ? 'active' : '' ?>">All</a>
            <a href="dashboard.php?category=hot<?= $search ? '&search=' . urlencode($search) : '' ?>" class="filter-pill <?= $category === 'hot' ? 'active' : '' ?>">🔥 Hot Coffee</a>
            <a href="dashboard.php?category=iced<?= $search ? '&search=' . urlencode($search) : '' ?>" class="filter-pill <?= $category === 'iced' ? 'active' : '' ?>">🧊 Iced Coffee</a>
        </div>

        <!-- Product Grid -->
        <?php if (empty($products)): ?>
            <div class="empty-state">
                <p>No products found. Try a different search or category.</p>
            </div>
        <?php else: ?>
            <div class="product-grid" id="productGrid">
                <?php foreach ($products as $product): ?>
                    <div class="product-card" data-id="<?= e($product['id']) ?>">
                        <div class="product-image-wrapper">
                            <img
                                src="<?= e($product['image_url']) ?>"
                                alt="<?= e($product['name']) ?>"
                                class="product-image"
                                loading="lazy"
                            >
                            <span class="category-tag tag-<?= e($product['category']) ?>">
                                <?= $product['category'] === 'hot' ? '🔥 Hot' : '🧊 Iced' ?>
                            </span>
                        </div>
                        <div class="product-info">
                            <h3 class="product-name"><?= e($product['name']) ?></h3>
                            <p class="product-desc"><?= e($product['description']) ?></p>
                            <div class="product-footer">
                                <span class="product-price">$<?= number_format($product['price'], 2) ?></span>
                                <button class="btn-add-cart" data-id="<?= e($product['id']) ?>" data-name="<?= e($product['name']) ?>">+</button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>

    <!-- About Section -->
    <section class="about">
        <div class="about-inner">
            <h2 class="about-title">☕ About Brew & Bean</h2>
            <div class="about-divider"></div>
            <p class="about-text">
                We source single-origin beans from small farms around the world and roast them in small batches right here in our shop. Every cup is crafted with care — because great coffee starts with great ingredients and a little bit of love.
            </p>
        </div>
    </section>

    <script>
        let searchTimeout;
        const searchInput = document.getElementById('searchInput');

        searchInput.addEventListener('input', function () {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                const params = new URLSearchParams(window.location.search);
                const cat = params.get('category');
                let url = 'dashboard.php';
                const parts = [];
                if (this.value) parts.push('search=' + encodeURIComponent(this.value));
                if (cat) parts.push('category=' + encodeURIComponent(cat));
                if (parts.length) url += '?' + parts.join('&');
                window.location.href = url;
            }, 400);
        });

        let cart = JSON.parse(sessionStorage.getItem('cbs_cart') || '[]');

        function updateCartBadge() {
            const badge = document.getElementById('cartBadge');
            const count = cart.reduce((sum, item) => sum + item.quantity, 0);
            badge.textContent = count;
        }

        document.querySelectorAll('.btn-add-cart').forEach(btn => {
            btn.addEventListener('click', function () {
                const id = this.dataset.id;
                const existing = cart.find(item => item.productId === id);
                if (existing) {
                    existing.quantity++;
                } else {
                    cart.push({ productId: id, quantity: 1 });
                }
                sessionStorage.setItem('cbs_cart', JSON.stringify(cart));
                updateCartBadge();

                const cartIcon = document.getElementById('cartIcon');
                cartIcon.style.transform = 'scale(1.2)';
                setTimeout(() => { cartIcon.style.transform = ''; }, 200);
            });
        });

        document.getElementById('cartIcon').addEventListener('click', function(e) {
            const cartData = {};
            cart.forEach(item => {
                cartData[item.productId] = item.quantity;
            });
            if (Object.keys(cartData).length === 0) {
                e.preventDefault();
                alert('Your cart is empty. Add some coffee first!');
            }
        });

        updateCartBadge();
    </script>
</body>
</html>
