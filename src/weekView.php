<?php
    require_once('config.php');

    function displayWeekViewCards($pdo) {
        session_start();
        require_once('config.php');
 
        // Check if the user is logged in, if not then redirect him to login page
        if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
            header("location: login.php");
            exit;
        }

        //Get the current day and the day that is 7 days after the current day.
        $today = date('Y-m-d');
        $weekAfterToday = date('Y-m-d', strtotime($today . ' + 7 days'));

        $daysOfWeekNextSevenDays = array();

        //Get each day between today and 7 days from today.
        for($i = 0; $i < 7; $i++) {
            if($i == 0) {
                $day = date('Y-m-d');
            } else {
                $day = date('Y-m-d', strtotime($today . " + {$i} days"));
            }
            array_push($daysOfWeekNextSevenDays, $day);
        }

        //Get all workouts for the current user between today and 7 days from now.
        $sql = "SELECT w.workoutID, w.workoutName, w.workoutDate  FROM workouts w INNER JOIN users u ON w.userID = u.userID WHERE username = ? 
        AND workoutDate >= ? AND workoutDate <= ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$_SESSION["username"], $today, $weekAfterToday]);

        $nextSevenDaysWorkouts = array();

        //If we get no results, set all indexs in the workout array to null.
        if($stmt->rowCount() < 1) {
            $$nextSevenDaysWorkouts = array_fill(0, 7, null);
        }

        //If there are workouts, store them in the workout array
        if($stmt->rowCount() > 0) {
            $nextSevenDaysWorkouts = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        $workoutDayMap = array_fill(0, 7, null);

        //Iterate through days starting with today and ending with 7 days from today. If there is a workout that matches the day, map it to the workoutDayMap array.
        $runningDay = date('Y-m-d', strtotime($today));
        for($i=0;$i<count($daysOfWeekNextSevenDays);$i++) {
            foreach($nextSevenDaysWorkouts as &$daysWorkout) {
                if($runningDay == $daysWorkout['workoutDate']) {
                    $workoutDayMap[$i] = $daysWorkout['workoutName'];
                }
            }
            //Add 1 day to the current day.
            $runningDay = date('Y-m-d', strtotime($runningDay . " + 1 days"));
        }

        //Display a card with the date and the name of the workout for that day. If no workout display a message stating no workout.
        echo "<div class=\"row row-cols-7\">";
        for($i = 0; $i < 7; $i++) {
            echo "<div class=\"col-sm\">
                <div class=\"card bg-light\" style=\"width: 100%; height: 10rem;\">
                <div class=\"card-header\">{$daysOfWeekNextSevenDays[$i]}</div>
                <div class=\"card-body\">";
                if($workoutDayMap[$i] == null) {
                    echo "No Planned Workout";
                } else {
                    echo "<a class=\"text-info\" href=\"./view-workout.php?workout={$workoutDayMap[$i]}\">{$workoutDayMap[$i]}</a>";
                }
                echo "</div>
            </div>
            </div>";
        }
        echo "</div>";
    }
    
?>