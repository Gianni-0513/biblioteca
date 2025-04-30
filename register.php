<?php
require_once 'functions.php';
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (register($_POST['email'], $_POST['password'])) {
        header('Location: login.php'); exit;
    } else {
        $message = 'Errore registrazione';
    }
}
?>
<!DOCTYPE html><html><body>
<h1>Registrazione</h1>
<form method="post">
    Email: <input type="email" name="email" required><br>
    Password: <input type="password" name="password" required><br>
    <button type="submit">Registrati</button>
</form>
<p><?= $message ?></p>
</body></html>