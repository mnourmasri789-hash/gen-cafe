<?php
require_once 'includes/db.php';
$pageTitle = 'İçecekler';

$stmt = $pdo->query("SELECT * FROM items WHERE category = 'Drink' ORDER BY created_at DESC");
$drinks = $stmt->fetchAll();

require_once 'includes/header.php';
?>

<!-- Page Header -->
<section class="page-header">
    <div class="page-header-content">
        <span class="section-label reveal">Menümüz</span>
        <h1 class="section-title reveal">İçeceklerimiz</h1>
        <p class="section-desc reveal">Özenle hazırlanan kahveler, taze meyve suları ve daha fazlası</p>
    </div>
</section>

<!-- Drinks Grid -->
<section class="menu-grid">
    <div class="container">
        <?php if (empty($drinks)): ?>
        <div class="empty-state">
            <div class="empty-state-icon">☕</div>
            <h2 class="empty-state-title">Henüz İçecek Eklenmemiş</h2>
            <p class="empty-state-desc">Çok yakında menümüz güncellenecektir.</p>
            <a href="index.php" class="btn-gold-outline"><i class="bi bi-house"></i> Ana Sayfaya Dön</a>
        </div>
        <?php else: ?>
        <div class="row g-4">
            <?php foreach ($drinks as $index => $item): ?>
            <div class="col-lg-4 col-md-6">
                <div class="menu-card reveal stagger-<?php echo ($index % 9) + 1; ?>">
                    <div class="menu-card-img-wrapper">
                        <?php if ($item['image'] && file_exists('assets/uploads/' . $item['image'])): ?>
                            <img src="assets/uploads/<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="menu-card-img">
                        <?php else: ?>
                            <div class="menu-card-placeholder">
                                <i class="bi bi-cup-hot"></i>
                            </div>
                        <?php endif; ?>
                        <div class="menu-card-img-overlay"></div>
                        <span class="menu-card-price"><?php echo number_format($item['price'], 2); ?> ₺</span>
                    </div>
                    <div class="menu-card-body">
                        <div>
                            <span class="menu-card-category">İçecek</span>
                            <h3 class="menu-card-title"><?php echo htmlspecialchars($item['name']); ?></h3>
                        </div>
                        <a href="details.php?id=<?php echo $item['id']; ?>" class="menu-card-btn">
                            Detayları Gör <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
