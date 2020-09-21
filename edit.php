<?php
$pdo = new PDO('mysql:host=localhost;port=3306;dbname=misc', 'apoo', 'yo');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
session_start();
$name="";
if (isset($_POST['Cancel'])) {
	header('Location: indexjs.php');
	return;
}
if (!isset($_SESSION['name'])) {
	$_SESSION['error']="Acess Denied";
	header('Location:indexjs.php');
}
else
{
   $name=$_SESSION['name'];
}
function validatePos(){
		for($i=1;$i<=9;$i++){
			if(!isset($_POST['year'.$i]))continue;
			if(!isset($_POST['desc'.$i]))continue;
			$year=$_POST['year'.$i];
			$desc=$_POST['desc'.$i];
			if(strlen($year)==0 || strlen($desc)==0){
				return "All fields are required";
			}
			if(!is_numeric($year)){
				return "Position year must be numeric";
			}
		}
		return true;
	}
	function validateEdu(){
		for($i=1;$i<=9;$i++){
			if(!isset($_POST['edu_year'.$i]))continue;
			if(!isset($_POST['edu_school'.$i]))continue;
			$year=$_POST['edu_year'.$i];
			$school=$_POST['edu_school'.$i];
			if(strlen($year)==0 || strlen($school)==0){
				return "All fields are required";
			}
			if(!is_numeric($year)){
				return "Education year must be numeric";
			}
		}
		return true;
	}

	if(isset($_POST['Save']))
{
	$position_validate = validatePos();


    if ($position_validate !== true) {
        $_SESSION["error"] = $position_validate;
        header("Location: edit.php?profile_id=" . $_GET["profile_id"]);
        die();
    }
    $position_validate = validateEdu();


    if ($position_validate !== true) {
        $_SESSION["error"] = $position_validate;
        header("Location: edit.php?profile_id=" . $_GET["profile_id"]);
        die();
    }

    if (strlen($_POST["first_name"]) < 1
        || strlen($_POST["last_name"]) < 1
        || strlen($_POST["email"]) < 1
        || strlen($_POST["headline"]) < 1
        || strlen($_POST["summary"]) < 1
    ) {
        $_SESSION["error"] = "All fields are required";
        header("Location: edit.php?profile_id=" . $_GET["profile_id"]);
        die();
    }

    if (strpos($_POST["email"], "@") === false) {
        $_SESSION["error"] = "Email address must contain @";
        header("Location: edit.php?profile_id=" . $_GET["profile_id"]);
        die();
    }
	$sql="UPDATE Profile SET  first_name=:fn, last_name=:ln,email=:em,headline=:he,summary=:su WHERE profile_id=:pid AND user_id=:uid";
	$stmt=$pdo->prepare($sql);
	$stmt->execute(array(
  ':fn' => $_POST['first_name'],
  ':ln' => $_POST['last_name'],
  ':em' => $_POST['email'],
  ':he' => $_POST['headline'],
  ':su' => $_POST['summary'],
   ':pid'=>$_GET['profile_id'],
':uid'=>$_SESSION['user_id']));
	
$stmt= $pdo->prepare('DELETE FROM Position WHERE profile_id=:pid');
$stmt->execute(array(':pid'=>$_GET['profile_id']));
$rank=1;
for($i=1;$i<=9;$i++)
{
	$profile_id = $_GET['profile_id'];
	if(! isset($_POST['year'.$i])) continue;
	if(! isset($_POST['desc'.$i]))continue;
	$year= $_POST['year'.$i]; 
	$desc=$_POST['desc'.$i];
	$stmt =$pdo->prepare('INSERT INTO Position (profile_id,rank,year,description) VALUES(:pid,:rank,:year,:desc)');
	$stmt->execute(array(
		':pid'=>$profile_id,
		':rank'=>$rank,
		':year'=>$year,
		':desc'=>$desc)) ;
	$rank++;
}
$stmt= $pdo->prepare('DELETE FROM Education WHERE profile_id=:pid');
$stmt->execute(array(':pid'=>$_GET['profile_id']));
$rank=1;
$profile_id = $_GET['profile_id'];
for($i=1;$i<=9;$i++)
{
	if(! isset($_POST['edu_year'.$i])) continue;
	if(! isset($_POST['edu_school'.$i]))continue;
	$year= $_POST['edu_year'.$i]; 
	$school=$_POST['edu_school'.$i];
	$institution_id=false;
	$stmt =$pdo->prepare('SELECT institution_id FROM Institution WHERE name=:name');
	$stmt->execute(array(':name'=>$school)) ;
	$row=$stmt->fetch(PDO::FETCH_ASSOC);
	if($row!==false)$institution_id=$row['institution_id'];
	if($institution_id===false){
		$stmt=$pdo->prepare('INSERT INTO Institution (name) VALUES (:name)');
		$stmt->execute(array(':name'=>$school));
		$institution_id=$pdo->lastInsertId();
	}
	$stmt =$pdo->prepare('INSERT INTO Education (profile_id,rank,year,institution_id) VALUES(:pid,:rank,:year,:iid)');
	$stmt->execute(array(
		':pid'=>$profile_id,
		':rank'=>$rank,
		':year'=>$year,
		':iid'=>$institution_id)) ;
	$rank++;
}

$_SESSION['success']='Profile updated';
	header('Location: indexjs.php');
	return;
}

