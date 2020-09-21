<?php
$pdo = new PDO('mysql:host=localhost;port=3306;dbname=misc', 'apoo', 'yo');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
session_start();
if (isset($_POST['cancel'])) {
	header('Location: indexjs.php');
	return;
}
if(isset($_POST['delete']) && isset($_POST['profile_id'])){
	$sql="DELETE FROM Profile WHERE profile_id= :apoo";
	$stmt= $pdo->prepare($sql);
	$stmt->execute(array(':apoo'=>$_POST['profile_id']));
	$_SESSION['success']='Profile Deleted';
	header('Location: indexjs.php');
	return;
}
if(!isset($_GET['profile_id'])){
	$_SESSION['error']="Missing profile_id";
	header('Location: indexjs.php');
	return;
}
$stmt= $pdo->prepare("SELECT first_name,last_name,profile_id FROM Profile WHERE profile_id= :xyz ");
$stmt->execute(array(":xyz"=>$_GET['profile_id']));
$row=$stmt->fetch(PDO::FETCH_ASSOC);
if($row==false){
	$_SESSION['error']='Bad value for profile_id';
	header('Location: indexjs.php');
	return;
}
?>
<h1>Deleting Profile</h1>
<p>First Name: <?= htmlentities($row['first_name'])?> </p>
<p>Last Name:<?= htmlentities($row['last_name'])?></p>
<form method="post">
	<input type="hidden" name="profile_id" value="<?= $row['profile_id'] ?>">
	<input type="submit" name="delete" value="Delete">
	<input type="submit" name="cancel" value="Cancel">
	
</form> 