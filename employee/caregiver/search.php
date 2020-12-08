<?php
require_once '../../auth/php/config.php';
require_once '../../auth/php/auth.php';

session_start();
if (auth([1, 2, 3, 4], $link)) {
  $criteria = [];
  if (isset($_POST['id']) && $_POST['id']) $criteria['user_id'] = (string) $_POST['id'];
  if (isset($_POST['age']) && $_POST['age'] !== '') $criteria['age'] = $_POST['age'];
  if (isset($_POST['contact_name']) && $_POST['contact_name']) $criteria['emergency_contact'] = $_POST['contact_name'];
  if (isset($_POST['contact_rel']) && $_POST['contact_rel']) $criteria['ec_relation'] = $_POST['contact_rel'];
  if (isset($_POST['date']) && $_POST['date']) $criteria['admit_date'] = $_POST['date'];
  if (isset($_POST['name']) && $_POST['name']) $criteria['name'] = $_POST['name'];

  if ($criteria) {
    $query = "SELECT user_id, first_name, last_name, emergency_contact, ec_relation,
                     admit_date, CONCAT(first_name, ' ', last_name) AS name,
                     TIMESTAMPDIFF(YEAR, dob, CURDATE()) AS age
                     FROM users u JOIN patients p ON u.user_id = p.patient_id
                     WHERE confirmed = 1 AND admit_date IS NOT NULL ";
    $bind = [''];
    foreach ($criteria as $col=>$val) {
      if ($col === 'name') {
        $query .= "HAVING name LIKE CONCAT('%', ?, '%')";
        $bind[0] .= 's';
        $bind[] = $val;
      } elseif ($col === 'age') {
        $query .= "AND TIMESTAMPDIFF(YEAR, dob, CURDATE()) = ? ";
        $bind[0] .= 'i';
        $bind[] = $val;
      } else {
        $query .= "AND $col LIKE CONCAT('%', ?, '%') ";
        $bind[0] .= 's';
        $bind[] = $val;
      }
    }
    rtrim($query, ' ');
    if ($stmt = $link->prepare($query)) {
      $stmt->bind_param(...$bind);
      $stmt->execute();
      $q_res = $stmt->get_result();

      $result = [];
      while ($set = $q_res->fetch_assoc()) {
        $result[] = $set;
      }
      echo json_encode($result);
      $stmt->close();
    }
  } else {
    require_once 'search-all.php';
  }
}
?>
