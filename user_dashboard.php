<?php
require_once 'functions.php';
if (!isLoggedIn() || isAdmin()) {
    header('Location: login.php');
    exit;
}
$user_id = $_SESSION['user_id'];
$books = getAllBooks();
$loans = getUserLoans($user_id);
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book_id'])) {
    requestLoan($user_id, $_POST['book_id']);
    header('Location: user_dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8" />
    <title>Dashboard Utente - Biblioteca</title>
    <link rel="stylesheet" href="./css/index.css" />
</head>
<body>

<!-- Navbar -->
<div class="navbar">
    <div class="navbar-container">
        <div class="navbar-brand"><i>📚</i> Biblioteca</div>
        <div class="navbar-links">
            <a href="logout.php" class="nav-btn">Logout</a>
        </div>
    </div>
</div>

<!-- Contenuto principale -->
<div class="main-content">
    <h2 class="main-title">Benvenuto Utente</h2>

    <!-- Richiesta prestito -->
    <section>
        <h3>Richiedi Prestito</h3>
        <div class="table-container">
            <table class="books-table">
                <tr><th>Titolo</th><th>Autore</th><th>Disponibile</th><th>Azione</th></tr>
                <?php foreach($books as $b): ?>
                <tr>
                    <td><a href="book_detail.php?id=<?=$b['id']?>"><?=htmlspecialchars($b['title'])?></a></td>
                    <td><?=htmlspecialchars($b['author'])?></td>
                    <td><?= $b['available'] ? 'Sì' : 'No' ?></td>
                    <td>
                        <?php if ($b['available']): ?>
                            <form method="post" style="margin:0;">
                                <input type="hidden" name="book_id" value="<?=$b['id']?>" />
                                <button type="submit" class="btn-primary">Richiedi</button>
                            </form>
                        <?php else: ?>
                            <span class="unavailable">Non disponibile</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </section>

    <!-- Prestiti utente -->
    <section>
        <h3>I tuoi prestiti</h3>
        <div class="table-container">
            <table class="books-table">
                <tr><th>Libro</th><th>Stato</th><th>Data Richiesta</th><th>Data Riconsegna</th></tr>
                <?php foreach($loans as $l): ?>
                <tr>
                    <td><?=htmlspecialchars($l['title'])?></td>
                    <td><?=htmlspecialchars($l['status'])?></td>
                    <td><?=$l['request_date']?></td>
                    <td><?=$l['return_date'] ?: '-'?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </section>
</div>

<!-- Footer -->
<div class="footer">
    &copy; <?= date('Y') ?> Biblioteca Digitale - Area Utente
</div>

</body>
</html>
