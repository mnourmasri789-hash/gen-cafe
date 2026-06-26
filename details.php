<?php
require_once 'includes/db.php';

$item = null;
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM items WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $item = $stmt->fetch();
}

$pageTitle = $item ? htmlspecialchars($item['name']) : 'Ürün Bulunamadı';
require_once 'includes/header.php';
?>

<?php if ($item): ?>
<!-- Item Details -->
<section class="detail-section">
    <div class="container">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6 reveal-left">
                <div class="detail-image-wrapper">
                    <?php if ($item['image'] && file_exists('assets/uploads/' . $item['image'])): ?>
                        <img src="assets/uploads/<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="detail-image">
                    <?php else: ?>
                        <div class="detail-image-placeholder">
                            <i class="bi bi-<?php echo $item['category'] === 'Drink' ? 'cup-hot' : 'cake2'; ?>"></i>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-lg-6 reveal-right">
                <div class="detail-info">
                    <span class="detail-category">
                        <i class="bi bi-<?php echo $item['category'] === 'Drink' ? 'cup-hot' : 'cake2'; ?>"></i>
                        <?php echo $item['category'] === 'Drink' ? 'İçecek' : 'Pasta'; ?>
                    </span>
                    <h1 class="detail-name"><?php echo htmlspecialchars($item['name']); ?></h1>
                    <div class="detail-price"><?php echo number_format($item['price'], 2); ?> ₺</div>
                    <div class="detail-divider"></div>
                    <p class="detail-description"><?php echo nl2br(htmlspecialchars($item['description'])); ?></p>
                    <a href="<?php echo $item['category'] === 'Drink' ? 'drinks.php' : 'cakes.php'; ?>" class="btn-gold-outline detail-back-btn">
                        <i class="bi bi-arrow-left"></i> Menüye Dön
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
<?php else: ?>
<!-- Not Found -->
<div class="empty-state" style="padding-top: 10rem;">
    <div class="empty-state-icon">🔍</div>
    <h2 class="empty-state-title">Ürün Bulunamadı</h2>
    <p class="empty-state-desc">Aradığınız ürün mevcut değil veya kaldırılmış olabilir.</p>
    <a href="index.php" class="btn-gold-outline"><i class="bi bi-house"></i> Ana Sayfaya Dön</a>
</div>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>
