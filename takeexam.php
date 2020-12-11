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

        } else if($_POST['action'] == 'Submit Answers'){
		var_dump($_POST);
		/*
		$choices = array();
		for($i = 1; $i <= $_SESSION['numquestions']; $i = $i + 1){
			$choices[$i] = $_POST[$i];
		}
		var_dump($choices);
		echo "</br>";
    		//var_dump($_SESSION['answerkey']);            
		$answers = $_SESSION['answerkey'];
		$_SESSION['answerkey'] = ''; //reset session variable in case student wants to retake exam
		foreach($answers as $key=>$value){
			echo 'Question: '.$key.' Answer: '.$value.'</br>';
		}
		*/
        } else if($_POST['action'] == 'Take Exam'){
		echo '<form method="post" action="checkscores.php">';
        	$examName = $_POST['examselection'];
		try{
        		$config = parse_ini_file("db.ini");
        		$dbh = new PDO($config['dsn'], $config["username"], $config["password"]);

        		$stmt = $dbh->prepare("SELECT questionNum, question, points FROM questions WHERE examName = :examname");
        		$stmt->execute(['examname'=>$examName]);
        		$data = $stmt->fetchAll();
			//var_dump($data);

			$anserKey = array();
			$_SESSION['numquestions'] = 1;
        		foreach($data as $row){
				$_SESSION['numquestions'] = $_SESSION['numquestions'] + 1; //keep track of number of questions
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
					
					//log the correct choice for each question
					if($row2['correct'] == 1){
						$answerKey[$questionNum] = $choiceNum;	
						}
				}
        		}
			$_SESSION['answerkey'] = $answerKey;
	
		} catch (PDOException $e) {
                         print "Error!" . $e->getMessage()."</br>";
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
	$config = parse_ini_file("db.ini");
        $dbh = new PDO($config['dsn'], $config["username"], $config["password"]);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	echo '<select name="examselection">';
     	foreach($dbh->query("SELECT name FROM exam") as $row){
		echo "<option>".$row[0]."</option>";
	}
        echo "</select>";
?>
        <input type="submit" name="action" value="Done">
	<input type="submit" name="action" value="Take Exam">
</form>
</body>
</html>
