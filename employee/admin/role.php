<?php
require_once '../../auth/php/config.php';
require '../../auth/php/auth.php';
session_start();

if (auth([1], $link)) {
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
    <table class="doctors">
      <tr>
        <th>Role</th>
        <th>Access Level</th>
      </tr>
  EOT;
  if ($stmt = $link->prepare('SELECT role_name, access_level FROM roles')) {
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($name, $level);

    while ($stmt->fetch()) {
      echo <<<"EOT"
      <tr>
        <td>$name</td>
        <td>$level</td>
      </tr>
      EOT;
    }

    $stmt->close();
  }
  echo <<<"EOT"
    </table>

    <form action="role-submit.php" method="post">
      <label>New Role
        <input type="text" name="name" maxlength="25" required>
      </label>
      <label>Access Level
        <input type="number" name="level" required>
      </label>
      <input type="submit" value="Submit">
    </form>
    <footer>
      <p>Retirement Home</p>
    </footer>
  </body>
  EOT;
} else {
  // Send the user away if they aren't allowed to be here
  header('Location: ../../index.html');
}
?>
