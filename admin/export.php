<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_admin();

$type = $_GET['type'] ?? '';

if ($type === 'quotes') {
    $rows = $pdo->query("SELECT enquiry_id, name, email, phone, company, service, budget, timeline, requirements, status, created_at FROM quotes ORDER BY created_at DESC")->fetchAll();
    $headers = ['Enquiry ID','Name','Email','Phone','Company','Service','Budget','Timeline','Requirements','Status','Submitted'];
    $filename = 'quotes_' . date('Y-m-d') . '.csv';
}
elseif ($type === 'consultations') {
    $rows = $pdo->query("SELECT name, email, phone, company, service, preferred_date, preferred_time, message, status, created_at FROM consultations ORDER BY created_at DESC")->fetchAll();
    $headers = ['Name','Email','Phone','Company','Service','Preferred Date','Preferred Time','Message','Status','Booked At'];
    $filename = 'consultations_' . date('Y-m-d') . '.csv';
}
elseif ($type === 'contacts') {
    $rows = $pdo->query("SELECT name, email, phone, subject, message, created_at FROM contact_enquiries ORDER BY created_at DESC")->fetchAll();
    $headers = ['Name','Email','Phone','Subject','Message','Received At'];
    $filename = 'contacts_' . date('Y-m-d') . '.csv';
}
elseif ($type === 'visitors') {
    $rows = $pdo->query("SELECT ip, page, device, referrer, visit_date, visit_time, created_at FROM visitor_logs ORDER BY created_at DESC LIMIT 5000")->fetchAll();
    $headers = ['IP','Page','Device','Referrer','Date','Time','Timestamp'];
    $filename = 'visitors_' . date('Y-m-d') . '.csv';
}
elseif ($type === 'all') {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="nexora_all_' . date('Y-m-d') . '.csv"');
    $out = fopen('php://output', 'w');

    fputcsv($out, ['=== QUOTE REQUESTS ===']);
    fputcsv($out, ['Enquiry ID','Name','Email','Phone','Company','Service','Budget','Timeline','Requirements','Status','Submitted']);
    foreach ($pdo->query("SELECT * FROM quotes ORDER BY created_at DESC") as $q) {
        fputcsv($out, [$q['enquiry_id'],$q['name'],$q['email'],$q['phone'],$q['company'],$q['service'],$q['budget'],$q['timeline'],$q['requirements'],$q['status'],$q['created_at']]);
    }
    fputcsv($out, []);

    fputcsv($out, ['=== CONSULTATIONS ===']);
    fputcsv($out, ['Name','Email','Phone','Company','Service','Date','Time','Message','Status','Booked']);
    foreach ($pdo->query("SELECT * FROM consultations ORDER BY created_at DESC") as $c) {
        fputcsv($out, [$c['name'],$c['email'],$c['phone'],$c['company'],$c['service'],$c['preferred_date'],$c['preferred_time'],$c['message'],$c['status'],$c['created_at']]);
    }
    fputcsv($out, []);

    fputcsv($out, ['=== CONTACT ENQUIRIES ===']);
    fputcsv($out, ['Name','Email','Phone','Subject','Message','Received']);
    foreach ($pdo->query("SELECT * FROM contact_enquiries ORDER BY created_at DESC") as $c) {
        fputcsv($out, [$c['name'],$c['email'],$c['phone'],$c['subject'],$c['message'],$c['created_at']]);
    }
    fputcsv($out, []);

    fputcsv($out, ['=== VISITOR LOGS (Last 5000) ===']);
    fputcsv($out, ['IP','Page','Device','Referrer','Date','Time']);
    foreach ($pdo->query("SELECT * FROM visitor_logs ORDER BY created_at DESC LIMIT 5000") as $v) {
        fputcsv($out, [$v['ip'],$v['page'],$v['device'],$v['referrer'],$v['visit_date'],$v['visit_time']]);
    }
    fclose($out);
    exit;
}
else {
    redirect(BASE_URL . 'admin/dashboard.php');
}

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');
$out = fopen('php://output', 'w');
fputcsv($out, $headers);
foreach ($rows as $r) {
    fputcsv($out, array_values($r));
}
fclose($out);
exit;