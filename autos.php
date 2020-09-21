<?php
$name="";
if ( ! isset($_GET['name']) || strlen($_GET['name']) < 1  ) {
    die('Name parameter missing');
}
if ( isset($_POST['logout']) ) {
    header('Location: index3.php');
    return;
}
$pdo = new PDO('mysql:host=localhost;port=3306;dbname=misc', 'apoo', 'yo');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$failure=false;
if(isset($_POST['make']) && isset($_POST['year']) && isset($_POST['mileage']))
{
	if(strlen($_POST['make'])<1)
	{
		$failure="Make is required";
	}
	else 
	{
		if(strlen($_POST['year'])<1 || strlen($_POST['mileage'])<1 || is_numeric($_POST['year'])==false || is_numeric($_POST['mileage'])==false)
		{
           $failure="Mileage and year must be numeric";
		}
		else
		{
		    $failure="Record inserted";	
		}
	}
}
if(isset($_POST['Add']) && $failure=="Record inserted")
{
	
	$stmt = $pdo->prepare('INSERT INTO autos
  (make, year, mileage) VALUES ( :mk, :yr, :mi)');
$stmt->execute(array(
  ':mk' => $_POST['make'],
  ':yr' => $_POST['year'],
  ':mi' => $_POST['mileage'])
);

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
 if(isset($_REQUEST['name'])){
     echo htmlentities($_REQUEST['name']);
     echo "\n";

 }
 ?>
</h1>
 <?php
if ( $failure !== false ) {
    echo('<p style="color: green;">'.htmlentities($failure)."</p>\n");
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
  <input type="submit" value="logout" name="logout">
 </form>
 
 	<?php
 	$stmt=$pdo->query("SELECT make,year,mileage FROM autos");
 	echo ('<p class="container1">'."Automobiles"."</p>\n");
 	echo "<ul>";
 	while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
 	  echo "<li>";
 	  echo ($row['year']);
 	  echo " ";
 	  echo (htmlentities($row['make']));
 	  echo "/";
 	  echo ($row['mileage']);
 	  echo "\n";
 	 }
 	 echo "</ul>"
 	 ?>

 </div>
</body>
</html>


