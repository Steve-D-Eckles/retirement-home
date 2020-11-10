<?php
// TODO: Authenticate user before allowing access

$con = mysqli_connect('localhost', 'root', '', 'retirement');

if ( mysqli_connect_errno() ) {
  exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}


echo <<<"EOT"
<form action='approve.php' method='post'>
  <table>
    <tr>
      <th>Name</th>
      <th>Role</th>
      <th>Confirm</th>
      <th>Deny</th>
    </tr>
EOT;
if ($stmt = $con->prepare('SELECT user_id, first_name, last_name, role_name
                           FROM users JOIN roles ON users.role = roles.role_id
                           WHERE confirmed = 0')) {
  $stmt->execute();
  $stmt->store_result();
  $stmt->bind_result($user_id, $first_name, $last_name, $role_name);

  while ($stmt->fetch()) {
    echo <<<"EOT"
    <tr>
      <td>$first_name $last_name</td>
      <td>$role_name</td>
      <td><input type='checkbox' name='confirm[]' value=$user_id></td>
      <td><input type='checkbox' name='deny[]' value=$user_id></td>
    </tr>
    EOT;
  }

}
echo <<<"EOT"
  </table>
  <input type='submit' value='Submit'>
</form>
EOT;
?>
