<?php
session_start();

if(isset($_POST['action'])){
        if($_POST['action'] == 'Done'){
                session_destroy();
		header("LOCATION:teacherlogin.php");
                return;

        } else if($_POST['action'] == 'Add Choice'){
                try {
                        $config = parse_ini_file("db.ini");
                        $dbh = new PDO($config['dsn'], $config["username"], $config["password"]);
                        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
			$dbh->beginTransaction();
		
			//if correct button is selected we have to change that column from the default to true (1)
                        if(isset($_POST['correct'])){
                                $stmt = $dbh->prepare("INSERT INTO answers(examName,questionNum,answer,correct)
                                VALUES(:examname,:qnum,:choice, :correct)");
                                $result = $stmt->execute(array(':examname'=>$_POST['examname'],
                                ':qnum'=>$_POST['qnum'],':choice'=>$_POST['choice'],
                                ':correct'=>$_POST['correct']));

                        //choice was not selected as correct we can rely on the default value
                        } else {
                                $stmt = $dbh->prepare("INSERT INTO answers(examName,questionNum,answer)
                                VALUES(:examname,:qnum,:choice)");
                                $result = $stmt->execute(array(':examname'=>$_POST['examname'],':qnum'=>$_POST['qnum'],
				':choice'=>$_POST['choice']));
                        }

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
<title> Create choices</title>
</head>

<body>
<h1> Create choices </h1>
<form form action="createchoices.php" method="post">
Exam Name: <input type="text" name="examname"> </br>
Question Number: <input type="text" name="qnum"> </br>
<!--Choice Number: <input type="text" name="choicenum"> </br>-->
Choice: <input type="text" name="choice"> </br>
<input type="radio" name="correct" value="1" /> Correct </input> </br>
<input type="submit" name="action" value="Add Choice">
<input type="submit" name="action" value="Done">
</form>
