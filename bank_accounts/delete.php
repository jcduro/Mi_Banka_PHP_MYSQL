<?php
include __DIR__ . '/../conexion.php'; // Only needed if PDO is instantiated here
// If it's pure API delete, maybe header.php has HTML output, but we are using it for DB conn.
// It's better to require DB if not already included, but assuming header.php includes it:

if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("DELETE FROM bank_accounts WHERE id = ?");
    $stmt->execute([$_GET['id']]);
}

header("Location: list.php");
exit;
