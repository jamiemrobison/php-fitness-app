<?php 
    require_once('config.php');

    function displayExerciseCards($pdo) {
        session_start();
        
        
        // Check if the user is logged in, if not then redirect him to login page
        if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
            header("location: login.php");
            exit;
        }

        if(!isset($_POST['searchTerm'])) {

            $sql = "SELECT * FROM exercises";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $currentMuscle = $data[0]['muscleGroup'];

                echo "<div class=\"collapse\" id=\"{$currentMuscle}\">";
                echo "<div class=\"row\">";
            for($i = 0; $i < count($data); $i++) {
                if($currentMuscle != $data[$i]['muscleGroup']) {
                    $currentMuscle = $data[$i]['muscleGroup'];
                    echo "<hr style=\"width:100%\", size=\"6\", color=black>";
                    echo "</div></div>";
                    echo "<div class=\"collapse\" id=\"{$currentMuscle}\">";
                    echo "<div class=\"row\">";
                }
                
                echo "<div class=\"card m-1\" style=\"width: 15rem; height: 20rem;\">
                <img src=\"..\\muscleGroupImages\\dumbbell.jpg\" class=\"card-img-top\" alt=\"...\">
                <div class=\"card-body\">
                <h5 class=\"card-title\">{$data[$i]['name']}</h5>
                <p class=\"card-text\">Required Equipment: {$data[$i]['equipment']}</p>
                <a href=\"{$data[$i]['videoURL']}\" class=\"btn btn-primary\" target=\"_blank\">YouTube</a>
                </div>
                </div>";
            
            }
            echo '</div>';
        }

    }

    function searchExercises($pdo) {
        if(isset($_POST['searchTerm'])) {
            $searchTerm = trim($_POST['searchTerm']);
            $sql = "SELECT name, muscleGroup, equipment, videoURL FROM exercises WHERE name LIKE '%{$searchTerm}%' OR muscleGroup LIKE '%{$searchTerm}%' OR equipment LIKE '%{$searchTerm}%'";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $exercises = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if(count($exercises) < 1) {
                echo "<div class=\"alert alert-warning\" role=\"alert\">
                No exercises found.
                </div>";
            }

            echo "<div class=\"row\">";
            for($i = 0; $i < count($exercises); $i++) {
                echo "<div class=\"card m-1\" style=\"width: 15rem; height: 20rem;\">
                <img src=\"..\\muscleGroupImages\\dumbbell.jpg\" class=\"card-img-top\" alt=\"...\">
                <div class=\"card-body\">
                <h5 class=\"card-title\">{$exercises[$i]['name']}</h5>
                <p class=\"card-text\">Required Equipment: {$exercises[$i]['equipment']}</p>
                <a href=\"{$exercises[$i]['videoURL']}\" class=\"btn btn-primary\" target=\"_blank\">YouTube</a>
                </div>
                </div>";
            }
            echo "</div>";
        }
    }
?>