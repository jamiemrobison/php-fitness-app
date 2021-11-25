<?php 
    session_start();
    require_once('config.php');
    
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
        header("location: login.php");
        exit;
    }

    function displayUserWorkoutOptions($pdo) {
        if(!isset($_POST['getExerciseFields'])) {
            $getAllUserWorkoutsSQL = "SELECT workoutName, numExercises, workoutDate, workoutID FROM workouts WHERE userID = {$_SESSION['id']}";
            $getAllUserWorkoutsPrep = $pdo->prepare($getAllUserWorkoutsSQL);
            $getAllUserWorkoutsPrep->execute();
            $allUserWorkouts = $getAllUserWorkoutsPrep->fetchAll(PDO::FETCH_ASSOC);

            if(!empty($allUserWorkouts)) {
                for($i=0;$i<count($allUserWorkouts);$i++) {
                    echo "<option value=\"{$allUserWorkouts[$i]['workoutName']}\">{$allUserWorkouts[$i]['workoutDate']}\t{$allUserWorkouts[$i]['workoutName']}</option>";
                }
            }
        }
    }

    function generateWorkoutDetailsForm($pdo) {
        if(isset($_POST['getExerciseFields'])) {
            $workoutName = $_POST['workoutName'];
            $_SESSION['workoutName'] = $workoutName;
        

            $getAllExerciseNamesOfWorkoutSQL = "SELECT e.name, wd.exID FROM workout_details wd INNER JOIN exercises e ON wd.exID = e.exID INNER JOIN workouts w ON w.workoutID = wd.workoutID WHERE w.workoutName='$workoutName'";
            $getAllExerciseNamesOfWorkoutPrep = $pdo->prepare($getAllExerciseNamesOfWorkoutSQL);
            $getAllExerciseNamesOfWorkoutPrep->execute();
            $exerciseArray = $getAllExerciseNamesOfWorkoutPrep->fetchAll(PDO::FETCH_ASSOC);
            $_SESSION['exerciseArray'] = $exerciseArray;

            echo "<div class=\"card bg-light ml-4\" style=\"width: 22rem;\">";
            for($i=1; $i<=count($exerciseArray);$i++) {
                echo "<form action=\"/fitness-app/php-fitness-app/src/complete-workout.php\" method=\"post\">
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
                echo "</form></div>";
        
        }

        if(isset($_POST['submitSets'])) {
            $_SESSION['setsPerExercise'] = array();
            for($j=1;$j<count($_POST);$j++) {
                array_push($_SESSION['setsPerExercise'], $_POST["exercise{$j}sets"]);
            }
            echo "<div class=\"card bg-light m-4\" style=\"width: 22rem;\">";
            for($i=1;$i<=count($_SESSION['exerciseArray']);$i++) {
                echo "<form action=\"/fitness-app/php-fitness-app/src/complete-workout.php\" method=\"post\"><div class=\"form-group\">";
                for($j=1;$j<=$_POST["exercise{$i}sets"];$j++) {
                    echo "<div class=\"mb-3\"><label class=\"form-label\">"; echo $_SESSION['exerciseArray'][$i-1]['name']; echo " Set {$j} Reps"; echo "</label>";
                    echo "<input type=\"number\" class=\"form-control\" name=\""; echo "ex{$i}set{$j}reps"; echo"\" required></div>";
                    echo "<div class=\"mb-1\"><label class=\"form-label\">"; echo $_SESSION['exerciseArray'][$i-1]['name']; echo " Set {$j} Weight"; echo "</label>";
                    echo "<input type=\"number\" class=\"form-control\" name=\""; echo "ex{$i}set{$j}weight"; echo"\" required></div>";
                }
            }
            echo "</div><button type=\"submit\" class=\"btn btn-primary mb-2\" name=\"submitWeightAndReps\">Add Completed Workout to the Database</button></form></div>";
        }
    }
        
    function submitExerciseDetails($pdo) {
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

            $repsArray = array();
            $weightArray = array();
            for($i=0;$i<count($values)-1;$i+=2) {
                array_push($repsArray, $values[$i]);
            }

            for($x=1;$x<count($values);$x+=2) {
                array_push($weightArray, $values[$x]);
            }

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
                }
            }
            echo "<div class=\"alert alert-success ml-4\" role=\"alert\" style=\"width: 22rem;\">
            Workout successfully updated!
          </div>";
        }
    }
      
    function getWorkoutDetails($pdo) {
        if(isset($_POST['getWorkoutDetails'])) {
            $findWorkoutSQL = "SELECT * FROM completed_exercises NATURAL JOIN workouts WHERE workoutName='{$_POST['workoutName']}' AND userID={$_SESSION['id']}";
            $findWorkoutPrep = $pdo->prepare($findWorkoutSQL);
            $findWorkoutPrep->execute();
            
            if($findWorkoutPrep->rowCount() < 1) {
                //get the exercises for the workout and display them
                $sql = "SELECT name, muscleGroup FROM workout_details NATURAL JOIN exercises NATURAL JOIN workouts WHERE workoutName='{$_POST['workoutName']}' AND userID={$_SESSION['id']}";
                $stmt = $pdo->prepare($sql);
                $stmt->execute();
                $workout = $stmt->fetchAll(PDO::FETCH_ASSOC);

                echo "<div class=\"card-header\">Planned Exercises</div>";
                echo "<ul class=\"list-group list-group-flush\">";
                foreach ($workout as &$exercise) {
                    echo "<li class=\"list-group-item\">{$exercise['name']}</li>";
                }
                echo "</ul>";
            } else {
                //get the exercises, and the set data for each exercise, and display them.
                $sql = "SELECT name, muscleGroup, numSet, reps, weight FROM workout_details NATURAL JOIN exercises NATURAL JOIN workouts NATURAL JOIN completed_exercises WHERE workoutName='{$_POST['workoutName']}' AND userID={$_SESSION['id']}";
                $stmt = $pdo->prepare($sql);
                $stmt->execute();
                $workout = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                echo "<div class=\"card-header\">Details</div>";
                echo "<ul class=\"list-group list-group-flush\">";
                foreach ($workout as &$exercise) {
                    echo "<li class=\"list-group-item\">{$exercise['name']}\tSet: {$exercise['numSet']}\t|\t{$exercise['reps']} Reps at\t{$exercise['weight']} lbs</li>";
                }
                echo "</ul>";
            }
        }
    }
?>