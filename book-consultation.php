<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

$success = '';
$error   = '';
$booking_id = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = trim($_POST['name'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $phone   = trim($_POST['phone'] ?? '');
    $company = trim($_POST['company'] ?? '');
    $service = trim($_POST['service'] ?? '');
    $date    = trim($_POST['preferred_date'] ?? '');
    $time    = trim($_POST['preferred_time'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (empty($name) || empty($email) || empty($date) || empty($time)) {
        $error = "Please fill all required fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } elseif (strtotime($date) < strtotime('today')) {
        $error = "Please select a future date.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO consultations (name,email,phone,company,service,preferred_date,preferred_time,message) VALUES (?,?,?,?,?,?,?,?)");
            $stmt->execute([$name, $email, $phone, $company, $service, $date, $time, $message]);
            $booking_id = 'CAL-' . date('Ymd') . '-' . strtoupper(substr(md5($email . time()), 0, 6));
            $success = "Thank you, " . e($name) . "! Your consultation is booked.";
        } catch (Exception $ex) {
            $error = "Something went wrong. Please try again.";
        }
    }
}

$services = get_services();

$pageTitle = 'Book Consultation';
$pageDesc  = 'Book a free 30-minute discovery call with a senior engineer.';

include 'includes/header.php';
?>

<?php if ($success): ?>

  <section class="hero" style="padding-bottom:2rem">
    <div class="container narrow">
      <div style="position:relative;background:linear-gradient(135deg,var(--primary),var(--cyan));color:#fff;border-radius:var(--radius-xl);padding:3rem 2.5rem;text-align:center;overflow:hidden;box-shadow:var(--shadow-glow)">
        <div style="position:absolute;inset:0;background:radial-gradient(circle at 20% 20%,rgba(255,255,255,0.2),transparent 40%),radial-gradient(circle at 80% 80%,rgba(255,0,128,0.25),transparent 40%)"></div>
        <div style="position:relative;z-index:1">
          <div style="width:80px;height:80px;margin:0 auto 1.2rem;border-radius:50%;background:rgba(255,255,255,0.2);display:grid;place-items:center;font-size:2.4rem;border:2px solid rgba(255,255,255,0.35)">
            <i class="bi bi-calendar-check-fill"></i>
          </div>
          <h2 style="font-size:1.7rem;font-weight:800;margin-bottom:0.6rem;color:#fff">Consultation Booked!</h2>
          <p style="opacity:0.9;margin-bottom:1.5rem;font-size:0.98rem"><?= $success ?></p>

          <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.8rem;margin-bottom:1.5rem;max-width:400px;margin-left:auto;margin-right:auto;text-align:left">
            <div style="background:rgba(255,255,255,0.15);border:1px solid rgba(255,255,255,0.2);border-radius:12px;padding:0.8rem">
              <div style="font-size:0.68rem;text-transform:uppercase;letter-spacing:0.1em;font-weight:700;opacity:0.85;margin-bottom:0.3rem">📅 Date</div>
              <div style="font-size:0.95rem;font-weight:800"><?= e(date('d M Y', strtotime($_POST['preferred_date'] ?? 'now'))) ?></div>
            </div>
            <div style="background:rgba(255,255,255,0.15);border:1px solid rgba(255,255,255,0.2);border-radius:12px;padding:0.8rem">
              <div style="font-size:0.68rem;text-transform:uppercase;letter-spacing:0.1em;font-weight:700;opacity:0.85;margin-bottom:0.3rem">🕐 Time</div>
              <div style="font-size:0.95rem;font-weight:800"><?= e($_POST['preferred_time'] ?? '') ?></div>
            </div>
          </div>

          <div style="display:inline-block;background:rgba(255,255,255,0.15);border:1px dashed rgba(255,255,255,0.4);border-radius:14px;padding:0.8rem 1.3rem;margin-bottom:1.5rem">
            <div style="font-size:0.68rem;text-transform:uppercase;letter-spacing:0.12em;font-weight:700;opacity:0.85;margin-bottom:0.25rem">Booking Reference</div>
            <div style="font-family:var(--font-mono);font-size:1rem;font-weight:800;letter-spacing:0.03em"><?= e($booking_id) ?></div>
          </div>

          <div style="display:flex;gap:0.6rem;justify-content:center;flex-wrap:wrap">
            <a href="<?= BASE_URL ?>" class="btn" style="background:#fff;color:var(--primary)">
              <i class="bi bi-house"></i> Back to Home
            </a>
            <a href="<?= BASE_URL ?>portfolio.php" class="btn" style="background:rgba(255,255,255,0.15);color:#fff;border:1px solid rgba(255,255,255,0.3)">
              <i class="bi bi-collection"></i> View Work
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>

<?php else: ?>

  <section class="hero">
    <div class="container" style="text-align:center">
      <span class="eyebrow">Consultation</span>
      <h1 class="display-2" style="margin-bottom:1rem">
        Book a <span class="gradient-text">free discovery call.</span>
      </h1>
      <p class="lead" style="margin:0 auto 2.5rem;text-align:center">
        30-minute call with a senior engineer. No sales pitch — just strategy.
      </p>

      <div class="hero-stats" style="justify-content:center;max-width:720px;margin-left:auto;margin-right:auto">
        <div class="hero-stat"><strong data-count="30">0</strong><span>Minute Call</span></div>
        <div class="hero-stat"><strong>Free</strong><span>Always</span></div>
        <div class="hero-stat"><strong data-count="24">0</strong><span>Hour Confirm</span></div>
        <div class="hero-stat"><strong data-count="500">0</strong><span>Calls Done</span></div>
      </div>
    </div>
  </section>

  <section class="section" style="padding-top:1rem">
    <div class="container">

      <div style="display:grid;grid-template-columns:1.4fr 1fr;gap:2rem;align-items:start" class="booking-grid">

        <!-- Form -->
        <div class="card reveal" style="padding:2.5rem;border-radius:var(--radius-xl)">
          <h3 style="font-size:1.3rem;font-weight:800;margin-bottom:0.4rem">Schedule your call</h3>
          <p style="color:var(--muted);font-size:0.9rem;margin-bottom:1.8rem">
            Pick a date and time that works for you. We'll confirm within 24 hours.
          </p>

          <?php if ($error): ?>
            <div class="alert error"><i class="bi bi-exclamation-triangle-fill"></i><span><?= e($error) ?></span></div>
          <?php endif; ?>

          <form method="post" class="form-grid" data-loading>

            <div class="form-row">
              <div class="field">
                <label for="bcName">Full Name <span class="req">*</span></label>
                <input type="text" id="bcName" name="name" placeholder="John Doe" required>
              </div>
              <div class="field">
                <label for="bcEmail">Email <span class="req">*</span></label>
                <input type="email" id="bcEmail" name="email" placeholder="john@company.com" required>
              </div>
            </div>

            <div class="form-row">
              <div class="field">
                <label for="bcPhone">Phone</label>
                <input type="text" id="bcPhone" name="phone" placeholder="+91 98765 43210">
              </div>
              <div class="field">
                <label for="bcCompany">Company</label>
                <input type="text" id="bcCompany" name="company" placeholder="Company Name">
              </div>
            </div>

            <div class="field">
              <label for="bcService">Service Interest</label>
              <select id="bcService" name="service">
                <option value="">Select a service (optional)</option>
                <?php foreach ($services as $s): ?>
                  <option value="<?= e($s['title']) ?>"><?= e($s['title']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="field">
              <label for="bcDate">Preferred Date <span class="req">*</span></label>
              <input type="date"
                     id="bcDate"
                     name="preferred_date"
                     required
                     min="<?= date('Y-m-d') ?>"
                     value="<?= date('Y-m-d', strtotime('+1 day')) ?>">
            </div>

            <div class="field">
              <label>Preferred Time <span class="req">*</span></label>
              <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(110px,1fr));gap:0.6rem" id="bcSlots">
                <?php
                $slots = [
                    ['10:00 AM', 'bi-sunrise',    'Morning'],
                    ['12:00 PM', 'bi-sun',        'Noon'],
                    ['02:00 PM', 'bi-sunset',     'Afternoon'],
                    ['04:00 PM', 'bi-moon-stars', 'Evening'],
                ];
                foreach ($slots as $i => $slot): ?>
                  <button type="button"
                          class="bc-slot <?= $i === 0 ? 'active' : '' ?>"
                          data-value="<?= $slot[0] ?>"
                          style="padding:0.8rem 0.6rem;background:var(--surface-2);border:1.5px solid var(--border-strong);border-radius:10px;cursor:pointer;font-family:inherit;font-size:0.82rem;font-weight:700;color:var(--text-2);display:flex;flex-direction:column;align-items:center;gap:0.2rem;transition:all 0.25s">
                    <i class="bi <?= $slot[1] ?>" style="font-size:1rem;color:var(--muted)"></i>
                    <span><?= $slot[0] ?></span>
                    <small style="font-size:0.68rem;color:var(--muted);text-transform:uppercase;letter-spacing:0.05em"><?= $slot[2] ?></small>
                  </button>
                <?php endforeach; ?>
              </div>
              <input type="hidden" name="preferred_time" id="bcTime" value="10:00 AM">
            </div>

            <div class="field">
              <label for="bcMessage">What would you like to discuss?</label>
              <textarea id="bcMessage" name="message" placeholder="Tell us about your project, goals, or challenges..." rows="4"></textarea>
            </div>

            <button type="submit" class="btn btn-primary btn-lg">
              <i class="bi bi-calendar-check-fill"></i> Book Free Consultation
            </button>

          </form>
        </div>

        <!-- Sidebar -->
        <aside style="display:flex;flex-direction:column;gap:1rem">

          <div class="clean-card reveal" data-delay="60">
            <div style="width:44px;height:44px;border-radius:10px;background:linear-gradient(135deg,var(--primary),var(--primary-2));color:#fff;display:grid;place-items:center;font-size:1.2rem;margin-bottom:0.9rem">
              <i class="bi bi-list-check"></i>
            </div>
            <h4 style="font-size:0.95rem;font-weight:800;margin-bottom:0.5rem">What to expect</h4>
            <ul style="list-style:none;padding:0;margin:0.7rem 0 0;display:flex;flex-direction:column;gap:0.6rem">
              <?php foreach ([
                'Understand your business & goals',
                'Discuss technical feasibility',
                'Recommend best approach',
                'Give rough timeline & budget',
              ] as $item): ?>
                <li style="display:flex;gap:0.5rem;font-size:0.82rem;color:var(--text-2);line-height:1.5">
                  <i class="bi bi-check-circle-fill" style="color:var(--green);flex-shrink:0;margin-top:0.1rem"></i>
                  <?= $item ?>
                </li>
              <?php endforeach; ?>
            </ul>
          </div>

          <div class="clean-card reveal" data-delay="120">
            <div style="width:44px;height:44px;border-radius:10px;background:linear-gradient(135deg,var(--cyan),var(--green));color:#fff;display:grid;place-items:center;font-size:1.2rem;margin-bottom:0.9rem">
              <i class="bi bi-shield-check"></i>
            </div>
            <h4 style="font-size:0.95rem;font-weight:800;margin-bottom:0.5rem">No commitment</h4>
            <p style="font-size:0.85rem;color:var(--muted);line-height:1.55;margin:0">
              Free 30-minute call. No sales pitch. Just honest advice from senior engineers who've shipped 500+ projects.
            </p>
          </div>

          <div class="clean-card reveal" data-delay="180">
            <div style="width:44px;height:44px;border-radius:10px;background:linear-gradient(135deg,var(--yellow),var(--pink));color:#fff;display:grid;place-items:center;font-size:1.2rem;margin-bottom:0.9rem">
              <i class="bi bi-clock-history"></i>
            </div>
            <h4 style="font-size:0.95rem;font-weight:800;margin-bottom:0.5rem">Quick response</h4>
            <p style="font-size:0.85rem;color:var(--muted);line-height:1.55;margin:0">
              We confirm bookings within 24 hours. If urgent, mention it in your message and we'll prioritize.
            </p>
          </div>

        </aside>

      </div>
    </div>
  </section>

  <style>
    .bc-slot:hover { border-color: var(--primary); }
    .bc-slot.active { background: var(--primary-soft) !important; border-color: var(--primary) !important; color: var(--primary) !important; }
    .bc-slot.active i, .bc-slot.active small { color: var(--primary) !important; }
    @media (max-width: 900px) { .booking-grid { grid-template-columns: 1fr !important; } }
  </style>

  <script>
  (function() {
    const slots = document.querySelectorAll('.bc-slot');
    const timeInput = document.getElementById('bcTime');
    if (!slots.length || !timeInput) return;
    slots.forEach(slot => {
      slot.addEventListener('click', () => {
        slots.forEach(s => s.classList.remove('active'));
        slot.classList.add('active');
        timeInput.value = slot.dataset.value;
      });
    });
  })();
  </script>

<?php endif; ?>

<?php include 'includes/footer.php'; ?>