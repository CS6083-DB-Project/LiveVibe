<!-- Project: LiveVibe
Author: Xun Gong, Wei Yu
Date: Dec 4th, 2014 -->

<!-- PHP and manipulate with livevibe database -->
<?php
require ("connectdb.php");

// Update lastaccess time
if (isset($_SESSION["username"])) {
    // Set local var
    $username_up = $_SESSION["username"];
    $login_type = $_SESSION["login_type"];

    // Set Public var of User You're viewing
    $city_pub = NULL;
    $state_pub = NULL;
    $follower_pub = 0;
    $following_pub = 0;
    $reviews = 0;
    $newMsg = 0;
    $star_pub = FALSE;
    $listname = NULL;
    $rm_t = NULL;     // time of newly added recommend time
    
    // Update lastaccesstime when page load
    $stmtLAT = $mysqli->prepare("CALL update_LAT(?,?,?)");
    $LAT = date("Y-m-d H:i:s");
    $stmtLAT->bind_param('sss', $username_up, $LAT, $login_type);
    $stmtLAT->execute();
    $mysqli->next_result();

    // Check If trying to View other User's Profile
    $username_public = $_GET["link"];
    $username_public = htmlspecialchars($username_public);
    $_SESSION["username_public"] = $username_public;

    if ($username_up == $username_public) {
        redirect("http://localhost:8888/livevibe/user_profile.php");
        exit();
    }
    // Grab Public Info. to Perform
    // Execute query
    $stmtUPublic = $mysqli->prepare("CALL up_info(?)");
    $stmtUPublic->bind_param('s', $username_public);
    $stmtUPublic->execute();
    $stmtUPublic->store_result();
    $stmtUPublic->bind_result($username, $city, $state, $flwer_num, $flw_num, $review_num);

    while ($stmtUPublic->fetch()) {
        $city_pub = $city;
        $state_pub = $state;
        $follower_pub = $flwer_num;
        $following_pub = $flw_num;
        $reviews = $review_num;        
    }

    $mysqli->next_result();
    
    // Grab Taste of User or Genre of Artist
    $tastes = array();
    $stmtT = $mysqli->prepare("CALL list_taste(?)");
    $stmtT->bind_param('s', $username_public);
    $stmtT->execute();
    $stmtT->bind_result($sub);
    while ($stmtT->fetch()) {
        $tastes[] = $sub;       
    }

    $mysqli->next_result();

    // Calculate Reputation to Display (Star User with a star)
    $repu = 0.4 * $follower_pub + 0.5 * $reviews + 0.1 * $following_pub;
    if ($repu > 2) {
        $star_pub = true;
    }

    // Check if already Followed
    $is_followed = FALSE;
    $stmtFLW_status = $mysqli->prepare("SELECT * FROM follow WHERE from_usr = ? AND to_usr = ?");
    $stmtFLW_status->bind_param('ss', $username_up, $username_public);
    $stmtFLW_status->execute();
    $stmtFLW_status->store_result();
    if ($stmtFLW_status->num_rows) {
        $is_followed = TRUE;
    }

    $mysqli->next_result();

    // Grab Concert You Plan to go (In attendance AND before concert time)
    $plan_to = array();
    $stmtPlan = $mysqli->prepare("CALL usr_plan_to(?)");
    $stmtPlan->bind_param('s', $username_public);
    $stmtPlan->execute();
    $stmtPlan->bind_result($username, $cid, $artistname, $start_time, $vname, $street, $city, $state, $zipcode);
    while ($stmtPlan->fetch()) {
        $one_concert = array("username"   => $username,
                             "cid"        => $cid,
                             "artistname" => $artistname,
                             "start_time" => $start_time,
                             "vname"      => $vname,
                             "street"     => $street,
                             "city"       => $city,
                             "state"      => $state,
                             "zipcode"    => $zipcode);
        $plan_to[] = $one_concert;
    }

    $mysqli->next_result();   

    // Grab Recommend List by User You Viewing
    $recomList = array();
    $stmtRL = $mysqli->prepare("CALL my_recomList(?)");
    $stmtRL->bind_param('s', $username_public);
    $stmtRL->execute();
    $stmtRL->bind_result($listname, $cid, $rm_time, $artistname, $start_time, $vname, $city);
    while ($stmtRL->fetch()) {
        $one_concert = array("listname"   => $listname,
                             "cid"        => $cid,
                             "rm_time"    => $rm_time,
                             "artistname" => $artistname,
                             "start_time" => $start_time,
                             "vname"      => $vname,
                             "city"       => $city,
                             );
        $recomList[] = $one_concert;
    }

    $mysqli->next_result();
    


}




