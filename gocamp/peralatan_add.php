<?php
require_once 'inc/config.php';
require_once 'inc/functions.php';
require_admin();

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama_alat'];
    $stok = (int)$_POST['stok'];
    $harga = (float)$_POST['harga'];
    $desc = $_POST['deskripsi'];
    $status = $_POST['status'];

    $stmt = $mysqli->prepare("INSERT INTO peralatan (nama_alat, stok, harga_sewa_per_hari, deskripsi, status) VALUES (?,?,?,?,?)");
    $stmt->bind_param("sidss", $nama, $stok, $harga, $desc, $status);
    if($stmt->execute()) {
        set_flash('Peralatan berhasil ditambahkan.');
        header('Location: index.php'); exit;
    } else {
        set_flash('Gagal menambahkan.');
    }
}
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Tambah Peralatan</title><link rel="stylesheet" href="assets/style.css"></head>
<body>
<div class="container">
  <h2>Tambah Peralatan</h2>
  <?php if($m=get_flash()): ?><div class="flash"><?=htmlspecialchars($m)?></div><?php endif;?>
  <form method="post">
    <div class="form-row"><label>Nama Alat</label><input type="text" name="nama_alat" required></div>
    <div class="form-row"><label>Stok</label><input type="text" name="stok" value="0"></div>
    <div class="form-row"><label>Harga sewa per hari</label><input type="text" name="harga" value="0.00"></div>
    <div class="form-row"><label>Deskripsi</label><textarea name="deskripsi"></textarea></div>
    <div class="form-row"><label>Status</label>
      <select name="status">
        <option value="tersedia">tersedia</option>
        <option value="dipinjam">dipinjam</option>
      </select>
    </div>
    <input type="submit" value="Simpan">
  </form>
  <p><a href="index.php">Kembali</a></p>
</div>
</body></html>
