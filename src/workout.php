<?php 
    session_start();
    require_once('config.php');
    
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
        header("location: login.php");
        exit;
    }

    function displayExerciseCountOptions() {
        for($i = 1; $i <= 10; $i++) {
            echo "<option";
            if($_POST['numExercises'] == $i) echo " selected";
            echo ">{$i}</option>";
        }
    }

    function displayWorkoutName() {
        if(isset($_POST['workoutName'])) echo "value=\"{$_POST['workoutName']}\"";
    }

    function displayWorkoutDate() {
        if(isset($_POST['workoutDate'])) echo "value=\"{$_POST['workoutDate']}\"";
    }

    function displayExerciseFields($pdo) {
        if(isset($_POST['workoutName'])) {
            $id = $_SESSION['id'];
            $sql = "SELECT * FROM exercises; SELECT workoutDate FROM workouts WHERE userID=$id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $allExercises = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $stmt->nextRowset();
            $dates = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach($dates as &$workoutDate) {
                if($workoutDate['workoutDate'] == $_POST['workoutDate']) {
                    echo "<div class=\"alert alert-danger\" role=\"alert\">
                    You already have a workout for this day.
                    </div>";
                    return;
                }    
            }
           

            
            $_SESSION['workoutName'] = $_POST['workoutName'];
            $_SESSION['workoutDate'] = $_POST['workoutDate'];
            $_SESSION['numExercises'] = $_POST['numExercises'];

            echo "<div class=\"card bg-light m-4\" style=\"width: 20rem;\">";

            for($i=1;$i<=$_POST['numExercises'];$i++) {
                echo "<form class=\"m-4\" action=\"/fitness-app/php-fitness-app/src/add-workout.php\" method=\"post\">
                <div class=\"form-group\">
                <label class=\"m-1\" for=\"exercise{$i}\">Exercise {$i}</label>
                <select class=\"form-control\" name=\"exercise{$i}\">";

                foreach($allExercises as &$exercise) {
                    echo "<option>{$exercise['name']}</option>";
                }

                echo "</select>
                </div>";
            }

            echo "<button type=\"submit\" class=\"btn btn-primary mb-2\" name=\"submitWorkout\">Submit Workout Plan!</button>";
            echo "</form>"; 
            echo "</div>";
        }
    }

    function submitWorkout($pdo) {
        if(isset($_POST['submitWorkout'])) {
            $workoutDate = $_SESSION['workoutDate'];
            $userID = $_SESSION['id'];
            $workoutName = $_SESSION['workoutName'];
            $numExercises = $_SESSION['numExercises'];
            
            $sql2 = "INSERT INTO workouts VALUES (null, '$workoutName', $numExercises, $userID, '$workoutDate')";
            $stmt2 = $pdo->prepare($sql2);
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

            echo "<div class=\"alert alert-success\" role=\"alert\">
            Added {$workoutName} on {$workoutDate} to your workouts!
            </div>";
        }
    }
?>