?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Xun Gong + Wei Yu">
    <title>LiveVibe | CS6083 Database Project</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/main.css" rel="stylesheet">
    <link href="css/bootstrap-select.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">  
    <link href="css/responsive.css" rel="stylesheet">
</head><!--/head-->

<body>
    <header id="header" role="banner">      
        <div class="main-nav">
            <div class="container">  
                <div class="row">                   
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="index.php">
                            <img class="img-responsive" src="images/logo.png" alt="logo">
                        </a>                    
                    </div>
                    <div class="collapse navbar-collapse">
                        <ul class="nav navbar-nav navbar-right">                 
                            <li class="scroll"><a href="index.php">Home</a></li>
                            <li class="scroll"><a href="#">Trend</a></li>
                            <li class="scroll"><a href="all_genre.php">Genre</a></li>
                            <li class="scroll"><a href="search.php">Search</a></li>
                            <li class="scroll"><a href="logout.php">Log out</a></li> 
                        </ul>
                    </div>
                </div>
            </div>
        </div>                    
    </header>
    <!-- header --> 

    <div id="user_page" class="container">

        <div class="user-profile panel panel-default">

            <div class="panel-body">
                <div class="row">
                    <div class="col-xs-12 col-sm-4 text-center">
                        <img src="http://api.randomuser.me/portraits/men/99.jpg" alt="" class="center-block img-circle img-responsive">
                    </div><!--/col--> 
                    <div class="col-xs-12 col-sm-8">
                        <h2><?php echo $username_public; ?>
                            <?php 
                            if($star_pub) {echo "<span class=\"fa fa-star\"></span>";}
                            ?>
                        </h2>

                        <p><h3><strong><?php echo $city_pub.", ".$state_pub?></strong></h3></p>
                        <p><strong>Taste: </strong>
                            <br>
                            <!-- use php loop to grab information -->
                            <?php
                            foreach ($tastes as $tag) {
                                echo "<span class=\"label label-info tags\">".$tag."</span>";
                                echo "<br>";
                            }
                            ?>
                        </p>
                        <br>
                        <?php
                        if ($is_followed) {
                            echo "<div class=\"col-md-4\">";
                            echo "<button name=\"follow\" type=\"submit\" class=\"btn btn-info btn-block\"><i class=\"fa fa-eye fa-lg\"></i>Followed</button></form>";
                            echo "</div>";
                        }
                        else {
                            echo "<div class=\"col-md-4\">";
                            echo "<form action=\"follow_action.php\" method=\"POST\"><button name=\"follow\" type=\"submit\" class=\"btn btn-success btn-block\"><i class=\"fa fa-plus-circle\"></i> Follow</button></form>";
                            echo "</div>";
                        }
                        ?>
                        <!-- <div class="col-md-4">
                        <form action="follow_action.php" method="POST"><button name="follow" type="submit" class="btn btn-success btn-block"><span class="fa fa-plus-circle"></span>  Follow</button></form>
                        </div> -->
                        </div><!--/col-->          
                        <div class="clearfix"></div>
                        <div class="col-xs-12 col-sm-4">
                            <h2><strong> <?php echo $follower_pub;?></strong></h2>                    
                            <p><small>Followers</small></p>

                        </div><!--/col-->
                        <div class="col-xs-12 col-sm-4">
                            <h2><strong><?php echo $following_pub;?></strong></h2>                    
                            <p><small>Following</small></p>
                            <!-- <button class="btn btn-info btn-block"><span class="fa fa-user"></span> View Profile </button> -->
                        </div><!--/col-->
                        <div class="col-xs-12 col-sm-4">
                            <h2><strong><?php echo $reviews;?></strong></h2>                    
                            <p><small>Reviews</small></p>  
                        </div><!--/col-->

                    </div><!--/row-->
                </div><!--/panel-body-->
            </div><!--/panel-->
        <!-- .user-profile  -->

        <!-- Display Plan to Go -->
        <div class="panel panel-default">
            <div class="panel-heading text-center">
                <h3>
                <?php echo "<strong>".$username_public." is going to...</strong>";?>
                </h3>
            </div>
            <div class="panel-body">
                <div class="row">
                <!-- php loop to show all plan to concert -->
                   <?php
                        foreach ($plan_to as $con) {
                            echo "<div class=\"col-md-4 concert-brief\">";
                            echo "<div class=\"concert-caption\">";
                            echo "<h4><span class=\"fa fa-calendar fa-lg\"> </span>   ".$con["start_time"]."   </h4>";
                            echo "<h3><a href=\"artist_public.php?link=",urlencode($con["artistname"]),"\">".$con["artistname"]."</a></h3>";
                            echo "</div>";
                            echo "<div class=\"location\"><h4>".$con["vname"]."</h4><p>";
                            echo "<span class=\"addr\">";
                            echo "<span class=\"street\">  ".$con["street"]."</span>";
                            echo "<br>";
                            echo "<span class=\"city\">  ".$con["city"]."</span>  ,";
                            echo "<br>";
                            echo "<span class=\"state\">  ".$con["state"]."</span>  ,";
                            echo "<span class=\"zipcode\">  ".$con["zipcode"]."</span>";
                            echo "<a href=concert_info.php?link=".$con["cid"]."><h4>Details</h4></a>";
                            echo "</span></p></div></div>";
                        }
                    ?>
                </div><!-- .row -->
            </div><!-- .panel-body-->
        </div><!-- .panel-->
        <!-- Plan to go  -->

        <!-- Display Recommend List He Made-->
        <div class="panel panel-default">
            <div class="panel-heading text-center">
                <h3>
                    <?php echo "<strong>".$username_public." 's Recommend Lists</strong>";?>
                </h3>
            </div> <!-- .panel-heading -->

            <div class="panel-body">
                <div class="row">
                    <!-- php loop concert -->
                    <?php
                    foreach ($recomList as $con) {
                        echo "<div class=\"col-md-4 concert-brief\">";
                        echo "<div class=\"concert-caption\">";
                        echo "<h4><span class=\"fa fa-calendar fa-lg\"></span>   ".$con["start_time"]."</h4>";
                        echo "<h3><a href=\"artist_public.php?link=", urlencode($con["artistname"]), "\">".$con["artistname"]."</a></h3>";
                        echo "</div>";
                        echo "<div class=\"location\"><span>".$con["vname"].", </span>";
                        echo "<span class=\"addr\">";
                        echo "<span class=\"city\">  ".$con["city"]."</span>";
                        echo "<a href=concert_info.php?link=".$con["cid"]."><h6>Details</h6></a>";
                        echo "<small>From the list: </small><h4><a href=\"show_recomList.php?link=", urlencode($con["listname"]),"\">".$con["listname"]."</a></h4>";
                        echo "</span></div></div>";
                    }
                    ?>
                </div><!-- .row -->
            </div><!-- .panel-body-->
        </div><!-- .panel-->
        <!-- My Recommend List  -->

    </div> <!-- #user_page.container -->


        <style>
            body {
                background-image: url("./images/bg/register_bg.png");
                background-color: #A30000;
                background-repeat:no-repeat;
                background-size:cover;
                background-position: top center !important;
                background-repeat: no-repeat !important;
                background-attachment: fixed;
            }

            .navbar-brand {
              background-color: #A30000;
              height: 80px;
              margin-bottom: 20px;
              position: relative;
              width: 638px;
              opacity: .95
            }

            #user_page {
                padding-top: 100px;
                color: #03695E;
            }

            .panel  {
                opacity: 0.9;
                box-shadow: rgba(0, 0, 0, 0.3) 20px 20px 20px;
            }

            .fa-star {
                color: #FFD700;
            }

            .fa-calendar {
                color: #A30000;
            }

            .navbar-collapse {
                padding-left: 0px;
                padding-right: 0px;
            }

        </style>

    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/bootstrap-select.js"></script>
    <script type="text/javascript" src="js/smoothscroll.js"></script>
    <script type="text/javascript" src="js/jquery.parallax.js"></script>
    <script type="text/javascript" src="js/jquery.scrollTo.js"></script>
    <script type="text/javascript" src="js/jquery.nav.js"></script>
    <script type="text/javascript" src="js/main.js"></script>  
</body>
</html>
