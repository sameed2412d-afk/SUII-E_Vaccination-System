<?php
session_start();
session_destroy();
header("Location: parent/login.php");
exit();
?>