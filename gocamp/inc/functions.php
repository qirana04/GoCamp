<?php
require_once __DIR__ . '/config.php';

// ambil semua peralatan
function get_all_peralatan() {
    global $mysqli;
    $res = $mysqli->query("SELECT * FROM peralatan ORDER BY id DESC");
    return $res->fetch_all(MYSQLI_ASSOC);
}

// ambil peralatan berdasarkan id
function get_peralatan($id) {
    global $mysqli;
    $stmt = $mysqli->prepare("SELECT * FROM peralatan WHERE id = ?");
    $stmt->bind_param("i",$id);
    $stmt->execute();
    $r = $stmt->get_result();
    return $r->fetch_assoc();
}

// buat peminjaman
function create_peminjaman($user_id, $peralatan_id, $tgl_pinjam, $tgl_kembali, $jumlah) {
    global $mysqli;
    $stmt = $mysqli->prepare("INSERT INTO peminjaman (user_id, peralatan_id, tanggal_pinjam, tanggal_kembali, jumlah) VALUES (?,?,?,?,?)");
    $stmt->bind_param("iissi", $user_id, $peralatan_id, $tgl_pinjam, $tgl_kembali, $jumlah);
    return $stmt->execute();
}

// ambil peminjaman (admin = semua; user = miliknya)
function get_peminjaman_all($only_user_id = null) {
    global $mysqli;
    if($only_user_id) {
        $stmt = $mysqli->prepare("SELECT p.*, u.username, a.nama_alat FROM peminjaman p JOIN users u ON p.user_id = u.id JOIN peralatan a ON p.peralatan_id = a.id WHERE p.user_id = ? ORDER BY p.id DESC");
        $stmt->bind_param("i", $only_user_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    } else {
        $res = $mysqli->query("SELECT p.*, u.username, a.nama_alat FROM peminjaman p JOIN users u ON p.user_id = u.id JOIN peralatan a ON p.peralatan_id = a.id ORDER BY p.id DESC");
        return $res->fetch_all(MYSQLI_ASSOC);
    }
}
