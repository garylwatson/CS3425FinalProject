<?php
session_start();
?>
<!DOCTYPE html>
<html>
<body>
<?php
if(isset($_POST['action'])){
        if($_POST['action'] == 'Done'){
                header("LOCATION:studentlogin.php");
                return;

        } else if($_POST['action'] == 'Take Exam'){
		echo '<form method="post" action="checkscores.php">';
        	$examName = $_POST['examselection'];
		$_SESSION['exam'] = $examName; //store what exam we're taking

		try{
        		$config = parse_ini_file("db.ini");
        		$dbh = new PDO($config['dsn'], $config["username"], $config["password"]);

			$dbh->beginTransaction();
			
			//Retrieve question number, question, and points
        		$stmt = $dbh->prepare("SELECT questionNum, question, points FROM questions WHERE examName = :examname");
        		$stmt->execute(['examname'=>$examName]);
        		$data = $stmt->fetchAll();

			$anserKey = array();
			$_SESSION['numquestions'] = 1;
		
			//Output question number and question to screen
        		foreach($data as $row){
				$_SESSION['numquestions'] = $_SESSION['numquestions'] + 1; //keep track of number of questions
				$questionNum = $row['questionNum'];
                		echo $row['questionNum'].". ".$row['question']."</br>";
				
				//Retrieve choices for each question
				$stmt2 = $dbh->prepare("SELECT answer, correct, choiceNum FROM answers WHERE examName = :examname
				and questionNum = :questionnum");
				$stmt2->execute(['examname'=>$examName,'questionnum'=>$questionNum]);
				$data2 = $stmt2->fetchAll();
			
				//Output each choice for outer loop question number as radio button
				foreach($data2 as $row2){
					$choiceNum = $row2['choiceNum'];
					$answer = $row2['answer'];
					echo '<input type="radio" name = "'.$questionNum.'"'.'value = "'.$choiceNum.'"'.
					'/>'.$answer.'</input> </br>';
					
					//log the correct choice for each question
					if($row2['correct'] == 1){
						$answerKey[$questionNum] = $choiceNum;	
						}
				}
        		}

		$_SESSION['answerkey'] = $answerKey;

		$dbh->commit();
		
		} catch (PDOException $e) {
                        print "Error!" . $e->getMessage()."</br>";
                        $dbh->rollback();	 
			die();
                }
		echo '<input type="submit" name="submit" value="Check Score">';
		echo '</form>';	
	}	
}

?>
<h1> Take Exam</h1>
<form form action="takeexam.php" method="post">
What exam would you like to take? </br>
<?php
try{
	$config = parse_ini_file("db.ini");
        $dbh = new PDO($config['dsn'], $config["username"], $config["password"]);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	$dbh->beginTransaction();	
	
	echo '<select name="examselection">';
     	foreach($dbh->query("SELECT name FROM exam") as $row){
		echo "<option>".$row[0]."</option>";
	}

        echo "</select>";

	$dbh->commit();

} catch(PDOException $e){
	print "Error!" . $e->getMessage()."</br>";
	$dbh->rollback();
 	die();
}
?>
        <input type="submit" name="action" value="Done">
	<input type="submit" name="action" value="Take Exam">
</form>
</body>
</html>
