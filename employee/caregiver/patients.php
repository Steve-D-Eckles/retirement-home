<?php
require_once '../../auth/php/config.php';
require '../../auth/php/auth.php';
session_start();

if(!isset($_SESSION['user_id']) || !auth([1, 2, 3, 4], $link)) {
  header("Location:../../auth/login_temp.php");
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>Patient Search</title>
    <link rel="stylesheet" type="text/css" href="../../assets/styles.css" />
    <script src="search.js" type="text/javascript" defer></script>
  </head>
  <body>
    <header>
      <a href="../auth/php/logout.php">Logout</a>

      <nav class="nav">
        <a href="home.php">Home</a>
      </nav>

    </header>

    <main class='roster-table'>
      <h1>Patients</h1>

      <form class="search-form">
        <p>Find a patient:</p>
        <label>Id
          <input type="number" name="id">
        </label>
        <label>Name
          <input type="text" name="name">
        </label>
        <label>Age
          <input type="number" name="age">
        </label>
        <label>Emergency Contact Name
          <input type="text" name="contact-name">
        </label>
        <label>Emergency Contact Relation
          <input type="text" name="contact-rel">
        </label>
        <label>Admission Date
          <input type="date" name="date">
        </label>
        <button type="button" id='search'>Search</button>
        <button type="button" id='reset'>Reset</button>
      </form>

      <div class='scrollable'>
      <table class='doctors'>
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Age</th>
            <th>Emergency Contact Name</th>
            <th>Emergency Contact Relation</th>
            <th>Admission Date</th>
          </tr>
        </thead>
        <tbody id='patients-table'>
          <?php
          if ($stmt = $link->prepare("SELECT user_id, first_name, last_name,
                                             TIMESTAMPDIFF(YEAR, dob, CURDATE()) AS age,
                                             emergency_contact, ec_relation, admit_date
                                      FROM users u JOIN patients p
                                      ON u.user_id = p.patient_id
                                      WHERE confirmed = 1 AND admit_date IS NOT NULL")) {
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($id, $fname, $lname, $age, $ec, $ecr, $date);

            while ($stmt->fetch()) {
              $name = ucfirst($fname) . ' ' . ucfirst($lname);
              echo <<<"EOT"
              <tr>
                <td>$id</td>
                <td>$name</td>
                <td>$age</td>
                <td>$ec</td>
                <td>$ecr</td>
                <td>$date</td>
              </tr>
              EOT;
            }
            $stmt->close();
          }
          ?>
        </tbody>
      </table>
    </div>
    </main>

    <footer>
      <p>Retirement Home</p>
    </footer>
  </body>
</html>
