<?php
session_start();

// Unset the session
$_SESSION = array();

// Destroy the session
session_destroy();

header("Location: ../pages/home.php");
exit();
