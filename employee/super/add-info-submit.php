<?php
require_once '../../auth/php/config.php';
require_once '../../auth/php/auth.php';
session_start();
header('Location: add-info.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST' && auth([1, 2], $link)) {
  $id = isset($_POST['id']) ? (int) $_POST['id'] : NULL;
  $group = isset($_POST['group']) ? (int) $_POST['group'] : NULL;
  $date = isset($_POST['date']) ? $_POST['date'] : NULL;

  echo "$id $group $date \n";

  if ($id === NULL || $group === NULL || $date === NULL) {
    exit('Required data missing');
  }

  if ($stmt = $link->prepare('UPDATE patients
                              SET group_id = ?, admit_date = ?
                              WHERE patient_id = ?')) {
    $stmt->bind_param('isi', $group, $date, $id);
    $stmt->execute();
    $stmt->close();
    header('Location: home.php');
  }
}

?>
