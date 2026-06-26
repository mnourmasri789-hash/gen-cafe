<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id'] ?? 0);

    if ($id <= 0) {
        $_SESSION['error'] = 'Geçersiz ürün ID.';
        header('Location: dashboard.php');
        exit;
    }

    // Fetch item to delete its image
    $stmt = $pdo->prepare("SELECT * FROM items WHERE id = ?");
    $stmt->execute([$id]);
    $item = $stmt->fetch();

    if (!$item) {
        $_SESSION['error'] = 'Ürün bulunamadı.';
        header('Location: dashboard.php');
        exit;
    }

    // Delete image file
    if ($item['image'] && file_exists('../assets/uploads/' . $item['image'])) {
        unlink('../assets/uploads/' . $item['image']);
    }

    // Delete from database
    try {
        $stmt = $pdo->prepare("DELETE FROM items WHERE id = ?");
        $stmt->execute([$id]);
        $_SESSION['success'] = '"' . $item['name'] . '" başarıyla silindi.';
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Veritabanı hatası: ' . $e->getMessage();
    }
}

header('Location: dashboard.php');
exit;
