<?php
    session_start();
    include('./user-info.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; text-align: center; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="welcome.php">IronWorks</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
        <li class="nav-item">
            <a class="nav-link" href="welcome.php">Home</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="exercise-library.php">Library</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="add-workout.php">New Workout</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="complete-workout.php">Complete Workout</a>
        </li>
        <li class="nav-item active">
            <a class="nav-link" href="user-settings.php">Settings <span class="sr-only">(current)</span></a>
        </li>
        <li class="nav-item">
            <a href="logout.php" class="btn btn-secondary">Log Out</a>
        </li>
        </ul>
    </div>
    </nav>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 mb-4 mr-4 mt-4">
                <div class="card" style="width: 18rem;">
                    <img class="card-img-top" src="../muscleGroupImages/blank-profile-picture.png" alt="profile picture">
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <?php displayUserInfo(); ?>
                            <li class="list-group-item"><a href="./reset-password.php" class="btn btn-warning">Reset Your Password</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 mb-4 mr-4 mt-4">
                <div class="card" style="width: 18rem;">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group m-4">
                            <label for="newUsername">Update Username</label>
                            <input type="text" class="form-control" name="newUsername" placeholder="Enter a new username">
                        </div>
                        <div class="form-group m-4">
                            <label for="newFname">Update First Name</label>
                            <input type="text" class="form-control" name="newFname" placeholder="Enter a new first name">
                        </div>
                        <div class="form-group m-4">
                            <label for="newLname">Update Last Name</label>
                            <input type="text" class="form-control" name="newLname" placeholder="Enter a new last name">
                        </div>
                        <div class="form-group m-4">
                            <label for="newHeight">Update Height</label>
                            <input type="number" class="form-control" name="newHeight" placeholder="Enter a new height in inches">
                        </div>
                        <div class="form-group m-4">
                            <label for="newWeight">Update Weight</label>
                            <input type="number" class="form-control" name="newWeight" placeholder="Enter a new weight in lbs">
                        </div>
                        <button type="submit" class="btn btn-primary mb-2" name="submitUserInfo">Update Info</button>
                    </form>
                    <?php updateUserInfo(); ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>