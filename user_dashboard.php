<?php
require_once 'functions.php';
if (!isLoggedIn() || isAdmin()) { header('Location: login.php'); exit; }
$user_id = $_SESSION['user_id'];
$books = getAllBooks();
$loans = getUserLoans($user_id);
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book_id'])) {
    requestLoan($user_id, $_POST['book_id']);
    header('Location: user_dashboard.php'); exit;
}
?>
<!DOCTYPE html><html><body>
<h1>Benvenuto Utente</h1>
<a href="logout.php">Logout</a>
<h2>Richiedi Prestito</h2>
<table border="1"><tr><th>Titolo</th><th>Autore</th><th>Disponibile</th><th>Azione</th></tr>
<?php foreach($books as $b): ?>
<tr>
    <td><?=htmlspecialchars($b['title'])?></td>
    <td><?=htmlspecialchars($b['author'])?></td>
    <td><?= $b['available'] ? 'Sì' : 'No' ?></td>
    <td>
    <?php if($b['available']): ?>
        <form method="post"><input type="hidden" name="book_id" value="<?=$b['id']?>"><button type="submit">Richiedi</button></form>
    <?php else: echo 'Non disponibile'; endif; ?>
    </td>
</tr>
<?php endforeach; ?>
</table>
<h2>I tuoi prestiti</h2>
<table border="1"><tr><th>Libro</th><th>Stato</th><th>Data Richiesta</th><th>Data Riconsegna</th></tr>
<?php foreach($loans as $l): ?>
<tr>
    <td><?=htmlspecialchars($l['title'])?></td>
    <td><?=htmlspecialchars($l['status'])?></td>
    <td><?=$l['request_date']?></td>
    <td><?=$l['return_date'] ?: '-'?></td>
</tr>
<?php endforeach; ?>
</table>
</body></html>