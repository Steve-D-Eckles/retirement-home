<?php
session_start();

if (!$_SESSION['loggedin']) {
  header("Location:../auth/login_temp.php");
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>Roster</title>
    <link rel="stylesheet" type="text/css" href="../assets/styles.css" />
    <script src="search.js" type="text/javascript" defer></script>
  </head>

  <body>

    <header>
      <a href="../auth/php/logout.php">Logout</a>

      <h1>Roster</h1>

      <nav class="nav">
        <a href="#">Home</a>
      </nav>

    </header>

    <main class="roster-table">

      <?php
      echo "<input type='date' id='roster-date' value='" . date('Y-m-d') . "'>";
      ?>
      <button class="check-submit roster-button" type="button" id="roster-date-search">Search</button>

      <table id="roster-table" class='doctors'>
        <tr>
          <th>Supervisor</th>
          <th>Doctor</th>
          <th>Group 1 Caregiver</th>
          <th>Group 2 Caregiver</th>
          <th>Group 3 Caregiver</th>
          <th>Group 4 Caregiver</th>
        </tr>
      </table>

    </main>

    <footer>
      <p>Retirement Home</p>
    </footer>

  </body>
</html>
