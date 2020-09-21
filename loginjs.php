<?php // Do not put any HTML above this line
$pdo = new PDO('mysql:host=localhost;port=3306;dbname=misc', 'apoo', 'yo');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
if ( isset($_POST['cancel'] ) ) {
    // Redirect the browser to game.php
    header("Location: indexjs.php");
    return;
}

$salt = 'XyZzy12*_';
$stored_hash = '1a52e17fa899cf40fb04cfc42e6352f1';  // Pw is php123

$failure = false;  // If we have no POST data

// Check to see if we have some POST data, if we do process it
session_start();
if ( isset($_POST['email']) && isset($_POST['pass']) ) {
    unset($_SESSION['email']);
    if ( strlen($_POST['email']) < 1 || strlen($_POST['pass']) < 1 ) {
        header('Location: loginjs.php');
        return;

    } else {
        $check = hash('md5', $salt.$_POST['pass']);
        $stmt = $pdo->prepare('SELECT user_id, name FROM users

WHERE email = :em  AND password = :pw ');

$stmt->execute(array( ':em' => $_POST['email'], ':pw' => $check ));

$row = $stmt->fetch(PDO::FETCH_ASSOC);
         $Email=$_POST['email'];
          if((strpos($Email,"@" ))>0){

        if ( $row!==false ) {  
            // Redirect the browser to autos.php
           $_SESSION['name'] = $row['name'];

$_SESSION['user_id'] = $row['user_id'];
            header("Location: indexjs.php");
            error_log("Login success ".$_POST['email']);
            return;
        }
        else{
             $_SESSION["error"] = "Incorrect password";
             header('Location: loginjs.php');
            error_log("Login fail ".$_POST['email']." $check");
            return;
            
        }
}
         else {
            header('Location: loginjs.php');
            return;
        }
       
    }
}

// Fall through into the View
?>
<!DOCTYPE html>
<html>
<head>
<?php require_once "bootstrap.php"; ?>
<title>Apoorva Agarwal's Login Page</title>
</head>
<body>
<div class="container">
<h1>Please Log In</h1>
<?php
// Note triple not equals and think how badly double
// not equals would work here...
if ( isset($_SESSION["error"]) ) {
    // Look closely at the use of single and double quotes
    echo('<p style="color: red;">'.$_SESSION["error"]."</p>\n");
    unset($_SESSION["error"]);
}
?>
<form method="POST">
<label for="email">Email</label>
<input type="text" name="email" id="email"><br/>
<label for="id_1723">Password</label>
<input type="password" name="pass" id="id_1723"><br/>
<input type="submit" value="Log In" onclick="return doValidate();">
<input type="submit" name="cancel" value="Cancel">
</form>
<p>
For a password hint, view source and find an account and password hint in the HTML comments
<!-- Hint: 
The account is umsi@umich.edu
    The password is the language you are worrking on(three letter word) (all lower case) followed by 123. -->
</p>
<script type="text/javascript">
    function doValidate() {
        console.log('Validating....');
        try{
            addr = document.getElementById('email').value;
            pw = document.getElementById('id_1723').value;
            console.log("Validating addr="+addr+" pw="+pw);
            if(addr==null || addr=="" || pw==null || pw==""){
                 alert("Both fields must be filled out");
                 return false;
            }     
            if(addr.indexOf('@')==-1){
                alert("Invalid email address");
                return false;
            }
            return true;
        }
        catch(e){
            return false;
        }
        return false;
    }
</script>
</div>
</body>
