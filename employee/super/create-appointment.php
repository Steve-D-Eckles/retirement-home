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
    <script type="text/javascript" src="info.js" defer></script>
  </head>
  <body>
    <header>
      <a href="../../auth/php/logout.php">Logout</a>

      <nav class="nav">
        <a href="home.php">Home</a>
      </nav>
    </header>
    <section class='centered-form-wrap'>
      <h1>Create Doctor's Appointment</h1>
      <form class='form-style' action='create-appointment-submit.php' method='post'>
        <input type="submit" disabled style="display: none" aria-hidden="true">
        <label>Patient ID
          <input type='text' id='pid' name='id' required>
        </label>
        <label>Patient Name
          <input type='text' value='' id='pname' disabled>
        </label>
        <label>Appointment Date
  EOT;
  echo "<input type='date' name='date' value='" . date('Y-m-d') . "'>";
  echo <<<"EOT"
        </label>
        <label>Doctor
          <select name="doctor">
            <option value="">Pick One</option>
  EOT;
  if ($stmt = $link->prepare("SELECT user_id, first_name, last_name
                              FROM users WHERE confirmed = 1 AND role = 3")) {
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $fname, $lname);

    while ($stmt->fetch()) {
      $name = ucfirst($fname) . ' ' . ucfirst($lname);
      echo "<option value='$id'>$name</option>";
    }
    $stmt->close();
  }
  echo <<<"EOT"
          </select>
        </label>
        <input type='submit' id='submit' value='Submit' disabled>
      </form>
    </section>
    <footer>
      <p>Retirement Home</p>
    </footer>
  </body>
  EOT;
}
?>
