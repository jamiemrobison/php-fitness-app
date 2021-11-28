<?php 

    session_start();
    include_once("./monthly-workout-info.php");

    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
        header("location: login.php");
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Month View</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; text-align: center; background-color: #F5FCFC; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark justify-content-between">
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
        <li class="nav-item active">
            <a class="nav-link" href="monthly-calendar.php">Calendar  <span class="sr-only">(current)</span></a>
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
        </ul>
        <a href="logout.php" class="btn btn-secondary" style="margin-left: auto; margin-right: 0;">Log Out</a>
    </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <div class="card bg-light m-4" style="width: 40rem; height: 15rem;">
                <form class="m-2" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="form-row">
                        <div class="col">
                        <label for="monthSelected">Select Month:</label>
                            <select class="form-control" name="monthSelected" placeholder="Select a month...">
                                <option>January</option>
                                <option>February</option>
                                <option>March</option>
                                <option>April</option>
                                <option>May</option>
                                <option>June</option>
                                <option>July</option>
                                <option>August</option>
                                <option>September</option>
                                <option>October</option>
                                <option>November</option>
                                <option>December</option>
                            </select>
                        </div>
                        <div class="col">
                        <label for="yearSelected">Enter 4-Digit Year:</label>
                            <input type="text" class="form-control" placeholder="YYYY" name="yearSelected" required>
                        </div>
                    </div>
                    <button class="btn btn-primary m-4" type="submit">Get Workouts</button>
                </form>
            </div>
        </div>
        <div class="row" style="width: 80rem;"> 
        <?php getMonthlyWorkouts($pdo) ?>
        </div>
    </div>
</body>
</html>