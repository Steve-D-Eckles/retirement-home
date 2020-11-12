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

  if ($stmt = $con->prepare('UPDATE users SET confirmed = CURDATE() WHERE user_id IN ' . '(' . implode(',', $placeholder) . ')')) {
    $stmt->bind_param(str_repeat('i', count($confirm)), ...$confirm);
    $stmt->execute();
    $stmt->close();
  }
}

if (count($deny) > 0) {
  $placeholder = array_fill(0, count($deny), '?');

  if ($stmt = $con->prepare('DELETE FROM users WHERE user_id IN ' . '(' . implode(',', $placeholder) . ')')) {
    $stmt->bind_param(str_repeat('i', count($deny)), ...$deny);
    $stmt->execute();
    $stmt->close();
  }
}

header('Location: approve.php')
?>
