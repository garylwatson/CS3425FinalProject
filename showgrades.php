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
			
			$dbh->beginTransaction();

			echo "<table border='1'>";
			echo "<TR>";
			echo "<TH> Exam Name </TH>";
			echo "<TH> Student ID </TH>";
			echo "<TH> Score </TH>";
			echo "</TR>";

			$examName = $_POST['examselection'];
	
			$stmt = $dbh->prepare("SELECT examName, id, score FROM score WHERE examName = :examname");
			$stmt->execute(['examname'=>$examName]);
			$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
			foreach($results as $row) {
				echo "<TR>";
				echo "<TD>".$row['examName']."</TD>";
				echo "<TD>".$row['id']."</TD>";
				echo "<TD>".$row['score']."</TD>";
				echo "</TR>";
			}
			echo "</table>";

			$dbh->commit();

                } catch (PDOException $e) {
                        print "Error!" . $e->getMessage()."</br>";
                        $dbh->rollback();
			die();
                }
        }
}
?>

<!DOCTYPE html>
<h1> Check Scores</h1>
<form form action="showgrades.php" method="post">
Which exam's scores would you like to see? </br>
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

} catch(PDOException $e) {
	print "Error!" . $e->getMessage()."</br>";
 	$dbh->rollback();
 	die();
}
?>
        <input type="submit" name="action" value="Done">
        <input type="submit" name="action" value="Show Grades">
</form>
</body>
</html>
