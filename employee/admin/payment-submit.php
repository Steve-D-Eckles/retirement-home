<?php
require_once '../../auth/php/config.php';
require_once '../../auth/php/auth.php';
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && auth([1], $link)) {
  $id = isset($_POST['id']) ? (int) $_POST['id'] : NULL;
  $amount = isset($_POST['amount']) ? (int) $_POST['amount'] : NULL;

  if ($id === NULL || $amount === NULL) {
    exit('Required data missing');
  }

  if ($stmt = $link->prepare('UPDATE patients
                              SET due = due - ?
                              WHERE patient_id = ?')) {
    $stmt->bind_param('ii', $amount, $id);
    $stmt->execute();
    $stmt->close();
  }

  if ($stmt = $link->prepare('SELECT due FROM patients WHERE patient_id = ?')) {
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($val);

    if ($stmt->fetch()) {
      echo $val;
    }
  }
}

?>
