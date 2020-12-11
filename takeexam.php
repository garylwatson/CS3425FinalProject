<?php
session_start();
if(isset($_POST['action'])){
        if($_POST['action'] == 'Done'){
                header("LOCATION:studentlogin.php");
                return;

        } else if($_POST['action'] == 'Submit Answers'){
                try {
                        $config = parse_ini_file("db.ini");
                        $dbh = new PDO($config['dsn'], $config["username"], $config["password"]);
                        //$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        //$stmt = $dbh->prepare("select password from student where id = :id");
                        //$stmt->bindParam(':id', $_SESSION['username']);
                        //$stmt->execute();

                } catch (PDOException $e) {
                        print "Error!" . $e->getMessage()."</br>";
                        die();
                }

        } else if($_POST['action'] == 'Take Exam'){
        	$examName = $_POST['examselection'];
		try{
        		$config = parse_ini_file("db.ini");
        		$dbh = new PDO($config['dsn'], $config["username"], $config["password"]);

        		$stmt = $dbh->prepare("SELECT questionNum, question, points FROM questions WHERE examName = :examname");
        		$stmt->execute(['examname'=>$examName]);
        		$data = $stmt->fetchAll();
			//var_dump($data);
			
        		foreach($data as $row){
				$questionNum = $row['questionNum'];
                		echo $row['questionNum'].". ".$row['question']."</br>";
				
				$stmt2 = $dbh->prepare("SELECT answer, correct, choiceNum FROM answers WHERE examName = :examname
				and questionNum = :questionnum");
				$stmt2->execute(['examname'=>$examName,'questionnum'=>$questionNum]);
				$data2 = $stmt2->fetchAll();
					
				foreach($data2 as $row2){
					$choiceNum = $row2['choiceNum'];
					$answer = $row2['answer'];
					echo '<input type="radio" name = "'.$questionNum.'"'.'value = "'.$choiceNum.'"'.
					'/>'.$answer.'</input> </br>';
				}
        		}
			/*	
			foreach($dbh->query("SELECT questionNum, question, points from questions 
			where examName = 'Exam1'") as $row){
                 		echo $row[0].". ".$row[1]."</br>";
         		}*/	
		} catch (PDOException $e) {
                         print "Error!" . $e->getMessage()."</br>";
                         die();
                }	
	}	
}

?>

<!DOCTYPE html>
<html>
<body>
<h1> Take Exam</h1>
<form form action="takeexam.php" method="post">
What exam would you like to take? </br>
<?php
	$config = parse_ini_file("db.ini");
        $dbh = new PDO($config['dsn'], $config["username"], $config["password"]);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	echo '<select name="examselection">';
     	foreach($dbh->query("SELECT name FROM exam") as $row){
		echo "<option>".$row[0]."</option>";
	}
        echo "</select>";
?>
        <input type="submit" name="action" value="Submit Answers">
        <input type="submit" name="action" value="Done">
	<input type="submit" name="action" value="Take Exam">
</form>
</body>
</html>
