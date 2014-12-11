<!-- Project: LiveVibe
Author: Xun Gong, Wei Yu
Date: Dec 4th, 2014 -->

<!-- PHP and manipulate with livevibe database -->
<?php
// Session start in connectdb.php file
require ("connectdb.php");

if (isset($_SESSION["username"]) && isset($_POST["comment"]) && !empty($_POST["comment"])) {
    // Set Local Var
    $cmt = $_POST["comment"];
    $usr = $_SESSION["username"];
    $cid_in = $_SESSION["go_to_cid"];

    $stmtCmt = $mysqli->prepare("INSERT INTO ucomments SET username = ?, cid = ?, content = ?, c_time = NOW()");
    $stmtCmt->bind_param("sss", $usr, $cid_in, $cmt);
    $stmtCmt->execute();

    $mysqli->next_result();

    $url = "http://localhost:8888/livevibe/concert_info.php?link=".$cid_in;
    redirect($url);
    exit();
}
else {
    echo "<script>alert(\"Empty Input.\")</script>";
    $url = "http://localhost:8888/livevibe/concert_info.php?link=".$_SESSION["go_to_cid"];
    redirect($url);
    exit();
}
?>