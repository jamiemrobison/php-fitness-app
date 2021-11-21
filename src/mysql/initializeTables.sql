SET foreign_key_checks = 0;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS workouts;
DROP TABLE IF EXISTS exercises;
DROP TABLE IF EXISTS workout_details;
DROP TABLE IF EXISTS completed_exercises;
SET foreign_key_checks = 1;

CREATE TABLE users(
    userID INT NOT NULL AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    fname VARCHAR(50),
    lname VARCHAR(50),
    height INT,
    weight INT,
    PRIMARY KEY (userID)
);

CREATE TABLE workouts(
    workoutID INT NOT NULL AUTO_INCREMENT,
    workoutName VARCHAR(50) NOT NULL,
    numExercises INT NOT NULL,
    userID INT NOT NULL,
    workoutDate DATE NOT NULL,
    PRIMARY KEY (workoutID),
    FOREIGN KEY (userID) REFERENCES users(userID)
);

CREATE TABLE exercises(
    exID INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(50),
    muscleGroup VARCHAR(20),
    equipment VARCHAR(100),
    videoURL VARCHAR(255),
    imagePath VARCHAR(255),
    PRIMARY KEY (exID)
);

CREATE TABLE workout_details(
    workoutID INT NOT NULL,
    exID INT NOT NULL,
    FOREIGN KEY (workoutID) REFERENCES workouts(workoutID),
    FOREIGN KEY (exID) REFERENCES exercises(exID),
    PRIMARY KEY (workoutID, exID)
);

CREATE TABLE completed_exercises(
    workoutID INT NOT NULL,
    exID INT NOT NULL,
    numSet INT,
    reps INT,
    weight INT,
    FOREIGN KEY (workoutID) REFERENCES workouts(workoutID),
    FOREIGN KEY (exID) REFERENCES exercises(exID),
    PRIMARY KEY (workoutID, exID, numSet)
);

/*********************
* CHEST EXERCISES
*********************/
INSERT INTO exercises VALUES (null, "Flat BB Bench Press", "Chest", "Barbell, Flat Bench Press", "https://www.youtube.com/watch?v=rT7DgCr-3pg", "muscleGroupImages\\Muscle_Group_Chest.jpg");
INSERT INTO exercises VALUES (null, "Incline BB Bench Press", "Chest", "Barbell, Incline Bench Press", "https://www.youtube.com/watch?v=jPLdzuHckI8", null);
INSERT INTO exercises VALUES (null, "Decline BB Bench Press", "Chest", "Barbell, Decline Bench Press", "https://www.youtube.com/watch?v=NM5lbuq92Aw", null);
INSERT INTO exercises VALUES (null, "Flat DB Bench Press", "Chest", "Dumbells, Flat Bench", "https://www.youtube.com/watch?v=ZzFblmTUxYU", null);
INSERT INTO exercises VALUES (null, "Incline DB Bench Press", "Chest", "Dumbells, Incline Bench", "https://www.youtube.com/watch?v=hChjZQhX1Ls", null);
INSERT INTO exercises VALUES (null, "Decline DB Bench Press", "Chest", "Dumbells, Decline Bench", "https://www.youtube.com/watch?v=Pf1nDoqx_1A", null);
INSERT INTO exercises VALUES (null, "Flat DB Fly", "Chest", "Dumbells, Flat Bench", "https://www.youtube.com/watch?v=MxfVDZrsdZ0", null);
INSERT INTO exercises VALUES (null, "Incline DB Fly", "Chest", "Dumbells, Incline Bench", "https://www.youtube.com/watch?v=ajdFwa-qM98", null);
INSERT INTO exercises VALUES (null, "Decline DB Fly", "Chest", "Dumbells, Decline Bench", "https://www.youtube.com/watch?v=IMALXhhHRKM", null);
INSERT INTO exercises VALUES (null, "Cable Fly", "Chest", "Dual Cable Machine", "https://www.youtube.com/watch?v=WEM9FCIPlxQ", null);

