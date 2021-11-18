<?php 
session_start();
require_once('config.php');

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

$getAllUserWorkoutsSQL = "SELECT workoutName, numExercises, workoutDate, workoutID FROM workouts WHERE userID = {$_SESSION['id']}";
$getAllUserWorkoutsPrep = $pdo->prepare($getAllUserWorkoutsSQL);
$getAllUserWorkoutsPrep->execute();
$allUserWorkouts = $getAllUserWorkoutsPrep->fetchAll(PDO::FETCH_ASSOC);

//print_r($allUserWorkouts);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Exercise Data</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 360px; padding: 20px; }
    </style>
</head>
<body>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
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
            <a class="nav-link" href="add-workout.php">New Workout</a>
        </li>
        <li class="nav-item active">
            <a class="nav-link" href="complete-workout.php">Complete Workout <span class="sr-only">(current)</span></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="user-settings.php">Settings</a>
        </li>
        <li class="nav-item" style="position: relative; left: 180%">
            <a href="logout.php" class="btn btn-secondary">Log Out</a>
        </li>
        </ul>
    </div>
    </nav>
    <div class="wrapper">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="workouts">Select Workout To Input Data For:</label>
                <select class="form-control" name="workoutName">
                <?php
                    if(!empty($allUserWorkouts)) {
                        for($i=0;$i<count($allUserWorkouts);$i++) {
                            $tmpWorkoutDate = $allUserWorkouts[$i]['workoutDate'];
                            echo "<option value=\"{$allUserWorkouts[$i]['workoutName']}\">{$allUserWorkouts[$i]['workoutDate']}\t{$allUserWorkouts[$i]['workoutName']}</option>";
                        }
                    }
                ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary mb-2" name="getExerciseFields">Next</button>
        </form>
        
        <?php 
            if(isset($_POST['getExerciseFields'])) {
                $workoutName = $_POST['workoutName'];
                $_SESSION['workoutName'] = $workoutName;
            

            $getAllExerciseNamesOfWorkoutSQL = "SELECT e.name, wd.exID FROM workout_details wd INNER JOIN exercises e ON wd.exID = e.exID INNER JOIN workouts w ON w.workoutID = wd.workoutID WHERE w.workoutName='$workoutName'";
            $getAllExerciseNamesOfWorkoutPrep = $pdo->prepare($getAllExerciseNamesOfWorkoutSQL);
            $getAllExerciseNamesOfWorkoutPrep->execute();
            $exerciseArray = $getAllExerciseNamesOfWorkoutPrep->fetchAll(PDO::FETCH_ASSOC);
            $_SESSION['exerciseArray'] = $exerciseArray;

            for($i=1; $i<=count($exerciseArray);$i++) {
                echo "<form action=\"/fitness-app/php-fitness-app/complete-workout.php\" method=\"post\">
                    <div class=\"form-group\">
                    <label for=\"exercise{$i} sets\">Exercise {$i}. {$_SESSION['exerciseArray'][$i - 1]['name']} Sets</label>
                    <select class=\"form-control\" name=\"exercise{$i}sets\">"; 

                    for($j=1;$j<=10;$j++) {
                        echo "<option>{$j}</option>";
                    }

                    echo "</select>
                    </div>";
            }
            echo "<button type=\"submit\" class=\"btn btn-primary mb-2\" name=\"submitSets\">Generate Reps and Weight Fields</button>";
                echo "</form>";
            
            }

            if(isset($_POST['submitSets'])) {
                $_SESSION['setsPerExercise'] = array();
                for($j=1;$j<count($_POST);$j++) {
                    array_push($_SESSION['setsPerExercise'], $_POST["exercise{$j}sets"]);
                }
                for($i=1;$i<=count($_SESSION['exerciseArray']);$i++) {
                    echo "<form action=\"/fitness-app/php-fitness-app/complete-workout.php\" method=\"post\"><div class=\"form-group\">";
                    for($j=1;$j<=$_POST["exercise{$i}sets"];$j++) {
                        echo "<div class=\"mb-3\"><label class=\"form-label\">"; echo $_SESSION['exerciseArray'][$i-1]['name']; echo " Set {$j} Reps"; echo "</label>";
                        echo "<input type=\"number\" class=\"form-control\" name=\""; echo "ex{$i}set{$j}reps"; echo"\"></div>";
                        echo "<div class=\"mb-1\"><label class=\"form-label\">"; echo $_SESSION['exerciseArray'][$i-1]['name']; echo " Set {$j} Weight"; echo "</label>";
                        echo "<input type=\"number\" class=\"form-control\" name=\""; echo "ex{$i}set{$j}weight"; echo"\"></div>";
                    }
                }
                echo "</div><button type=\"submit\" class=\"btn btn-primary mb-2\" name=\"submitWeightAndReps\">Add Completed Workout to the Database</button></form>";
            }

            if(isset($_POST['submitWeightAndReps'])) {
                $fields = array();
                $values = array();
                foreach($_POST as $field => $value) {
                    $fields[] = $field;
                    $values[] = $value;
                }

                

                $userID = $_SESSION['id'];
                $workoutName = $_SESSION['workoutName'];

                $getWorkoutID = "SELECT workoutID from workouts WHERE workoutName='$workoutName' AND userID=$userID";
                $getWorkoutIDPrep = $pdo->prepare($getWorkoutID);
                $getWorkoutIDPrep->execute();
                $workoutIDarr = $getWorkoutIDPrep->fetch(PDO::FETCH_ASSOC);
                $workoutID = $workoutIDarr['workoutID'];

                // print_r($fields);
                // echo "<br><br>";
                // print_r($values);
                // echo "<br><br>";
                // print_r($_SESSION['exerciseArray']);
                // echo "<br><br>";
                $repsArray = array();
                $weightArray = array();
                for($i=0;$i<count($values)-1;$i+=2) {
                    array_push($repsArray, $values[$i]);
                }

                for($x=1;$x<count($values);$x+=2) {
                    array_push($weightArray, $values[$x]);
                }

                // print_r($repsArray);
                // echo "<br><br>";
                // print_r($weightArray);
                // echo "<br><br>";
                // print_r($_SESSION['exerciseArray']);
                // echo "<br><br>";
                // echo $_SESSION['exerciseArray'][0]['name'];
                // echo "<br><br>";
                $repArrayIterator = 0;
                for($j=0;$j<count($_SESSION['exerciseArray']);$j++) {
                    $exID = $_SESSION['exerciseArray'][$j]['exID'];
                    for($k=0;$k<$_SESSION['setsPerExercise'][$j];$k++) {
                        $setNum = $k+1;
                        $numReps = $repsArray[$repArrayIterator];
                        $weight = $weightArray[$repArrayIterator];
                        $repArrayIterator++;
                        $completedExerciseSQL = "INSERT INTO completed_exercises VALUES($workoutID, $exID, $setNum, $numReps, $weight)";
                        $completedExercisePrep = $pdo->prepare($completedExerciseSQL);
                        $completedExercisePrep->execute();
                        // echo $completedExerciseSQL;
                        // echo "<br>";
                    }
                }

                // echo "<br><br>";
                // print_r($_SESSION['setsPerExercise']);
                // echo "<br>";
                

            }
        ?>
    </div>
    
</body>
</html>