<?php 
session_start();
$_SESSION['test'] = 'retained';
//$_SESSION['username'] = '';
//$_SESSION['password'] = '';
 ?>
<!DOCTYPE html>
<html>

<head>
<title> Welcome!</title>
</head>

<body>
<h1> Teacher login </h1>
<form form action="teacherlogin.php" method="post">
User Name: <input type="text" name="teachuser">
Password: <input type="password" name="teachpass">
<input type="submit" name="login" value="Submit">
</form>


<h1>Student login </h1>
<form form action="studentlogin.php" method="post">
Student ID: <input type="text" name="stuid">
Password: <input type="password" name="stupass">
<input type="submit" name="login" value="Submit">
</form>
</body>
</html>

