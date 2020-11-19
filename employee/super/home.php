<?php
require_once '../../auth/php/config.php';
require_once '../../auth/php/auth.php';
session_start();

if (auth([1, 2], $link)) {
  echo 'This is the Supervisor Home';
} else {
  header('Location: ../../index.html');
}
?>
