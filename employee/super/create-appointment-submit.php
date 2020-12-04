<?php
require_once '../../auth/php/config.php';
require_once '../../auth/php/auth.php';
session_start();
header('Location: create-appointment.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST' && auth([1, 2], $link)) {
  $pid = isset($_POST['id']) ? (int) $_POST['id'] : NULL;
  $did = isset($_POST['doctor']) ? (int) $_POST['doctor'] : NULL;
  $date = isset($_POST['date']) ? $_POST['date'] : NULL;

  if ($pid === NULL || $did === NULL || $date === NULL) {
    exit('Required data missing');
  }

  if ($stmt = $link->prepare("INSERT INTO appointments (patient_id, doctor_id, appt_date)
                              VALUES (?, ?, ?)")) {
    $stmt->bind_param('iis', $pid, $did, $date);
    $stmt->execute();
  }
}
?>
