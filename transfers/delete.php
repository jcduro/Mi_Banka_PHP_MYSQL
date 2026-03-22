<?php
include __DIR__ . '/../../conexion.php';

if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("DELETE FROM transfers WHERE id = ?");
    $stmt->execute([$_GET['id']]);
}

header("Location: list.php");
exit;
