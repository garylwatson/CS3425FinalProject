<?php
session_start();

//var_dump($_POST);
             
$choices = array();
for($i = 1; $i < $_SESSION['numquestions']; $i = $i + 1){
	$choices[$i] = $_POST[$i];
}

//var_dump($choices);
//echo "</br>";
//var_dump($_SESSION['answerkey']);
//$answers = $_SESSION['answerkey'];
//$_SESSION['answerkey'] = ''; //reset session variable in case student wants to retake exam
$j = 1;
foreach($_SESSION['answerkey'] as $key=>$value){
	echo 'Question: '.$key.' Correct Answer: '.$value.' My Answer: '.$choices[$j].'</br>';
	$j = $j + 1;
}
                
?>
