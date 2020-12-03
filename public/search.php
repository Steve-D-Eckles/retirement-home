<?php
require_once '../auth/php/config.php';
require_once '../auth/php/auth.php';
session_start();

if(!isset($_SESSION['user_id'])) {
  header("Location:../../auth/login_temp.php");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $date = isset($_POST['date']) ? $_POST['date'] : NULL;
  if ($date) {
    if ($stmt = $link->prepare('SELECT supervisor_id, doctor_id, care_one_id,
                                       care_two_id, care_three_id, care_four_id
                                FROM roster WHERE roster_date = ?')) {
      $stmt->bind_param('s', $date);
      $stmt->execute();
      $res = $stmt->get_result();

      if ($set = $res->fetch_assoc()) {
        $set = array_map('get_name', $set);
        echo json_encode($set);
      }

      $stmt->close();
    }
  }
} else {
  header("Location:../index.html");
}

function get_name($id) {
  if ($func_stmt = $GLOBALS["link"]->prepare("SELECT first_name, last_name
                                              FROM users
                                              WHERE user_id = ?")) {
   $func_stmt->bind_param('i', $id);
   $func_stmt->execute();
   $func_stmt->store_result();
   $func_stmt->bind_result($first, $last);

   if ($func_stmt->fetch()) {
     return ucfirst($first) . ' ' . ucfirst($last);
   }

   $func_stmt->close();
 }
}
?>
