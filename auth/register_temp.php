<?php

echo <<< "EOT"
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Register</title>
    <link rel="stylesheet" href="../assets/styles.css">
    <script type="text/javascript" src="ifpatient.js"></script>
  </head>
  <body>

    <header>
      <a href="index.html">Back</a>

      <nav class="nav">
        <a href="index.html">Home</a>
      </nav>
    </header>

    <!-- Register Form -->


    <section class="auth">
    <h1>Register</h1>

      <form class="form-style register" action="php/register.php" method="post">

        <label for="email">Email</label>
        <input type="text" name="email" required/>

        <label for="password">Password</label>
        <input type="password" name="password" required/>

        <label for="role">Role</label>
        <select onchange="patient(role)" name="role">
          <option value=1 >Admin</option>
          <option value=2 >Supervisor</option>
          <option value=3 >Doctor</option>
          <option value=4 >Caregiver</option>
          <option value=5 >Patient</option>
          <option value=6 >Family Member</option>
        </select>

        <label for="fname">First Name</label>
        <input type="text" name="fname" required/>

        <label for="lname">Last Name</label>
        <input type="text" name="lname" required/>

        <label for="phone">Phone Number (xxx-xxx-xxxx)</label>
        <input type="tel" id="phone" name="phone" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" required/>

        <label for="dob">Date of Birth</label>
        <input type="date" id="date-of-birth" name="dob" required/>

        <input id="submit" type="submit" value="Submit">
      </form>

    </section>
  </body>
</html>
EOT;
// flash if errors
session_start();
if(isset($_SESSION['error'])){
  echo $_SESSION['error'];
  unset( $_SESSION['error'] );
}


?>
