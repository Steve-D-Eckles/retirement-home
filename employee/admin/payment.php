<?php
require_once '../../auth/php/config.php';
require_once '../../auth/php/auth.php';
session_start();

if(!isset($_SESSION['user_id'])){
  header("Location:../../auth/login_temp.php");
}

if (auth([1], $link)) {
  echo <<<"EOT"
  <head>
    <link rel="stylesheet" href="../../assets/styles.css">
    <script type="text/javascript" src="#"></script>
  </head>
  <body>
    <header>
      <a href="../../auth/php/logout.php">Logout</a>

      <h1>Payment</h1>

      <nav class="nav">
        <a href="home.php">Home</a>
      </nav>

    </header>

    <section class="centered-form-wrap">

      <form class="form-style register" action="payment.php" method="post">
        <label for="patient_id">Patient ID</label>
        <input type="text" id='pid' name='id' required/>

        <label for="total_due">Total Due</label>
        <input type="text" name="total_due" value="100000" readonly/>

        <label for="new_pay">New Payment</label>
        <input type="new_pay" name="new_pay" required/>

        <div class="payment">
          <input class="check-submit" type="submit" value="Ok" onclick="total_after()">
          <input class="check-submit" type="reset">
        </div>
      </form>

      <button class="check-submit check-button" type="button" name="update">Update</button>

    </section>

    <footer>
      <p>Retirement Home</p>
    </footer>
  </body>
  EOT;
}
?>
