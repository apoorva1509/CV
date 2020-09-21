<?php
$name="";
session_start();
if(isset($_POST['cancel'])){
 	header('Location: indexjs.php');
 	return;
 }
 if (!isset($_SESSION['name'])) {
	$_SESSION['error']="Acess Deni ";
	header('Location:indexjs.php');
}
else
{
   $name=$_SESSION['name'];
}
if(!isset($_SESSION['user_id'])){
	die("ACCESS DENIED");
	
}
$pdo = new PDO('mysql:host=localhost;port=3306;dbname=misc', 'apoo', 'yo');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
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


if(isset($_POST['Add'])){
	if(isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email']) && isset($_POST['headline']) && isset($_POST['summary']))
{
	if(strlen($_POST['first_name'])==0 || strlen($_POST['last_name'])==0 || strlen($_POST['email'])==0 || strlen($_POST['headline'])==0 || strlen($_POST['summary'])==0)
	{
		
		$_SESSION['error']="All fields are required";
		header('Location: addjs.php');
		return;
	}
		if (strpos($_POST['email'],'@')==false) {
			# code...
			$_SESSION['error']="Email address must contain @";
		header('Location: addjs.php'); 
		return;
		}
		for($i=1;$i<=9;$i++){
			if(! isset($_POST['year'.$i])) continue;
	if(! isset($_POST['desc'.$i]))continue;
	$year= $_POST['year'.$i]; 
	$desc=$_POST['desc'.$i];
	if(strlen($year)==0 || strlen($desc)==0){
		$_SESSION['error']="All fields are required";
		header('Location: addjs.php'); 
		return;
		}
		if(! is_numeric($year)){
		$_SESSION['error']="Position year must be numeric";
		header('Location: addjs.php'); 
		return;
		}
	}
	$position_validate = validateEdu();


    if ($position_validate !== true) {
        $_SESSION["error"] = $position_validate;
        header("Location: addjs.php");
        return;
    }

$stmt = $pdo->prepare('INSERT INTO Profile
  (user_id, first_name, last_name,email,headline,summary) VALUES ( :uid,:fn,:ln,:em,:he,:su)');
$stmt->execute(array(
   ':uid' => $_SESSION['user_id'],
  ':fn' => $_POST['first_name'],
  ':ln' => $_POST['last_name'],
  ':em' => $_POST['email'],
  ':he' => $_POST['headline'],
  ':su' => $_POST['summary'])
);
$profile_id=$pdo -> lastInsertId();
$rank=1;
for($i=1;$i<=9;$i++)
{
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
$rank=1;
$institution_id=false;
for($i=1;$i<=9;$i++)
{
	if(! isset($_POST['edu_year'.$i])) continue;
	if(! isset($_POST['edu_school'.$i]))continue;
	$year= $_POST['edu_year'.$i]; 
	$school=$_POST['edu_school'.$i];
	
	$stmt =$pdo->prepare('SELECT institution_id FROM Institution WHERE name=:name');
	$stmt->execute(array(':name'=>$school)) ;
	$row=$stmt->fetch(PDO::FETCH_ASSOC);
	if($row!==false)$institution_id=$row['institution_id'];
	if($institution_id===false){
		$stmt=$pdo->prepare('INSERT INTO Institution (name) VALUES (:name)');
		$stmt->execute(array(':name'=>$school));
		$institution_id=$pdo->lastInsertId();
	}
	$stmt =$pdo->prepare('INSERT INTO Education (profile_id,rank,year,institution_id) VALUES (:pid,:rank,:year,:iid)');
	$stmt->execute(array(
		':pid'=>$profile_id,
		':rank'=>$rank,
		':year'=>$year,
		':iid'=>$institution_id)) ;
	$rank++;
}
		    $_SESSION['success']="Profile added";
		    header('Location: indexjs.php');
		    return;	
 }
}
?>
<!DOCTYPE html>
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
 <h1>Adding Profile for 
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
 	<p>First Name:
<input type="text" name="first_name" size="50"/></p>
<p>Last Name:
<input type="text" name="last_name" size="50"/></p>

<p>Email:
<input type="text" name="email"/></p>
<p>Headline:<br/>
<input type="text" name="headline" size="80"/></p>
<p>Summary:<br/>
<textarea name="summary" rows="8" cols="80"></textarea>
</p>
<p>
	Education: <input type="submit" id="addEdu" value="+">
	<div id="edu_fields"></div>
	</p>
<p>
	Position:<input type="submit" value="+" id="addPos">
	<div id="position_fields"></div>
</p>

<input type="submit" value="Add" name="Add">
<input type="submit" name="cancel" value="Cancel">
 </form>
 <script >
 	countPos=0;
 	countEdu=0;
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