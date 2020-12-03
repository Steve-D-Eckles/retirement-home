<?php
function auth($targets, $link) {
  if (isset($_SESSION['loggedin'])) {
    if ($stmt = $link->prepare('SELECT role FROM users WHERE user_id = ?')) {
      $stmt->bind_param('i', $_SESSION['user_id']);
      $stmt->execute();
      $stmt->store_result();
      $stmt->bind_result($role);
      if ($stmt->fetch()) {
        if(in_array($role, $targets)){
          return true;
        } else {
          redirect_by_role($role);
        }
      }
    }
  }
  return false;
}

function redirect_by_role($role_id){
  if($role_id == 1){
    header('Location:../../employee/admin/home.php');
  }
  if($role_id == 2){
    header('Location:../../employee/super/home.php');
  }
  if($role_id == 3){
    header('Location:../../employee/doctor/home.php');
  }
  if($role_id == 4){
    header('Location:../../employee/caregiver/home.php');
  }
  if($role_id == 5){
    header('Location:../../patient/home.php');
  }
  if($role_id == 6){
    //header('Location:');
    //exit;
  }
}
?>
