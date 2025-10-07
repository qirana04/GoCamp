<?php
require_once 'inc/config.php';
require_once 'inc/functions.php';
require_login();

$user = current_user();
if($user['role'] === 'admin') {
    $rows = get_peminjaman_all(null);
} else {
    $rows = get_peminjaman_all($user['id']);
}

// admin bisa ubah status via ?action=return&id=...
if($user['role']==='admin' && isset($_GET['action']) && $_GET['action']==='return') {
    $id = (int)$_GET['id'];
    $stmt = $mysqli->prepare("UPDATE peminjaman SET status='dikembalikan' WHERE id=?");
    $stmt->bind_param("i",$id);
    $stmt->execute();

    // setelah dikembalikan, tambahkan stok kembali
    $stmt2 = $mysqli->prepare("SELECT peralatan_id, jumlah FROM peminjaman WHERE id=?");
    $stmt2->bind_param("i",$id);
    $stmt2->execute();
    $r = $stmt2->get_result()->fetch_assoc();
    if($r) {
        $stmt3 = $mysqli->prepare("UPDATE peralatan SET stok = stok + ?, status = 'tersedia' WHERE id = ?");
        $stmt3->bind_param("ii", $r['jumlah'], $r['peralatan_id']);
        $stmt3->execute();
    }

    set_flash('Status peminjaman diperbarui.');
    header('Location: peminjaman_list.php');
    exit;
}
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Daftar Peminjaman</title><link rel="stylesheet" href="assets/style.css"></head>
<body>
<div class="container">
  <nav style="margin-bottom:10px;">
    <div><strong>GoCamp</strong></div>
    <div>
      Halo, <?=htmlspecialchars($user['username'])?> | <a href="index.php">Peralatan</a> | <a href="logout.php">Logout</a>
    </div>
  </nav>

  <?php if($m=get_flash()): ?><div class="flash"><?=htmlspecialchars($m)?></div><?php endif; ?>

  <h2>Daftar Peminjaman</h2>
  <table>
    <thead><tr><th>#</th><th>User</th><th>Peralatan</th><th>Tgl Pinjam</th><th>Tgl Kembali</th><th>Jumlah</th><th>Status</th><th>Aksi</th></tr></thead>
    <tbody>
      <?php foreach($rows as $r): ?>
        <tr>
          <td><?=htmlspecialchars($r['id'])?></td>
          <td><?=htmlspecialchars($r['username'])?></td>
          <td><?=htmlspecialchars($r['nama_alat'])?></td>
          <td><?=htmlspecialchars($r['tanggal_pinjam'])?></td>
          <td><?=htmlspecialchars($r['tanggal_kembali'])?></td>
          <td><?=htmlspecialchars($r['jumlah'])?></td>
          <td><?=htmlspecialchars($r['status'])?></td>
          <td>
            <?php if($user['role']==='admin' && $r['status']==='dipinjam'): ?>
              <a href="peminjaman_list.php?action=return&id=<?= $r['id'] ?>" onclick="return confirm('Konfirmasi dikembalikan?')">Tandai dikembalikan</a>
            <?php else: ?>
              -
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach;?>
    </tbody>
  </table>
</div>
</body>
</html>
