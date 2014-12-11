<!-- Project: LiveVibe
Author: Xun Gong, Wei Yu
Date: Dec 4th, 2014 -->

<!-- PHP and manipulate with livevibe database -->
<?php
// Session start in connectdb.php file
require ("connectdb.php");

if (isset($_SESSION["username"]) && isset($_POST["review"]) && !empty($_POST["review"])) {
    // Set Local Var
    $rvw = $_POST["review"];
    $usr = $_SESSION["username"];
    $cid_in = $_SESSION["go_to_cid"];

    // Check if Concert Already started: cannot review before start_time
    $time = date("Y-m-d H:i:s");
    $stmt1 = $mysqli->prepare("SELECT start_time FROM concerts WHERE cid = ?");
    $stmt1->bind_param("s", $cid_in);
    $stmt1->execute();
    $stmt1->bind_result($start_time);

    while ($stmt1->fetch()) {
       if ($start_time > $time) {
        echo "<script>alert(\"You Can not Review A Future Concert\")</script>";
        $url = "http://localhost:8888/livevibe/concert_info.php?link=".$cid_in;
        redirect($url);
        exit();
        }
    }

    $mysqli->next_result();

    // Check if Did Plan to
    $stmt3 = $mysqli->prepare("SELECT * FROM attendance WHERE username = ? AND cid = ?");
    $stmt3->bind_param("ss", $usr, $cid_in);
    $stmt3->execute();
    $stmt3->store_result();
    if (!$stmt3->num_rows) {
        echo "<script>alert(\"You Didn't Plan to go, cannot review.\")</script>";
        $url = "http://localhost:8888/livevibe/concert_info.php?link=".$cid_in;
        redirect($url);
        exit();  
    }

    // Legal operation
    $stmtRVW = $mysqli->prepare("UPDATE attendance SET review = ?, rv_time = NOW() WHERE username = ? AND cid = ?");
    $stmtRVW->bind_param("sss", $rvw, $usr, $cid_in);

    $stmtRVW->execute();

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