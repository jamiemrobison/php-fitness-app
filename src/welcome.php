<?php 
    session_start();
    include_once("./weekView.php"); 
    include_once("./thousand-pound.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; text-align: center; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="welcome.php">IronWorks</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
        <li class="nav-item active">
            <a class="nav-link" href="welcome.php">Home <span class="sr-only">(current)</span></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="exercise-library.php">Library</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="add-workout.php">New Workout</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="complete-workout.php">Complete Workout</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="view-workout.php">View Workout</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="user-settings.php">Settings</a>
        </li>
        <li class="nav-item">
            <a href="logout.php" class="btn btn-secondary">Log Out</a>
        </li>
        </ul>
    </div>
    </nav>
    
   

    <div class="jumbotron">
    <h1 class="display-4">Hello, <?php echo htmlspecialchars($_SESSION["username"]); ?></h1>
    <p class="lead">Welcome to IronWorks. The fitness tracking application for weightlifters.</p>
    <hr class="my-4">
    </div>

    <div class="container">
        <div class="row">
        <div class="card m-4" style="width: 25rem;">
        <div class="row">
            <div class="col">
                <img class="card-img-top" src="../muscleGroupImages/blank-profile-picture.png" style="height: 100%;">
            </div>
            <div class = "col">
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                    <?php
                        echo "<li class=\"list-group-item\">{$_SESSION['fname']}\t{$_SESSION['lname']}</li>";
                        echo "<li class=\"list-group-item\">Height: {$_SESSION['height']}</li>";
                        echo "<li class=\"list-group-item\">Weight: {$_SESSION['weight']}</li>";
                    ?>
                    </ul>
                </div>
            </div>
        </div>
        </div>
        <div class="card m-4" style="width: 25rem;">
            <?php getMaxLifts($pdo); ?>
        </div>
        </div>
    </div>
    <div class="container">
        <h5>Here's what you have going on this week</h5>
        <?php displayWeekViewCards($pdo); ?>
    </div>
</body>
</html>