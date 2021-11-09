<?php 
session_start();
require_once('config.php');

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
$sql = "SELECT * FROM exercises";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$allExercises = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Workout</title>
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
        <a class="navbar-brand" href="#">IronWorks</a>
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
        <li class="nav-item  active">
            <a class="nav-link" href="add-workout.php">New Workout <span class="sr-only">(current)</span></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="complete-workout.php">Complete Workout</a>
        </li>
        </ul>
    </div>
    </nav>
    <div class="wrapper">
        

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Workout Name</label>
                <input type="text" name="workoutName" class="form-control" <?php if(isset($_POST['workoutName'])) echo "value=\"{$_POST['workoutName']}\""?> required>
            </div>
            <div class="form-group">
                <label>Number of Exercises</label>
                <select class="form-control" id="numExercisesSelect" name="numExercises">
                    <option <?php if($_POST['numExercises'] == 1) echo "selected"; ?>>1</option>
                    <option <?php if($_POST['numExercises'] == 2) echo "selected"; ?>>2</option>
                    <option <?php if($_POST['numExercises'] == 3) echo "selected"; ?>>3</option>
                    <option <?php if($_POST['numExercises'] == 4) echo "selected"; ?>>4</option>
                    <option <?php if($_POST['numExercises'] == 5) echo "selected"; ?>>5</option>
                    <option <?php if($_POST['numExercises'] == 6) echo "selected"; ?>>6</option>
                    <option <?php if($_POST['numExercises'] == 7) echo "selected"; ?>>7</option>
                    <option <?php if($_POST['numExercises'] == 8) echo "selected"; ?>>8</option>
                    <option <?php if($_POST['numExercises'] == 9) echo "selected"; ?>>9</option>
                    <option <?php if($_POST['numExercises'] == 10) echo "selected"; ?>>10</option>
                </select>
            </div>
            <div class="form-group">
                <label>Workout Date</label>
                <input type="date" id="workoutDate" name="workoutDate" <?php if(isset($_POST['workoutDate'])) echo "value=\"{$_POST['workoutDate']}\"" ?> required>
            </div>
            <button type="submit" class="btn btn-primary mb-2" name="submitNumExercises">Generate Exercise Fields</button>

        </form>

        <?php 
            if(isset($_POST['submitNumExercises'])) {

                //stored in session vars because accessing them in regular variables later on doesn't seem to work
                $_SESSION['workoutName'] = $_POST['workoutName'];
                $_SESSION['workoutDate'] = $_POST['workoutDate'];
                $_SESSION['numExercises'] = $_POST['numExercises'];
                // $workoutDate = date("Y-m-d", strtotime($workoutDate));

                // echo $_SESSION['workoutName'];
                // echo $_POST['workoutDate'];
                // echo $_POST['numExercises'];


                for($i=1;$i<=$_POST['numExercises'];$i++) {
                    echo "<form action=\"/fitness-app/php-fitness-app/add-workout.php\" method=\"post\">
                    <div class=\"form-group\">
                    <label for=\"exercise{$i}\">Exercise</label>
                    <select class=\"form-control\" name=\"exercise{$i}\">";

                    foreach($allExercises as &$exercise) {
                        echo "<option>{$exercise['name']}</option>";
                    }

                    echo "</select>
                    </div>";
                }

                echo "<button type=\"submit\" class=\"btn btn-primary mb-2\" name=\"submitWorkout\">Submit Workout Plan!</button>";
                echo "</form>";
            }

            if(isset($_POST['submitWorkout'])) {

                // echo $_SESSION['workoutName'];
                // echo $workoutDate;
                //echo $_SESSION['id'];
                $workoutDate = $_SESSION['workoutDate'];
                $userID = $_SESSION['id'];
                $workoutName = $_SESSION['workoutName'];
                $numExercises = $_SESSION['numExercises'];
                
                $sql2 = "INSERT INTO workouts VALUES (null, '$workoutName', $numExercises, $userID, '$workoutDate')";
                $stmt2 = $pdo->prepare($sql2);

                // echo "<br> {$_SESSION['workoutName']}";
                // echo "<br> {$_SESSION['id']}";
                // echo "<br> {$_SESSION['workoutDate']}";
                
                $stmt2->execute();

                $exerciseArray = array();
                
                for($i=1;$i<=$numExercises;$i++) {
                    array_push($exerciseArray, $_POST["exercise{$i}"]);
                }

                $getWorkoutID = "SELECT workoutID from workouts WHERE workoutName='$workoutName' AND userID=$userID";
                $getWorkoutIDPrep = $pdo->prepare($getWorkoutID);
                $getWorkoutIDPrep->execute();
                $workoutIDarr = $getWorkoutIDPrep->fetch(PDO::FETCH_ASSOC);
                $workoutID = $workoutIDarr['workoutID'];


                //echo $workoutID;
                //insert a row into workout_details for each exercise in the workout
                foreach($exerciseArray as &$exerciseToAdd) {
                    //get the exerciseID for the exercise we're adding to the workout_details table
                    $getExID = "SELECT exID FROM exercises WHERE name='$exerciseToAdd'";
                    $getExIDPrep = $pdo->prepare($getExID);
                    $getExIDPrep->execute();
                    $exIDarr = $getExIDPrep->fetch(PDO::FETCH_ASSOC);
                    $exID = $exIDarr['exID'];
                    
                    //insert a row for the workoutID and exerciseID to workout_details table
                    $insertExercise = "INSERT INTO workout_details VALUES ($workoutID, $exID)";
                    $insertExercisePrep = $pdo->prepare($insertExercise);
                    $insertExercisePrep->execute();
                }
            }

        ?>

        
        
    </div>
</body>
</html>