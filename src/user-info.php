<?php 
    session_start();
     
    // Check if the user is logged in, if not then redirect him to login page
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
        header("location: login.php");
        exit;
    }

    function displayUserInfo() {
        require_once('config.php');
        $userInfoSQL = "SELECT username, fname, lname, weight, height FROM users WHERE userID = {$_SESSION['id']}";
        $userInfoPrep = $pdo->prepare($userInfoSQL);
        $userInfoPrep->execute();
        $userInfo = $userInfoPrep->fetch(PDO::FETCH_ASSOC);

        echo "<li class=\"list-group-item\">Username: {$userInfo['username']}</li>";
        echo "<li class=\"list-group-item\">Full Name: {$userInfo['fname']}\t{$userInfo['lname']}</li>";
        echo "<li class=\"list-group-item\">Height: {$userInfo['height']}</li>";
        echo "<li class=\"list-group-item\">Weight: {$userInfo['weight']}</li>";
    }

    //TODO: Handle updating database when Update Info button is clicked.
?>