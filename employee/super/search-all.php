<?php
require_once '../../auth/php/config.php';
require_once '../../auth/php/auth.php';

if(session_id() == '' || !isset($_SESSION)) {
    // session isn't started
    session_start();
}

if (auth([1, 2], $link)) {
  if ($stmt = $link->prepare("SELECT user_id, role_name, salary,
                                     CONCAT(CONCAT(UCASE(LEFT(first_name, 1)), SUBSTRING(first_name, 2)), ' ', CONCAT(UCASE(LEFT(last_name, 1)), SUBSTRING(last_name, 2))) AS name
                              FROM users u JOIN employees e ON u.user_id = e.employee_id
                              JOIN roles r ON r.role_id = u.role
                              WHERE confirmed = 1")) {
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
