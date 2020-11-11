<?php
// Include config file
require_once "config.php";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

  $email = $_POST['email'];
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
  $role = $_POST['role'];
  $first_name = $_POST['fname'];
  $last_name = $_POST['lname'];
  $phone = $_POST['phone'];
  $dob = $_POST['dob'];
  $unconfirmed = 0;

// check for empty values in form
  foreach($_POST as $name => $value) {
    if(empty(trim($value))){
      // if empty show error message
      session_start();
      $_SESSION["error"] = "<p> Please enter: <p>". $name;
      header("Location:../register_temp.php");
    }
  }
}

// Prepare sql statement for inserting into users
$stmt = mysqli_prepare($link, "INSERT INTO users (`first_name`, `last_name`, `email`, `password`, `phone`, `dob`, `role`, `confirmed`) VALUES (?,?,?,?,?,?,?,?)");
mysqli_stmt_bind_param($stmt, 'ssssssii', $first_name, $last_name, $email, $password, $phone, $dob, $role, $unconfirmed);
mysqli_stmt_execute($stmt);

//check for errors from database entry
if(mysqli_stmt_error($stmt)){
  //if error return back to register page and show error message
  session_start();
  $_SESSION["error"] = errno(mysqli_stmt_errno($stmt));
  header("Location:../register_temp.php");
}else{
  header("Location:../login.html");
}

//takes error number and returns error message
function errno($no){
  if($no == 1062){
    return "<p> email already in use. </p>";
  }
}


mysqli_stmt_close($stmt);
// Close connection
mysqli_close($link);
?>
