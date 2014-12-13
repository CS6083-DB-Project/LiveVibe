<!-- Project: LiveVibe
Author: Xun Gong, Wei Yu
Date: Dec 4th, 2014 -->

<!-- PHP and manipulate with livevibe database -->
<?php
// Session start in connectdb.php file
require ("connectdb.php");
$_SESSION["username"] = $_POST["username"];
$_SESSION["login_type"] = NULL;
if ($_POST["type"] == "user") {
    $_SESSION["login_type"] = "user";
}
if ($_POST["type"] == 'artist') {
    $_SESSION["login_type"] = "artist";
}
// Set to local var
$uname   =    $_SESSION["username"];
$type    =    $_SESSION["login_type"];
$pwd     =    $_POST["password"];
$real    =    $_POST["realname"];
$birth   =    $_POST["birth"];
$city    =    $_POST["city"];
$state   =    $_POST["state"];
$zipcode =    $_POST["zipcode"];
$bio     =    $_POST["bio"];
$reg_time =   date("Y-m-d H:i:s");
// Update Database
if (!empty($_POST["username"]) && !empty($_POST["password"])) {
    if ($type == 'user') {
        $stmtRegUsr = $mysqli->prepare("CALL uprofile_insert(?,?,?,?,?,?,?,?)");
        $stmtRegUsr->bind_param("ssssssss", $uname, $pwd, $real, $birth, $city, $state, $zipcode, $reg_time);
        $stmtRegUsr->execute();
    }
    $mysqli->next_result();
    if ($type == 'artist') {
        $stmtRegArt = $mysqli->prepare("CALL artist_insert(?,?,?,?)");
        $stmtRegArt->bind_param("ssss", $uname, $pwd, $bio, $reg_time);
        $stmtRegArt->execute();
    }
    // $mysqli->next_result();
}
?>