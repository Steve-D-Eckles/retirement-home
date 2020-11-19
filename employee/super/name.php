<?php
require_once '../../auth/php/config.php';
require '../../auth/php/auth.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && auth([1, 2], $link)) {
  $user_id = (int) $_POST['id'];
  if ($stmt = $link->prepare('SELECT first_name, last_name
                              FROM users u JOIN patients p ON u.user_id = p.patient_id
                              WHERE confirmed = 1 AND patient_id = ?')) {
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($fname, $lname);
    if ($stmt->fetch()) {
      echo ucfirst($fname) . ' ' . ucfirst($lname);
    } else {
      echo "Invalid Patient ID";
    }
    $stmt->close();
  }
}
?>
