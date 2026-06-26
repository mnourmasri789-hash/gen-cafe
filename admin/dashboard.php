<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

require_once '../includes/db.php';

// Fetch all items
$stmt = $pdo->query("SELECT * FROM items ORDER BY created_at DESC");
$items = $stmt->fetchAll();

// Flash messages
$success = $_SESSION['success'] ?? '';
$error = $_SESSION['error'] ?? '';
unset($_SESSION['success'], $_SESSION['error']);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel | Genç Cafe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<div class="admin-dashboard">
    <!-- Admin Header -->
    <header class="admin-header">
        <div class="container d-flex justify-content-between align-items-center">
            <a href="dashboard.php" class="admin-header-brand" style="text-decoration:none;">☕ Genç Cafe <small style="font-size:0.7rem;color:var(--text-muted);font-family:'Inter',sans-serif;">ADMIN</small></a>
            <div class="d-flex align-items-center gap-3">
                <span class="admin-header-user">
                    <i class="bi bi-person-circle"></i> <?php echo htmlspecialchars($_SESSION['admin_username']); ?>
                </span>
                <a href="../index.php" class="btn-gold-outline" style="padding:0.4rem 1rem;font-size:0.75rem;" target="_blank">
                    <i class="bi bi-eye"></i> Siteyi Gör
                </a>
                <a href="logout.php" class="btn-admin-delete" style="text-decoration:none;">
                    <i class="bi bi-box-arrow-right"></i> Çıkış
                </a>
            </div>
        </div>
    </header>

    <!-- Admin Content -->
    <div class="admin-content">
        <div class="container">
            <?php if ($success): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle"></i> <?php echo htmlspecialchars($success); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>

            <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-exclamation-triangle"></i> <?php echo htmlspecialchars($error); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>

            <!-- Stats Row -->
            <div class="row g-4 mb-4">
                <div class="col-md-4">
                    <div class="admin-card text-center">
                        <div style="font-size:2.5rem;color:var(--gold);"><?php echo count($items); ?></div>
                        <div style="color:var(--text-muted);font-size:0.9rem;text-transform:uppercase;letter-spacing:2px;">Toplam Ürün</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="admin-card text-center">
                        <div style="font-size:2.5rem;color:#64B5F6;"><?php echo count(array_filter($items, fn($i) => $i['category'] === 'Drink')); ?></div>
                        <div style="color:var(--text-muted);font-size:0.9rem;text-transform:uppercase;letter-spacing:2px;">İçecek</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="admin-card text-center">
                        <div style="font-size:2.5rem;color:#F093BA;"><?php echo count(array_filter($items, fn($i) => $i['category'] === 'Cake')); ?></div>
                        <div style="color:var(--text-muted);font-size:0.9rem;text-transform:uppercase;letter-spacing:2px;">Pasta</div>
                    </div>
                </div>
            </div>

            <!-- Items Table -->
            <div class="admin-card">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 style="font-family:'Playfair Display',serif;font-size:1.5rem;color:var(--text-primary);margin:0;">
                        <i class="bi bi-grid" style="color:var(--gold);"></i> Menü Yönetimi
                    </h2>
                    <button class="btn-gold" data-bs-toggle="modal" data-bs-target="#addModal" style="padding:0.6rem 1.5rem;font-size:0.8rem;">
                        <i class="bi bi-plus-lg"></i> Yeni Ürün Ekle
                    </button>
                </div>

                <?php if (empty($items)): ?>
                <div class="text-center py-5">
                    <div style="font-size:3rem;opacity:0.3;margin-bottom:1rem;">📋</div>
                    <p style="color:var(--text-muted);">Henüz ürün eklenmemiş. Yukarıdaki butona tıklayarak yeni ürün ekleyebilirsiniz.</p>
                </div>
                <?php else: ?>
                <div class="table-responsive">
                    <table class="table admin-table">
                        <thead>
                            <tr>
                                <th>Görsel</th>
                                <th>Ürün Adı</th>
                                <th>Kategori</th>
                                <th>Fiyat</th>
                                <th>Tarih</th>
                                <th class="text-end">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($items as $item): ?>
                            <tr>
                                <td>
                                    <?php if ($item['image'] && file_exists('../assets/uploads/' . $item['image'])): ?>
                                        <img src="../assets/uploads/<?php echo htmlspecialchars($item['image']); ?>" alt="" class="item-thumb">
                                    <?php else: ?>
                                        <div class="item-thumb-placeholder">
                                            <i class="bi bi-<?php echo $item['category'] === 'Drink' ? 'cup-hot' : 'cake2'; ?>"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td style="font-weight:600;color:var(--text-primary);"><?php echo htmlspecialchars($item['name']); ?></td>
                                <td>
                                    <span class="<?php echo $item['category'] === 'Drink' ? 'badge-drink' : 'badge-cake'; ?>">
                                        <?php echo $item['category'] === 'Drink' ? 'İçecek' : 'Pasta'; ?>
                                    </span>
                                </td>
                                <td style="font-weight:600;color:var(--gold);"><?php echo number_format($item['price'], 2); ?> ₺</td>
                                <td style="font-size:0.85rem;"><?php echo date('d.m.Y', strtotime($item['created_at'])); ?></td>
                                <td class="text-end">
                                    <button class="btn-admin-edit me-1"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editModal"
                                        data-edit-id="<?php echo $item['id']; ?>"
                                        data-edit-name="<?php echo htmlspecialchars($item['name']); ?>"
                                        data-edit-category="<?php echo $item['category']; ?>"
                                        data-edit-price="<?php echo $item['price']; ?>"
                                        data-edit-description="<?php echo htmlspecialchars($item['description']); ?>"
                                        data-edit-image="<?php echo htmlspecialchars($item['image']); ?>">
                                        <i class="bi bi-pencil"></i> Düzenle
                                    </button>
                                    <button class="btn-admin-delete"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteModal"
                                        data-delete-id="<?php echo $item['id']; ?>"
                                        data-delete-name="<?php echo htmlspecialchars($item['name']); ?>">
                                        <i class="bi bi-trash"></i> Sil
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Add Item Modal -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-plus-circle" style="color:var(--gold);"></i> Yeni Ürün Ekle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="add_item.php" method="POST" enctype="multipart/form-data" class="admin-form">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="itemName" class="form-label">Ürün Adı</label>
                            <input type="text" class="form-control" id="itemName" name="name" required placeholder="Ürün adını girin">
                        </div>
                        <div class="col-md-3">
                            <label for="itemCategory" class="form-label">Kategori</label>
                            <select class="form-select" id="itemCategory" name="category" required>
                                <option value="">Seçin</option>
                                <option value="Drink">İçecek</option>
                                <option value="Cake">Pasta</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="itemPrice" class="form-label">Fiyat (₺)</label>
                            <input type="number" class="form-control" id="itemPrice" name="price" step="0.01" min="0" required placeholder="0.00">
                        </div>
                        <div class="col-12">
                            <label for="itemDescription" class="form-label">Açıklama</label>
                            <textarea class="form-control" id="itemDescription" name="description" rows="4" placeholder="Ürün hakkında detaylı açıklama yazın..."></textarea>
                        </div>
                        <div class="col-12">
                            <label for="itemImage" class="form-label">Ürün Görseli</label>
                            <input type="file" class="form-control" id="itemImage" name="image" accept="image/*">
                            <div class="img-preview-container" id="imagePreview" style="display:none;"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-gold-outline" data-bs-dismiss="modal" style="padding:0.5rem 1.5rem;font-size:0.85rem;">İptal</button>
                    <button type="submit" class="btn-gold" style="padding:0.6rem 1.8rem;font-size:0.85rem;">
                        <i class="bi bi-check-lg"></i> Ürün Ekle
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Item Modal -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-pencil-square" style="color:var(--gold);"></i> Ürünü Düzenle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="edit_item.php" method="POST" enctype="multipart/form-data" class="admin-form">
                <input type="hidden" name="id" id="editItemId">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="editItemName" class="form-label">Ürün Adı</label>
                            <input type="text" class="form-control" id="editItemName" name="name" required>
                        </div>
                        <div class="col-md-3">
                            <label for="editItemCategory" class="form-label">Kategori</label>
                            <select class="form-select" id="editItemCategory" name="category" required>
                                <option value="Drink">İçecek</option>
                                <option value="Cake">Pasta</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="editItemPrice" class="form-label">Fiyat (₺)</label>
                            <input type="number" class="form-control" id="editItemPrice" name="price" step="0.01" min="0" required>
                        </div>
                        <div class="col-12">
                            <label for="editItemDescription" class="form-label">Açıklama</label>
                            <textarea class="form-control" id="editItemDescription" name="description" rows="4"></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Mevcut Görsel</label>
                            <div id="editCurrentImage" class="mb-2"></div>
                            <label for="editItemImage" class="form-label">Yeni Görsel (opsiyonel)</label>
                            <input type="file" class="form-control" id="editItemImage" name="image" accept="image/*">
                            <div class="img-preview-container" id="editImagePreview" style="display:none;"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-gold-outline" data-bs-dismiss="modal" style="padding:0.5rem 1.5rem;font-size:0.85rem;">İptal</button>
                    <button type="submit" class="btn-gold" style="padding:0.6rem 1.8rem;font-size:0.85rem;">
                        <i class="bi bi-check-lg"></i> Güncelle
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-exclamation-triangle" style="color:#FF6B6B;"></i> Ürünü Sil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <p style="color:var(--text-secondary);">
                    <strong id="deleteItemName" style="color:var(--text-primary);"></strong> ürününü silmek istediğinizden emin misiniz?
                </p>
                <p style="color:var(--text-muted);font-size:0.85rem;">Bu işlem geri alınamaz.</p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn-gold-outline" data-bs-dismiss="modal" style="padding:0.5rem 1.5rem;font-size:0.85rem;">İptal</button>
                <form action="delete_item.php" method="POST" style="display:inline;">
                    <input type="hidden" name="id" id="deleteItemId">
                    <button type="submit" class="btn-admin-delete" style="padding:0.5rem 1.5rem;font-size:0.85rem;">
                        <i class="bi bi-trash"></i> Sil
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/script.js"></script>
</body>
</html>
