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
  </head>
  <body>
    <header>
      <a href="../../auth/php/logout.php">Logout</a>

      <nav class="nav">
        <a href="home.php">Home</a>
      </nav>
    </header>
    <main class='home-links'>
      <h1>Admin Home</h1>
      <nav>
        <a href="role.php">View / Add Roles</a>
        <a href="../super/add-info.php">Add Patient Info</a>
        <a href="../super/approve.php">Pending Registrations</a>
        <a href="../super/create-roster.php">Create a Roster</a>
<<<<<<< HEAD
        <a href="../../public/roster.php">View Rosters</a>
=======
        <a href="../public/roster.php">View Rosters</a>
>>>>>>> 323e02fa719f4f9c9dc0ccefa477e2126321abb4
      </nav>
    </main>
    <footer>
      <p>Retirement Home</p>
    </footer>
  </body>
  EOT;
}
?>
