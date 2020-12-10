<?php
session_start();

echo "post stuid " . $_POST['stuid'];
echo "post password " . $_POST['stupass'];
$stuid = $_POST['stuid'];
$stupass = $_POST['stupass'];

//$_SESSION['username'] = $_POST['stuid'];
//$_SESSION['password'] = $_POST['stupass'];
$_SESSION['username'] = $stuid;
$_SESSION['password'] = $stupass;
echo "session stuid " . $_SESSION['username'];
echo "session password" . $_SESSION['password'];

if(isset($_POST["Logout"])){
        header("LOCATION:index.php");
        return;
}

if(isset($_POST["studentaction"])){
        if($_POST["studentaction"] == "Change password") {
                header("LOCATION:changepassword.php");
                return;
        }
        if($_POST["studentaction"] == "Take exam") {
                header("LOCATION:takeexam.php");
                return;
        }
}

?>
<!DOCTYPE html>
<html>
<body>
<h1> Student Utilities</h1>
<form form action="studentlogin.php" method="post">
What would you like to do?:
        <select name="studentaction">
                <option> Change password </option>
                <option> Take exam </option>
        </select>
        <input type="submit" name="submit" value="Submit">
        <input type="submit" name="Logout" value="Logout">
</form>
</body>
</html>
