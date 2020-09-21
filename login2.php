<?php // Do not put any HTML above this line

if ( isset($_POST['cancel'] ) ) {
    // Redirect the browser to game.php
    header("Location: index6.php");
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
        $_SESSION["error"]= "User name and password are required";
        header('Location: login2.php');
        return;

    } else {
        $check = hash('md5', $salt.$_POST['pass']);
         $Email=$_POST['email'];
          if((strpos($Email,"@" ))>0){

        if ( $check == $stored_hash ) {
            // Redirect the browser to autos.php
            $_SESSION['email']=$_POST['email'];
            $_SESSION['pass']=$_POST['pass'];
            header("Location: index6.php");
            error_log("Login success ".$_POST['email']);
            return;
        }
        else{
             $_SESSION["error"] = "Incorrect password";
             header('Location: login2.php');
            error_log("Login fail ".$_POST['email']." $check");
            return;
            
        }
}
         else {
            $_SESSION["error"]="Email must have an at-sign(@)";
            header('Location: login2.php');
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
<label for="nam">User Name</label>
<input type="text" name="email" id="nam"><br/>
<label for="id_1723">Password</label>
<input type="password" name="pass" id="id_1723"><br/>
<input type="submit" value="Log In">
<a href="index6.php">Cancel</a>
</form>
<p>
For a password hint, view source and find an account and password hint in the HTML comments
<!-- Hint: The password is the language you are worrking on(three letter word) (all lower case) followed by 123. -->
</p>
</div>
</body>
