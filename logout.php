<?php

session_start();

unset($_SESSION['file_tracking_username']);

header('location: login.php');

?>