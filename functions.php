<?php
require_once 'config.php';

function register($email, $password) {
    global $conn;
    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
    $stmt->bind_param('ss', $email, $hashed);
    return $stmt->execute();
}

function login($email, $password) {
    global $conn;
    $stmt = $conn->prepare("SELECT id, password, role FROM users WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->bind_result($id, $hash, $role);
    if ($stmt->fetch() && password_verify($password, $hash)) {
        $_SESSION['user_id'] = $id;
        $_SESSION['role'] = $role;
        return true;
    }
    return false;
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function getAllBooks() {
    global $conn;
    $res = $conn->query("SELECT * FROM books");
    return $res->fetch_all(MYSQLI_ASSOC);
}

function requestLoan($user_id, $book_id) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO loans (user_id, book_id) VALUES (?, ?)");
    $stmt->bind_param('ii', $user_id, $book_id);
    return $stmt->execute();
}

function getUserLoans($user_id) {
    global $conn;
    $stmt = $conn->prepare(
        "SELECT l.id, b.title, b.author, l.status, l.request_date, l.return_date
        FROM loans l
        JOIN books b ON l.book_id = b.id
        WHERE l.user_id = ?"
    );
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function addBook($title, $author) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO books (title, author) VALUES (?, ?)");
    $stmt->bind_param('ss', $title, $author);
    return $stmt->execute();
}

function updateBook($id, $title, $author, $available) {
    global $conn;
    $stmt = $conn->prepare("UPDATE books SET title=?, author=?, available=? WHERE id=?");
    $stmt->bind_param('ssii', $title, $author, $available, $id);
    return $stmt->execute();
}

function deleteBook($id) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM books WHERE id = ?");
    $stmt->bind_param('i', $id);
    return $stmt->execute();
}

function getPendingLoans() {
    global $conn;
    $res = $conn->query(
        "SELECT l.id, u.email, b.title, l.request_date
         FROM loans l
         JOIN users u ON l.user_id = u.id
         JOIN books b ON l.book_id = b.id
         WHERE l.status = 'pending'"
    );
    return $res->fetch_all(MYSQLI_ASSOC);
}

function approveLoan($loan_id, $days = 14) {
    global $conn;
    $return_date = date('Y-m-d H:i:s', strtotime("+{$days} days"));
    $stmt1 = $conn->prepare("UPDATE loans SET status='approved', return_date=? WHERE id=?");
    $stmt1->bind_param('si', $return_date, $loan_id);
    $stmt1->execute();
    $stmt2 = $conn->prepare(
        "UPDATE books SET available=0 WHERE id=(SELECT book_id FROM loans WHERE id=?)"
    );
    $stmt2->bind_param('i', $loan_id);
    return $stmt2->execute();
}

function getApprovedLoans() {
    global $conn;
    $res = $conn->query(
        "SELECT l.id, u.email, b.title, l.request_date, l.return_date
         FROM loans l
         JOIN users u ON l.user_id = u.id
         JOIN books b ON l.book_id = b.id
         WHERE l.status = 'approved'"
    );
    return $res->fetch_all(MYSQLI_ASSOC);
}

function returnLoan($loan_id) {
    global $conn;
    // mark loan returned
    $stmt1 = $conn->prepare("UPDATE loans SET status='returned' WHERE id = ?");
    $stmt1->bind_param('i', $loan_id);
    $stmt1->execute();
    // make book available again
    $stmt2 = $conn->prepare(
        "UPDATE books SET available = 1 WHERE id = (SELECT book_id FROM loans WHERE id = ?)"
    );
    $stmt2->bind_param('i', $loan_id);
    return $stmt2->execute();
}

function getReviews($book_id, $includeHidden = false) {
    global $conn;
    if ($includeHidden && isAdmin()) {
        $stmt = $conn->prepare(
            "SELECT r.id, r.content, r.created_at, r.hidden, u.email, r.user_id
             FROM reviews r JOIN users u ON r.user_id=u.id
             WHERE r.book_id=? ORDER BY r.created_at DESC"
        );
    } else {
        $stmt = $conn->prepare(
            "SELECT r.id, r.content, r.created_at, u.email, r.user_id
             FROM reviews r JOIN users u ON r.user_id=u.id
             WHERE r.book_id=? AND r.hidden=0 ORDER BY r.created_at DESC"
        );
    }
    $stmt->bind_param('i', $book_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function addReview($user_id, $book_id, $content) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO reviews (user_id, book_id, content) VALUES (?, ?, ?)");
    $stmt->bind_param('iis', $user_id, $book_id, $content);
    return $stmt->execute();
}

function updateReview($id, $user_id, $content) {
    global $conn;
    $stmt = $conn->prepare(
        "UPDATE reviews SET content=?, created_at=CURRENT_TIMESTAMP WHERE id=? AND user_id=?"
    );
    $stmt->bind_param('sii', $content, $id, $user_id);
    return $stmt->execute();
}

function deleteReview($id) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM reviews WHERE id=?");
    $stmt->bind_param('i', $id);
    return $stmt->execute();
}

function hideReview($id) {
    global $conn;
    if (!isAdmin()) return false;
    $stmt = $conn->prepare("UPDATE reviews SET hidden=1 WHERE id=?");
    $stmt->bind_param('i', $id);
    return $stmt->execute();
}
?>