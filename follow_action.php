
<!-- Project: LiveVibe
Author: Xun Gong, Wei Yu
Date: Dec 4th, 2014 -->

<!-- PHP and manipulate with livevibe database -->
<?php
// Session start in connectdb.php file
require ("connectdb.php");

if (isset($_POST["follow"]) && isset($_SESSION["username_public"]) && isset($_SESSION["username"])) {
    
    // Add follow relationship to table follow
    // Set local vars
    $from_uname = $_SESSION["username"];
    $to_uname = $_SESSION["username_public"];

    // Execute query
    $stmtFLW = $mysqli->prepare("CALL follow_action(?, ?)");
    $stmtFLW->bind_param('ss', $from_uname, $to_uname);
    $stmtFLW->execute();
    $mysqli->next_result();
    $back_url = "http://localhost:8888/livevibe/user_public.php?link=".$to_uname;
    unset($_SESSION["username_public"]);
    redirect($back_url);
    exit();
    
}
?>
