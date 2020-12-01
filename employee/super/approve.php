<?php
require_once '../../auth/php/config.php';
require '../../auth/php/auth.php';

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
    <form action='approve-submit.php' method='post'>
      <table class='doctors'>
        <tr>
          <th>Name</th>
          <th>Role</th>
          <th>Confirm</th>
          <th>Deny</th>
        </tr>
  EOT;
  if ($stmt = $link->prepare('SELECT user_id, first_name, last_name, role_name
                             FROM users JOIN roles ON users.role = roles.role_id
                             WHERE confirmed = 0')) {
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($user_id, $first_name, $last_name, $role_name);

    while ($stmt->fetch()) {
      echo <<<"EOT"
      <tr>
        <td>$first_name $last_name</td>
        <td>$role_name</td>
        <td><input type='checkbox' name='confirm[]' value=$user_id></td>
        <td><input type='checkbox' name='deny[]' value=$user_id></td>
      </tr>
      EOT;
    }
    $stmt->close();
  }
  echo <<<"EOT"
      </table>
      <input type='submit' value='Submit'>
    </form>
  </body>
  EOT;
}
?>
