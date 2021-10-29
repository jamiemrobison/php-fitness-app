<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Exercises</title>
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
<div class="wrapper">
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



$currentMuscle = $data[0]['muscleGroup'];
    echo '<h1><span class="badge badge-secondary">';
    echo $currentMuscle;
    echo '</span></h1>';
for($i = 0; $i < count($data); $i++) {
    if($currentMuscle != $data[$i]['muscleGroup']) {
        $currentMuscle = $data[$i]['muscleGroup'];
        echo '<h1><span class="badge badge-secondary">';
        echo $currentMuscle;
        echo '</span></h1>';
    }
    //echo '<div class="grid-item">';
    //echo $data[$i]['name'];
    echo "<div class=\"card\" style=\"width: 18rem;\">
    <img src=\"...\" class=\"card-img-top\" alt=\"...\">
    <div class=\"card-body\">
      <h5 class=\"card-title\">{$data[$i]['name']}</h5>
      <p class=\"card-text\">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
      <a href=\"#\" class=\"btn btn-primary\">Youtube</a>
    </div>
  </div>";
    
}
//print_r($data);
?>

</div>
</body>
</html>