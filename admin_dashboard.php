<?php
require_once 'functions.php';
if (!isLoggedIn() || !isAdmin()) { header('Location: login.php'); exit; }
$books = getAllBooks();
$pending = getPendingLoans();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) {
        addBook($_POST['title'], $_POST['author']);
    } elseif (isset($_POST['update'])) {
        updateBook($_POST['id'], $_POST['title'], $_POST['author'], isset($_POST['available']));
    } elseif (isset($_POST['delete'])) {
        deleteBook($_POST['id']);
    } elseif (isset($_POST['approve'])) {
        approveLoan($_POST['loan_id']);
    }
    header('Location: admin_dashboard.php'); exit;
}
?>
<!DOCTYPE html><html><body>
<h1>Admin Dashboard</h1>
<a href="logout.php">Logout</a>
<h2>Gestione Libri</h2>
<form method="post">
    <input type="hidden" name="add">
    Titolo: <input name="title" required>
    Autore: <input name="author" required>
    <button type="submit">Aggiungi Libro</button>
</form>
<table border="1"><tr><th>ID</th><th>Titolo</th><th>Autore</th><th>Disponibile</th><th>Azione</th></tr>
<?php foreach($books as $b): ?>
<tr>
    <form method="post">
    <td><input type="hidden" name="id" value="<?=$b['id']?>"><?=$b['id']?></td>
    <td><input name="title" value="<?=htmlspecialchars($b['title'])?>"></td>
    <td><input name="author" value="<?=htmlspecialchars($b['author'])?>"></td>
    <td><input type="checkbox" name="available" <?=$b['available']?'checked':''?>></td>
    <td>
        <button name="update">Aggiorna</button>
        <button name="delete">Elimina</button>
    </td>
    </form>
</tr>
<?php endforeach; ?>
</table>
<h2>Richieste di Prestito</h2>
<table border="1"><tr><th>ID</th><th>Utente</th><th>Libro</th><th>Data</th><th>Azione</th></tr>
<?php foreach($pending as $p): ?>
<tr>
    <td><?=$p['id']?></td>
    <td><?=htmlspecialchars($p['email'])?></td>
    <td><?=htmlspecialchars($p['title'])?></td>
    <td><?=$p['request_date']?></td>
    <td>
        <form method="post"><input type="hidden" name="loan_id" value="<?=$p['id']?>"><button name="approve">Approva</button></form>
    </td>
</tr>
<?php endforeach; ?>
</table>
</body></html>