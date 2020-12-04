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
$p_id = $_POST['p_id'];

// Capitalize names
$first_name = ucfirst($first_name);
$last_name = ucfirst($last_name);
$current_date = date("Y/m/d");

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
        EOT;
  //Grabs appointments info by user id and the date
  if ($stmt = $link->prepare('SELECT u.first_name, u.last_name, appt_date, comment, morn_med, afternoon_med, night_med
                              FROM appointments AS a
                              JOIN users AS u
                              ON a.patient_id = u.user_id
                              WHERE doctor_id = ?
                              AND patient_id = ?
                              AND comment IS NOT NULL
                              ORDER BY appt_date DESC
                              LIMIT 1')) {
      $stmt->bind_param('ii', $user_id, $p_id);
      $stmt->execute();
      $stmt->store_result();

      if ($stmt->num_rows > 0) {
        $stmt->bind_result($patient_fname, $patient_lname, $date, $comment, $morn_med, $afternoon_med, $night_med);
        $stmt->fetch();

        $patient_fname = ucfirst($patient_fname);
        $patient_lname = ucfirst($patient_lname);

        if($comment){
          echo <<<"EOT"
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
          EOT;
        }else{
          echo <<<"EOT"

            <h2> No appointments </h2>

          EOT;
        }

      }

      $stmt->close();
    }


        //Grabs appointments info by user id and the date
        if ($stmt = $link->prepare('SELECT appt_id, comment
                                    FROM appointments AS a
                                    JOIN users AS u
                                    ON a.patient_id = u.user_id
                                    WHERE doctor_id = ?
                                    AND patient_id = ?
                                    AND appt_date = ?')) {
            $stmt->bind_param('iis', $user_id, $p_id, $current_date);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
              $stmt->bind_result($appt_id, $new_comment);
              $stmt->fetch();

              if(!$new_comment){

                echo <<<"EOT"

                  <h2> New Prescription </h2>
                  <p> $current_date $new_comment </p>

                  <form action="add-appointment-info.php" method="post">
                    <table class='checklist'>
                      <tr class="row">
                        <th>Comment</th>
                        <th>Morning Medicine</th>
                        <th>Afternoon Medicine</th>
                        <th>Night Medicine</th>
                        <th>Submit</th>
                      </tr>

                      <tr class="row">
                        <td class='check'><input name='comment'></td>
                        <td class='check'><input name='morn_med'></td>
                        <td class='check'><input name='afternoon_med'></td>
                        <td class='check'><input name='night_med'></td>
                        <td><button class='check-submit' name='appt_id' type="submit" value="$appt_id">Enter</td>
                      </tr>
                    </table>
                  </form>
                  EOT;

                } else {
                  echo <<<"EOT"

                    <h2> Prescription added for today! </h2>

                  EOT;
                }


            } else {
              echo <<<"EOT"

                <h2> No appointments </h2>

              EOT;
            }

            $stmt->close();
          }

  echo <<<"EOT"

          </section>

    <footer>
      <p>Retirement Home</p>
    </footer>
  </body>
  EOT;
}
?>
