<?php
require_once 'inc/config.php';
if(is_logged_in()) header('Location: index.php');

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $mysqli->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
    $stmt->bind_param("s",$username);
    $stmt->execute();
    $r = $stmt->get_result()->fetch_assoc();
    if($r && password_verify($password, $r['password'])) {
        unset($r['password']);
        $_SESSION['user'] = $r;
        set_flash('Login berhasil. Selamat datang, '.$r['username']);
        header('Location: index.php');
        exit;
    } else {
        set_flash('Username atau password salah.');
    }
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Login - GoCamp</title><link rel="stylesheet" href="assets/style.css"></head>
<body>
<div class="container">
  <h2>Login</h2>
  <?php if($m = get_flash()): ?><div class="flash"><?=htmlspecialchars($m)?></div><?php endif;?>
  <form method="post">
    <div class="form-row"><label>Username</label><input type="text" name="username"></div>
    <div class="form-row"><label>Password</label><input type="password" name="password"></div>
    <input type="submit" value="Login">
  </form>
  <p>Belum punya akun? <a href="register.php">Register</a></p>
</div>
</body>
</html>
