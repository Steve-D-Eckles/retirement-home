<?php
require_once '../../auth/php/config.php';
require_once '../../auth/php/auth.php';

if(session_id() == '' || !isset($_SESSION)) {
    // session isn't started
    session_start();
}

if (auth([1, 2, 3, 4], $link)) {
  if ($stmt = $link->prepare("SELECT user_id, first_name, last_name,
                                     CONCAT(CONCAT(UCASE(LEFT(first_name, 1)), SUBSTRING(first_name, 2)), ' ', CONCAT(UCASE(LEFT(last_name, 1)), SUBSTRING(last_name, 2))) AS name,
                                     TIMESTAMPDIFF(YEAR, dob, CURDATE()) AS age,
                                     emergency_contact, ec_relation, admit_date
                              FROM users u JOIN patients p ON u.user_id = p.patient_id
                              WHERE confirmed = 1 AND admit_date IS NOT NULL")) {
    $stmt->execute();
    $q_res = $stmt->get_result();
    $result = [];

    while ($set = $q_res->fetch_assoc()) {
      $result[] = $set;
    }
    echo json_encode($result);
    $stmt->close();
  }
}
?>
