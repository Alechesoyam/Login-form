<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" || isset($_POST['login'])) {

  $success = "SUCCESSFUL";
  include "../Views/Login.php";
}else{
  $error = "ERROR";
  include "../Views/Login.php";
}


?>