<?php
require_once '../../auth/php/config.php';
require_once '../../auth/php/auth.php';

if($_SERVER["REQUEST_METHOD"] == "POST"){
  $date = $_POST['date'];
  $family_code = $_POST['family_code'];
  $patient_id= $_POST['patient_id'];


// Getting checklist Info
if ($stmt = $link->prepare('SELECT caregiver_id, morn_med, afternoon_med, night_med, breakfast, lunch, dinner
                            FROM checklists
                            WHERE patient_id = ?
                            AND list_date = ?')) {
    $stmt->bind_param('is', $patient_id, $date);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
      $stmt->bind_result($caregiver_id, $morn_med, $afternoon_med, $night_med, $breakfast, $lunch, $dinner);
      $stmt->fetch();

      //checking if task is completed and if so returning "done"
      $morn_med = done($morn_med);
      $afternoon_med = done($afternoon_med);
      $night_med = done($night_med);
      $breakfast = done($breakfast);
      $lunch = done($lunch);
      $dinner = done($dinner);
    }else{
      $morn_med = "";
      $afternoon_med = "";
      $night_med = "";
      $breakfast = "";
      $lunch = "";
      $dinner = "";
      $caregiver_name = "";
      $caregiver_id = NULL;
    }

    $stmt->close();
  }

  // Getting Caregiver name
  if($caregiver_id != NULL){
    $caregiver_name = get_name_by_id($caregiver_id, $link);
  }

  // Getting Doctor ID
  if ($stmt = $link->prepare('SELECT doctor_id, appt_date, comment
                              FROM appointments
                              WHERE patient_id = ?
                              AND appt_date = ?')) {
      $stmt->bind_param('is', $patient_id, $date);
      $stmt->execute();
      $stmt->store_result();

      if ($stmt->num_rows > 0) {
        $stmt->bind_result($doc_id, $appt_date, $comment);
        $stmt->fetch();
    }else{
      $doc_id = NULL;
      $appt_date = "";
      $doc_name = "";
    }
    $stmt->close();
  }

  // Getting Doctor Name
  if($doc_id != NULL){
    $doc_name = get_name_by_id($doc_id, $link);
  }
}

// Return string for Done or Uncompleted
function done($task){
  if($task === 0){
    return "";
  }else{
    return "&#x2713";
  }
}

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


echo <<< "EOT"
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Family Member</title>
    <link rel="stylesheet" href="../../assets/styles.css">
  </head>
  <body>
    <header>
      <a href="../../auth/login_temp.php">Back</a>

      <h1>Family's Home</h1>

      <nav class="nav">
        <a href="home.php">Home</a>
      </nav>

    </header>

    <section class="centered-form-wrap">

      <h2> Patient Search </h2>

      <form class="form-style register" action="home.php" method="post">
        <label for="date">Date</label>
        <input type="date" name="date" required/>

        <label for="family_code">Family Code</label>
        <input type="text" name="family_code" required/>


        <label for="patient_id">Patient ID</label>
        <input type="text" name="patient_id" required/>

        <input class="check-submit" id="submit" type="submit" value="Submit">
      </form>
EOT;

if($_SERVER["REQUEST_METHOD"] == "POST"){
  echo <<<"EOT"
      <table class='doctors'>
        <tr>
          <th>Doctor</th>
          <th>Doctor's Appointment</th>
          <th>Caregiver</th>
        </tr>
        <tr>
          <td>$doc_name</td>
          <td>$appt_date</td>
          <td>$caregiver_name</td>
        </tr>
      </table>

      <table class='checklist'>
        <tr class="row">
          <th>Morning Medicine</th>
          <th>Afternoon Medicine</th>
          <th>Night Medicine</th>
          <th>Breakfast</th>
          <th>Lunch</th>
          <th>Dinner</th>
        </tr>
        <tr class="row">
          <td class='check'><div>$morn_med</div></td>
          <td class='check'><div>$afternoon_med</div></td>
          <td class='check'><div>$night_med</div></td>
          <td class='check'><div>$breakfast</div></td>
          <td class='check'><div>$lunch</div></td>
          <td class='check'><div>$dinner</div></td>
        </tr>
      </table>
  EOT;
  }
echo <<<"EOT"
      </section>
      <footer>
        <p>Retirement Home</p>
      </footer>

    </body>
  </html>
EOT;
?>
