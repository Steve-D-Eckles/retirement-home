<?php
require_once '../../auth/php/config.php';
require '../../auth/php/auth.php';

session_start();
header('Location: create-roster.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST' && auth([1, 2], $link)) {
  $date = isset($_POST['date']) ? $_POST['date'] : NULL;
  $super = isset($_POST['super']) ? $_POST['super'] : NULL;
  $doctor = isset($_POST['doctor']) ? $_POST['doctor'] : NULL;
  $cgone = isset($_POST['cgone']) ? $_POST['cgone'] : NULL;
  $cgtwo = isset($_POST['cgtwo']) ? $_POST['cgtwo'] : NULL;
  $cgthree = isset($_POST['cgthree']) ? $_POST['cgthree'] : NULL;
  $cgfour = isset($_POST['cgfour']) ? $_POST['cgfour'] : NULL;

  if ($date !== NULL && $doctor !== NULL && $super !== NULL && $cgone !== NULL
      && $cgtwo !== NULL && $cgthree !== NULL && $cgfour !== NULL) {

    if ($stmt = $link->prepare('INSERT INTO roster (roster_date, supervisor_id,
                                                    doctor_id, care_one_id,
                                                    care_two_id, care_three_id,
                                                    care_four_id)
                                VALUES (?, ?, ?, ?, ?, ?, ?)')) {
      $stmt->bind_param('siiiiii', $date, $super, $doctor, $cgone, $cgtwo,
                        $cgthree, $cgfour);
      $stmt->execute();
      if ($stmt->affected_rows === 0) {
        $stmt->close();
        // TODO: session error logging
      } else {
        $stmt->close();
        if ($stmt = $link->prepare('SELECT patient_id, group_id
                                    FROM patients p JOIN users u ON p.patient_id = u.user_id
                                    WHERE confirmed = 1')) {
          $stmt->execute();
          $stmt->store_result();
          $stmt->bind_result($pid, $group);
          $patients = [];
          while ($stmt->fetch()) {
            $patients[] = [$pid, $group];
          }
          $stmt->close();
        }
        $query = "INSERT INTO checklists (patient_id, caregiver_id, list_date) VALUES ";
        $cgs = [$cgone, $cgtwo, $cgthree, $cgfour];
        $bind = [""];
        for ($i = 0; $i < 4; $i++) {
          for ($j = 0; $j < count($patients); $j++) {
            if ($patients[$j][1] == $i + 1) {
              $query .= "(?, ?, ?),";
              $bind[0] .= "iis";
              $bind[] = $patients[$j][0];
              $bind[] = $cgs[$i];
              $bind[] = $date;
            }
          }
        }
        $query = rtrim($query, ',');
        if ($stmt = $link->prepare($query)) {
          $stmt->bind_param(...$bind);
          $stmt->execute();
        }
      }
    }
  }
}
?>
