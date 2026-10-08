<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

$success = '';
$error   = '';
$enquiry_id = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = trim($_POST['name'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $phone   = trim($_POST['phone'] ?? '');
    $company = trim($_POST['company'] ?? '');
    $service = trim($_POST['service'] ?? '');
    $budget  = trim($_POST['budget'] ?? '');
    $timeline= trim($_POST['timeline'] ?? '');
    $reqs    = trim($_POST['requirements'] ?? '');

    if (empty($name) || empty($email) || empty($service) || empty($reqs)) {
        $error = "Please fill all required fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } else {
        try {
            $enquiry_id = 'REQ-' . date('Y') . '-' . str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT);
            $stmt = $pdo->prepare("INSERT INTO quotes (enquiry_id,name,email,phone,company,service,budget,timeline,requirements) VALUES (?,?,?,?,?,?,?,?,?)");
            $stmt->execute([$enquiry_id, $name, $email, $phone, $company, $service, $budget, $timeline, $reqs]);
            $success = "Quote submitted successfully!";
        } catch (Exception $ex) {
            $error = "Something went wrong. Please try again.";
        }
    }
}

$services = get_services();

$pageTitle = 'Request a Quote';
$pageDesc  = 'Tell us about your project — get a detailed quote within 24 hours.';

include 'includes/header.php';
?>

<?php if ($success): ?>

  <section class="hero" style="padding-bottom:2rem">
    <div class="container narrow">
      <div style="position:relative;background:linear-gradient(135deg,var(--primary),var(--cyan));color:#fff;border-radius:var(--radius-xl);padding:3rem 2.5rem;text-align:center;overflow:hidden;box-shadow:var(--shadow-glow)">
        <div style="position:absolute;inset:0;background:radial-gradient(circle at 20% 20%,rgba(255,255,255,0.2),transparent 40%),radial-gradient(circle at 80% 80%,rgba(255,0,128,0.25),transparent 40%)"></div>
        <div style="position:relative;z-index:1">
          <div style="width:80px;height:80px;margin:0 auto 1.2rem;border-radius:50%;background:rgba(255,255,255,0.2);display:grid;place-items:center;font-size:2.5rem;border:2px solid rgba(255,255,255,0.35)">
            <i class="bi bi-check2-circle"></i>
          </div>
          <h2 style="font-size:1.7rem;font-weight:800;margin-bottom:0.6rem;color:#fff">Quote Request Received!</h2>
          <p style="opacity:0.9;margin-bottom:1.5rem;font-size:0.98rem">
            Thank you <?= e($_POST['name'] ?? '') ?>. We'll review your project and reply within 24 hours.
          </p>

          <div style="display:inline-block;background:rgba(255,255,255,0.15);border:1px dashed rgba(255,255,255,0.4);border-radius:14px;padding:1rem 1.5rem;margin-bottom:1.5rem">
            <div style="font-size:0.7rem;text-transform:uppercase;letter-spacing:0.12em;font-weight:700;opacity:0.85;margin-bottom:0.3rem">Your Enquiry ID</div>
            <div style="font-family:var(--font-mono);font-size:1.2rem;font-weight:800;letter-spacing:0.03em"><?= e($enquiry_id) ?></div>
          </div>

          <p style="font-size:0.85rem;opacity:0.85;margin-bottom:1.5rem">
            <i class="bi bi-info-circle"></i> Save this ID — reference it in future communication.
          </p>

          <div style="display:flex;gap:0.6rem;justify-content:center;flex-wrap:wrap">
            <a href="<?= BASE_URL ?>" class="btn" style="background:#fff;color:var(--primary)">
              <i class="bi bi-house"></i> Back to Home
            </a>
            <a href="<?= BASE_URL ?>portfolio.php" class="btn" style="background:rgba(255,255,255,0.15);color:#fff;border:1px solid rgba(255,255,255,0.3)">
              <i class="bi bi-collection"></i> View Portfolio
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>

<?php else: ?>

  <section class="hero">
    <div class="container" style="text-align:center">
      <span class="eyebrow">Request a Quote</span>
      <h1 class="display-2" style="margin-bottom:1rem">
        Tell us about <span class="gradient-text">your project.</span>
      </h1>
      <p class="lead" style="margin:0 auto 2.5rem;text-align:center">
        Fill the form and we'll get back with a detailed quote within 24 hours.
      </p>
    </div>
  </section>

  <section class="section" style="padding-top:1rem">
    <div class="container narrow">
      <div class="card reveal" style="padding:2.5rem;border-radius:var(--radius-xl)">

        <h3 style="font-size:1.3rem;font-weight:800;margin-bottom:0.4rem">Project Requirements</h3>
        <p style="color:var(--muted);font-size:0.9rem;margin-bottom:1.8rem">
          All fields marked with <span style="color:var(--red)">*</span> are required.
        </p>

        <?php if ($error): ?>
          <div class="alert error"><i class="bi bi-exclamation-triangle-fill"></i><span><?= e($error) ?></span></div>
        <?php endif; ?>

        <form method="post" class="form-grid" data-loading>

          <div class="form-row">
            <div class="field">
              <label for="rqName">Full Name <span class="req">*</span></label>
              <input type="text" id="rqName" name="name" placeholder="John Doe" required>
            </div>
            <div class="field">
              <label for="rqEmail">Email <span class="req">*</span></label>
              <input type="email" id="rqEmail" name="email" placeholder="john@company.com" required>
            </div>
          </div>

          <div class="form-row">
            <div class="field">
              <label for="rqPhone">Phone</label>
              <input type="text" id="rqPhone" name="phone" placeholder="+91 98765 43210">
            </div>
            <div class="field">
              <label for="rqCompany">Company</label>
              <input type="text" id="rqCompany" name="company" placeholder="Company Name">
            </div>
          </div>

          <div class="form-row">
            <div class="field">
              <label for="rqService">Service Needed <span class="req">*</span></label>
              <select id="rqService" name="service" required>
                <option value="">Select a service</option>
                <?php foreach ($services as $s): ?>
                  <option value="<?= e($s['title']) ?>"><?= e($s['title']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="field">
              <label for="rqBudget">Budget Range</label>
              <select id="rqBudget" name="budget">
                <option value="">Select budget</option>
                <option>Under $1,000</option>
                <option>$1,000 – $5,000</option>
                <option>$5,000 – $20,000</option>
                <option>$20,000+</option>
              </select>
            </div>
          </div>

          <div class="field">
            <label for="rqTimeline">Timeline</label>
            <select id="rqTimeline" name="timeline">
              <option value="">Select timeline</option>
              <option>Urgent (1–2 weeks)</option>
              <option>Standard (1–2 months)</option>
              <option>Flexible (3+ months)</option>
            </select>
          </div>

          <div class="field">
            <label for="rqReqs">Project Requirements <span class="req">*</span></label>
            <textarea id="rqReqs" name="requirements" placeholder="Describe what you want to build — key features, target users, goals, integrations..." rows="6" required></textarea>
          </div>

          <button type="submit" class="btn btn-primary btn-lg">
            <i class="bi bi-send-check-fill"></i> Submit Quote Request
          </button>

        </form>
      </div>
    </div>
  </section>

<?php endif; ?>

<?php include 'includes/footer.php'; ?>