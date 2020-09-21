<?php
$name="";
session_start();
if(isset($_POST['Cancel'])){
 	header('Location: view.php');
 	return;
 }
if ( ! isset($_SESSION['email']) || strlen($_SESSION['email']) < 1  ) {
    die('Not Logged in');
}
else
{
	$name=$_SESSION['email'];
}
$pdo = new PDO('mysql:host=localhost;port=3306;dbname=misc', 'apoo', 'yo');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if(isset($_POST['Add'])){
	if(isset($_POST['make']) && isset($_POST['year']) && isset($_POST['mileage']))
{
	if(strlen($_POST['make'])<1 )
	{
		
		$_SESSION['error']="Make is required";
		header('Location: add.php');
		return;
	}
	else 
	{
		if(strlen($_POST['year'])<1 || strlen($_POST['mileage'])<1 || is_numeric($_POST['year'])==false || is_numeric($_POST['mileage'])==false)
		{
           $_SESSION['error']="Mileage and year must be numeric";
           header('Location: add.php');
           return;
		}
		else{
$stmt = $pdo->prepare('INSERT INTO autos
  (make, year, mileage) VALUES ( :mk, :yr, :mi)');
$stmt->execute(array(
  ':mk' => $_POST['make'],
  ':yr' => $_POST['year'],
  ':mi' => $_POST['mileage'])
);
		    $_SESSION['success']="Record inserted";
		    header('Location: view.php');
		    return;	
		}
	}
}
}
 
?>
<!DOCTYPE html>
<html>
<head>
 <title>Apoorva Agarwal </title>
 <link href="starter-template1.css" rel="stylesheet">
</head>
<body>
 <div class="container">
 <h1>Tracking Autos for 
 	<?php
 if(isset($name)){
     echo htmlentities($name);
     echo "\n";
 }
 ?>
</h1>
 <?php
if ( isset($_SESSION["error"]) ) {
    // Look closely at the use of single and double quotes
    echo('<p style="color: red;">'.$_SESSION["error"]."</p>\n");
    unset($_SESSION["error"]);
}
?>

 <form method="POST">
 	<p>Make:
<input type="text" name="make" size="50"/></p>
<p>Year:
<input type="text" name="year"/></p>
<p>Mileage:
<input type="text" name="mileage"/></p>
  <input type="submit" value="Add" name="Add">
  <input type="submit" value="Cancel" name="Cancel">
 </form>
 </div>
</body>
</html>