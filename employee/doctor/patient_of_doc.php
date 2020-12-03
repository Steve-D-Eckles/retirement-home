<?php
require_once '../../auth/php/config.php';
require_once '../../auth/php/auth.php';
session_start();

if(!isset($_SESSION['user_id'])){
  header("Location:../../auth/login_temp.php");
}

$first_name = $_SESSION['first_name'];
$last_name = $_SESSION['last_name'];
$user_id = $_SESSION['user_id'];

// Capitalize names
$first_name = ucfirst($first_name);
$last_name = ucfirst($last_name);
$current_date = date("Y/m/d");

//Grabs appointments info by user id and the date
if ($stmt = $link->prepare('SELECT u.first_name, u.last_name, appt_date, comment, morn_med, afternoon_med, night_med
                            FROM appointments AS a
                            JOIN users AS u
                            ON a.patient_id = u.user_id
                            WHERE doctor_id = ?
                            AND appt_date < ?
                            ORDER BY appt_date DESC
                            LIMIT 1')) {
    $stmt->bind_param('is', $user_id, $current_date);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
      $stmt->bind_result($patient_fname, $patient_lname, $date, $comment, $morn_med, $afternoon_med, $night_med);
      $stmt->fetch();
    }

    $stmt->close();
  }

$patient_fname = ucfirst($patient_fname);
$patient_lname = ucfirst($patient_lname);

if (auth([3], $link)) {
    echo <<<"EOT"
        <head>
          <link rel="stylesheet" href="../../assets/styles.css">
        </head>
        <body>
          <header>
            <a href="../../auth/php/logout.php">Logout</a>

            <h1>$first_name's Patient</h1>

            <nav class="nav">
              <a href="home.php">Home</a>
            </nav>
          </header>

          <section class='doctor'>

          <h2>$patient_fname $patient_lname's Appointments</h2>

          <table class='checklist'>
            <tr class="row">
              <th>Date</th>
              <th>Comment</th>
              <th>Morning Medicine</th>
              <th>Afternoon Medicine</th>
              <th>Night Medicine</th>
            </tr>

          <tr class="row">
            <td class='check'>$date</td>
            <td class='check'>$comment</td>
            <td class='check'>$morn_med</td>
            <td class='check'>$afternoon_med</td>
            <td class='check'>$night_med</td>
          </tr>

        </table>

      </section>

    <footer>
      <p>Retirement Home</p>
    </footer>
  </body>
  EOT;
}
?>
