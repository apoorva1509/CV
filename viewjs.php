<?php
session_start();
$pdo = new PDO('mysql:host=localhost;port=3306;dbname=misc', 'apoo', 'yo');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
if(!isset($_GET['profile_id'])){
	$_SESSION['error']="Missing profile_id";
	header('Location: indexjs.php');
	return;
}
$stmt= $pdo->prepare("SELECT * FROM Profile WHERE profile_id= :xyz ");
$stmt->execute(array(":xyz"=>$_GET['profile_id']));
$row=$stmt->fetch(PDO::FETCH_ASSOC);
if($row==false){
	$_SESSION['error']='Bad value for profile_id';
	header('Location: indexjs.php');
	return;
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Apoorva Agarwal </title>
<?php require_once "bootstrap.php"; ?>
</head>
<body>
<div class="container">
<h1>Profile Information</h1>
<p>First Name: <?= htmlentities($row['first_name'])?> </p>
<p>Last Name: <?= htmlentities($row['last_name'])?> </p>
<p>Email: <?= htmlentities($row['email'])?> </p>
<p>Headline: </p>
<p><?= htmlentities($row['headline'])?> </p>
<p>Summary: </p>
<p><?= htmlentities($row['summary'])?> </p>
<p>Education: </p>
<?php
$profile_id=$row['profile_id'];
$stmt=$pdo->prepare('SELECT * FROM Education JOIN Institution ON Education.institution_id=Institution.institution_id WHERE profile_id=:prof ORDER BY rank');
$stmt->execute(array(':prof'=>$profile_id));
$educations=array();
echo "<p><ul>";
while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
	echo "<li>";
	echo ($row['year'].":".$row['name']);
}
echo "</ul></p>";
?>
<p>Position: </p>
<?php
$stmt=$pdo->prepare('SELECT * FROM Position WHERE profile_id=:prof ORDER BY rank');
$stmt->execute(array(':prof'=>$profile_id));
$positions=array();
echo "<p><ul>";
while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
	echo "<li>";
	echo ($row['year'].":".$row['description']);
}
echo "</ul></p>";
?>
<p>
<p><a href="indexjs.php">Done</a></p>
</div>
</body>
</html>