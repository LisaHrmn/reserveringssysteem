<?php
// Initialize the session
require_once "config.php";

session_start();
// Check if the user is already logged in, if yes then redirect him to welcome page
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: reservation.php");
    exit;
}

// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = "";
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = "Vul uw gebruikersnaam in.";
    } else{
        $username = trim($_POST["username"]);
    }
    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Vul uw wachtwoord in.";
    } else{
        $password = trim($_POST["password"]);
    }
    // Validate credentials
    if(empty($username_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT id, username, password FROM users WHERE username = ?";
        if($stmt = mysqli_prepare($con, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            // Set parameters
            $param_username = $username;
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);
                // Check if username exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $username, $db_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if($password == $db_password){
                            // Password is correct, so start a new session
                            session_start();
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;
                            // Redirect user to welcome page
                            header("location: reservation.php");
                        } else{
                            // Display an error message if password is not valid
                            $password_err = "Uw wachtwoord is onjuist.";
                        }
                    }
                } else{
                    // Display an error message if username doesn't exist
                    $username_err = "Geen account gevonden met deze gebruikersnaam.";
                }
            } else{
                echo "Systeem error.";
            }
        }
        // Close statement
        mysqli_stmt_close($stmt);
    }
    // Close connection
    mysqli_close($con);
}
?>

<!doctype html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Login</title>
        <link rel="stylesheet" href="css/style.css">
    </head>

    <body>
    <header id="header">
        <p>Adinda Bijles</p>
    </header>
    <div class="container">
        <h1>Login</h1>
        <div id="login">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <input type="text" name="username" class="form-control" placeholder="Gebruikersnaam.." value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <input type="password" name="password" placeholder="Wachtwoord..." class="form-control">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login">
                <br>
            </div>
        </div>
        </form>
    </div>
    </body>
</html>
