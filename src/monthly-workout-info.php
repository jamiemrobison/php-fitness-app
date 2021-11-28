<?php 
    require_once('config.php');

    function getMonthlyWorkouts($pdo) {

        session_start();

        if(isset($_POST['monthSelected']) && isset($_POST['yearSelected'])) {
            $monthNumber = null;
            $year = $_POST['yearSelected'];
            $id = $_SESSION['id'];
            $leapYear = 0;
            $dayCount = 0;

            if($year % 4 == 0) {
                $leapYear = 1;

                if($year % 100 == 0) { $leapYear = 0; }
                if($year % 400 == 0) { $leapYear = 1; }
            }


            switch($_POST['monthSelected']) {
                case "January":
                    $monthNumber = "01";
                    $dayCount = 31;
                    break;
                case "February":
                    $monthNumber = "02";
                    $leapYear ? $dayCount = 29 : $dayCount = 28;
                    break;
                case "March":
                    $monthNumber = "03";
                    $dayCount = 31;
                    break;
                case "April":
                    $monthNumber = "04";
                    $dayCount = 30;
                    break;
                case "May":
                    $monthNumber = "05";
                    $dayCount = 31;
                    break;
                case "June":
                    $monthNumber = "06";
                    $dayCount = 30;
                    break;
                case "July":
                    $monthNumber = "07";
                    $dayCount = 31;
                    break;
                case "August":
                    $monthNumber = "08";
                    $dayCount = 30;
                    break;
                case "September":
                    $monthNumber = "09";
                    $dayCount = 31;
                    break;
                case "October":
                    $monthNumber = "10";
                    $dayCount = 30;
                    break;
                case "November":
                    $monthNumber = "11";
                    $dayCount = 31;
                    break;
                case "December":
                    $monthNumber = "12";
                    $dayCount = 30;
                    break;
            }

            

            
            $sql = "SELECT * FROM workouts WHERE userID=$id AND workoutDate LIKE '$year-$monthNumber-%'";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $workouts = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $workoutDayMap = array_fill(0, $dayCount, null);
            
            $runningDay = date("Y-m-d", strtotime("{$year}-{$monthNumber}-01"));
            
            for($i = 0; $i < $dayCount; $i++) {
                foreach($workouts as &$workout) {
                    if($runningDay == $workout['workoutDate']) {
                        $workoutDayMap[$i] = $workout['workoutName'];
                    }
                }
                $runningDay = date('Y-m-d', strtotime($runningDay . " + 1 days"));
            }

            for($i = 0, $j = 1; $i < $dayCount; $i++, $j++) {
                $date = date("Y-m-d", strtotime("{$year}-{$monthNumber}-{$j}"));

                echo "<div class=\"card bg-light m-1\" style=\"width: 10rem; height: 8rem;\">
                <div class=\"card-header\">{$date}</div>
                <div class=\"card-body\">";
                if($workoutDayMap[$i] == null) {
                    echo "No Planned Workout";
                } else {
                    echo "<a class=\"text-info\" href=\"./view-workout.php?workout={$workoutDayMap[$i]}\">{$workoutDayMap[$i]}</a>";
                }
                echo "</div></div>";
            }

            
        }
    }
    
?>