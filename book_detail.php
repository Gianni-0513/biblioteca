<?php
require_once 'functions.php';
$book_id = intval($_GET['id']);
$books = getAllBooks(); // oppure funzione getBookById
$reviews = getReviews($book_id, true);

if ($_SERVER['REQUEST_METHOD']==='POST' && isLoggedIn()) {
    if (isset($_POST['add_review'])) {
        addReview($_SESSION['user_id'], $book_id, $_POST['content']);
    } elseif (isset($_POST['update_review'])) {
        updateReview($_POST['review_id'], $_SESSION['user_id'], $_POST['content']);
    } elseif (isset($_POST['delete_review'])) {
        deleteReview($_POST['review_id'], $_SESSION['user_id']);
    } elseif (isset($_POST['hide_review'])) {
        hideReview($_POST['review_id']);
    }
    if (!isLoggedIn()) {
        header('Location: index.php');           // non loggati → pubblica
        exit;
    } elseif (isAdmin()) {
        header('Location: admin_dashboard.php'); // amministratore
        exit;
    } else {
        header('Location: user_dashboard.php');  // utente normale
        exit;
    }
}
?>
<!DOCTYPE html><html><body>
<h1>Recensioni per Libro #<?=$book_id?></h1>
<ul>
<?php foreach($reviews as $r): ?>
    <li>
      <strong><?=$r['email']?></strong> (<?=$r['created_at']?>):
      <?php if (!empty($r['hidden'])): ?><em>(nascosta)</em><?php endif; ?>
      <p><?=htmlspecialchars($r['content'])?></p>
      <?php if(isLoggedIn() && $r['user_id']==$_SESSION['user_id']): ?>
        <form method="post" style="display:inline">
          <input type="hidden" name="review_id" value="<?=$r['id']?>">
          <button name="delete_review">Elimina</button>
        </form>
        <form method="post">
          <input type="hidden" name="review_id" value="<?=$r['id']?>">
          <textarea name="content"><?=htmlspecialchars($r['content'])?></textarea>
          <button name="update_review">Modifica</button>
        </form>
      <?php endif; ?>
      <?php if(isAdmin()): ?>
        <form method="post" style="display:inline">
          <input type="hidden" name="review_id" value="<?=$r['id']?>">
          <button name="hide_review">Nascondi</button>
        </form>
      <?php endif; ?>
    </li>
<?php endforeach; ?>
</ul>

<?php if(isLoggedIn()): ?>
<form method="post">
  <textarea name="content" required></textarea>
  <button name="add_review">Aggiungi Recensione</button>
</form>
<?php else: ?>
<p>Effettua il login per scrivere recensioni.</p>
<?php endif; ?>

<?php if (!isLoggedIn()): ?>
  <a href="index.php">Torna all'elenco libri</a>
<?php elseif (isAdmin()): ?>
  <a href="admin_dashboard.php">Torna a Admin Dashboard</a>
<?php else: ?>
  <a href="user_dashboard.php">Torna al tuo profilo</a>
<?php endif; ?>
</body></html>