<?php
require_once 'functions.php';
$books = getAllBooks();
?>
<!DOCTYPE html>
<html><head><title>Biblioteca</title></head><body>
<h1>Elenco Libri</h1>
<table border="1"><tr><th>Titolo</th><th>Autore</th><th>Disponibile</th></tr>
<?php foreach($books as $b): ?>
<tr>
    <td><?=htmlspecialchars($b['title'])?></td>
    <td><?=htmlspecialchars($b['author'])?></td>
    <td><?= $b['available'] ? 'Sì' : 'No' ?></td>
</tr>
<?php endforeach; ?>
</table>

<a href="login.php">Login</a> | <a href="register.php">Registrati</a>
</body></html>