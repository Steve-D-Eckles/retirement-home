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
      <a href="index.html">Back</a>
    </header>

    <!-- Login Form -->

    <h1>Login</h1>
    <section>
      <form class="" action="login.php" method="post">

        <input type="text" name="email" value="Email" required/>
        <input type="text" name="password" value="Password" required/>

        <input type="submit" value="Submit">

      </form>

    </section>
  </body>
</html>
EOT;

?>