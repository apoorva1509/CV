<?php
session_start();
$pdo = new PDO('mysql:host=localhost;port=3306;dbname=misc', 'apoo', 'yo');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>
<!DOCTYPE html>
<html>
<head>
<title>Apoorva Agarwal </title>
<?php require_once "bootstrap.php"; ?>
</head>
<body>
<div class="container">
<h1>Apoorva Agarwal's Resume Registry</h1>
<?php
if( isset($_SESSION["error"]) ) {
    // Look closely at the use of single and double quotes
    echo('<p style="color: red;">'.$_SESSION["error"]."</p>\n");
    unset($_SESSION["error"]);
}
if ( isset($_SESSION['success']) ) {
  echo('<p style="color: green;">'.htmlentities($_SESSION['success'])."</p>\n");
  unset($_SESSION['success']);
}
if (! isset($_SESSION['name'])) { 
	echo('<p>
<a href="loginjs.php">Please log in</a>
</p>');
	echo('<table border="1">'."\n");
 	$stmt=$pdo->query("SELECT profile_id, first_name, last_name,email,headline,summary FROM Profile");
 	while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
 	  echo "<tr><th>";
 	  echo "Name";
 	  echo "<th><th>";
 	  echo "Headline";
 	  echo "<th><tr>";
 	  echo ("<tr><td>");
 	  echo ('<a href="viewjs.php?profile_id='.$row['profile_id'].'">');
 	  echo (htmlentities($row['first_name'])." ".htmlentities($row['last_name']));
 	  echo "</a>";
 	  echo ("<td><td>");
 	  echo (htmlentities($row['headline']));
 	  echo ("</td></tr>\n");
 	}
 	echo ('</table>');

}
else
{ 
	
	echo "<p>";
echo('<a href="logout1.php">Logout</a>');
echo('</p>');
 	echo('<table border="1">'."\n");
 	$stmt=$pdo->query("SELECT profile_id, first_name, last_name,email,headline,summary FROM Profile");
 	while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
 	  echo "<tr><th>";
 	  echo "Name";
 	  echo "<th><th>";
 	  echo "Headline";
 	  echo "<th><th>";
 	  echo "Action";
 	  echo "<th><tr>";
 	  echo ("<tr><td>");
 	  echo ('<a href="viewjs.php?profile_id='.$row['profile_id'].'">');
 	  echo (htmlentities($row['first_name'])." ".htmlentities($row['last_name']));
 	  echo "</a>";
 	  echo ("<td><td>");
 	  echo (htmlentities($row['headline']));
 	  echo ("<td><td>");
 	  echo ('<a href="edit.php?profile_id='.$row['profile_id'].'">Edit</a> ');
 	  echo ('<a href="delete.php?profile_id='.$row['profile_id'].'">Delete</a>');
 	  echo ("</td></tr>\n");
 	}
 	echo ('</table>');

 	
 	 echo('<p>');
echo('<a href="addjs.php">Add New Entry</a>'."\n");
echo "</p>";

}?>
</div>
</body>
</html> 