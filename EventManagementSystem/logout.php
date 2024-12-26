<?php
require_once 'classes/Session.php';

Session::start();
Session::destroy();

header("Location: login.php");
?> 

<?php 
session_start();
 ?>

<?php
unset($_SESSION['message']); // Clear the message so it doesn't reappear on refresh
  session_unset(); // Remove all session variables
  session_destroy(); // Destroy the session
  header('Location: login.php'); 
?>