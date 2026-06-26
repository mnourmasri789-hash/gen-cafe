<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id'] ?? 0);
    $name = trim($_POST['name'] ?? '');
    $category = $_POST['category'] ?? '';
    $price = floatval($_POST['price'] ?? 0);
    $description = trim($_POST['description'] ?? '');

    // Validate inputs
    if ($id <= 0 || empty($name) || empty($category) || $price <= 0) {
        $_SESSION['error'] = 'Lütfen tüm zorunlu alanları doldurun.';
        header('Location: dashboard.php');
        exit;
    }

    if (!in_array($category, ['Drink', 'Cake'])) {
        $_SESSION['error'] = 'Geçersiz kategori seçimi.';
        header('Location: dashboard.php');
        exit;
    }

    // Fetch existing item
    $stmt = $pdo->prepare("SELECT * FROM items WHERE id = ?");
    $stmt->execute([$id]);
    $existingItem = $stmt->fetch();

    if (!$existingItem) {
        $_SESSION['error'] = 'Ürün bulunamadı.';
        header('Location: dashboard.php');
        exit;
    }

    $imageName = $existingItem['image'];

    // Handle new image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $fileType = mime_content_type($_FILES['image']['tmp_name']);

        if (in_array($fileType, $allowedTypes)) {
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $newImageName = uniqid('item_') . '.' . $ext;
            $uploadPath = '../assets/uploads/' . $newImageName;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                // Delete old image if exists
                if ($existingItem['image'] && file_exists('../assets/uploads/' . $existingItem['image'])) {
                    unlink('../assets/uploads/' . $existingItem['image']);
                }
                $imageName = $newImageName;
            } else {
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

    // Update database
    try {
        $stmt = $pdo->prepare("UPDATE items SET name = ?, category = ?, price = ?, image = ?, description = ? WHERE id = ?");
        $stmt->execute([$name, $category, $price, $imageName, $description, $id]);
        $_SESSION['success'] = '"' . $name . '" başarıyla güncellendi.';
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Veritabanı hatası: ' . $e->getMessage();
    }
}

header('Location: dashboard.php');
exit;
