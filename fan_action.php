<!-- Project: LiveVibe
Author: Xun Gong, Wei Yu
Date: Dec 4th, 2014 -->

<!-- PHP and manipulate with livevibe database -->
<?php
// Session start in connectdb.php file
require ("connectdb.php");

if (isset($_POST["follow"]) && isset($_SESSION["artistname"]) && isset($_SESSION["username"])) {
    
    // Add follow relationship to table follow
    // Set local vars
    $username = $_SESSION["username"];
    $artistname = $_SESSION["artistname"];

    // Execute query
    $stmtFAN = $mysqli->prepare("INSERT INTO fans SET username = ?, artistname = ?, fan_time = NOW()");
    $stmtFAN->bind_param('ss', $username, $artistname);
    $stmtFAN->execute();
    $mysqli->next_result();
    $back_url = "http://localhost:8888/livevibe/artist_public.php?link=".$artistname;
    unset($_SESSION["artistname"]);
    redirect($back_url);
    exit();
}
?>