if(!isset($_GET['profile_id'])){
	$_SESSION['error']="Missing profile_id";
	header('Location: indexjs.php');
	return;
}

$stmt= $pdo->prepare("SELECT * FROM Profile WHERE profile_id= :prof  AND user_id=:uid");
$stmt->execute(array(":prof"=>$_GET['profile_id'], ':uid'=>$_SESSION['user_id']));
$row=$stmt->fetch(PDO::FETCH_ASSOC);
if($row===false){
	$_SESSION['error']='Could not load profile';
	header('Location: indexjs.php');
	return;
}
$fi=htmlentities($row['first_name']);
$la=htmlentities($row['last_name']);
$ema=htmlentities($row['email']);
$hea=htmlentities($row['headline']);
$summ=htmlentities($row['summary']);

$profile_id=$row['profile_id'];
$stmt=$pdo->prepare('SELECT * FROM Position WHERE profile_id=:prof ORDER BY rank');
$stmt->execute(array(':prof'=>$profile_id));
$positions=array();
while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
	$positions[]=$row;
}

$stmt=$pdo->prepare('SELECT * FROM Education JOIN Institution ON Education.institution_id=Institution.institution_id WHERE profile_id=:prof ORDER BY rank');
$stmt->execute(array(':prof'=>$profile_id));
$educations=array();
$educations[]=$stmt->fetch(PDO::FETCH_ASSOC); 



?>
<html>
<head>
 <title>Apoorva Agarwal </title>
 <link href="starter-template1.css" rel="stylesheet">
 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

  <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/ui-lightness/jquery-ui.css"> 

  <script src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>

  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js" integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30=" crossorigin="anonymous"></script>
</head>
<body>
 <div class="container">
<h1>Editing Profile for
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
    echo('<p style="color: red;">'.htmlentities($_SESSION["error"])."</p>\n");
    unset($_SESSION["error"]);
}
?>
<form method="post">
	<p>First Name:
<input type="text" name="first_name" size="50" value="<?= $fi ?>"/></p>
<p>Last Name:
<input type="text" name="last_name" size="50" value="<?= $la ?>"/></p>

