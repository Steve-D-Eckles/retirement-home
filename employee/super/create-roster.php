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
    <form class='form-style' action='create-roster-submit.php' method='post'>
      <label>Date:
  EOT;
  echo "<input type='date' name='date' value='" . date('Y-m-d') . "'></label>";

  if ($stmt = $link->prepare("SELECT user_id, first_name, last_name, role
                              FROM users WHERE confirmed = 1 AND role IN (2,3,4)")) {
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $fname, $lname, $role);

    $supers = [];
    $doctors = [];
    $caregivers = [];

    while ($stmt->fetch()) {
      switch ($role) {
        case 2:
          $supers[] = ["name" => ucfirst($fname) . ' ' . ucfirst($lname), "id" => $id];
          break;
        case 3:
          $doctors[] = ["name" => ucfirst($fname) . ' ' . ucfirst($lname), "id" => $id];
          break;
        case 4:
          $caregivers[] = ["name" => ucfirst($fname) . ' ' . ucfirst($lname), "id" => $id];
          break;
      }
    }
    $stmt->close();
  }

  function select ($label, $name, $arr) {
    echo <<<"EOT"
      <label>$label:
        <select name="$name">
        <option value="">Pick One</option>
    EOT;
    foreach ($arr as $elem) {
      echo "<option value=$elem[id]>$elem[name]</option>";
    }
    echo <<<"EOT"
        </select>
      </label>
    EOT;
  }
  select('Supervisor', 'super', $supers);
  select('Doctor', 'doctor', $doctors);
  select('Caregiver 1', 'cgone', $caregivers);
  select('Caregiver 2', 'cgtwo', $caregivers);
  select('Caregiver 3', 'cgthree', $caregivers);
  select('Caregiver 4', 'cgfour', $caregivers);

  echo <<<"EOT"
      <input type='submit' value='Submit'>
    </form>
    <footer>
      <p>Retirement Home</p>
    </footer>
  EOT;
} else {
  // Send the user away if they aren't allowed to be here
  header('Location: ../../index.html');
}
?>
