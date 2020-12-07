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

$first_name = ucfirst($first_name);
$last_name = ucfirst($last_name);
$date = date("Y/m/d");

if($_SERVER["REQUEST_METHOD"] == "POST"){
  $date = $_POST['date'];
}
if (auth([1, 4], $link)) {
  echo <<<"EOT"
  <!DOCTYPE html>
  <html lang="en" dir="ltr">
    <head>
      <meta charset="utf-8">
      <title>Caregiver</title>
      <link rel="stylesheet" href="../../assets/styles.css">
      <script type="text/javascript" src="checklist_disable.js" defer></script>

    </head>
    <body>

    <header>
      <a href="../../auth/php/logout.php">Logout</a>

      <h1>$first_name's Home</h1>

      <nav class="nav">
        <a href="home.php">Home</a>
      </nav>
    </header>

    <section class='patient'>

      <h2> Date </h2>

      <form class="form-style register" action="home.php" method="post">

      <label for="date">$date</label><br>
      <input type="date" name="date" value="date">

      <input class="check-submit" type="submit" value="submit">
      </form>

    <form action="checklist.php" method="post">
    <table class='checklist'>
      <tr class="row">
        <th>Name</th>
        <th>Morning Medicine</th>
        <th>Afternoon Medicine</th>
        <th>Night Medicine</th>
        <th>Breakfast</th>
        <th>Lunch</th>
        <th>Dinner</th>
        <th>Submit</th>
      </tr>

  EOT;

  //Grabs checklist info by user id and the date
    if ($stmt = $link->prepare('SELECT u.first_name, u.last_name, list_id, morn_med, afternoon_med, night_med, breakfast, lunch, dinner
                                FROM checklists AS c
                                JOIN users AS u
                                ON c.patient_id = u.user_id
                                WHERE caregiver_id = ?
                                AND list_date = ?')) {
        $stmt->bind_param('is', $user_id, $date);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
          $stmt->bind_result($patient_fname, $patient_lname, $list_id, $morn_med, $afternoon_med, $night_med, $breakfast, $lunch, $dinner);


  // Makes a row for every patients checklist
          while ($stmt->fetch()) {

            echo <<<"EOT"
              <tr class="row">
                <td><div>$patient_fname $patient_lname</div></td>
                <td class='check'><input class='checkbox' type='checkbox' name='morn_med' value=$morn_med></td>
                <td class='check'><input class='checkbox' type='checkbox' name='afternoon_med' value=$afternoon_med></td>
                <td class='check'><input class='checkbox' type='checkbox' name='night_med' value=$night_med></td>
                <td class='check'><input class='checkbox' type='checkbox' name='breakfast' value=$breakfast></td>
                <td class='check'><input class='checkbox' type='checkbox' name='lunch' value=$lunch></td>
                <td class='check'><input class='checkbox' type='checkbox' name='dinner' value=$dinner></td>
                <td><button class='check-submit' name='list_id' type="submit" value="$list_id">Enter</td>
              </tr>

            EOT;
          }
        }
      }

  echo <<<"EOT"
    </table>
    </form>
    </section>

    <footer>
      <p>Retirement Home</p>
    </footer>

    </body>
  </html>
  EOT;
}
?>
