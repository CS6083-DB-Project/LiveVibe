<!-- Project: LiveVibe
Author: Xun Gong, Wei Yu
Date: Dec 4th, 2014 -->

<!-- PHP and manipulate with livevibe database -->
<?php
// Session start in connectdb.php file
require ("connectdb.php");

if (isset($_SESSION["username"]) && !empty($_POST["user"])) {
    $get_user = $_POST["user"];
    // Exactly username, case-sensitive
    $stmtFU = $mysqli->prepare("SELECT username FROM users WHERE username = ?");
    $stmtFU->bind_param("s", $get_user);
    $stmtFU->execute();
    $stmtFU->store_result();

    if ($stmtFU->num_rows != 0) {
        // Successfully Found and Redirecting
        $user_page = "http://localhost:8888/livevibe/user_public.php?link=".$get_user;
        $mysqli->next_result();
        redirect($user_page);
        exit();
    }
    else {
            echo "<script>alert(\"Username Not Found!\")</script>";
            redirect("http://localhost:8888/livevibe/search.php");
            exit();
    }
}
else {
            echo "<script>alert(\"Empty Input!\")</script>";
            redirect("http://localhost:8888/livevibe/search.php");
            exit();
}


?>

