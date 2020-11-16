<?php
require_once '../auth/php/config.php';
session_start();
$first_name = $_SESSION['first_name'];
$last_name = $_SESSION['last_name'];
$user_id = $_SESSION['user_id'];

$first_name = ucfirst($first_name);
$last_name = ucfirst($last_name);

date_default_timezone_set('UTC');
$date = "2020-11-16";


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
$morn_med = done($morn_med);
$afternoon_med = done($afternoon_med);
$night_med = done($night_med);
$breakfast = done($breakfast);
$lunch = done($lunch);
$dinner = done($dinner);

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
      <a href="">Logout</a>

      <h1>$first_name's Home</h1>

      <nav class="nav">
        <a href="home.php">Home</a>
      </nav>
    </header>


    <section class='patient'>
      <article>
        <p>ID: $user_id</p>
        <p>$first_name $last_name</p>
        <p>$date</p>
      </article>


      <table class='checklist'>
        <tr>
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
        <tr>
          <td>Doctor</td>
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

function done($task){
  if($task == 0){
    return "Uncomplete";
  }else{
    return "Done";
  }
}
?>
