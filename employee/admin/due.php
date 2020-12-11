<?php
require_once '../../auth/php/config.php';
require '../../auth/php/auth.php';
session_start();

if(!isset($_SESSION['user_id'])){
  header("Location:../../auth/login_temp.php");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && auth([1], $link)) {
  $pid = (int) $_POST['id'];
  if ($stmt = $link->prepare('SELECT due
                              FROM patients
                              WHERE patient_id = ? AND admit_date IS NOT NULL')) {
    $stmt->bind_param('i', $pid);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($due);
    if ($stmt->fetch()) {
      echo $due;
    } else {
      echo "Invalid Patient ID";
    }
    $stmt->close();
  }
}
?>
