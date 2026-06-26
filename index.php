<?php
require_once 'includes/db.php';
$pageTitle = 'Ana Sayfa';

// Fetch latest 6 items for featured section
$stmt = $pdo->query("SELECT * FROM items ORDER BY created_at DESC LIMIT 6");
$featuredItems = $stmt->fetchAll();

require_once 'includes/header.php';
?>

<!-- Hero Section -->
<section class="hero-section" id="hero">
    <div class="hero-bg" data-parallax="0.4"></div>
    <div class="hero-overlay"></div>
    
    <!-- Floating Particles -->
    <div class="hero-particles">
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
    </div>
    
    <div class="hero-content">
        <div class="hero-logo">☕ Premium Cafe Deneyimi</div>
        <div class="hero-divider"></div>
        <h1 class="hero-title">Genç Cafe</h1>
        <p class="hero-subtitle">En özel kahveler, el yapımı tatlılar ve unutulmaz bir atmosfer. Her anınızı özel kılmak için buradayız.</p>
        <div class="hero-cta">
            <a href="https://www.instagram.com/osmangazi_genccafe/" target="_blank" class="btn-gold">
                <i class="bi bi-instagram"></i> Bizi Takip Edin
            </a>
        </div>
    </div>
</section>

<!-- Featured Items Section -->
<?php if (!empty($featuredItems)): ?>
<section class="featured-section section-padding">
    <div class="container">
        <div class="section-header">
            <span class="section-label reveal">Menümüz</span>
            <h2 class="section-title reveal">Öne Çıkanlar</h2>
            <p class="section-desc reveal">En sevilen içecek ve tatlılarımızdan bir seçki</p>
        </div>
        
        <div class="row g-4">
            <?php foreach ($featuredItems as $index => $item): ?>
            <div class="col-lg-4 col-md-6">
                <div class="menu-card reveal stagger-<?php echo ($index % 6) + 1; ?>">
                    <div class="menu-card-img-wrapper">
                        <?php if ($item['image'] && file_exists('assets/uploads/' . $item['image'])): ?>
                            <img src="assets/uploads/<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="menu-card-img">
                        <?php else: ?>
                            <div class="menu-card-placeholder">
                                <i class="bi bi-<?php echo $item['category'] === 'Drink' ? 'cup-hot' : 'cake2'; ?>"></i>
                            </div>
                        <?php endif; ?>
                        <div class="menu-card-img-overlay"></div>
                        <span class="menu-card-price"><?php echo number_format($item['price'], 2); ?> ₺</span>
                    </div>
                    <div class="menu-card-body">
                        <div>
                            <span class="menu-card-category"><?php echo $item['category'] === 'Drink' ? 'İçecek' : 'Pasta'; ?></span>
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
        
        <div class="text-center mt-5 reveal">
            <a href="drinks.php" class="btn-gold-outline me-3">
                <i class="bi bi-cup-hot"></i> Tüm İçecekler
            </a>
            <a href="cakes.php" class="btn-gold-outline">
                <i class="bi bi-cake2"></i> Tüm Pastalar
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Parallax Divider -->
<section class="parallax-divider">
    <div class="parallax-divider-bg" data-parallax="0.3"></div>
    <div class="parallax-divider-overlay">
        <p class="parallax-divider-text">"Her yudumda bir hikaye, her lokmada bir mutluluk..."</p>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