<p>Email:
<input type="text" name="email" value="<?= $ema ?>"/></p>
<p>Headline:<br/>
<input type="text" name="headline" size="80" value="<?= $hea ?>"/></p>
<p>Summary:<br/>
<textarea name="summary" rows="8" cols="80" ><?= $summ ?></textarea>
</p>
<p>
	Education: <input type="submit" id="addEdu" value="+">
	<div id="edu_fields">
		<?php
		$countEdu=0;
		if(count($educations)>0){
			$stmt=$pdo->prepare('SELECT * FROM Education JOIN Institution ON Education.institution_id=Institution.institution_id WHERE profile_id=:prof ORDER BY rank');
$stmt->execute(array(':prof'=>$profile_id));
$educations=array();
		while($row=$stmt->fetch(PDO::FETCH_ASSOC)) {
			$countEdu++;
			echo '<div id="edu'.$countEdu.'">'."\n";
			echo '<p>Year: <input type="text" name="edu_year'.$countEdu.'"';
 		echo 'value="'.$row['year'].'"/>'."\n";
 		echo '<input type="button" value="-" ';
 		echo 'onclick="$(\'#edu'.$countEdu.'\').remove();
 		return false;">'."\n";
 		echo "</p>\n";
 		echo '<p>School: <input type="text" size="80" name="edu_school'.$countEdu.'" class="school" value="'.htmlentities($row['name']).'"/>';
 		echo "\n</div>\n";
		}
	 }
		?>
	</div>
</p>
<p>
	Position:<input type="submit" value="+" id="addPos">
	<div id="position_fields">
		<?php
		$pos=0;
 	foreach($positions as $position){
 		$pos++;

 		echo '<div id="position'.$pos.'">'."\n";
 		echo '<p>Year: <input type="text" name="year'.$pos.'"';
 		echo 'value="'.$position['year'].'"/>'."\n";
 		echo '<input type="button" value="-" ';
 		echo 'onclick="$(\'#position'.$pos.'\').remove();
 		return false;">'."\n";
 		echo "</p>\n";
 		echo '<textarea name="desc'.$pos.'"rows="8" cols="80">'."\n";
 		echo htmlentities($position['description'])."\n";
 		echo "\n</textarea>\n</div>\n";
 	}
 	?>
	</div>
</p>
<p>
<input type="hidden" name="profile_id" value="<?= $row['profile_id'] ?>">
	</p>
<p>	<input type="submit" value="Save" name="Save" >
	<input type="submit" name="Cancel" value="Cancel"></p>
</form>
 <script >
 	countPos=<?=$pos ?>;
 	countEdu=<?=$countEdu?>;
 	$(document).ready(function(){
    window.console && console.log('Document ready called');
    $('#addPos').click(function(event){
        // http://api.jquery.com/event.preventdefault/
        event.preventDefault();
        if ( countPos >= 9 ) {
            alert("Maximum of nine position entries exceeded");
            return;
        }
        countPos++;
        window.console && console.log("Adding position "+countPos);
        $('#position_fields').append(
            '<div id="position'+countPos+'"> \
            <p>Year: <input type="text" name="year'+countPos+'" value="" /> \
            <input type="button" value="-" \
                onclick="$(\'#position'+countPos+'\').remove();return false;"></p> \
            <textarea name="desc'+countPos+'" rows="8" cols="80"></textarea>\
            </div>');
    });

    $('#addEdu').click(function(event){
        // http://api.jquery.com/event.preventdefault/
        event.preventDefault();
        if ( countEdu >= 9 ) {
            alert("Maximum of nine position entries exceeded");
            return;
        }
        countEdu++;
        window.console && console.log("Adding Education "+countEdu);
        var source=$("#edu-template").html();
        $('#edu_fields').append(source.replace(/@COUNT@/g,countEdu));
        $('.school').autocomplete({
        	source:"school.php"
        });
    });
     $('.school').autocomplete({
        	source:"school.php"
        });
});
 </script>
 <script id="edu-template" type="text">
 	<div id="edu@COUNT@">
 		<p>Year: <input type="text" name="edu_year@COUNT@" value=""/>
 			<input type="button" value="-" onclick="$('#edu@COUNT@').remove();return false;"><br>
 			<p>School: <input type="text" size="80" name="edu_school@COUNT@" class="school" value="" />
 			</p>
 			<div>
 </script>
</div>
</body>
</html>