<?php
session_start();

if(isset($_POST['action'])){
        if($_POST['action'] == 'Done'){
                header("LOCATION:teacherlogin.php");
                return;

        } else if($_POST['action'] == 'Add Question'){
                try {
                        $config = parse_ini_file("db.ini");
                        $dbh = new PDO($config['dsn'], $config["username"], $config["password"]);
                        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                        $stmt = $dbh->prepare("insert into questions(examName,questionNum,question,points) values(:examname,
			:qnum,:q,:points)");
                        $result = $stmt->execute(array(':examname'=>$_POST['examname'],':qnum'=>$_POST['qnum'],
			':q'=>$_POST['q'],':points'=>$_POST['points']));

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
<title> Create Question</title>
</head>
<body>
<h1> Create Question </h1>
<form form action="createquestions.php" method="post">
Exam Name: <input type="text" name="examname"> </br>
Question Number: <input type="text" name="qnum"> </br>
Question: <input type="text" name="q"> </br>
Points: <input type="text" name="points"> </br>
<input type="submit" name="action" value="Add Question">
<input type="submit" name="action" value="Done">
</form>
