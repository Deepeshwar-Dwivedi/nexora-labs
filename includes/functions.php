<?php

/* ========== HELPERS ========== */
if (!function_exists('e')) {
    function e($str) {
        return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('redirect')) {
    function redirect($url) {
        header("Location: $url");
        exit;
    }
}

/* ========== AUTH ========== */
if (!function_exists('is_logged_in')) {
    function is_logged_in() {
        return isset($_SESSION['user_id']);
    }
}

if (!function_exists('is_admin')) {
    function is_admin() {
        if (!is_logged_in()) return false;
        global $pdo;
        $stmt = $pdo->prepare("SELECT role FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch();
        return $user && in_array($user['role'], ['super_admin', 'admin']);
    }
}

if (!function_exists('require_login')) {
    function require_login() {
        if (!is_logged_in()) redirect(BASE_URL . 'admin/login.php');
    }
}

if (!function_exists('require_admin')) {
    function require_admin() {
        if (!is_admin()) redirect(BASE_URL . 'admin/login.php');
    }
}

if (!function_exists('current_user')) {
    function current_user() {
        global $pdo;
        if (!is_logged_in()) return null;
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        return $stmt->fetch();
    }
}

/* ========== DATA HELPERS ========== */
if (!function_exists('feature_list')) {
    function feature_list($str) {
        return array_map('trim', explode(',', $str ?? ''));
    }
}

if (!function_exists('get_services')) {
    function get_services() {
        global $pdo;
        return $pdo->query("SELECT * FROM services ORDER BY id")->fetchAll();
    }
}

if (!function_exists('get_technologies')) {
    function get_technologies() {
        return [
            ['name'=>'HTML5','icon'=>'bi-filetype-html'],
            ['name'=>'CSS3','icon'=>'bi-filetype-css'],
            ['name'=>'JavaScript','icon'=>'bi-filetype-js'],
            ['name'=>'PHP 8','icon'=>'bi-filetype-php'],
            ['name'=>'MySQL','icon'=>'bi-database'],
            ['name'=>'Bootstrap','icon'=>'bi-bootstrap'],
            ['name'=>'React','icon'=>'bi-react'],
            ['name'=>'Node.js','icon'=>'bi-node-plus'],
            ['name'=>'Python','icon'=>'bi-filetype-py'],
            ['name'=>'Flutter','icon'=>'bi-phone'],
            ['name'=>'AWS','icon'=>'bi-cloud'],
            ['name'=>'Docker','icon'=>'bi-box'],
            ['name'=>'Git','icon'=>'bi-git'],
            ['name'=>'OpenAI','icon'=>'bi-robot'],
        ];
    }
}

/* ========== ICON / COLOR MAPS ========== */
if (!function_exists('cat_icon')) {
    function cat_icon($cat) {
        $map = [
            'ERP'         => 'bi-diagram-3',
            'CRM'         => 'bi-people',
            'E-commerce'  => 'bi-cart3',
            'Mobile Apps' => 'bi-phone',
            'Websites'    => 'bi-globe2',
            'Software'    => 'bi-cpu',
            'AI'          => 'bi-robot',
            'Automation'  => 'bi-gear',
        ];
        return $map[$cat] ?? 'bi-window-stack';
    }
}

if (!function_exists('blog_cat_icon')) {
    function blog_cat_icon($cat) {
        $map = [
            'Software'               => 'bi-cpu',
            'Business'               => 'bi-briefcase',
            'AI'                     => 'bi-robot',
            'Web Development'        => 'bi-code-slash',
            'Technology'             => 'bi-cpu-fill',
            'Digital Transformation' => 'bi-arrow-repeat',
        ];
        return $map[$cat] ?? 'bi-file-text';
    }
}

/* ========== TRACKING ========== */
if (!function_exists('track_visit')) {
    function track_visit($pdo, $page) {
        try {
            $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
            $ua = substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255);
            $ref = substr($_SERVER['HTTP_REFERER'] ?? 'Direct', 0, 255);
            $device = 'Desktop';
            if (preg_match('/(mobile|android|iphone)/i', $ua)) $device = 'Mobile';
            elseif (preg_match('/(ipad|tablet)/i', $ua)) $device = 'Tablet';

            $stmt = $pdo->prepare("INSERT INTO visitor_logs (ip, user_agent, page, referrer, device, visit_date, visit_time) VALUES (?,?,?,?,?,CURDATE(),CURTIME())");
            $stmt->execute([$ip, $ua, $page, $ref, $device]);
        } catch (Exception $e) {}
    }
}