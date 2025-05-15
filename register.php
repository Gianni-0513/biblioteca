<?php
require_once 'functions.php';
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (register($_POST['email'], $_POST['password'])) {
        header('Location: login.php');
        exit;
    } else {
        $message = 'Errore nella registrazione';
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Registrazione - Biblioteca</title>
    <link rel="stylesheet" href="./css/index.css">
</head>
<body>

<!-- Navbar -->
<div class="navbar">
    <div class="navbar-container">
        <div class="navbar-brand"><i>📚</i> Biblioteca</div>
        <div class="navbar-links">
            <a href="index.php" class="nav-btn">Home</a>
            <a href="login.php" class="nav-btn">Login</a>
        </div>
    </div>
</div>

<!-- Contenuto -->
<div class="main-content">
    <h2 class="main-title">Registrazione Nuovo Utente</h2>
    
    <div class="login-form-container">
        <form method="post" class="login-form">
            <label>Email</label>
            <input type="email" name="email" required>

            <label>Password</label>
            <input type="password" name="password" required>

            <button type="submit">Registrati</button>

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
