<?php
require_once 'inc/config.php';
if(is_logged_in()) header('Location: index.php');

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    if($username === '' || $password === '') {
        set_flash('Username & password wajib diisi.');
    } else {
        // cek unik
        $stmt = $mysqli->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s",$username);
        $stmt->execute();
        $res = $stmt->get_result();
        if($res->num_rows > 0) {
            set_flash('Username sudah dipakai.');
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $mysqli->prepare("INSERT INTO users (username, password, role) VALUES (?,?, 'user')");
            $stmt->bind_param("ss", $username, $hash);
            if($stmt->execute()) {
                set_flash('Registrasi berhasil. Silakan login.');
                header('Location: login.php');
                exit;
            } else {
                set_flash('Gagal registrasi.');
            }
        }
    }
}
?>
<?php include 'inc/header.php' ?? null; ?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Register - GoCamp</title><link rel="stylesheet" href="assets/style.css"></head>
<body>
<div class="container">
  <h2>Register</h2>
  <?php if($m = get_flash()): ?><div class="flash"><?=htmlspecialchars($m)?></div><?php endif;?>
  <form method="post">
    <div class="form-row"><label>Username</label><input type="text" name="username"></div>
    <div class="form-row"><label>Password</label><input type="password" name="password"></div>
    <input type="submit" value="Daftar">
  </form>
  <p>Sudah punya akun? <a href="login.php">Login</a></p>
</div>
</body>
</html>
