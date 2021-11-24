<?php
    session_start();
    require_once('config.php');

    function getMaxLifts($pdo) {
        $sql = "SELECT MAX(weight) as maxWeight FROM completed_exercises NATURAL JOIN exercises NATURAL JOIN workouts WHERE name='Deadlift' AND userID={$_SESSION['id']}";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $maxDeadlift = $stmt->fetch(PDO::FETCH_ASSOC);
        $maxDeadlift = $maxDeadlift['maxWeight'];
        $deadliftPercent = $maxDeadlift / 10.0;
        

        $sql = "SELECT MAX(weight) as maxWeight FROM completed_exercises NATURAL JOIN exercises NATURAL JOIN workouts WHERE name='Flat BB Bench Press' AND userID={$_SESSION['id']}";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $maxBenchPress = $stmt->fetch(PDO::FETCH_ASSOC);
        $maxBenchPress = $maxBenchPress['maxWeight'];
        $benchPercent = $maxBenchPress / 10.0;
        

        $sql = "SELECT MAX(weight) as maxWeight FROM completed_exercises NATURAL JOIN exercises NATURAL JOIN workouts WHERE name='Back Squat' AND userID={$_SESSION['id']}";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $maxSquat = $stmt->fetch(PDO::FETCH_ASSOC);
        $maxSquat = $maxSquat['maxWeight'];
        $squatPercent = $maxSquat / 10.0;

        $total = $maxDeadlift + $maxBenchPress + $maxSquat;
        $diff1000 = 1000 - $total;
        
        echo "<div class=\"progress\" style=\"height: 20px;\">
        <div class=\"progress-bar\" role=\"progressbar\" style=\"width: {$deadliftPercent}%\" aria-valuenow=\"{$maxDeadlift}\" aria-valuemin=\"0\" aria-valuemax=\"1000\">Deadlift</div>
        <div class=\"progress-bar bg-success\" role=\"progressbar\" style=\"width: {$benchPercent}%\" aria-valuenow=\"{$maxBenchPress}\" aria-valuemin=\"0\" aria-valuemax=\"1000\">Bench Press</div>
        <div class=\"progress-bar bg-info\" role=\"progressbar\" style=\"width: {$squatPercent}%\" aria-valuenow=\"{$maxSquat}\" aria-valuemin=\"0\" aria-valuemax=\"1000\">Squat</div>
        </div>
        <ul class=\"list-group list-group-flush\">
        <li class=\"list-group-item\">Deadlift: {$maxDeadlift} lbs</li>
        <li class=\"list-group-item\">Bench Press: {$maxBenchPress} lbs</li>
        <li class=\"list-group-item\">Squat: {$maxSquat} lbs</li>
        <li class=\"list-group-item\">Total: {$total} lbs. Only {$diff1000} lbs to go!</li>
        </ul>";
    }

?>