<?php 
    session_start();
     
    // Check if the user is logged in, if not then redirect him to login page
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
        header("location: login.php");
        exit;
    }

    function displayUserInfo() {
        require_once('./config.php');
        $userInfoSQL = "SELECT username, fname, lname, weight, height FROM users WHERE userID = {$_SESSION['id']}";
        $userInfoPrep = $pdo->prepare($userInfoSQL);
        $userInfoPrep->execute();
        $userInfo = $userInfoPrep->fetch(PDO::FETCH_ASSOC);

        $_SESSION['currentUsername'] = $userInfo['username'];
        $_SESSION['currentFname'] = $userInfo['fname'];
        $_SESSION['currentLname'] = $userInfo['lname'];
        $_SESSION['currentHeight'] = $userInfo['height'];
        $_SESSION['currentWeight'] = $userInfo['weight'];

        echo "<li class=\"list-group-item\">Username: {$userInfo['username']}</li>";
        echo "<li class=\"list-group-item\">Full Name: {$userInfo['fname']}\t{$userInfo['lname']}</li>";
        echo "<li class=\"list-group-item\">Height: {$userInfo['height']}</li>";
        echo "<li class=\"list-group-item\">Weight: {$userInfo['weight']}</li>";
    }

    function updateUserInfo() {
        if(isset($_POST['submitUserInfo'])) {
            require_once('./config.php');
            $updateUsername = isset($_POST['newUsername']);
            $updateFname = isset($_POST['newFname']);
            $updateLname = isset($_POST['newLname']);
            $updateHeight = isset($_POST['newHeight']);
            $updateWeight = isset($_POST['newWeight']);

            if($updateUsername) { $newUsername = $_POST['newUsername']; }
            if($updateFname) { $newFname = $_POST['newFname']; }
            if($updateLname) { $newLname = $_POST['newLname']; }
            if($updateHeight) { $newHeight = $_POST['newHeight']; }
            if($updateWeight) { $newWeight = $_POST['newWeight']; }

            $username = $updateUsername ? (String) $newUsername : (String) $_SESSION['currentUsername'];
            $fname = $updateFname ? (String) $newFname : (String) $_SESSION['currentFname'];
            $lname = $updateLname ? (String) $newLname : (String) $_SESSION['currentLname'];
            $height = $updateHeight ? (int) $newHeight : (int) $_SESSION['currentHeight'];
            $weight = $updateWeight ? (int) $newWeight : (int) $_SESSION['currentWeight'];
            $id = $_SESSION['id'];

            //TODO: Something keeps making this fail. Works fine in php shell. Has to be something with how  the document is sending the values form the form. (That's why the castings are there in the block above).
            //This is a problem for future Tyler.
            $updateUserInfoSQL = "UPDATE users SET username='$username', fname='$fname', lname='$lname', height=$height, weight=$weight WHERE userID=$id";
            $updateUserInfoPrep = $pdo->prepare($updateUserInfoSQL);
            $updateUserInfoPrep->execute();
        }
    }
?>