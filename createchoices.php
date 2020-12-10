<?php
session_start();

if(isset($_POST['action'])){
        if($_POST['action'] == 'Done'){
                header("LOCATION:teacherlogin.php");
                return;

        } else if($_POST['action'] == 'Add Choice'){
                try {
                        $config = parse_ini_file("db.ini");
                        $dbh = new PDO($config['dsn'], $config["username"], $config["password"]);
                        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                        $stmt = $dbh->prepare("insert into answers(examName,questionNum,choiceNum,answer) 
			values(:examname,:qnum,:choicenum,:choice)");
                        $result = $stmt->execute(array(':examname'=>$_POST['examname'],':qnum'=>$_POST['qnum'],':choicenum'=>
                        $_POST['choicenum'],':choice'=>$_POST['choice']));

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
<title> Create choices</title>
</head>

<body>
<h1> Create choices </h1>
<form form action="createchoices.php" method="post">
Exam Name: <input type="text" name="examname"> </br>
Question Number: <input type="text" name="qnum"> </br>
Choice Number: <input type="text" name="choicenum"> </br>
Choice: <input type="text" name="choice"> </br>
<input type="submit" name="action" value="Add Choice">
<input type="submit" name="action" value="Done">
</form>
