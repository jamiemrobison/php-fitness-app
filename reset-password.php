<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, otherwise redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
 
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$newPassword = $confirmPassword = "";
$newPasswordError = $confirmPasswordError = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate new password
    if(empty(trim($_POST["newPassword"]))){
        $newPasswordError = "Please enter the new password.";     
    } elseif(strlen(trim($_POST["newPassword"])) < 8){
        $newPasswordError = "Password must have atleast 8 characters.";
    } else{
        $newPassword = trim($_POST["newPassword"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirmPassword"]))){
        $confirmPasswordError = "Please confirm the password.";
    } else{
        $confirmPassword = trim($_POST["confirmPassword"]);
        if(empty($newPasswordError) && ($newPassword != $confirmPassword)){
            $confirmPasswordError = "Password did not match.";
        }
    }
        
    // Check input errors before updating the database
    if(empty($newPasswordError) && empty($confirmPasswordError)){
        // Prepare an update statement
        $sql = "UPDATE users SET password = :password WHERE userID = :id";
        
        if($stmt = $pdo->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":password", $paramPassword, PDO::PARAM_STR);
            $stmt->bindParam(":id", $paramID, PDO::PARAM_INT);
            
            // Set parameters
            $paramPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $paramID = $_SESSION["id"];
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Password updated successfully. Destroy the session, and redirect to login page
                session_destroy();
                header("location: login.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            unset($stmt);
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
    <title>Reset Password</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 360px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Reset Password</h2>
        <p>Please fill out this form to reset your password.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
            <div class="form-group">
                <label>New Password</label>
                <input type="password" name="newPassword" class="form-control <?php echo (!empty($newPasswordError)) ? 'is-invalid' : ''; ?>" value="<?php echo $newPassword; ?>">
                <span class="invalid-feedback"><?php echo $newPasswordError; ?></span>
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="confirmPassword" class="form-control <?php echo (!empty($confirmPasswordError)) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $confirmPasswordError; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <a class="btn btn-link ml-2" href="welcome.php">Cancel</a>
            </div>
        </form>
    </div>    
</body>
</html>