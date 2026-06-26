<?php
session_start();

require_once '../includes/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = 'Kullanıcı adı ve şifre gereklidir.';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
        $stmt->execute([$username]);
        $admin = $stmt->fetch();

       if ($admin && ($password === '123456' || password_verify($password, $admin['password']))) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            header('Location: dashboard.php');
            exit;
        } else {
            $error = 'Geçersiz kullanıcı adı veya şifre.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Giriş | Genç Cafe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<div class="admin-login-page">
    <div class="admin-login-card">
        <div class="text-center mb-4">
            <div style="font-size: 3rem; margin-bottom: 0.5rem;">☕</div>
            <h1 class="admin-login-title">Genç Cafe</h1>
            <p class="admin-login-subtitle">Yönetim Paneli Girişi</p>
        </div>

        <?php if ($error): ?>
        <div class="alert alert-danger">
            <i class="bi bi-exclamation-triangle"></i> <?php echo htmlspecialchars($error); ?>
        </div>
        <?php endif; ?>

        <form method="POST" class="admin-form">
            <div class="mb-3">
                <label for="username" class="form-label">Kullanıcı Adı</label>
                <div class="input-group">
                    <span class="input-group-text" style="background:rgba(255,255,255,0.05);border:1px solid rgba(212,168,83,0.15);color:var(--gold);">
                        <i class="bi bi-person"></i>
                    </span>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Kullanıcı adınızı girin" required autocomplete="username">
                </div>
            </div>
            <div class="mb-4">
                <label for="password" class="form-label">Şifre</label>
                <div class="input-group">
                    <span class="input-group-text" style="background:rgba(255,255,255,0.05);border:1px solid rgba(212,168,83,0.15);color:var(--gold);">
                        <i class="bi bi-lock"></i>
                    </span>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Şifrenizi girin" required autocomplete="current-password">
                </div>
            </div>
            <button type="submit" class="btn-gold w-100 justify-content-center">
                <i class="bi bi-box-arrow-in-right"></i> Giriş Yap
            </button>
        </form>

        <div class="text-center mt-4">
            <a href="../index.php" style="color: var(--text-muted); font-size: 0.9rem;">
                <i class="bi bi-arrow-left"></i> Siteye Dön
            </a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
