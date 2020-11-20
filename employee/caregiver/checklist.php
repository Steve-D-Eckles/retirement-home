<?php
require_once '../../auth/php/config.php';

session_start();

// Building insert into string by concatinating all that are checked
if($_SERVER["REQUEST_METHOD"] == "POST"){

  $sql = "UPDATE checklists SET ";
  $list_id = $_POST['list_id'];
  $completed = [];

  if (isset($_POST['morn_med'])){
    $completed[] = "morn_med = 1";
  }

  if (isset($_POST['afternoon_med'])){
    $completed[] = "afternoon_med = 1";
  }

  if (isset($_POST['night_med'])){
    $completed[] = "night_med = 1";
  }

  if (isset($_POST['breakfast'])){
    $completed[] = "breakfast = 1";
  }

  if (isset($_POST['lunch'])){
    $completed[] = "lunch = 1";
  }

  if (isset($_POST['dinner'])){
    $completed[] = "dinner = 1";
  }

  for($i = 0; $i < count($completed); $i++){
    if ($i == count($completed) - 1){
      $sql = $sql . $completed[$i] . " ";
    }else{
      $sql = $sql . $completed[$i] . ", ";
    }
  }

  $sql = $sql . "WHERE list_id = " . $list_id;

  if ($link->query($sql) === TRUE) {
    header('Location:home.php');
  } else {
    echo "Error updating record: " . $link->error;
  }
}
?>
