<?php
// Initialize the session
session_start();
require_once('config.php');
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

$today = date('Y-m-d');
$weekAfterToday = date('Y-m-d', strtotime($today . ' + 7 days'));

$daysOfWeekNextSevenDays = array();

for($i = 0; $i < 7; $i++) {
    if($i == 0) {
        $day = date('Y-m-d');
    } else {
        $day = date('Y-m-d', strtotime($today . " + {$i} days"));
    }
    array_push($daysOfWeekNextSevenDays, $day);
}


//TODO: This is not the intended behavior, need to fetch the workouts, then match them to the generated dates
//if no workout exists, then put no workout/add-workout
$sql = "SELECT w.workoutID, w.workoutName, w.workoutDate  FROM workouts w INNER JOIN users u ON w.userID = u.userID WHERE username = ? 
AND workoutDate >= ? AND workoutDate <= ?";

$stmt = $pdo->prepare($sql);
$stmt->execute([$_SESSION["username"], $today, $weekAfterToday]);

$nextSevenDaysWorkouts = array();

if($stmt->rowCount() < 1) {
    $$nextSevenDaysWorkouts = array_fill(0, 7, null);
}

if($stmt->rowCount() > 0) {
    $nextSevenDaysWorkouts = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
$workoutDayMap = array_fill(0, 7, null);
//print_r($daysOfWeekNextSevenDays);



//map workouts to dates for display in week glance cards
// foreach($nextSevenDaysWorkouts as &$daysWorkout) {
//     for($i=0;$i<count($daysOfWeekNextSevenDays);$i++) {
//         if($daysWorkout['workoutDate'] == $daysOfWeekNextSevenDays[$i]) {
//             $workoutDayMap["{$daysOfWeekNextSevenDays}"] = $daysWorkout['workoutName'];
//         }
//     }
// }

$runningDay = date('Y-m-d', strtotime($today));
for($i=0;$i<count($daysOfWeekNextSevenDays);$i++) {
    foreach($nextSevenDaysWorkouts as &$daysWorkout) {
        if($runningDay == $daysWorkout['workoutDate']) {
            $workoutDayMap[$i] = $daysWorkout['workoutName'];
        }
    }
    $runningDay = date('Y-m-d', strtotime($runningDay . " + 1 days"));
}


//print_r($workoutDayMap);

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
            <a class="nav-link" href="user-settings.php">Settings</a>
        </li>
        <li class="nav-item">
            <a href="logout.php" class="btn btn-secondary">Log Out</a>
        </li>
        </ul>
    </div>
    </nav>
    
    <h1 class="my-5">Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Welcome to the site.</h1>

    <p>
        <a href="reset-password.php" class="btn btn-warning">Reset Your Password</a>
    </p>
    <br>
    <h5>Here's what you have going on this week</h3>
    
    <div class="container">
        <?php
        echo "<div class=\"row row-cols-7\">";
        for($i = 0; $i < 7; $i++) {
            //echo "<div class=\"week-glance-item\">";
            echo "<div class=\"col-sm\">
                <div class=\"card bg-dark text-white\" style=\"width: 100%; height: 10rem;\">
                <div class=\"card-header\">{$daysOfWeekNextSevenDays[$i]}</div>
                <div class=\"card-body\">";
                if($workoutDayMap[$i] == null) {
                    echo "No Planned Workout";
                } else {
                    echo $workoutDayMap[$i];
                }
                echo "</div>
            </div>
            </div>";
            //echo "<div class=\"week-glance-item-sub\">{$nextSevenDaysWorkouts[$i]}</div>";
            //echo "</div>";
        }
        echo "</div>";
        // for($i = 0; $i < 7; $i++) {
        //     echo "<div class=\"card\">{$nextSevenDaysWorkouts[$i]}</div>";
        // }

        //print_r($nextSevenDaysWorkouts);
        ?>
    </div>
</body>
</html>