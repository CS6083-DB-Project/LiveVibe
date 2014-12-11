<!-- Project: LiveVibe
Author: Xun Gong, Wei Yu
Date: Dec 4th, 2014 -->

<!-- PHP and manipulate with livevibe database -->
<?php
// Session start in connectdb.php file
require ("connectdb.php");

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    $usr = $_SESSION["username"];
    $cid_in = $_SESSION["go_to_cid"];

    // Check if Concert already started
    $time = date("Y-m-d H:i:s");
    $stmt1 = $mysqli->prepare("SELECT start_time FROM concerts WHERE cid = ?");
    $stmt1->bind_param("s", $cid_in);
    $stmt1->execute();
    $stmt1->bind_result($start_time);

    while ($stmt1->fetch()) {
       if ($start_time < $time) {
        echo "<script>alert(\"Concert already Started. Can not Plan to go.\")</script>";
        $url = "http://localhost:8888/livevibe/concert_info.php?link=".$cid_in;
        redirect($url);
        exit();
        }
    }
    
    // Plan to
    $stmtPl = $mysqli->prepare("INSERT INTO attendance SET username = ?, cid = ?, rating = NULL, review = NULL, rv_time = NULL");
    $stmtPl->bind_param("ss", $usr, $cid_in);
    $stmtPl->execute();
    $mysqli->next_result();

    $url = "http://localhost:8888/livevibe/concert_info.php?link=".$cid_in;
    redirect($url);
    exit();
}

?>
