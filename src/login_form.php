<?php

require "../vendor/autoload.php";
use LoginOpdracht\classes\User;

// Is de login button aangeklikt?
if (isset($_POST['login-btn'])) {

    require_once('classes/user.php');
    $user = new User();

    $user->username = $_POST['username'];
    $user->SetPassword($_POST['password']);

    // Validatie gegevens
    $errors = $user->ValidateUser();

    // Indien geen fouten dan inloggen
    if (count($errors) == 0) {
        //Inlogen
        if ($user->LoginUser()) {
            // Ga naar pagina??
            header("location: index.php");
        } else {
            array_push($errors, "Login mislukt");
        }
    }

    if (count($errors) > 0) {
        $message = implode("\\n", $errors);
        echo "
        <script>alert('" . $message . "')</script>
        <script>window.location = 'login_form.php'</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP - PDO Login and Registration</title>
</head>
<body>

<h3>PHP - PDO Login and Registration</h3>
<hr/>

<form action="" method="POST">
    <h4>Login here...</h4>
    <hr>

    <label for="username">Username</label>
    <input type="text" name="username" id="username" required />
    <br>
    <label for="password">Password</label>
    <input type="password" name="password" id="password" required />
    <br>
    <button type="submit" name="login-btn">Login</button>
    <br>
    <a href="register_form.php">Registration</a>
</form>

</body>
</html>