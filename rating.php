<!-- Project: LiveVibe
Author: Xun Gong, Wei Yu
Date: Dec 4th, 2014 -->

<!-- PHP and manipulate with livevibe database -->
<?php
// Session start in connectdb.php file
require ("connectdb.php");

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST["score"])) {
    $rating_usr = $_POST["score"];
    $usr = $_SESSION["username"];
    $cid_in = $_SESSION["go_to_cid"];

    // Check if Concert Already started: cannot rating before start_time
    $time = date("Y-m-d H:i:s");
    $stmt2 = $mysqli->prepare("SELECT start_time FROM concerts WHERE cid = ?");
    $stmt2->bind_param("s", $cid_in);
    $stmt2->execute();
    $stmt2->bind_result($start_time);
    
    while ($stmt2->fetch()) {
       if ($start_time > $time) {
        echo "<script>alert(\"You Can not Rating A Future Concert\")</script>";
        $url = "http://localhost:8888/livevibe/concert_info.php?link=".$cid_in;
        redirect($url);
        exit(); 
        }
    }

    $mysqli->next_result();

    // Check if Plan to: rating must after plan to
    $stmt1 = $mysqli->prepare("SELECT * FROM attendance WHERE username = ? AND cid = ?");
    $stmt1->bind_param("ss", $usr, $cid_in);
    $stmt1->execute();
    $stmt1->store_result();
    if (!$stmt1->num_rows) {
        echo "<script>alert(\"You Didn't Plan to go, cannot rating.\")</script>";
        $url = "http://localhost:8888/livevibe/concert_info.php?link=".$cid_in;
        redirect($url);
        exit();  
    }

    $mysqli->next_result();
    // Update Rating
    $stmtRating = $mysqli->prepare("UPDATE attendance SET rating = ? WHERE username = ? AND cid = ?");
    $stmtRating->bind_param("iss", $rating_usr, $usr, $cid_in);
    $stmtRating->execute();

    $url = "http://localhost:8888/livevibe/concert_info.php?link=".$cid_in;
    redirect($url);
    exit();
}
else {
    $url = "http://localhost:8888/livevibe/concert_info.php?link=".$_SESSION["go_to_cid"];
    redirect($url);
    exit();
}

?>

