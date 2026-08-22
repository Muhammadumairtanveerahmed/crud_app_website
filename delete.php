<?php
require_once 'config/db.php';

$id = intval($_GET['id'] ?? 0);

if ($id > 0) {
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: index.php?msg=Product deleted");
    exit;
}

header("Location: index.php?msg=Invalid product ID");
exit;
