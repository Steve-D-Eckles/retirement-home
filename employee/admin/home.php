<?php
require_once '../../auth/php/config.php';
require_once '../../auth/php/auth.php';
session_start();

if (auth([1, 2], $link)) {
  echo <<<"EOT"
  <head>
    <link rel="stylesheet" href="../../assets/styles.css">
  </head>
  <body>
    <header>
      <a href="../../index.html">Logout</a>

      <nav class="nav">
        <a href="home.php">Home</a>
      </nav>
    </header>
    <h1>Admin Home</h1>
    <a href="role.php">View / Add Roles</a>
    <a href="../super/add-info.php">Add Patient Info</a>
    <a href="../super/approve.php">Approve Pending Registrations</a>
    <footer>
      <p>Retirement Home</p>
    </footer>
  </body>
  EOT;
}
?>
