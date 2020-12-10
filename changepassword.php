<?php
session_start();

echo $_SESSION['username'];
echo $_SESSION['password'];
echo $_SESSION['test'];
var_dump($_SESSION);

?>
