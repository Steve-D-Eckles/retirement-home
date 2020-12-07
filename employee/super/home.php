<?php
require_once '../../auth/php/config.php';
require_once '../../auth/php/auth.php';
session_start();

if(!isset($_SESSION['user_id'])){
  header("Location:../../auth/login_temp.php");
}

if (auth([1, 2], $link)) {
  echo <<<"EOT"
  <head>
    <link rel="stylesheet" href="../../assets/styles.css">
    <title>Supervisor Home</title>
  </head>
  <body>
    <header>
      <a href="../../auth/php/logout.php">Logout</a>

      <h1>Supervisor Home</h1>

      <nav class="nav">
        <a href="home.php">Home</a>
      </nav>
    </header>
    <main class='home-links'>

      <nav>
        <a href="add-info.php">Add Patient Info</a>
        <a href="approve.php">Pending Registrations</a>
        <a href="../super/create-roster.php">Create a Roster</a>
        <a href="../../public/roster.php">View Rosters</a>
        <a href="create-appointment.php">Create Doctors' Appointments</a>
      </nav>
    </main>
    <footer>
      <p>Retirement Home</p>
    </footer>
  </body>
  EOT;
}
?>
