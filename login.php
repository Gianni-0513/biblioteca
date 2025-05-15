<?php
require_once 'functions.php';
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (login($_POST['email'], $_POST['password'])) {
        if (isAdmin()) header('Location: admin_dashboard.php');
        else header('Location: user_dashboard.php');
        exit;
    } else {
        $message = 'Credenziali errate';
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Login - Biblioteca</title>
    <link rel="stylesheet" href="./css/index.css">
</head>
<body>

<!-- Navbar -->
<div class="navbar">
    <div class="navbar-container">
        <div class="navbar-brand"><i>📚</i> Biblioteca</div>
        <div class="navbar-links">
            <a href="index.php" class="nav-btn">Home</a>
            <a href="register.php" class="nav-btn">Registrati</a>
        </div>
    </div>
</div>

<!-- Contenuto -->
<div class="main-content">
    <h2 class="main-title">Login Utente</h2>
    
    <div class="login-form-container">
        <form method="post" class="login-form">
            <label>Email</label>
            <input type="email" name="email" required>
            
            <label>Password</label>
            <input type="password" name="password" required>
            
            <button type="submit">Accedi</button>

            <?php if ($message): ?>
                <p class="error-message"><?= htmlspecialchars($message) ?></p>
            <?php endif; ?>
        </form>
    </div>
</div>

<!-- Footer -->
<div class="footer">
    &copy; <?= date('Y') ?> Biblioteca Digitale
</div>

</body>
</html>
