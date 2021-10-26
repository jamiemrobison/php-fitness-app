<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Exercises</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<?php 
session_start();
require_once('config.php');
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}


$sql = "SELECT * FROM exercises";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo '<div class="grid-container">';

$currentMuscle = $data[0]['muscleGroup'];
    echo '<div class="grid-item-mg">';
    echo $currentMuscle;
    echo '</div>';
for($i = 0; $i < count($data); $i++) {
    if($currentMuscle != $data[$i]['muscleGroup']) {
        $currentMuscle = $data[$i]['muscleGroup'];
        echo '<div class="grid-item-mg">';
        echo $currentMuscle;
        echo '</div>';
    }
    echo '<div class="grid-item">';
    echo $data[$i]['name'];
    echo '</div>';
}
//print_r($data);
?>
</body>
</html>