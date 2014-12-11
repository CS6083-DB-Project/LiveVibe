<!-- Project: LiveVibe
Author: Xun Gong, Wei Yu
Date: Dec 4th, 2014 -->

<!-- PHP and manipulate with livevibe database -->
<?php
// Session start in connectdb.php file
require ("connectdb.php");
// Only Logged in and select made can add genre
if (isset($_SESSION["username"]) && isset($_POST["genre"])) {
    // Set to local var
    $type = $_SESSION["login_type"];
    $uname = $_SESSION["username"];
    $genre = $_POST["genre"];

    // Execute
    $stmtAG = $mysqli->prepare("CALL add_genre(?, ?, ?)");
    $stmtAG->bind_param('sss', $uname, $type, $genre);
    $stmtAG->execute();

    $mysqli->next_result();

    if ($type == "user") {
        redirect('http://localhost:8888/livevibe/user_profile.php');
        exit();
    }

    if ($type == "artist") {
        redirect('http://localhost:8888/livevibe/artist_profile.php');
        exit();
    } 
}

?>