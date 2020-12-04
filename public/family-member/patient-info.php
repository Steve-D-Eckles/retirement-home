<?php
require_once '../../auth/php/config.php';
require_once '../../auth/php/auth.php';

if($_SERVER["REQUEST_METHOD"] == "POST"){
  $date = $_POST['date'];
  $family_code = $_POST['family_code'];
  $patient_id= $_POST['patient_id'];
}

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

?>
