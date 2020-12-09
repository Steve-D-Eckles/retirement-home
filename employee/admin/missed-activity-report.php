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

      <h1>Admin's Report</h1>

      <nav class="nav">
        <a href="home.php">Home</a>
      </nav>
    </header>
    <section class="centered-form-wrap">



    <form class="form-style register" action="missed-activity-report.php" method="post">
      <label for="date">Date</label>
      <input type="date" name="date" required/>
      <input class="check-submit" id="submit" type="submit" value="Submit">
    </form>


EOT;
  if($_SERVER["REQUEST_METHOD"] == "POST"){

      $date = $_POST['date'];

        echo <<<"EOT"

        <h2> Missed Checklist Info </h2>

            <table class='checklist'>
              <tr class="row">
                <th>Caregiver</th>
                <th>Morning Medicine</th>
                <th>Afternoon Medicine</th>
                <th>Night Medicine</th>
                <th>Breakfast</th>
                <th>Lunch</th>
                <th>Dinner</th>
              </tr>
        EOT;

        // Return string for Done or Uncompleted
        function checklist_info($task){
          if($task === 0){
            return "X";
          }else{
            return "&#x2713";
          }
        }

        // Getting checklist Info
        if ($stmt = $link->prepare('SELECT caregiver_id, morn_med, afternoon_med, night_med, breakfast, lunch, dinner
                                    FROM checklists
                                    WHERE list_date = ?
                                    AND (morn_med = 0
                                         OR afternoon_med = 0
                                         OR night_med = 0
                                         OR breakfast = 0
                                         OR lunch = 0
                                         OR dinner = 0)')) {
            $stmt->bind_param('s', $date);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
              $stmt->bind_result($caregiver_id, $morn_med, $afternoon_med, $night_med, $breakfast, $lunch, $dinner);
              while ($stmt->fetch()) {


                if ($stmt = $link->prepare('SELECT first_name, last_name
                                            FROM users
                                            WHERE user_id = ?')) {
                    $stmt->bind_param('i', $caregiver_id);
                    $stmt->execute();
                    $stmt->store_result();

                    if ($stmt->num_rows > 0) {
                      $stmt->bind_result($fname, $lname);
                      $stmt->fetch();

                      $caregiver_name = ucfirst($fname) . " " . ucfirst($lname);
                    }
                  }


                $morn_med = checklist_info($morn_med);
                $afternoon_med = checklist_info($afternoon_med);
                $night_med = checklist_info($night_med);
                $breakfast = checklist_info($breakfast);
                $lunch = checklist_info($lunch);
                $dinner = checklist_info($dinner);

                echo <<<"EOT"

                <tr class="row">
                  <td><div>$caregiver_name</div></td>
                  <td><div>$morn_med</div></td>
                  <td><div>$afternoon_med</div></td>
                  <td><div>$night_med</div></td>
                  <td><div>$breakfast</div></td>
                  <td><div>$lunch</div></td>
                  <td><div>$dinner</div></td>
                </tr>

                EOT;


              }

              }
            }

            $stmt->close();
        echo <<<"EOT"

            </table>
            <h2> Missed Appointment Info </h2>

            <table class='checklist'>
              <tr class="row">
                <th>Doctor</th>
                <th>Patient</th>
                <th>Comment</th>
                <th>Morning Medicine</th>
                <th>Afternoon Medicine</th>
                <th>Night Medicine</th>
              </tr>
        EOT;

        function get_name_by_id($id, $link){
          if ($stmt = $link->prepare('SELECT first_name, last_name
                                      FROM users
                                      WHERE user_id = ?')) {
              $stmt->bind_param('i', $id);
              $stmt->execute();
              $stmt->store_result();

              if ($stmt->num_rows > 0) {
                $stmt->bind_result($fname, $lname);
                $stmt->fetch();

                return ucfirst($fname) . " " . ucfirst($lname);
            }else{
              return "";
            }
            $stmt->close();
          }
        }

        // Return string for Done or Uncompleted
        function appointment_info($task){
          if($task == NULL){
            return "X";
          }else{
            return $task;
          }
        }

        // Getting Doctor ID
        if ($stmt = $link->prepare('SELECT doctor_id, patient_id, comment, morn_med, afternoon_med, night_med
                                    FROM appointments
                                    WHERE appt_date = ?
                                    AND (comment IS NULL
                                         OR morn_med IS NULL
                                         OR afternoon_med IS NULL
                                         OR night_med IS NULL)')) {
            $stmt->bind_param('s', $date);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
              $stmt->bind_result($doc_id, $patient_id, $comment, $morn_med, $afternoon_med, $night_med);


              while ($stmt->fetch()) {



                $patient_name = get_name_by_id($patient_id, $link);
                $doc_name = get_name_by_id($doc_id, $link);

                  $comment = appointment_info($comment);
                  $morn_med = appointment_info($morn_med);
                  $afternoon_med = appointment_info($afternoon_med);
                  $night_med = appointment_info($night_med);



                  echo <<<"EOT"
                    <tr class="row">
                      <td>$doc_name</td>
                      <td>$patient_name</td>
                      <td>$comment</td>
                      <td>$morn_med</td>
                      <td>$afternoon_med</td>
                      <td>$night_med</td>
                    </tr>
                  EOT;

                }
              }
              $stmt->close();
          }

        }

echo <<<'EOT'
    </table>
    </section>
    <footer>
      <p>Retirement Home</p>
    </footer>
  </body>
  EOT;
}


?>
