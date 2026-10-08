<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

$success = '';
$error   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = trim($_POST['name'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $phone   = trim($_POST['phone'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $error = "Please fill all required fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO contact_enquiries (name,email,phone,subject,message) VALUES (?,?,?,?,?)");
            $stmt->execute([$name, $email, $phone, $subject, $message]);
            $success = "Thank you, " . e($name) . "! We'll get back to you within one business day.";
        } catch (Exception $ex) {
            $error = "Something went wrong. Please try again.";
        }
    }
}

$pageTitle = 'Contact';
$pageDesc  = 'Get in touch with Nexora Labs. We reply within one business day.';

include 'includes/header.php';
?>

<section class="hero">
  <div class="container" style="text-align:center">
    <span class="eyebrow">Contact</span>
    <h1 class="display-2" style="margin-bottom:1rem">
      Let's <span class="gradient-text">talk.</span>
    </h1>
    <p class="lead" style="margin:0 auto 2.5rem;text-align:center">
      Tell us about your project — we reply within one business day.
    </p>
  </div>
</section>

<section class="section" style="padding-top:1rem">
  <div class="container">
    <div style="display:grid;grid-template-columns:1fr 1.3fr;gap:2rem;align-items:start" class="contact-grid">

      <!-- Left: Info -->
      <div style="display:flex;flex-direction:column;gap:1rem">

        <div class="clean-card reveal" style="display:flex;gap:1rem;align-items:flex-start">
          <div style="width:48px;height:48px;border-radius:12px;background:linear-gradient(135deg,var(--primary),var(--primary-2));color:#fff;display:grid;place-items:center;font-size:1.3rem;flex-shrink:0">
            <i class="bi bi-envelope-fill"></i>
          </div>
          <div style="min-width:0">
            <div style="font-size:0.7rem;font-weight:800;letter-spacing:0.1em;text-transform:uppercase;color:var(--primary);margin-bottom:0.25rem">Email</div>
            <h4 style="font-size:0.95rem;font-weight:700;margin-bottom:0.3rem">Drop us a line</h4>
            <p style="font-size:0.84rem;color:var(--muted);line-height:1.5;margin:0 0 0.5rem">For general enquiries and project discussions.</p>
            <a href="mailto:hello@nexoralabs.test" style="font-size:0.86rem;color:var(--primary);font-weight:700;display:inline-flex;align-items:center;gap:0.3rem">
              hello@nexoralabs.test <i class="bi bi-arrow-right"></i>
            </a>
          </div>
        </div>

        <div class="clean-card reveal" data-delay="60" style="display:flex;gap:1rem;align-items:flex-start">
          <div style="width:48px;height:48px;border-radius:12px;background:linear-gradient(135deg,var(--cyan),var(--green));color:#fff;display:grid;place-items:center;font-size:1.3rem;flex-shrink:0">
            <i class="bi bi-telephone-fill"></i>
          </div>
          <div>
            <div style="font-size:0.7rem;font-weight:800;letter-spacing:0.1em;text-transform:uppercase;color:var(--cyan);margin-bottom:0.25rem">Phone</div>
            <h4 style="font-size:0.95rem;font-weight:700;margin-bottom:0.3rem">Give us a call</h4>
            <p style="font-size:0.84rem;color:var(--muted);line-height:1.5;margin:0 0 0.5rem">Mon – Fri, 10 AM to 7 PM IST</p>
            <a href="tel:+919876543210" style="font-size:0.86rem;color:var(--primary);font-weight:700;display:inline-flex;align-items:center;gap:0.3rem">
              +91 98765 43210 <i class="bi bi-arrow-right"></i>
            </a>
          </div>
        </div>

        <div class="clean-card reveal" data-delay="120" style="display:flex;gap:1rem;align-items:flex-start">
          <div style="width:48px;height:48px;border-radius:12px;background:linear-gradient(135deg,var(--pink),var(--yellow));color:#fff;display:grid;place-items:center;font-size:1.3rem;flex-shrink:0">
            <i class="bi bi-geo-alt-fill"></i>
          </div>
          <div>
            <div style="font-size:0.7rem;font-weight:800;letter-spacing:0.1em;text-transform:uppercase;color:var(--pink);margin-bottom:0.25rem">Location</div>
            <h4 style="font-size:0.95rem;font-weight:700;margin-bottom:0.3rem">Visit our studio</h4>
            <p style="font-size:0.84rem;color:var(--muted);line-height:1.5;margin:0">Bandra West, Mumbai, Maharashtra 400050, India</p>
          </div>
        </div>

        <div class="clean-card reveal" data-delay="180" style="display:flex;gap:1rem;align-items:flex-start">
          <div style="width:48px;height:48px;border-radius:12px;background:linear-gradient(135deg,var(--primary),var(--cyan));color:#fff;display:grid;place-items:center;font-size:1.3rem;flex-shrink:0">
            <i class="bi bi-clock-fill"></i>
          </div>
          <div>
            <div style="font-size:0.7rem;font-weight:800;letter-spacing:0.1em;text-transform:uppercase;color:var(--primary);margin-bottom:0.25rem">Response Time</div>
            <h4 style="font-size:0.95rem;font-weight:700;margin-bottom:0.3rem">We reply fast</h4>
            <p style="font-size:0.84rem;color:var(--muted);line-height:1.5;margin:0">Average reply time under 4 hours during business days.</p>
          </div>
        </div>

      </div>

      <!-- Right: Form -->
      <div class="card reveal" data-delay="100" style="padding:2rem;border-radius:var(--radius-xl)">
        <h3 style="font-size:1.2rem;font-weight:800;margin-bottom:0.4rem">Send us a message</h3>
        <p style="color:var(--muted);font-size:0.9rem;margin-bottom:1.5rem">Fill out the form and we'll get back to you shortly.</p>

        <?php if ($success): ?>
          <div class="alert success"><i class="bi bi-check-circle-fill"></i><span><?= $success ?></span></div>
        <?php endif; ?>
        <?php if ($error): ?>
          <div class="alert error"><i class="bi bi-exclamation-triangle-fill"></i><span><?= e($error) ?></span></div>
        <?php endif; ?>

        <form method="post" class="form-grid" data-loading>
          <div class="form-row">
            <div class="field">
              <label for="ctName">Full Name <span class="req">*</span></label>
              <input type="text" id="ctName" name="name" placeholder="John Doe" required>
            </div>
            <div class="field">
              <label for="ctEmail">Email <span class="req">*</span></label>
              <input type="email" id="ctEmail" name="email" placeholder="john@company.com" required>
            </div>
          </div>
          <div class="form-row">
            <div class="field">
              <label for="ctPhone">Phone</label>
              <input type="text" id="ctPhone" name="phone" placeholder="+91 98765 43210">
            </div>
            <div class="field">
              <label for="ctSubject">Subject <span class="req">*</span></label>
              <input type="text" id="ctSubject" name="subject" placeholder="What's this about?" required>
            </div>
          </div>
          <div class="field">
            <label for="ctMessage">Message <span class="req">*</span></label>
            <textarea id="ctMessage" name="message" placeholder="Tell us about your project..." rows="6" required></textarea>
          </div>
          <button type="submit" class="btn btn-primary btn-lg">
            <i class="bi bi-send-fill"></i> Send Message
          </button>
        </form>
      </div>

    </div>
  </div>
</section>

<style>
@media (max-width: 900px) { .contact-grid { grid-template-columns: 1fr !important; } }
</style>

<?php include 'includes/footer.php'; ?>