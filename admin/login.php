<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

if (is_logged_in() && is_admin()) {
    redirect(BASE_URL . 'admin/dashboard.php');
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $pass  = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND role IN ('super_admin','admin')");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($pass, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role']    = $user['role'];
        $_SESSION['name']    = $user['name'];
        redirect(BASE_URL . 'admin/dashboard.php');
    } else {
        $error = "Invalid admin credentials.";
    }
}

$pageTitle = 'Admin Login';
include '../includes/header.php';
?>

<section class="auth-wrap">
  <div class="auth-card">
    <div class="auth-icon"><i class="bi bi-shield-lock-fill"></i></div>
    <h1>Admin Login</h1>
    <p class="sub">Restricted access for administrators only</p>

    <?php if ($error): ?>
      <div class="alert error">
        <i class="bi bi-exclamation-triangle-fill"></i>
        <span><?= e($error) ?></span>
      </div>
    <?php endif; ?>

    <form method="post" class="form-grid">
      <div class="field">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" placeholder="admin@nexora.test" required autofocus>
      </div>
      <div class="field">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="••••••••" required>
      </div>
      <button type="submit" class="btn btn-primary btn-lg" style="width:100%;margin-top:0.5rem">
        <i class="bi bi-box-arrow-in-right"></i> Sign In
      </button>
    </form>

    <p style="text-align:center;margin-top:1.5rem;font-size:0.82rem;color:var(--muted)">
      <i class="bi bi-info-circle"></i> Default: admin@nexora.test / admin123
    </p>
  </div>
</section>

<?php include '../includes/footer.php'; ?><?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

if (is_logged_in() && is_admin()) {
    redirect(BASE_URL . 'admin/dashboard.php');
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $pass  = $_POST['password'] ?? '';
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND role IN ('super_admin','admin')");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($pass, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role']    = $user['role'];
        $_SESSION['name']    = $user['name'];
        redirect(BASE_URL . 'admin/dashboard.php');
    } else {
        $error = "Invalid admin credentials.";
    }
}

$pageTitle = 'Admin Login';
include '../includes/header.php';
?>

<section style="min-height:calc(100vh - 200px);display:grid;place-items:center;padding:3rem 1rem">
  <div style="width:100%;max-width:440px;background:var(--surface);border:1px solid var(--border);border-radius:var(--radius-xl);padding:2.5rem 2.25rem;box-shadow:var(--shadow-lg);position:relative;overflow:hidden">

    <div style="position:absolute;top:-50%;right:-50%;width:200%;height:200%;background:radial-gradient(circle at 30% 20%,var(--primary-soft),transparent 40%);pointer-events:none"></div>

    <div style="position:relative;z-index:1">
      <div style="width:64px;height:64px;margin:0 auto 1.2rem;border-radius:14px;background:linear-gradient(135deg,var(--primary),var(--cyan));color:#fff;font-size:1.7rem;display:grid;place-items:center;box-shadow:0 10px 25px var(--primary-glow)">
        <i class="bi bi-shield-lock-fill"></i>
      </div>

      <h1 style="font-size:1.55rem;font-weight:800;text-align:center;margin-bottom:0.4rem;letter-spacing:-0.02em">
        Admin Login
      </h1>
      <p style="color:var(--muted);font-size:0.9rem;text-align:center;margin-bottom:1.8rem">
        Restricted access for administrators only
      </p>

      <?php if ($error): ?>
        <div class="alert error">
          <i class="bi bi-exclamation-triangle-fill"></i>
          <span><?= e($error) ?></span>
        </div>
      <?php endif; ?>

      <form method="post" class="form-grid">
        <div class="field">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" placeholder="admin@nexora.test" required autofocus>
        </div>
        <div class="field">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" placeholder="••••••••" required>
        </div>
        <button type="submit" class="btn btn-primary btn-lg" style="width:100%;margin-top:0.5rem">
          <i class="bi bi-box-arrow-in-right"></i> Sign In
        </button>
      </form>

      <p style="text-align:center;margin-top:1.5rem;font-size:0.8rem;color:var(--muted)">
        <i class="bi bi-info-circle"></i> Default: admin@nexora.test / admin123
      </p>
    </div>
  </div>
</section>

<?php include '../includes/footer.php'; ?>