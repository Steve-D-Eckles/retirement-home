<?php
require_once '../../auth/php/config.php';
require_once '../../auth/php/auth.php';

session_start();
if (auth([1, 2], $link)) {
  $criteria = [];
  if (isset($_POST['id']) && $_POST['id']) $criteria['user_id'] = (string) $_POST['id'];
  if (isset($_POST['salary']) && $_POST['salary'] !== '') $criteria['salary'] = $_POST['salary'];
  if (isset($_POST['role']) && $_POST['role']) $criteria['role_name'] = $_POST['role'];
  if (isset($_POST['name']) && $_POST['name']) $criteria['name'] = $_POST['name'];

  if ($criteria) {
    $query = "SELECT user_id, salary, role_name,
                     CONCAT(CONCAT(UCASE(LEFT(first_name, 1)),
                     SUBSTRING(first_name, 2)), ' ', CONCAT(UCASE(LEFT(last_name, 1)),
                     SUBSTRING(last_name, 2))) AS name
                     FROM users u JOIN employees e ON u.user_id = e.employee_id
                     JOIN roles r ON r.role_id = u.role
                     WHERE confirmed = 1 ";
    $bind = [''];
    foreach ($criteria as $col=>$val) {
      if ($col === 'name') {
        $query .= "HAVING name LIKE CONCAT('%', ?, '%')";
        $bind[0] .= 's';
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
