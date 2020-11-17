<?php
require_once '../auth/php/config.php';
session_start();
$first_name = $_SESSION['first_name'];
$last_name = $_SESSION['last_name'];
$user_id = $_SESSION['user_id'];


date_default_timezone_set('UTC');
// TODO: get correct time according to day
$date = "2020-11-16";

// Getting checklist Info
if ($stmt = $link->prepare('SELECT caretaker_id, morn_med, afternoon_med, night_med, breakfast, lunch, dinner
                            FROM checklists
                            WHERE patient_id = ?
                            AND list_date = ?')); {
    $stmt->bind_param('is', $user_id, $date);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
      $stmt->bind_result($caretaker_id, $morn_med, $afternoon_med, $night_med, $breakfast, $lunch, $dinner);
      $stmt->fetch();
  }
}

//checking if task is completed and if so returning "done"
$morn_med = done($morn_med);
$afternoon_med = done($afternoon_med);
$night_med = done($night_med);
$breakfast = done($breakfast);
$lunch = done($lunch);
$dinner = done($dinner);


// Getting Caregiver name
if ($stmt = $link->prepare('SELECT first_name, last_name
                            FROM users
                            WHERE user_id = ?')); {
    $stmt->bind_param('i', $caretaker_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
      $stmt->bind_result($caregiver_fname, $caregiver_lname);
      $stmt->fetch();
  }
}

// Getting Doctor ID
if ($stmt = $link->prepare('SELECT doctor_id
                            FROM appointments
                            WHERE patient_id = ?
                            AND appt_date = ?')); {
    $stmt->bind_param('is', $user_id, $date);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
      $stmt->bind_result($doc_id);
      $stmt->fetch();
  }
}

// Getting Doctor Name
if ($stmt = $link->prepare('SELECT first_name, last_name
                            FROM users
                            WHERE user_id = ?')); {
    $stmt->bind_param('i', $doc_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
      $stmt->bind_result($doc_fname, $doc_lname);
      $stmt->fetch();
  }
}

// Capitalize names
$first_name = ucfirst($first_name);
$last_name = ucfirst($last_name);
$caregiver_fname = ucfirst($caregiver_fname);
$caregiver_lname = ucfirst($caregiver_lname);
$doc_fname = ucfirst($doc_fname);
$doc_lname = ucfirst($doc_lname);

echo <<< "EOT"
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Home</title>
    <link rel="stylesheet" href="../assets/styles.css">
  </head>
  <body>

    <header>
      <a href="../auth/login_temp.php">Logout</a>

      <h1>$first_name's Home</h1>

      <nav class="nav">
        <a href="home.php">Home</a>
      </nav>
    </header>


    <section class='patient'>
      <article>
        <p>$first_name $last_name</p>
        <p>ID: $user_id</p>
        <p>$date</p>
      </article>


      <table class='checklist'>
        <tr class="row">
          <th>Doctor</th>
          <th>Doctor's Appointment</th>
          <th>Caregiver</th>
          <th>Morning Medicine</th>
          <th>Afternoon Medicine</th>
          <th>Night Medicine</th>
          <th>Breakfast</th>
          <th>Lunch</th>
          <th>Dinner</th>
        </tr>
        <tr class='row'>
          <td>$doc_fname $doc_lname</td>
          <td>Doc appointments</td>
          <td>$caregiver_fname $caregiver_lname</td>
          <td>$morn_med</td>
          <td>$afternoon_med</td>
          <td>$night_med</td>
          <td>$breakfast</td>
          <td>$lunch</td>
          <td>$dinner</td>
        </tr>
      </table>

    </section>
  </body>
</html>
EOT;

// Return string for Done or Uncompleted
function done($task){
  if($task == 0){
    return "Uncomplete";
  }else{
    return "Done";
  }
}


?>