/*********************
* BACK EXERCISES
*********************/
INSERT INTO exercises VALUES (null, "Bent Over BB Row", "Back", "Barbell", "https://www.youtube.com/watch?v=axoeDmW0oAY&t=255s", null);
INSERT INTO exercises VALUES (null, "Bent Over DB Row", "Back", "Dumbells, Flat Bench", "https://www.youtube.com/watch?v=djKXLt7kv7Q", null);
INSERT INTO exercises VALUES (null, "Seated Cable Row", "Back", "Cable Machine, Grip Attachment", "https://www.youtube.com/watch?v=4mRy8U542Fo", null);
INSERT INTO exercises VALUES (null, "Lat Pull Down (Wide)", "Back", "Lat Pull Down Machine, Wide Bar Attachment", "https://www.youtube.com/watch?v=lueEJGjTuPQ", null);
INSERT INTO exercises VALUES (null, "Lat Pull Down (Close)", "Back", "Lat Pull Down Machine, V-Grip Attachment", "https://www.youtube.com/watch?v=ecRF8ERf2q4", null);
INSERT INTO exercises VALUES (null, "Cable Lat Push Down", "Back", "Single Cable, Flat Bar Attachment", "https://www.youtube.com/watch?v=aXTKdnCIyWA", null);
INSERT INTO exercises VALUES (null, "Deadlift", "Back", "Barbell", "https://www.youtube.com/watch?v=VL5Ab0T07e4", null);

/*********************
* TRICEP EXERCISES
*********************/
INSERT INTO exercises VALUES (null, "DB Kickback", "Triceps", "Dumbells", "https://www.youtube.com/watch?v=m9me06UBPKc", null);
INSERT INTO exercises VALUES (null, "Skull Crusher", "Triceps", "EZ-Curl Bar, Flat Bench", "https://www.youtube.com/watch?v=NIWKqcmpBug", null);
INSERT INTO exercises VALUES (null, "Cable Pushdown", "Triceps", "Single Cable Machine, Bar Attachment", "https://www.youtube.com/watch?v=8CbJK7mmisE", null);
INSERT INTO exercises VALUES (null, "Close Grip BB Bench Press", "Triceps", "Flat Bench Press, Barbell", "https://www.youtube.com/watch?v=cXbSJHtjrQQ", null);
INSERT INTO exercises VALUES (null, "Dips", "Triceps", "Dip Machine/Two Benches", "https://www.youtube.com/watch?v=6MwtkyNC2ZY", null);
INSERT INTO exercises VALUES (null, "Overhead Extensions", "Triceps", "Dumbell", "https://www.youtube.com/watch?v=-Vyt2QdsR7E", null);
INSERT INTO exercises VALUES (null, "One Arm Overhead Extensions", "Triceps", "Dumbell", "https://www.youtube.com/watch?v=_gsUck-7M74", null);

/*********************
* BICEP EXERCISES
*********************/
INSERT INTO exercises VALUES (null, "Alternating DB Curl", "Biceps", "Dumbells", "https://www.youtube.com/watch?v=pzBgFa7cZ_g", null);
INSERT INTO exercises VALUES (null, "BB Curl", "Biceps", "Barbell", "https://www.youtube.com/watch?v=dDI8ClxRS04", null);
INSERT INTO exercises VALUES (null, "Seated Incline DB Curl", "Biceps", "Inclined Bench, Dumbells", "https://www.youtube.com/watch?v=t-gztaNpemg", null);
INSERT INTO exercises VALUES (null, "Hammer Curl", "Biceps", "Dumbells", "https://www.youtube.com/watch?v=0IAM2YtviQY", null);
INSERT INTO exercises VALUES (null, "Cable Curl", "Biceps", "Single Cable Machine, Bar Attachment", "https://www.youtube.com/watch?v=840rgLSw-84", null);
INSERT INTO exercises VALUES (null, "BB Preacher Curl", "Biceps", "Barbell, Preacher Curl Bench", "https://www.youtube.com/watch?v=Gydpcouclx8", null);
INSERT INTO exercises VALUES (null, "DB Preacher Curl", "Biceps", "Dumbell, Preacher Curl Bench", "https://www.youtube.com/watch?v=vngli9UR6Hw", null);

