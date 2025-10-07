<?php
require_once 'inc/config.php';
require_once 'inc/functions.php';

$peralatans = get_all_peralatan();
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>GoCamp</title><link rel="stylesheet" href="assets/style.css"></head>
<body>
<div class="container">
  <nav>
    <div><strong>GoCamp</strong></div>
    <div>
      <?php if(is_logged_in()): ?>
        Halo, <?=htmlspecialchars(current_user()['username'])?> |
        <a href="peminjaman_list.php">Peminjaman</a> |
        <?php if(current_user()['role']==='admin'): ?>
          <a href="peralatan_add.php">Tambah Peralatan</a> |
        <?php endif; ?>
        <a href="logout.php">Logout</a>
      <?php else: ?>
        <a href="login.php">Login</a> | <a href="register.php">Register</a>
      <?php endif; ?>
    </div>
  </nav>

  <?php if($m = get_flash()): ?><div class="flash"><?=htmlspecialchars($m)?></div><?php endif; ?>

  <h2>Daftar Peralatan</h2>
  <table>
    <thead><tr><th>#</th><th>Nama</th><th>Stok</th><th>Harga / hari</th><th>Status</th><th>Aksi</th></tr></thead>
    <tbody>
      <?php foreach($peralatans as $p): ?>
        <tr>
          <td><?=htmlspecialchars($p['id'])?></td>
          <td><?=htmlspecialchars($p['nama_alat'])?><br><small><?=nl2br(htmlspecialchars($p['deskripsi']))?></small></td>
          <td><?=htmlspecialchars($p['stok'])?></td>
          <td><?=number_format($p['harga_sewa_per_hari'],2)?></td>
          <td><?=htmlspecialchars($p['status'])?></td>
          <td>
            <?php if(is_logged_in()): ?>
              <?php if($p['stok']>0 && $p['status']=='tersedia'): ?>
                <a href="peminjaman.php?peralatan_id=<?= $p['id'] ?>">Pinjam</a>
              <?php else: ?>
                Tidak bisa pinjam
              <?php endif; ?>
              <?php if(current_user()['role']==='admin'): ?>
                | <a href="peralatan_edit.php?id=<?= $p['id'] ?>">Edit</a> | <a href="peralatan_delete.php?id=<?= $p['id'] ?>" onclick="return confirm('Yakin hapus?')">Hapus</a>
              <?php endif; ?>
            <?php else: ?>
              <a href="login.php">Login untuk pinjam</a>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
</body>
</html>
