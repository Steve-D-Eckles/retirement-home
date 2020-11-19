<?php

echo <<< "EOT"
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Login</title>
    <link rel="stylesheet" href="../assets/styles.css">
  </head>
  <body>

    <header>
      <a href="../index.html">Back</a>

      <nav class="nav">
        <a href="../index.html">Home</a>
      </nav>
    </header>

    <!-- Login Form -->


    <section class="auth">
      <h1>Login</h1>

      <form class="form-style" action="php/login.php" method="post">

        <label for="email">Email</label>
        <input type="text" name="email" required/>

        <label for="password">Password</label>
        <input type="password" name="password" required/>

        <input type="submit" value="Submit">

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
