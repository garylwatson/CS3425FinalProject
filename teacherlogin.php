<?php 
session_start();
if(isset($_POST["Logout"])){
        header("LOCATION:index.php");
        return;
}

if(isset($_POST["teachaction"])){
	if($_POST["teachaction"] == "Create student") {
		header("LOCATION:createstudent.php");
		return;	
	}
	if($_POST["teachaction"] == "Create exams") {
                header("LOCATION:createexams.php");
                return;
        }
	if($_POST["teachaction"] == "Create questions") {
        	header("LOCATION:createquestions.php");
        	return;
	}
	if($_POST["teachaction"] == "Create choices for problems") {
        	header("LOCATION:createchoices.php");
        	return;
	}
	if($_POST["teachaction"] == "Show student's grades") {
        	header("LOCATION:showgrades.php");
        	return;
	}
}
	
?>
<!DOCTYPE html>
<html>
<body>
<h1> Teacher Utilities</h1>
<form form action="teacherlogin.php" method="post">
What would you like to do?:
	<select name="teachaction">
		<option>Create student</option>
		<option>Create exams</option>
		<option>Create questions</option>
		<option>Create choices for problems</option> <!--this should allow user to set correct answer too-->
		<option>Show student's grades</option>
	</select>
	<input type="submit" name="submit" value="Submit">
	<input type="submit" name="Logout" value="Logout">
</form>
</body>
</html>


