<?php
require_once 'includes/config.php';

$name     = 'Super Admin';
$email    = 'admin@nexora.test';
$password = 'admin123';
$hash     = password_hash($password, PASSWORD_DEFAULT);

// Delete old admin if exists
$pdo->prepare("DELETE FROM users WHERE email = ?")->execute([$email]);

// Insert fresh admin
$stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'super_admin')");
$stmt->execute([$name, $email, $hash]);

echo "<h2 style='color:green;font-family:sans-serif'>✅ Admin created successfully!</h2>";
echo "<div style='font-family:sans-serif;font-size:16px;line-height:1.8'>";
echo "<p><strong>Login URL:</strong> <a href='admin/login.php'>admin/login.php</a></p>";
echo "<p><strong>Email:</strong> admin@nexora.test</p>";
echo "<p><strong>Password:</strong> admin123</p>";
echo "<p style='color:red'><strong>⚠️ Delete this file now for security!</strong></p>";
echo "</div>";