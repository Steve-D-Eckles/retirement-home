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
$date = date("Y/m/d");
$till_date = date("Y/m/d");

if($_SERVER["REQUEST_METHOD"] == "POST"){
  $till_date = $_POST['till_date'];
}

if (auth([3], $link)) {
  echo <<<"EOT"
  <head>
    <link rel="stylesheet" href="../../assets/styles.css">
  </head>
  <body>
    <header>
      <a href="../../auth/php/logout.php">Logout</a>

      <h1>$first_name's Home</h1>

      <nav class="nav">
        <a href="home.php">Home</a>
      </nav>
    </header>

    <section class='doctor'>

    <h2> Till Date </h2>
    <form class="form-style register" action="home.php" method="post">

    <label for="till_date">$till_date</label><br>
    <input type="date" name="till_date" value="till_date">

    <input class="check-submit" type="submit" value="submit">
    </form>

    <h2>Past Appointments</h2>

    <table class='checklist'>
      <tr class="row">
        <th>Name</th>
        <th>Date</th>
        <th>Comment</th>
        <th>Morning Medicine</th>
        <th>Afternoon Medicine</th>
        <th>Night Medicine</th>
      </tr>

  EOT;

  //Grabs appointments info by user id and the date
    if ($stmt = $link->prepare('SELECT u.first_name, u.last_name, appt_date, comment, morn_med, afternoon_med, night_med
                                FROM appointments AS a
                                JOIN users AS u
                                ON a.patient_id = u.user_id
                                WHERE doctor_id = ?
                                AND appt_date < ?')) {
        $stmt->bind_param('is', $user_id, $date);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
          $stmt->bind_result($patient_fname, $patient_lname, $date, $comment, $morn_med, $afternoon_med, $night_med);


  // Makes a row for every patients checklist
  while ($stmt->fetch()) {
    echo <<<"EOT"
        <tr class="row">
          <td>$patient_fname $patient_lname</td>
          <td class='check'>$date</td>
          <td class='check'>$comment</td>
          <td class='check'>$morn_med</td>
          <td class='check'>$afternoon_med</td>
          <td class='check'>$night_med</td>
        </tr>

    EOT;
    }
  }
}
echo <<<"EOT"

      </table>

      <h2>Upcoming Appointments</h2>

      <form action="patient_of_doc.php" method="post">
      <table class='checklist'>
        <tr class="row">
          <th>Name</th>
          <th>Date</th>
          <th>Done</th>
        </tr>

    EOT;

    //Grabs appointments info by user id and the date
      if ($stmt = $link->prepare('SELECT u.user_id, u.first_name, u.last_name, appt_date, comment
                                  FROM appointments AS a
                                  JOIN users AS u
                                  ON a.patient_id = u.user_id
                                  WHERE doctor_id = ?
                                  AND appt_date > ?
                                  AND appt_date <= ?')) {
          $stmt->bind_param('iss', $user_id, $date, $till_date);
          $stmt->execute();
          $stmt->store_result();

          if ($stmt->num_rows > 0) {
            $stmt->bind_result($p_id, $patient_fname, $patient_lname, $date, $comment);


            // Makes a row for every patients checklist
            while ($stmt->fetch()) {
              echo <<<"EOT"

                  <tr class="row">
                    <td>
                      <button class='check-submit' name='p_id' type="submit" value="$p_id">
                      $patient_fname $patient_lname
                    </td>
                    <td class='check'>$date</td>

              EOT;


              if($comment){
                echo <<<"EOT"
                <td class='check'> &#x2713 </td>
                EOT;
              }else{
                echo <<<"EOT"
                <td class='check'>  </td>
                EOT;
              }

            }
          }
        }
  echo <<<"EOT"
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
