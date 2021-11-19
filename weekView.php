<?php
    function displayWeekViewCards() {
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

        $runningDay = date('Y-m-d', strtotime($today));
        for($i=0;$i<count($daysOfWeekNextSevenDays);$i++) {
            foreach($nextSevenDaysWorkouts as &$daysWorkout) {
                if($runningDay == $daysWorkout['workoutDate']) {
                    $workoutDayMap[$i] = $daysWorkout['workoutName'];
                }
            }
            $runningDay = date('Y-m-d', strtotime($runningDay . " + 1 days"));
        }
        echo "<div class=\"row row-cols-7\">";
        for($i = 0; $i < 7; $i++) {
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
        }
        echo "</div>";
    }
    
?>