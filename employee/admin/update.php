<?php
require_once '../../auth/php/config.php';
require_once '../../auth/php/auth.php';

if(session_id() == '' || !isset($_SESSION)) {
    // session isn't started
    session_start();
}

if (auth([1], $link)) {
  if ($stmt = $link->prepare("UPDATE patients
                              SET due = due + (SELECT 10 * TIMESTAMPDIFF(DAY, last_update, CURDATE()))
                              WHERE admit_date IS NOT NULL")) {
    $stmt->execute();
    $stmt->close();
  }
  if ($stmt = $link->prepare("SELECT patient_id FROM patients WHERE admit_date IS NOT NULL")) {
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($pid);

    $patients = [];

    while ($stmt->fetch()) {
      $patients[] = $pid;
    }
    $stmt->close();
  }


  if ($stmt = $link->prepare("UPDATE patients
                              SET due = due + (SELECT 50 * count(appt_id))
                                                FROM patients p JOIN appointments a
                                                ON p.patient_id = a.patient_id
                                                WHERE a.patient_id = ? AND comment IS NOT NULL
                                                AND appt_date BETWEEN last_update AND CURDATE()
                                                GROUP BY a.patient_id)
                              WHERE patient_id = ? AND admit_date IS NOT NULL") {
    foreach ($patients as $pid) {
      $stmt->bind_param('ii', $pid, $pid);
      $stmt->execute();
    }
    $stmt->close();
  }

  if ($stmt = $link->prepare("SELECT patient_id, due FROM patients
                              WHERE admit_date IS NOT NULL"))
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
