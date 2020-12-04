<?php
require_once '../../auth/php/config.php';
require_once '../../auth/php/auth.php';
session_start();

if($_SERVER["REQUEST_METHOD"] == "POST"){
  $comment = $_POST['comment'];
  $morn_med = $_POST['morn_med'];
  $afternoon_med = $_POST['afternoon_med'];
  $night_med = $_POST['night_med'];
  $appt_id = $_POST['appt_id'];
}

if ($comment === NULL || $morn_med === NULL || $afternoon_med === NULL || $night_med === NULL) {
  exit('Required data missing');
}

if ($stmt = $link->prepare('UPDATE appointments
                            SET comment=?, morn_med=?, afternoon_med=?, night_med=?
                            WHERE appt_id = ?')) {
  $stmt->bind_param('ssssi', $comment, $morn_med, $afternoon_med, $night_med, $appt_id);
  $stmt->execute();
  $stmt->close();
  header('Location: home.php');
 }
