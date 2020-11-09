<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$email = $password = $confirm_password = "";
$email_err = $password_err = $confirm_password_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Validate username
    if(empty(trim($_POST["email"]))){
        $username_err = "Please enter a email";
  
    // Close connection
    mysqli_close($link);
}

?>
