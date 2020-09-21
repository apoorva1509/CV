<?php
$name="";
session_start();
if(isset($_POST['Cancel'])){
 	header('Location: index6.php');
 	return;
 }
if ( ! isset($_SESSION['email']) || strlen($_SESSION['email']) < 1  ) {
    die('Acess Denied');
}
else
{
	$name=$_SESSION['email'];
}
$pdo = new PDO('mysql:host=localhost;port=3306;dbname=misc', 'apoo', 'yo');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if(isset($_POST['Add'])){
	if(isset($_POST['make']) && isset($_POST['year']) && isset($_POST['mileage']) && isset($_POST['model']))
{
	if(strlen($_POST['make'])<1 )
	{
		
		$_SESSION['error']="All fields are required";
		header('Location: add1.php');
		return;
	}
	else 
	{
		if (strlen($_POST['model'])<1) {
			# code...
			$_SESSION['error']="All fields are required";
		header('Location: add1.php');
		return;
		}
		if(strlen($_POST['year'])<1 || strlen($_POST['mileage'])<1 || is_numeric($_POST['year'])==false || is_numeric($_POST['mileage'])==false)
		{
           $_SESSION['error']="Mileage and year must be numeric";
           header('Location: add1.php');
           return;
		}
		else{
$stmt = $pdo->prepare('INSERT INTO autos1
  (make, year, mileage,model) VALUES ( :mk, :yr, :mi, :mo)');
$stmt->execute(array(
  ':mk' => $_POST['make'],
  ':yr' => $_POST['year'],
  ':mi' => $_POST['mileage'],
  ':mo' => $_POST['model'])
);
		    $_SESSION['success']="Record added";
		    header('Location: index6.php');
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
 <h1>Tracking Automobiles for 
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
<p>Model:
<input type="text" name="model" size="50"/></p>

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