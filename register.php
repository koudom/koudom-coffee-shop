<?php
require_once __DIR__ . '/includes/config.php';
initDB();

requireGuest();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';

    if (empty($name) || empty($email) || empty($password)) {
        $error = 'Please fill in all fields.';
    } elseif (strlen($name) < 2) {
        $error = 'Name must be at least 2 characters.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } elseif ($password !== $confirm) {
        $error = 'Passwords do not match.';
    } else {
        $pdo = getDB();
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->fetch()) {
            $error = 'An account with this email already exists.';
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$name, $email, $hashed]);

            $_SESSION['user'] = [
                'id' => $pdo->lastInsertId(),
                'name' => $name,
                'email' => $email,
            ];
            redirectTo('dashboard.php');
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<?php renderHead('Register — Brew & Bean'); ?>
<body class="auth-page">
    <div class="auth-wrapper">
        <div class="auth-card page-enter">
            <div class="auth-logo">
                <span class="logo-icon">☕</span>
                <h1 class="logo-text">Brew & Bean</h1>
            </div>
            <h2 class="auth-title">Join us</h2>
            <p class="auth-subtitle">Create your account</p>

            <?php if ($error): ?>
                <div class="message message-error"><?= e($error) ?></div>
            <?php endif; ?>

            <form method="POST" class="auth-form" novalidate>
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" placeholder="Alex Johnson" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="your@email.com" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Min. 6 characters" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Repeat password" required>
                </div>
                <button type="submit" class="btn btn-primary">Create Account</button>
            </form>

            <p class="auth-link">
                Already have an account? <a href="index.php">Sign in</a>
            </p>
        </div>
    </div>
</body>
</html>
