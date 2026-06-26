<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $category = $_POST['category'] ?? '';
    $price = floatval($_POST['price'] ?? 0);
    $description = trim($_POST['description'] ?? '');
    $imageName = null;

    // Validate inputs
    if (empty($name) || empty($category) || $price <= 0) {
        $_SESSION['error'] = 'Lütfen tüm zorunlu alanları doldurun.';
        header('Location: dashboard.php');
        exit;
    }

    if (!in_array($category, ['Drink', 'Cake'])) {
        $_SESSION['error'] = 'Geçersiz kategori seçimi.';
        header('Location: dashboard.php');
        exit;
    }

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $fileType = mime_content_type($_FILES['image']['tmp_name']);

        if (in_array($fileType, $allowedTypes)) {
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $imageName = uniqid('item_') . '.' . $ext;
            $uploadPath = '../assets/uploads/' . $imageName;

            if (!move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                $_SESSION['error'] = 'Görsel yüklenirken bir hata oluştu.';
                header('Location: dashboard.php');
                exit;
            }
        } else {
            $_SESSION['error'] = 'Geçersiz dosya türü. Sadece JPG, PNG, GIF ve WEBP kabul edilir.';
            header('Location: dashboard.php');
            exit;
        }
    }

    // Insert into database
    try {
        $stmt = $pdo->prepare("INSERT INTO items (name, category, price, image, description) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $category, $price, $imageName, $description]);
        $_SESSION['success'] = '"' . $name . '" başarıyla eklendi.';
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Veritabanı hatası: ' . $e->getMessage();
    }
}

header('Location: dashboard.php');
exit;
