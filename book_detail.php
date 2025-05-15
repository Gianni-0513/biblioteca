<?php
require_once 'functions.php';
$book_id = intval($_GET['id']);
$reviews = getReviews($book_id, true);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isLoggedIn()) {
    if (isset($_POST['add_review'])) {
        addReview($_SESSION['user_id'], $book_id, $_POST['content']);
    } elseif (isset($_POST['update_review'])) {
        updateReview($_POST['review_id'], $_SESSION['user_id'], $_POST['content']);
    } elseif (isset($_POST['delete_review'])) {
        deleteReview($_POST['review_id']);
    } elseif (isset($_POST['hide_review'])) {
        hideReview($_POST['review_id']);
    }

    // Redirect per aggiornare la pagina e prevenire invii doppi
    header('Location: book_detail.php?id=' . $book_id);
    exit;
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Recensioni Libro</title>
    <link rel="stylesheet" href="./css/index.css">
</head>
<body>

<!-- Navbar -->
<div class="navbar">
    <div class="navbar-container">
        <div class="navbar-brand"><i>📚</i> Biblioteca</div>
        <div class="navbar-links">
            <?php if (!isLoggedIn()): ?>
                <a href="login.php" class="nav-btn">Login</a>
            <?php else: ?>
                <a href="<?= isAdmin() ? 'admin_dashboard.php' : 'user_dashboard.php' ?>" class="nav-btn">Dashboard</a>
                <a href="logout.php" class="nav-btn">Logout</a>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Contenuto principale -->
<div class="main-content">
    <h2 class="main-title">Recensioni per il libro #<?= $book_id ?></h2>

    <!-- Lista recensioni -->
    <div class="table-container">
        <table class="books-table">
            <tr><th>Utente</th><th>Data</th><th>Recensione</th><th>Azioni</th></tr>
            <?php foreach ($reviews as $r): ?>
                <tr>
                <?php if (isLoggedIn() && $r['user_id'] == $_SESSION['user_id']): ?>
                      <form method="post">
                          <tr>
                              <td><?= htmlspecialchars($r['email']) ?></td>
                              <td><?= $r['created_at'] ?> <?= !empty($r['hidden']) ? '<em>(nascosta)</em>' : '' ?></td>
                              <td>
                                  <input type="hidden" name="review_id" value="<?= $r['id'] ?>">
                                  <textarea name="content" class="input-small"><?= htmlspecialchars($r['content']) ?></textarea>
                              </td>
                              <td>
                                  <button name="update_review">💾</button>
                                  <button name="delete_review" formmethod="post" formaction="book_detail.php" formaction="?id=<?= $book_id ?>">🗑️</button>
                                  <?php if (isAdmin()): ?>
                                      <button name="hide_review" formmethod="post" formaction="?id=<?= $book_id ?>">🙈</button>
                                  <?php endif; ?>
                              </td>
                          </tr>
                      </form>
                  <?php else: ?>
                      <tr>
                          <td><?= htmlspecialchars($r['email']) ?></td>
                          <td><?= $r['created_at'] ?> <?= !empty($r['hidden']) ? '<em>(nascosta)</em>' : '' ?></td>
                          <td><p><?= htmlspecialchars($r['content']) ?></p></td>
                          <td>
                              <?php if (isLoggedIn() && $r['user_id'] == $_SESSION['user_id']): ?>
                                  <form method="post" style="display:inline">
                                      <input type="hidden" name="review_id" value="<?= $r['id'] ?>">
                                      <button name="delete_review">🗑️</button>
                                  </form>
                              <?php endif; ?>
                              <?php if (isAdmin()): ?>
                                  <form method="post" style="display:inline">
                                      <input type="hidden" name="review_id" value="<?= $r['id'] ?>">
                                      <button name="hide_review">🙈</button>
                                  </form>
                              <?php endif; ?>
                          </td>
                      </tr>
                  <?php endif; ?>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <!-- Aggiungi nuova recensione -->
    <?php if (isLoggedIn()): ?>
        <h3>Aggiungi una Recensione</h3>
        <form method="post" class="admin-form">
            <textarea name="content" placeholder="Scrivi la tua recensione..." required></textarea>
            <button name="add_review">➕ Invia Recensione</button>
        </form>
    <?php else: ?>
        <p class="warning">Effettua il <a href="login.php">login</a> per scrivere una recensione.</p>
    <?php endif; ?>
</div>

<!-- Footer -->
<div class="footer">
    &copy; <?= date('Y') ?> Biblioteca Digitale - Recensioni
</div>

</body>
</html>
