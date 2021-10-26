<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$username = $password = $confirmPassword = "";
$usernameError = $passwordError = $confirmPasswordError = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate username
    if(empty(trim($_POST["username"]))){
        $usernameError = "Please enter a username.";
    } elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))){
        $usernameError = "Username can only contain letters, numbers, and underscores.";
    } else{
        // Prepare a select statement
        $sql = "SELECT userID FROM users WHERE username = :username";
        
        if($statement = $pdo->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $statement->bindParam(":username", $paramUsername, PDO::PARAM_STR);
            
            // Set parameters
            $paramUsername = trim($_POST["username"]);
            
            // Attempt to execute the prepared statement
            if($statement->execute()){
                if($statement->rowCount() == 1){
                    $usernameError = "This username is already taken.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            unset($statement);
        }
    }
    
    // Validate password
    if(empty(trim($_POST["password"]))){
        $passwordError = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 8){
        $passwordError = "Password must have atleast 8 characters.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    
    if(empty(trim($_POST["confirmPassword"]))){
        $confirmPasswordError = "Please confirm password.";     
    } else{
        $confirmPassword = trim($_POST["confirmPassword"]);
        if(empty($passwordError) && ($password != $confirmPassword)){
            $confirmPasswordError = "Password did not match.";
        }
    }
    
    // Check input errors before inserting in database
    if(empty($usernameError) && empty($passwordError) && empty($confirmPasswordError)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO users (username, password) VALUES (:username, :password)";
         
        if($statement = $pdo->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $statement->bindParam(":username", $paramUsername, PDO::PARAM_STR);
            $statement->bindParam(":password", $param_password, PDO::PARAM_STR);
            
            // Set parameters
            $paramUsername = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            
            // Attempt to execute the prepared statement
            if($statement->execute()){
                // Redirect to login page
                header("location: login.php");
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            unset($statement);
        }
    }
    
    // Close connection
    unset($pdo);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 360px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Sign Up</h2>
        <p>Please fill this form to create an account.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control <?php echo (!empty($usernameError)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                <span class="invalid-feedback"><?php echo $usernameError; ?></span>
            </div>    
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control <?php echo (!empty($passwordError)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
                <span class="invalid-feedback"><?php echo $passwordError; ?></span>
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="confirmPassword" class="form-control <?php echo (!empty($confirmPasswordError)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirmPassword; ?>">
                <span class="invalid-feedback"><?php echo $confirmPasswordError; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-secondary ml-2" value="Reset">
            </div>
            <p>Already have an account? <a href="login.php">Login here</a>.</p>
        </form>
    </div>    
</body>
</html>