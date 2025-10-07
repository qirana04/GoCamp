<?php
session_start();

$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'gocamp';

$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($mysqli->connect_errno) {
    die("Koneksi DB gagal: " . $mysqli->connect_error);
}

// helper = file khusus(berisi kode bantu) sederhana untuk flash message
function set_flash($msg) {
    $_SESSION['flash'] = $msg;
}
function get_flash() {
    if(!empty($_SESSION['flash'])) {
        $m = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $m;
    }
    return '';
}

// simple auth checks = pemeriksaan identitas sederhana
function is_logged_in() {
    return !empty($_SESSION['user']);
}
function current_user() {
    return $_SESSION['user'] ?? null;
}
function require_login() {
    if(!is_logged_in()) {
        set_flash('Silakan login dulu.');
        header('Location: login.php');
        exit;
    }
}
function require_admin() {
    if(!is_logged_in() || ($_SESSION['user']['role'] ?? '') !== 'admin') {
        set_flash('Akses admin diperlukan.');
        header('Location: index.php');
        exit;
    }
}
