<!-- Project: LiveVibe
Author: Xun Gong, Wei Yu
Date: Dec 4th, 2014 -->

<!-- PHP and manipulate with livevibe database -->
<?php
// Session start in connectdb.php file
require ("connectdb.php");

if ($_SERVER['REQUEST_METHOD'] == "POST" && !empty($_POST["date_time"]) && !empty($_POST["con_link"]) && isset($_POST["venue"])) {
    $poster = $_SESSION["username"];
    $type = $_SESSION["login_type"];
    $date_time = $_POST["date_time"];
    $con_link = $_POST["con_link"];
    $venue = $_POST["venue"];
    $venue = strtok($venue, ',');

    // Generate a unique cid
    $gen_cid = 5500100000 + rand(1000, 9999);
    $gen_cid = (string)$gen_cid;

    if ($type == "artist") {
        // Post Into Concert and Update anew table
        $aname = $poster;
        $stmtArtP = $mysqli->prepare("CALL art_Post(?, ?, ?, ?, ?)");
        $stmtArtP->bind_param("sssss", $gen_cid, $venue, $aname, $date_time, $con_link);
        $stmtArtP->execute();
        $mysqli->next_result();
        echo "<script>alert(\"Successfully Posted!\")</script>";
        redirect("http://localhost:8888/livevibe/artist_profile.php");
        exit();
    }

    if ($type == "user") {
        // Post Into Concert and Update unew table
        $aname = $_POST["artistname"];
        $stmtUsrP = $mysqli->prepare("CALL user_Post(?, ?, ?, ?, ?, ?)");
        $stmtUsrP->bind_param("ssssss", $gen_cid, $venue, $aname, $date_time, $con_link, $poster);
        $stmtUsrP->execute();
        $mysqli->next_result();
        echo "<script>alert(\"Successfully Posted!\")</script>";
        redirect("http://localhost:8888/livevibe/user_profile.php");
        exit();
    }
}
else {
    echo "<script>alert(\"Post Form Not Fully Filled!\")</script>";
    $url = "http://localhost:8888/livevibe/index.php";
    redirect($url);
    exit();
}

?>