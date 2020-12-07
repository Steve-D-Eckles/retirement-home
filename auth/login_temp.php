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

      <h1>Login</h1>

      <nav class="nav">
        <a href="../index.html">Home</a>
      </nav>
    </header>

    <!-- Login Form -->


    <section class="centered-form-wrap">

    <h2> Welcome </h2>

      <form class="form-style" action="php/login.php" method="post">

        <label for="email">Email</label>
        <input type="text" name="email" required/>

        <label for="password">Password</label>
        <input type="password" name="password" required/>

        <input class='check-submit' type="submit" value="Submit">

      </form>

      <p><a class="link" href="../public/family-member/home.php">Family Member?</a></p>

    </section>

    <footer>
      <p>Retirement Home</p>
    </footer>

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
