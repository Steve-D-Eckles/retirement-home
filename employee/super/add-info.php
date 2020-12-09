<?php
require_once '../../auth/php/config.php';
require '../../auth/php/auth.php';
session_start();

if(!isset($_SESSION['user_id'])){
  header("Location:../../auth/login_temp.php");
}

if (auth([1, 2], $link)) {
  echo <<<"EOT"
  <head>
    <link rel="stylesheet" href="../../assets/styles.css">
    <title>Add Patient Info</title>
    <script type="text/javascript" src="info.js" defer></script>
  </head>
  <body>
    <header>
      <a href="../../auth/php/logout.php">Logout</a>

      <h1>Additional Patient Information</h1>

      <nav class="nav">
        <a href="home.php">Home</a>
      </nav>
    </header>
    <section class='centered-form-wrap'>

      <form class='form-style' action='add-info-submit.php' method='post'>

        <input type="submit" disabled style="display: none" aria-hidden="true">

        <label>Patient ID</label>
        <input type='text' id='pid' name='id' required>

        <label>Patient Name</label>
        <input type='text' value='' id='pname' disabled>

        <label>Group</label>
        <input type='number' name='group' min='1' max='4' required>

        <label>Admission Date</label>
  EOT;
  echo "<input type='date' name='date' value='" . date('Y-m-d') . "'>";
  echo <<<"EOT"

        <input class='check-submit' type='submit' id='submit' value='Submit' disabled>
      </form>
    </section>
    <footer>
      <p>Retirement Home</p>
    </footer>
  </body>
  EOT;
}
?>
