<?php
require_once 'inc/config.php';
require_admin();
$id = (int)($_GET['id'] ?? 0);
if($id) {
    $stmt = $mysqli->prepare("DELETE FROM peralatan WHERE id = ?");
    $stmt->bind_param("i",$id);
    $stmt->execute();
    set_flash('Peralatan dihapus.');
}
header('Location: index.php');
exit;
