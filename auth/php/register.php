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

// check for empty values in form
  foreach($_POST as $name => $value){
    if(empty(trim($value))){
      echo $name;
      // if empty show error message
      session_start();
      $_SESSION["error"] = "<p> Please enter: <p>". $name;
      header("Location:../register_temp.php");
    }
  }
}

// Prepare sql statement for inserting into users
$stmt = mysqli_prepare($link, "INSERT INTO users (`first_name`, `last_name`, `email`, `password`, `phone`, `dob`, `role`, `confirmed`) VALUES (?,?,?,?,?,?,?,NULL)");
mysqli_stmt_bind_param($stmt, 'ssssssi', $first_name, $last_name, $email, $password, $phone, $dob, $role);
mysqli_stmt_execute($stmt);

//check for errors from database entry
if(mysqli_stmt_error($stmt)){
  //if error return back to register page and show error message
  session_start();
  $_SESSION["error"] = errno(mysqli_stmt_errno($stmt));
  header("Location:../register_temp.php");
}else{
  header("Location:../login_temp.php");
}
mysqli_stmt_close($stmt);

//roles of employees
$employees = array(1, 2, 3, 4);

// Grab user id with email
$stmt = mysqli_prepare($link, "SELECT user_id FROM users WHERE email = ?");
mysqli_stmt_bind_param($stmt, 's', $email);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);
mysqli_stmt_bind_result($stmt, $user);
mysqli_stmt_fetch($stmt);
$user_id = $user;
mysqli_stmt_close($stmt);

// If role is patient
if($role == 5){
  // Insert into patient table with user id
  $stmt = mysqli_prepare($link, "INSERT INTO patients (`patient_id`, `family_code`, `emergency_contact`, `ec_relation`, `group_id`) VALUES (?,?,?,?,1)");
  mysqli_stmt_bind_param($stmt, 'iiss', $user_id, $_POST['familycode'], $_POST['emer_contact'], $_POST['relation'] );
  mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);
}elseif (inarray($role, $employees)){
  // Insert into employees table with user id
  $stmt = mysqli_prepare($link, "INSERT INTO employees (`employee_id`, `salary`, `group_id`) VALUES (?,null,1)");
  mysqli_stmt_bind_param($stmt, 'i', $user_id);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);
}

//takes error number and returns error message
function errno($no){
  if($no == 1062){
    return "<p> email already in use. </p>";
  }
}

// Close connection
mysqli_close($link);
?>
