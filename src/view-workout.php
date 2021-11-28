<?php 
    session_start();
    include("./workout-details.php"); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Workout</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; text-align: center; background-color: #F5FCFC; }
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
        <li class="nav-item">
            <a class="nav-link" href="welcome.php">Home</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="exercise-library.php">Library</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="monthly-calendar.php">Calendar</a>
        </li>  
        <li class="nav-item">
            <a class="nav-link" href="add-workout.php">New Workout</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="complete-workout.php">Complete Workout</a>
        </li>
        <li class="nav-item active">
            <a class="nav-link" href="view-workout.php">View Workout <span class="sr-only">(current)</span></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="user-settings.php">Settings</a>
        </li>
        </ul>
        <a href="logout.php" class="btn btn-secondary" style="margin-left: auto; margin-right: 0;">Log Out</a>
    </div>
    </nav>

    <div class="container-fluid">
        <div class="row justify-content-center">
                <div class="card mt-4" style="width: 18rem;">
                    <div class="card-header">
                        Select Workout
                    </div>
                    <form class="m-4" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <select class="form-control" name="workoutName">
                        <?php
                            displayUserWorkoutOptions($pdo);
                        ?>
                    </select>
                    <button type="submit" class="btn btn-primary m-2" name="getWorkoutDetails">View Workout</button>
                    </form>
                </div>
                <?php getWorkoutDetails($pdo); ?>
        </div>
    </div>
</body>
</html>