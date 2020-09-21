<?php
session_start();
$name="";
if ( ! isset($_SESSION['email']) || strlen($_SESSION['email']) < 1  ) {
    die('Not Logged in');
}
else
{
	$name=$_SESSION['email'];
}
$pdo = new PDO('mysql:host=localhost;port=3306;dbname=misc', 'apoo', 'yo');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
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
<?php // line added to turn on color syntax highlight

if ( isset($_SESSION['success']) ) {
  echo('<p style="color: green;">'.htmlentities($_SESSION['success'])."</p>\n");
  unset($_SESSION['success']);
}
?>
 
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
 	 <p>
<a href="add.php">Add New</a>
|
<a href="logout.php">Logout</a>
</p>

 </div>
</body>
</html>


