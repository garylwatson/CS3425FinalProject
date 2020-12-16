<?php
session_start();

if(isset($_POST['action'])){
	if($_POST['action'] == 'Done'){
        	header("LOCATION:teacherlogin.php");
        	return;

	} else if($_POST['action'] == 'Add Student'){
        	try {
			$config = parse_ini_file("db.ini");
			$dbh = new PDO($config['dsn'], $config["username"], $config["password"]);
			$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				

			$dbh->beginTransaction();

			$stmt = $dbh->prepare("INSERT INTO student(id,name,major) VALUES(:stuid,:stuname,:stumaj)");
			$result = $stmt->execute(array(':stuid'=>$_POST['stuid'],':stuname'=>$_POST['stuname'],':stumaj'=>
			$_POST['stumaj']));

			$dbh->commit();

		} catch (PDOException $e) {
			print "Error!" . $e->getMessage()."</br>";
			$dbh->rollback();
			die();
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
<h1> Create student </h1>
Students will be created with "default" as their password
<form form action="createstudent.php" method="post">
Student ID: <input type="text" name="stuid"> </br>
Name: <input type="text" name="stuname"> </br>
Major: <input type="text" name="stumaj"> </br>
<input type="submit" name="action" value="Add Student">
<input type="submit" name="action" value="Done">
</form>

