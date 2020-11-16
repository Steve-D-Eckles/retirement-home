<?php
require_once '../../auth/php/config.php';
require '../../auth/php/auth.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && auth([1], $link)) {
  $name = $_POST['name'];
  $level = $_POST['level'];

  if ($stmt = $link->prepare('INSERT INTO roles (role_name, access_level)
                              VALUES (?, ?)')) {
    $stmt->bind_param('si', $name, $level);
    $stmt->execute();
    $stmt->close();
  }
}
header('Location: role.php');
?>
