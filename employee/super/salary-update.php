<?php
require_once '../../auth/php/config.php';
require '../../auth/php/auth.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && auth([1], $link)) {
  $id = $_POST['update-id'];
  $salary = $_POST['update-salary'];

  if ($stmt = $link->prepare('UPDATE employees SET salary = ? WHERE employee_id = ?')) {
    $stmt->bind_param('ii', $salary, $id);
    $stmt->execute();
    $stmt->close();
  }
}
header('Location: employee-search.php')
?>
