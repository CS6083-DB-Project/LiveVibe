<!-- Project: LiveVibe
Author: Xun Gong, Wei Yu
Date: Dec 4th, 2014 -->

<!-- PHP and manipulate with livevibe database -->
<?php
// Session start in connectdb.php file
require ("connectdb.php");

if ($_SERVER['REQUEST_METHOD'] == "POST" && !empty($_POST["listname"]) && isset($_POST["concerts"])) {
    $username_in = $_SESSION["username"];
    $listname_in = $_POST["listname"];
    $concert_in = $_POST["concerts"];
    $cid_in = strtok($concert_in, ',');

    // Add to recommend table
    $stmtRecom = $mysqli->prepare("INSERT INTO recommend SET username = ?, cid = ?, listname = ?, rm_time = NOW()");
    $stmtRecom->bind_param("sss", $username_in, $cid_in, $listname_in);
    $stmtRecom->execute();
    $mysqli->next_result();

    echo "<script>alert(\"Successfully Posted!\")</script>";
    redirect("http://localhost:8888/livevibe/user_profile.php");
    exit();
}
else {
    echo "<script>alert(\"Recommend Form Not Fully Filled!\")</script>";
    $url = "http://localhost:8888/livevibe/user_profile.php";
    redirect($url);
    exit();
}

?>