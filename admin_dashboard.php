<?php
require_once 'functions.php';
if (!isLoggedIn() || !isAdmin()) {
    header('Location: login.php');
    exit;
}
$books = getAllBooks();
$pending = getPendingLoans();
$approvedLoans = getApprovedLoans();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) {
        addBook($_POST['title'], $_POST['author']);
    } elseif (isset($_POST['update'])) {
        updateBook($_POST['id'], $_POST['title'], $_POST['author'], isset($_POST['available']));
    } elseif (isset($_POST['delete'])) {
        deleteBook($_POST['id']);
    } elseif (isset($_POST['approve'])) {
        approveLoan($_POST['loan_id']);
    } elseif (isset($_POST['return'])) {
        returnLoan($_POST['loan_id']);
    }
    header('Location: admin_dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Admin - Biblioteca</title>
    <link rel="stylesheet" href="./css/index.css">
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
    <h2 class="main-title">Pannello Amministratore</h2>

    <!-- Gestione Libri -->
    <section>
        <h3>Gestione Libri</h3>
        <form method="post" class="admin-form">
            <input type="hidden" name="add">
            <input name="title" placeholder="Titolo" required>
            <input name="author" placeholder="Autore" required>
            <button type="submit">➕ Aggiungi Libro</button>
        </form>

        <div class="table-container">
            <table class="books-table">
                <tr><th>ID</th><th>Titolo</th><th>Autore</th><th>Disponibile</th><th>Azione</th></tr>
                <?php foreach($books as $b): ?>
                <tr>
                    <form method="post">
                        <td><input type="hidden" name="id" value="<?=$b['id']?>"><?=$b['id']?></td>
                        <td>
                            <input type="text" name="title" value="<?=htmlspecialchars($b['title'])?>" class="input-small">
                        </td>
                        <td>
                            <input name="author" value="<?=htmlspecialchars($b['author'])?>" class="input-small">
                        </td>
                        <td><input type="checkbox" name="available" <?=$b['available']?'checked':''?>></td>
                        <td>
                            <button name="update">💾</button>
                            <button name="delete">🗑️</button>
                            <button><a href="book_detail.php?id=<?=$b['id']?>">💬</a> </button>
                        </td>
                    </form>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </section>

    <!-- Richieste Prestito -->
    <section>
        <h3>Richieste di Prestito</h3>
        <div class="table-container">
            <table class="books-table">
                <tr><th>ID</th><th>Utente</th><th>Libro</th><th>Data</th><th>Azione</th></tr>
                <?php foreach($pending as $p): ?>
                <tr>
                    <td><?=$p['id']?></td>
                    <td><?=htmlspecialchars($p['email'])?></td>
                    <td><?=htmlspecialchars($p['title'])?></td>
                    <td><?=$p['request_date']?></td>
                    <td>
                        <form method="post">
                            <input type="hidden" name="loan_id" value="<?=$p['id']?>">
                            <button name="approve">✔️ Approva</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </section>

    <!-- Prestiti Approvati -->
    <section>
        <h3>Prestiti Approvati</h3>
        <div class="table-container">
            <table class="books-table">
                <tr><th>ID</th><th>Utente</th><th>Libro</th><th>Richiesta</th><th>Ritorno</th><th>Azione</th></tr>
                <?php foreach($approvedLoans as $a): ?>
                <tr>
                    <td><?=$a['id']?></td>
                    <td><?=htmlspecialchars($a['email'])?></td>
                    <td><?=htmlspecialchars($a['title'])?></td>
                    <td><?=$a['request_date']?></td>
                    <td><?=$a['return_date']?></td>
                    <td>
                        <form method="post">
                            <input type="hidden" name="loan_id" value="<?=$a['id']?>">
                            <button name="return">📤 Riconsegnato</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </section>
</div>

<!-- Footer -->
<div class="footer">
    &copy; <?= date('Y') ?> Biblioteca Digitale - Area Amministratore
</div>

</body>
</html>
