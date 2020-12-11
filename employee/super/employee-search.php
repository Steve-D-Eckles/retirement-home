<?php
require_once '../../auth/php/config.php';
require '../../auth/php/auth.php';
session_start();

if(!isset($_SESSION['user_id']) || !auth([1, 2], $link)) {
  header("Location:../../auth/login_temp.php");
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>Employee Search</title>
    <link rel="stylesheet" type="text/css" href="../../assets/styles.css" />
    <script src="search.js" type="text/javascript" defer></script>
  </head>
  <body>
    <header>
      <a href="../../auth/php/logout.php">Logout</a>

      <h1>Employees</h1>

      <nav class="nav">
        <a href="home.php">Home</a>
      </nav>

    </header>

    <main class='roster-table'>

      <div class='employee-form-wrap'>
        <form class="search-form">
          <p>Find an employee:</p>
          <label>Id
            <input type="number" name="id">
          </label>
          <label>Name
            <input type="text" name="name">
          </label>
          <label>Role
            <input type="text" name="role">
          </label>
          <label>Salary
            <input type="number" name="salary">
          </label>
          <button class="check-submit" type="button" id='search'>Search</button>
          <button class="check-submit" type="button" id='reset'>Reset</button>
        </form>

      <?php
        if (auth([1], $link, false)) {
          echo <<<"EOT"
          <form class="search-form">
            <p>Update a salary:</p>
            <label>Employee ID:
              <input type="number" name="update-id">
            </label>
            <label>New salary:
              <input type="number" name="update-salary">
            </label>
            <button class="check-submit" type='button' id='update'>Update</button>
          </form>
          EOT;
        }
      ?>
      </div>
      <div class='scrollable'>
      <table class='doctors'>
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Role</th>
            <th>Salary</th>
          </tr>
        </thead>
        <tbody id='employee-table'>
          <?php
          if ($stmt = $link->prepare("SELECT user_id, first_name, last_name, salary, role_name

                                      FROM users u JOIN employees e
                                      ON u.user_id = e.employee_id
                                      JOIN roles r ON u.role = r.role_id
                                      WHERE confirmed = 1")) {
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($id, $fname, $lname, $salary, $role);

            while ($stmt->fetch()) {
              $name = ucfirst($fname) . ' ' . ucfirst($lname);
              echo <<<"EOT"
              <tr>
                <td>$id</td>
                <td>$name</td>
                <td>$role</td>
                <td>$salary</td>
              </tr>
              EOT;
            }
            $stmt->close();
          }
          ?>
        </tbody>
      </table>
    </div>
    </main>

    <footer>
      <p>Retirement Home</p>
    </footer>
  </body>
</html>
