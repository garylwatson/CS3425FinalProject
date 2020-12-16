<?php
session_start();

if(isset($_POST['action'])){
        if($_POST['action'] == 'Done'){
                header("LOCATION:studentlogin.php");
                return;

        } else if($_POST['action'] == 'Change Password'){
                try {
                        $config = parse_ini_file("db.ini");
                        $dbh = new PDO($config['dsn'], $config["username"], $config["password"]);
                        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
			$dbh->beginTransaction();

			//retrieve old password
                        $stmt = $dbh->prepare("SELECT password FROM student WHERE id = :id");
			$stmt->bindParam(':id', $_SESSION['username']);
			$stmt->execute();
			$oldpass = $stmt->fetchColumn();

			//compare inputted old password to database stored old password
			$userDefinedOldPass = $_POST['oldpass'];
			$userDefinedNewPass = $_POST['newpass'];
			
			//if passwords match
			if($oldpass == $userDefinedOldPass){
                        	$stmt2 = $dbh->prepare("UPDATE student SET password = :newpass WHERE id = :id");
                        	$result = $stmt2->execute(array(':newpass'=>$userDefinedNewPass,':id'=>$_SESSION['username']));
			} else {
				echo "Wrong old password, please try again!";
			}
			
			$dbh->commit();

                } catch (PDOException $e) {
                        print "Error!" . $e->getMessage()."</br>";
                        $dbh->commit();
			die();
                }
        }
}

?>
<!DOCTYPE html>
<html
<head>
<title> Change password </title>
</head>

<body>
<form form action="changepassword.php" method="post">
Old Password: <input type="text" name="oldpass"> </br>
New Password: <input type="text" name="newpass"> </br>
<input type="submit" name="action" value="Change Password">
<input type="submit" name="action" value="Done">
</form>
