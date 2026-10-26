<?php
session_start();

$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'db_film_rental';

// Nama file login mengandung "&", jadi di URL harus ditulis %26
define('LOGIN_PAGE', 'Halaman_Login_%26_Autefikansi.php');

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try {
    $conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
    $conn->set_charset('utf8mb4');
} catch (mysqli_sql_exception $e) {
    die('Koneksi database gagal. Periksa config/config.php dan pastikan MySQL aktif.');
}

// Bahasa: default id, bisa diganti lewat ?lang=en atau ?lang=id di halaman mana pun
if (!isset($_SESSION['lang'])) { $_SESSION['lang'] = 'id'; }
if (isset($_GET['lang'])) { $_SESSION['lang'] = ($_GET['lang'] === 'en') ? 'en' : 'id'; }
require_once __DIR__ . '/lang.php';

function e($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

function require_login(){
    if (empty($_SESSION['user_id'])) { header('Location: ' . LOGIN_PAGE); exit; }
}

function csrf_token(){
    if (empty($_SESSION['csrf'])) { $_SESSION['csrf'] = bin2hex(random_bytes(32)); }
    return $_SESSION['csrf'];
}
function csrf_valid($t){
    return isset($_SESSION['csrf']) && is_string($t) && hash_equals($_SESSION['csrf'], $t);
}

function validate_rental(array $in){
    $errors = [];
    $borrower = trim(filter_var($in['nama_peminjam'] ?? '', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $film     = trim(filter_var($in['judul_film'] ?? '', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $note     = trim(filter_var($in['catatan'] ?? '', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $d1 = trim($in['tanggal_pinjam'] ?? '');
    $d2 = trim($in['tanggal_kembali'] ?? '');
    $status = $in['status'] ?? '';

    if ($borrower === '' || mb_strlen($borrower) > 100) { $errors[] = t('err_borrower'); }
    if ($film === '' || mb_strlen($film) > 150)         { $errors[] = t('err_film'); }

    $a = DateTime::createFromFormat('Y-m-d', $d1);
    $b = DateTime::createFromFormat('Y-m-d', $d2);
    if (!$a || $a->format('Y-m-d') !== $d1 || !$b || $b->format('Y-m-d') !== $d2) {
        $errors[] = t('err_date');
    } elseif ($b < $a) {
        $errors[] = t('err_range');
    }

    switch ($status) {
        case 'dipinjam':
        case 'dikembalikan':
        case 'terlambat':
            break;
        default:
            $errors[] = t('err_status');
    }

    return [[
        'nama_peminjam'=>$borrower,'judul_film'=>$film,'tanggal_pinjam'=>$d1,
        'tanggal_kembali'=>$d2,'status'=>$status,'catatan'=>$note
    ], $errors];
}

function status_label($s){
    switch ($s) {
        case 'dipinjam': return t('borrowed');
        case 'dikembalikan': return t('returned');
        case 'terlambat': return t('late');
        default: return e($s);
    }
}

// Tautan ganti bahasa: tetap di halaman yang sama dan mempertahankan ?id=
function lang_link($l){
    $self = rawurlencode(basename($_SERVER['PHP_SELF']));
    $q = $_GET; $q['lang'] = $l;
    return $self . '?' . http_build_query($q);
}

function page_header($title){
    echo '<!DOCTYPE html><html lang="' . e($_SESSION['lang']) . '"><head><meta charset="UTF-8">';
    echo '<title>' . e($title) . '</title></head><body>';
    echo '<p><b>' . e(t('app_title')) . '</b> | ' . e(t('language')) . ': ';
    echo '<a href="' . e(lang_link('id')) . '">ID</a> | <a href="' . e(lang_link('en')) . '">EN</a>';
    if (!empty($_SESSION['user_id'])) {
        echo ' | ' . e(t('hello')) . ', ' . e($_SESSION['username']);
        echo ' | <a href="logout.php">' . e(t('logout')) . '</a>';
    }
    echo '</p><hr>';
}
function page_footer(){ echo '</body></html>'; }

// Form input bersama untuk tambah.php dan edit.php
function render_form_fields(array $data, array $errors){
    foreach ($errors as $er) { echo '<p><b>' . e($er) . '</b></p>'; }
    echo '<input type="hidden" name="csrf" value="' . e(csrf_token()) . '">';
    echo '<table cellpadding="4">';
    echo '<tr><td>' . e(t('borrower')) . '</td><td><input type="text" name="nama_peminjam" maxlength="100" value="' . e($data['nama_peminjam']) . '"></td></tr>';
    echo '<tr><td>' . e(t('film')) . '</td><td><input type="text" name="judul_film" maxlength="150" value="' . e($data['judul_film']) . '"></td></tr>';
    echo '<tr><td>' . e(t('borrow_date')) . '</td><td><input type="date" name="tanggal_pinjam" value="' . e($data['tanggal_pinjam']) . '"></td></tr>';
    echo '<tr><td>' . e(t('return_date')) . '</td><td><input type="date" name="tanggal_kembali" value="' . e($data['tanggal_kembali']) . '"></td></tr>';
    echo '<tr><td>' . e(t('status')) . '</td><td><select name="status">';
    foreach (['dipinjam','dikembalikan','terlambat'] as $s) {
        echo '<option value="' . $s . '"' . ($data['status'] === $s ? ' selected' : '') . '>' . e(status_label($s)) . '</option>';
    }
    echo '</select></td></tr>';
    echo '<tr><td>' . e(t('note')) . '</td><td><textarea name="catatan" rows="3" cols="30">' . e($data['catatan']) . '</textarea></td></tr>';
    echo '</table>';
    echo '<p><button type="submit">' . e(t('save')) . '</button> <a href="index.php">' . e(t('cancel')) . '</a></p>';
}
