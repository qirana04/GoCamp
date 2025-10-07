<?php
require_once 'inc/config.php';
require_once 'inc/functions.php';
require_admin();

$id = (int)($_GET['id'] ?? 0);
$item = get_peralatan($id);
if(!$item) { set_flash('Peralatan tidak ditemukan.'); header('Location: index.php'); exit; }

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama_alat'];
    $stok = (int)$_POST['stok'];
    $harga = (float)$_POST['harga'];
    $desc = $_POST['deskripsi'];
    $status = $_POST['status'];
    $stmt = $mysqli->prepare("UPDATE peralatan SET nama_alat=?, stok=?, harga_sewa_per_hari=?, deskripsi=?, status=? WHERE id=?");
    $stmt->bind_param("sidssi", $nama, $stok, $harga, $desc, $status, $id);
    if($stmt->execute()) {
        set_flash('Peralatan diperbarui.');
        header('Location: index.php'); exit;
    } else set_flash('Gagal update.');
}
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Edit Peralatan</title><link rel="stylesheet" href="assets/style.css"></head>
<body>
<div class="container">
  <h2>Edit Peralatan</h2>
  <?php if($m=get_flash()): ?><div class="flash"><?=htmlspecialchars($m)?></div><?php endif;?>
  <form method="post">
    <div class="form-row"><label>Nama Alat</label><input type="text" name="nama_alat" required value="<?=htmlspecialchars($item['nama_alat'])?>"></div>
    <div class="form-row"><label>Stok</label><input type="text" name="stok" value="<?=htmlspecialchars($item['stok'])?>"></div>
    <div class="form-row"><label>Harga sewa per hari</label><input type="text" name="harga" value="<?=htmlspecialchars($item['harga_sewa_per_hari'])?>"></div>
    <div class="form-row"><label>Deskripsi</label><textarea name="deskripsi"><?=htmlspecialchars($item['deskripsi'])?></textarea></div>
    <div class="form-row"><label>Status</label>
      <select name="status">
        <option value="tersedia" <?= $item['status']=='tersedia'?'selected':'' ?>>tersedia</option>
        <option value="dipinjam" <?= $item['status']=='dipinjam'?'selected':'' ?>>dipinjam</option>
      </select>
    </div>
    <input type="submit" value="Update">
  </form>
  <p><a href="index.php">Kembali</a></p>
</div>
</body></html>
