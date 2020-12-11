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
    <script type="text/javascript" src="payment.js" defer></script>
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

      <form class="form-style register">
        <label for="patient_id">Patient ID</label>
        <input type="text" id='pid' name='id' required/>

        <label for="total_due">Total Due</label>
        <input type="text" name="total_due" id='due' value="" readonly/>

        <label for="new_pay">New Payment</label>
        <input type="number" name="new_pay" id='amount' required/>

        <div class="payment">
          <button type="button" class="check-submit check-button" id="submit">Submit</button>
          <button type="button" class="check-submit check-button" id="reset">Reset</button>
        </div>
      </form>

      <button class="check-submit check-button" type="button" id="update">Update</button>

    </section>

    <footer>
      <p>Retirement Home</p>
    </footer>
  </body>
  EOT;
}
?>
