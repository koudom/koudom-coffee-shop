<?php
require_once __DIR__ . '/includes/config.php';
initDB();

requireGuest();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = 'Please fill in all fields.';
    } else {
        $pdo = getDB();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
            ];
            redirectTo('dashboard.php');
        } else {
            $error = 'Invalid email or password.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<?php renderHead('Login — Brew & Bean'); ?>
<body class="auth-page">
    <div class="auth-wrapper">
        <div class="auth-card page-enter">
            <div class="auth-logo">
                <span class="logo-icon">☕</span>
                <h1 class="logo-text">Brew & Bean</h1>
            </div>
            <h2 class="auth-title">Welcome back</h2>
            <p class="auth-subtitle">Sign in to your account</p>

            <?php if ($error): ?>
                <div class="message message-error"><?= e($error) ?></div>
            <?php endif; ?>

            <form method="POST" class="auth-form" novalidate>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="your@email.com" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="••••••••" required>
                </div>
                <button type="submit" class="btn btn-primary">Sign In</button>
            </form>

            <p class="auth-link">
                Don't have an account? <a href="register.php">Create one</a>
            </p>
        </div>
    </div>
</body>
</html>
