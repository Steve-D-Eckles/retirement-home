<?php
// Include config file
require_once "config.php";
session_start();
$_SESSION = array();
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

  $email = $_POST['email'];

  // check for empty values in form
  foreach($_POST as $name => $value){
    if(empty(trim($value))){
      echo $name;
      // if empty show error message
      $_SESSION["error"] = "<p> Please enter: <p>". $name;
      header("Location:../login_temp.php");
    }
  }
  //grab id, password, and role with email
  if ($stmt = $link->prepare('SELECT user_id, password, first_name, last_name, role
                              FROM users
                              WHERE email = ?')) {
      $stmt->bind_param('s', $email);
      $stmt->execute();
      $stmt->store_result();

      //check if an account exists with the email
      if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $password, $first_name, $last_name, $role);
        $stmt->fetch();

        //verify entered password with hashed password
        if (password_verify($_POST['password'], $password)) {
          session_regenerate_id();
          $_SESSION['loggedin'] = TRUE;
          $_SESSION['user_id'] = $user_id;
          $_SESSION['first_name'] = $first_name;
          $_SESSION['last_name'] = $last_name;
        }else{

          $_SESSION["error"] = "incorrect email and/or password";
          header("Location:../login_temp.php");
          exit;
        }
      }else{
      $_SESSION["error"] = "incorrect email and/or password";
      header("Location:../login_temp.php");
      exit;
      }

      $stmt->close();
    }
    if(isset($_SESSION['loggedin'])){
      redirect_by_role($role);
    }

}

function redirect_by_role($role){
  if($role == 1){
    //header('Location:');
    //exit;
  }
  if($role == 2){
    //header('Location:');
    //exit;
  }
  if($role == 3){
    //header('Location:');
    //exit;
  }
  if($role == 4){
    header('Location:../../employee/caregiver/home.php');
    exit;
  }
  if($role == 5){
    header('Location: ../../patient/home.php');
    exit;
  }
  if($role == 6){
    //header('Location:');
    //exit;
  }
}
?>
