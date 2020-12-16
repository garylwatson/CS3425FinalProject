<?php
session_start();
 
//$_SESSION['numquestions'] keeps track of number of questions
//in taken exam. Data is written to it in takeexam.php            

//Selected answers are posted from takexam for each question
//and read into $choices
$choices = array();
for($i = 1; $i < $_SESSION['numquestions']; $i = $i + 1){
	$choices[$i] = $_POST[$i];
}

try{
	$config = parse_ini_file("db.ini");
        $dbh = new PDO($config['dsn'], $config["username"], $config["password"]);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$dbh->beginTransaction();
	
	//$_SESSION['answerkey'] maps question to correct answer
	//data is written to the array in takeexam.php
	$j = 1;
	$totalScore = 0;
	$examName = $_SESSION['exam'];

	echo 'Exam: '.$examName.'</br>';
	
	//Output question number, correct answer, and earned points for each question
	foreach($_SESSION['answerkey'] as $key=>$value){
		$chosenAnswer = $choices[$j];
		$correctAnswer = $value;
		$questionNumber = $key;

		echo 'Question: '.$key.' Correct Answer: '.$value.' My Answer: '.$choices[$j];
		
		//if we selected the right answer then we want to find out how many points we got
		//and add that to the total score
		if($chosenAnswer == $correctAnswer){
			$stmt = $dbh->prepare("SELECT points FROM questions WHERE examName = :examname 
			and questionNum = :questionnum");
                        $stmt->execute(['examname'=>$examName, 'questionnum'=>$questionNumber]);
                        $data = $stmt->fetchColumn();
			echo " Points: ".$data."</br>";
			$totalScore = $totalScore + $data;
		} else {
			echo " Points: 0 </br>";
		}
				
		$j = $j + 1;
	}

	//Find total score available for exam
	$stmt2 = $dbh->prepare("SELECT points FROM exam WHERE name = :examname");
	$stmt2->execute(['examname'=>$examName]);
	$points = $stmt2->fetchColumn();
	
	//Output total score
	echo 'You scored '.$totalScore.' out of '.$points.' points.</br>';
	
	//Update database with score for student having taken exam
	$stmt3 = $dbh->prepare("INSERT INTO score(examName,id,score,totalPoints) values(:examname, :id, :score, :totalPoints)");
       	$stmt3->execute(['examname'=>$examName,'id'=>$_SESSION['username'],'score'=>$totalScore,'totalPoints'=>$points]);
	
	//output button to be done checking scores
	echo '<form form action="studentlogin.php" method="post">';
	echo '<input type="submit" name="action" value="Done">';
	echo '</form>';
	
	$dbh->commit();

} catch (PDOException $e) {
	print "Error!" . $e->getMessage()."</br>";
	$dbh->rollback();	
	die();	          
}    
?>
