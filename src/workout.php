<?php 
    session_start();
    
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

    function displayExerciseFields() {
        if(isset($_POST['workoutName'])) {
            // echo "<div>displayFields gets called</div>";
            require_once('config.php');
            $sql = "SELECT * FROM exercises";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $allExercises = $stmt->fetchAll(PDO::FETCH_ASSOC);

            
            $_SESSION['workoutName'] = $_POST['workoutName'];
            $_SESSION['workoutDate'] = $_POST['workoutDate'];
            $_SESSION['numExercises'] = $_POST['numExercises'];


            for($i=1;$i<=$_POST['numExercises'];$i++) {
                echo "<form action=\"/fitness-app/php-fitness-app/src/add-workout.php\" method=\"post\">
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
    }

    function submitWorkout() {
        if(isset($_POST['submitWorkout'])) {
            require_once('config.php');

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
        }
    }
?>