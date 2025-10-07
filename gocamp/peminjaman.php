<?php
require_once 'inc/config.php';
require_once 'inc/functions.php';
require_login();

$peralatan_id = (int)($_GET['peralatan_id'] ?? 0);
$peralatan = $peralatan_id ? get_peralatan($peralatan_id) : null;

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = current_user()['id'];
    $peralatan_id = (int)$_POST['peralatan_id'];
    $tgl_pinjam = $_POST['tanggal_pinjam'];
    $tgl_kembali = $_POST['tanggal_kembali'];
    $jumlah = (int)$_POST['jumlah'];

    // cek stok
    $p = get_peralatan($peralatan_id);
    if(!$p) {
        set_flash('Peralatan tidak ditemukan.');
    } elseif($jumlah <= 0 || $jumlah > $p['stok']) {
        set_flash('Jumlah tidak valid. Stok tersedia: ' . $p['stok']);
    } else {
        if(create_peminjaman($user_id, $peralatan_id, $tgl_pinjam, $tgl_kembali, $jumlah)) {
            // update stok dan status sederhana
            $newstok = $p['stok'] - $jumlah;
            $status = $newstok>0 ? 'tersedia' : 'dipinjam';
            $stmt = $mysqli->prepare("UPDATE peralatan SET stok=?, status=? WHERE id=?");
            $stmt->bind_param("isi", $newstok, $status, $peralatan_id);
            $stmt->execute();

            set_flash('Peminjaman berhasil dibuat.');
            header('Location: peminjaman_list.php');
            exit;
        } else {
            set_flash('Gagal membuat peminjaman.');
        }
    }
}
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Pinjam Peralatan</title><link rel="stylesheet" href="assets/style.css"></head>
<body>
<div class="container">
  <h2>Form Peminjaman</h2>
  <?php if($m=get_flash()): ?><div class="flash"><?=htmlspecialchars($m)?></div><?php endif;?>
  <form method="post">
    <input type="hidden" name="peralatan_id" value="<?=htmlspecialchars($peralatan['id'] ?? '')?>">
    <div class="form-row"><label>Peralatan</label>
      <input type="text" value="<?=htmlspecialchars($peralatan['nama_alat'] ?? '')?>" disabled>
    </div>
    <div class="form-row"><label>Tanggal Pinjam</label><input type="date" name="tanggal_pinjam" required></div>
    <div class="form-row"><label>Tanggal Kembali</label><input type="date" name="tanggal_kembali" required></div>
    <div class="form-row"><label>Jumlah</label><input type="number" name="jumlah" value="1" min="1"></div>
    <input type="submit" value="Pinjam">
  </form>
  <p><a href="index.php">Kembali</a></p>
</div>
</body></html>
