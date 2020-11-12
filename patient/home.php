<?php
echo <<< "EOT"
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Home</title>
  </head>
  <body>

  </body>
</html>
EOT;
session_start();
echo "Hello ".$_SESSION['first_name'];

?>
