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
<!DOCTYPE html><html><body>
<h1>Login</h1>
<form method="post">
    Email: <input type="email" name="email" required><br>
    Password: <input type="password" name="password" required><br>
    <button type="submit">Login</button>
</form>
<p><?= $message ?></p>
</body></html>