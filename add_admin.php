<?php
/**
 * One-time script: Add a new admin user to the 'admins' table.
 * Run once, then DELETE this file from your server for security.
 */

require_once __DIR__ . '/includes/db.php';

$username = 'nidoo';
$password = '123456';

// Hash the password securely with bcrypt
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

try {
    $stmt = $pdo->prepare("INSERT INTO admins (username, password) VALUES (?, ?)");
    $stmt->execute([$username, $hashedPassword]);

    echo "<p style='font-family:sans-serif;color:green;'>
        ✅ Admin user <strong>{$username}</strong> created successfully!<br>
        Password is stored as a bcrypt hash.<br><br>
        <strong style='color:red;'>⚠️ Please delete this file (add_admin.php) from your server now.</strong>
    </p>";

} catch (PDOException $e) {
    // Check for duplicate username
    if ($e->getCode() == 23000) {
        echo "<p style='font-family:sans-serif;color:orange;'>
            ⚠️ Username <strong>{$username}</strong> already exists in the database.
        </p>";
    } else {
        echo "<p style='font-family:sans-serif;color:red;'>
            ❌ Error: " . htmlspecialchars($e->getMessage()) . "
        </p>";
    }
}
?>
