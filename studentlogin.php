<?php
session_start();

if(!isset($_SESSION['username'])){
	$_SESSION['username'] = $_POST['stuid'];
	$_SESSION['password'] = $_POST['stupass'];
}
if(isset($_POST["Logout"])){
        header("LOCATION:index.php");
	session_destroy();
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
<?php

try{
        $config = parse_ini_file("db.ini");
        $dbh = new PDO($config['dsn'], $config["username"], $config["password"]);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $dbh->beginTransaction();

        //retrieve name using student id
        $stmt = $dbh->prepare("SELECT name FROM student WHERE id = :id");
        $stmt->bindParam(':id', $_SESSION['username']);
        $stmt->execute();
        $name = $stmt->fetchColumn();

        $dbh->commit();

} catch (PDOException $e){
        print "Error!" . $e->getMessage()."</br>";
        $dbh->rollback();
        die();
}

echo "<h3> Hello, ".$name."!</h3>";
?>
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
