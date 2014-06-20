<?php
session_unset();
setcookie('Username', '', time() - 3600);
header('Location: login.php') ;
?>