<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Genç Cafe - Premium kahve ve tatlı deneyimi. En özel içecekler ve pastalar ile sizi bekliyoruz.">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' | Genç Cafe' : 'Genç Cafe'; ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="page-loading">

<!-- Page Transition Overlay -->
<div class="page-transition-overlay"></div>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-genccafe fixed-top">
    <div class="container">
        <a class="navbar-brand navbar-brand-cafe" href="index.php">☕ Genç Cafe</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>" href="index.php">
                        <i class="bi bi-house-heart"></i> Ana Sayfa
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'drinks.php' ? 'active' : ''; ?>" href="drinks.php">
                        <i class="bi bi-cup-hot"></i> İçecekler
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'cakes.php' ? 'active' : ''; ?>" href="cakes.php">
                        <i class="bi bi-cake2"></i> Pastalar
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="https://www.instagram.com/osmangazi_genccafe/" target="_blank">
                        <i class="bi bi-instagram"></i> Instagram
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
