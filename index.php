<?php
require_once 'functions.php';
$books = getAllBooks();
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Biblioteca</title>
    <link rel="stylesheet" href="./css/index.css">
    <!-- Icone Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>

<!-- Navbar -->
<nav class="navbar">
    <div class="navbar-container">
        <div class="navbar-brand">
            <i class="bi bi-book-half"></i> Biblioteca
        </div>
        <div class="navbar-links">
            <a href="login.php" class="nav-btn">Login</a>
            <a href="register.php" class="nav-btn">Registrati</a>
        </div>
    </div>
</nav>

<!-- Titolo -->
<main class="main-content">
    <h1 class="main-title">Scaffale dei Libri</h1>

    <!-- Scaffale grafico -->
    <div class="shelf-container">
        <!-- Libri sopra -->
        <div class="shelf-top">
            <div class="book-deco red"></div>
            <div class="book-deco green"></div>
            <div class="book-deco blue"></div>
            <div class="book-deco yellow"></div>
            <div class="book-deco brown"></div>
        </div>

        <!-- Tabella come scaffale -->
        <div class="shelf-body">
            <div class="shelf-side left"></div>

            <table class="book-table">
                <thead>
                    <tr>
                        <th>Titolo</th>
                        <th>Autore</th>
                        <th>Disponibilità</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($books as $b): ?>
                    <tr>
                        <td><a href="book_detail.php?id=<?= $b['id'] ?>"><?= htmlspecialchars($b['title']) ?></a></td>
                        <td><?= htmlspecialchars($b['author']) ?></td>
                        <td class="<?= $b['available'] ? 'available' : 'unavailable' ?>">
                            <?= $b['available'] ? 'Disponibile' : 'Non disponibile' ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="shelf-side right"></div>
        </div>
    </div>
</main>

<!-- Footer -->
<footer class="footer">
    <p>&copy; <?= date('Y') ?> Biblioteca Digitale</p>
</footer>

</body>
</html>

