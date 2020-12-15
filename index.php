<?php 
session_start();

if(isset($_POST['login'])){
	//can't login as two people at the same time
	if($_POST['teachuser'] != '' and $_POST['stuid'] != ''){
		echo 'You may only login as teacher or student, not both';
	//if we are trying to login as teacher
	} else if($_POST['teachuser'] != ''){
		//if username and password for teacher match predefined account
		if ($_POST['teachuser'] == 'user' and $_POST['teachpass'] == 'password'){
			echo "You're logged in!";
			sleep(1);
			header("LOCATION:teacherlogin.php");
		} else {
			echo "Wrong username or password, please try again!";
		}
	//we have to dynamically check student login
	} else if(isset($_POST['stuid'])){
		$_SESSION['username'] = $_POST['stuid'];
		$userDefinedPassword = $_POST['stupass'];

		$config = parse_ini_file("db.ini");
                $dbh = new PDO($config['dsn'], $config["username"], $config["password"]);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		//get password from database
		$stmt = $dbh->prepare("SELECT password FROM student WHERE id = :id");
		$stmt->execute(['id'=>$_SESSION['username']]);

		$databaseDefinedPassword = $stmt->fetchColumn();
		
		//if the user put in the right password
		if($userDefinedPassword == $databaseDefinedPassword){
			echo "You're logged in!";
			sleep(1);
			header("LOCATION:studentlogin.php");
		} else {
			echo "Student: Wrong username or password, please try again!";
		}	
	}
}		
		
 ?>
<!DOCTYPE html>
<html>

<head>
<title> Welcome!</title>
</head>

<body>
<h3> Teacher login </h3>
<form form action="index.php" method="post">
User Name: <input type="text" name="teachuser">
Password: <input type="password" name="teachpass">

<h3>Student login </h3>
Student ID: <input type="text" name="stuid">
Password: <input type="password" name="stupass">
</br>
<input type="submit" name="login" value="Login">
</form>
</body>
</html>