/*********************
* LEG EXERCISES
*********************/
INSERT INTO exercises VALUES (null, "Back Squat", "Legs", "Barbell, Squat Rack", "https://www.youtube.com/watch?v=bEv6CCg2BC8", null);
INSERT INTO exercises VALUES (null, "Front Squat", "Legs", "Barbell, Squat Rack", "https://www.youtube.com/watch?v=v-mQm_droHg", null);
INSERT INTO exercises VALUES (null, "Quad Extensions", "Legs", "Quad Extension Machine", "https://www.youtube.com/watch?v=ljO4jkwv8wQ", null);
INSERT INTO exercises VALUES (null, "Leg Press", "Legs", "Leg Press Machine", "https://www.youtube.com/watch?v=W1SD96lrudY", null);
INSERT INTO exercises VALUES (null, "Hamstring Extensions", "Legs", "Hamstring Extension Machine", "https://www.youtube.com/watch?v=Mv_8-XwVMFQ", null);
INSERT INTO exercises VALUES (null, "BB Lunges", "Legs", "Barbell", "https://www.youtube.com/watch?v=ci4rsmlOk24", null);
INSERT INTO exercises VALUES (null, "DB Lunges", "Legs", "Dumbells", "https://www.youtube.com/watch?v=auyE2hZGB9k", null);
INSERT INTO exercises VALUES (null, "BB Calf Raise", "Legs", "Barbell, Platform", "https://www.youtube.com/watch?v=BIRC4Qj7tsw", null);
INSERT INTO exercises VALUES (null, "DB Calf Raise", "Legs", "Dumbells, Platform", "https://www.youtube.com/watch?v=wxwY7GXxL4k", null);

/*********************
* SHOULDER EXERCISES
*********************/
INSERT INTO exercises VALUES (null, "Seated DB Shoulder Press", "Shoulders", "Back-Supporting Bench, Dumbells", "https://www.youtube.com/watch?v=lfb3ffbrd4Q", null);
INSERT INTO exercises VALUES (null, "Arnolds", "Shoulders", "Back-Suppoirting Bench, Dumbells", "https://www.youtube.com/watch?v=6Z15_WdXmVw", null);
INSERT INTO exercises VALUES (null, "Lateral Raises", "Shoulders", "Dumbells", "https://www.youtube.com/watch?v=b_LEX4n9lOs", null);
INSERT INTO exercises VALUES (null, "DB Front Delt Raises", "Shoulders", "Dumbells", "https://www.youtube.com/watch?v=ALxDJEStRCA", null);
INSERT INTO exercises VALUES (null, "EZ-Curl Front Delt Raises", "Shoulders", "EZ-Curl Bar", "https://www.youtube.com/watch?v=sxeY7kMYhLc", null);
INSERT INTO exercises VALUES (null, "Bent Over Rear Delt Fly", "Shoulders", "Dumbells", "https://www.youtube.com/watch?v=EA7u4Q_8HQ0", null);
INSERT INTO exercises VALUES (null, "Rear Delt Cable Fly", "Shoulders", "Dual Cable Machines, Hand Grip Attachment", "https://www.youtube.com/watch?v=JENKmsEZQO8", null);
INSERT INTO exercises VALUES (null, "Seated BB Military Press", "Shoulders", "Back-Supporting Bench, Barbell", "https://www.youtube.com/watch?v=uiYOYMDiA44", null);
INSERT INTO exercises VALUES (null, "Standing BB Military Press", "Shoulders", "Barbell", "https://www.youtube.com/watch?v=_RlRDWO2jfg", null);

COMMIT;
