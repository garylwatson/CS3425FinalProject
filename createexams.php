<?php
session_start();

if(isset($_POST['action'])){
        if($_POST['action'] == 'Done'){
                header("LOCATION:teacherlogin.php");
                return;

        } else if($_POST['action'] == 'Add Exam'){
                try {
                        $config = parse_ini_file("db.ini");
                        $dbh = new PDO($config['dsn'], $config["username"], $config["password"]);
                        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                        $stmt = $dbh->prepare("insert into exam(name,points) values(:examname,:points)");
                        $result = $stmt->execute(array(':examname'=>$_POST['examname'],':points'=>$_POST['points']));

                } catch (PDOException $e) {
                        print "Error!" . $e->getMessage()."</br>";
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
<h1> Create exam </h1>
<form form action="createexams.php" method="post">
Exam Name: <input type="text" name="examname"> </br>
Total Points: <input type="text" name="points"> </br>
<input type="submit" name="action" value="Add Exam">
<input type="submit" name="action" value="Done">
</form>

