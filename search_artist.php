<!-- Project: LiveVibe
Author: Xun Gong, Wei Yu
Date: Dec 4th, 2014 -->

<!-- PHP and manipulate with livevibe database -->
<?php
// Session start in connectdb.php file
require ("connectdb.php");

if (isset($_SESSION["username"]) && !empty($_POST["artist"])) {
    $get_artist = $_POST["artist"];
    echo $get_artist;
    // Exactly artistname, case-sensitive
    $stmtFA = $mysqli->prepare("SELECT artistname FROM artists WHERE artistname = ?");
    $stmtFA->bind_param("s", $get_artist);
    $stmtFA->execute();
    $stmtFA->store_result();

    if ($stmtFA->num_rows != 0) {
        // Successfully Found and Redirecting
        $art_page = "http://localhost:8888/livevibe/artist_public.php?link=".$get_artist;
        $art_page = htmlentities($art_page);
        redirect($art_page);
        $mysqli->next_result();
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

