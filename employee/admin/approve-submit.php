<?php
// TODO: Authenticate user before allowing access

$con = mysqli_connect('localhost', 'root', '', 'retirement');

if ( mysqli_connect_errno() ) {
  exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

$confirm = [];
$deny = [];
foreach ($_POST['confirm'] as $id) {
  $confirm[] = (int) $id;
}

foreach ($_POST['deny'] as $id) {
  $deny[] = (int) $id;
}

if (count($confirm) > 0) {
  $placeholder = array_fill(0, count($confirm), '?');

  if ($stmt = $con->prepare('UPDATE users SET confirmed = 1 WHERE user_id IN' . '(' . implode(',', $placeholder) . ')')) {
    $stmt->bind_param(str_repeat('i', count($confirm)), ...$confirm);
    $stmt->execute();
    $stmt->close();
  }
}

if (count($deny) > 0) {
  $placeholder = array_fill(0, count($deny), '?');

  if ($stmt = $con->prepare('SELECT role FROM users WHERE user_id IN' . '(' . implode(',', $placeholder) . ')')) {
    $stmt->bind_param(str_repeat('i', count($deny)), ...$deny);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($role);

    $deny_roles = [];
    foreach ($deny as $id) {
      $stmt->fetch();
      $deny_roles[] = [$id, $role];
    }
    $stmt->close();
  }

  foreach ($deny_roles as $pair) {
    if ($pair[1] === 5) {
      if ($stmt = $con->prepare('DELETE FROM patients WHERE patient_id = ?')) {
        $stmt->bind_param('i', $pair[0]);
        $stmt->execute();
        $stmt->close();
      }
    } elseif (in_array($pair[1], [1, 2, 3, 4])) {
      if ($stmt = $con->prepare('DELETE FROM employees WHERE employee_id = ?')) {
        $stmt->bind_param('i', $pair[0]);
        $stmt->execute();
        $stmt->close();
      }
    }
  }

  if ($stmt = $con->prepare('DELETE FROM users WHERE user_id IN' . '(' . implode(',', $placeholder) . ')')) {
    $stmt->bind_param(str_repeat('i', count($deny)), ...$deny);
    $stmt->execute();
    $stmt->close();
  }
}

header('Location: approve.php')
?>
