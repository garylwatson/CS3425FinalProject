<?php
session_start();

if(isset($_POST['action'])){
        if($_POST['action'] == 'Done'){
                header("LOCATION:teacherlogin.php");
                return;

        } else if($_POST['action'] == 'Show Grades'){
                try {
			$config = parse_ini_file("db.ini");
			$dbh = new PDO($config['dsn'], $config['username'], $config['password']);
			$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			echo "<table border='1'>";
			echo "<TR>";
			echo "<TH> Exam Name </TH>";
			echo "<TH> Student ID </TH>";
			echo "<TH> Score </TH>";
			echo "</TR>";

			foreach( $dbh->query("SELECT examName, id, score FROM score") as $row ) {
				echo "<TR>";
				echo "<TD>".$row[0]."</TD>";
				echo "<TD>".$row[1]."</TD>";
				echo "<TD>".$row[2]."</TD>";
				echo "</TR>";
			}
			echo "</table>";
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
<title> Show grades</title>
</head>

<body>
<h1> Show grades </h1>
<form form action="showgrades.php" method="post">
<input type="submit" name="action" value="Show Grades">
<input type="submit" name="action" value="Done">
</form>
