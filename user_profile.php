<!-- Project: LiveVibe
Author: Xun Gong, Wei Yu
Date: Dec 4th, 2014 -->

<!-- PHP and manipulate with livevibe database -->
<?php
require ("connectdb.php");

if (isset($_SESSION["username"])) {
    // Set local var
    $username_up = $_SESSION["username"];
    $login_type = $_SESSION["login_type"];
    $city_up = NULL;
    $state_up = NULL;
    $follower_up = 0;
    $following_up = 0;
    $reviews = 0;
    $newMsg = 0;
    $star = NULL;
    $listname = NULL;
    $rm_t = NULL;     // time of newly added recommend time

    // Before Update Lastaccess: Grab News Feed on Recommended by LiveVibe Star User
    // Got How many newMessage user got
    $stmtMsg = $mysqli->prepare("CALL usr_newMsg_num(?)");
    $stmtMsg->bind_param('s', $username_up);
    $stmtMsg->execute();
    $stmtMsg->bind_result($newMessage);
    while ($stmtMsg->fetch()) {
        $newMsg = $newMessage;
    }

    $mysqli->next_result();

    // Got Concerts started with new update
    $newCon = array();
    $stmtNC = $mysqli->prepare("CALL usr_new_feed(?)");
    $stmtNC->bind_param('s', $username_up);
    $stmtNC->execute();
    $stmtNC->bind_result($star, $listname, $rm_time, $cid, $artistname, $start_time, $vname, $street, $city, $state, $zipcode);
    while ($stmtNC->fetch()) {
        $one_concert = array("star"       => $star,
                             "listname"   => $listname,
                             "cid"        => $cid,
                             "artistname" => $artistname,
                             "start_time" => $start_time,
                             "vname"      => $vname,
                             "street"     => $street,
                             "city"       => $city,
                             "state"      => $state,
                             "zipcode"    => $zipcode);
        $newCon[] = $one_concert;
    }

    $mysqli->next_result();

    // Update lastaccesstime when page load
    $stmtLAT = $mysqli->prepare("CALL update_LAT(?,?,?)");
    $LAT = date("Y-m-d H:i:s");
    $stmtLAT->bind_param('sss', $username_up, $LAT, $login_type);
    $stmtLAT->execute();
    $mysqli->next_result();

    // Grab user date to perform user information
    // Execute query
    $stmtUinfo = $mysqli->prepare("CALL up_info(?)");
    $stmtUinfo->bind_param('s', $username_up);
    $stmtUinfo->execute();
    $stmtUinfo->bind_result($username, $city, $state, $flwer_num, $flw_num, $review_num);

    while ($stmtUinfo->fetch()) {
        $city_up = $city;
        $state_up = $state;
        $follower_up = $flwer_num;
        $following_up = $flw_num;
        $reviews = $review_num;        
    }

    $mysqli->next_result();

    // Grab genre select options
    $genre_opt = array();
    $stmtGenre = $mysqli->prepare("CALL list_genre()");
    $stmtGenre->execute();
    $stmtGenre->bind_result($sub);
    while ($stmtGenre->fetch()) {
        $genre_opt[] = $sub;       
    }

    $mysqli->next_result();

    // Grab Taste of User or Genre of Artist
    $tastes = array();
    $stmtT = $mysqli->prepare("CALL list_taste(?)");
    $stmtT->bind_param('s', $username_up);
    $stmtT->execute();
    $stmtT->bind_result($sub);
    while ($stmtT->fetch()) {
        $tastes[] = $sub;       
    }

    $mysqli->next_result();

    // Calculate Reputation to Display (Star User with a star)
    $_SESSION["is_star_usr"] = false;
    $repu = 0.4 * $follower_up + 0.5 * $reviews + 0.1 * $following_up;
    if ($repu > 2) {
        $_SESSION["is_star_usr"] = true;
    }

    // Grab Concert You Plan to go (In attendance AND before concert time)
    $plan_to = array();
    $stmtPlan = $mysqli->prepare("CALL usr_plan_to(?)");
    $stmtPlan->bind_param('s', $username_up);
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

    // Grab Concert Recommended by LiveVibe System based on Taste
    $VibeSense = array();
    $stmtVS = $mysqli->prepare("CALL usr_vibe_sense(?)");
    $stmtVS->bind_param('s', $username_up);
    $stmtVS->execute();
    $stmtVS->bind_result($cid, $artistname, $start_time, $vname, $street, $city, $state, $zipcode);
    while ($stmtVS->fetch()) {
        $one_concert = array(
                             "cid"        => $cid,
                             "artistname" => $artistname,
                             "start_time" => $start_time,
                             "vname"      => $vname,
                             "street"     => $street,
                             "city"       => $city,
                             "state"      => $state,
                             "zipcode"    => $zipcode);
        $VibeSense[] = $one_concert;
    }

    $mysqli->next_result(); 

    // Grab ALL Venue Info
    $venue_opt = array();
    $stmtV = $mysqli->prepare("SELECT vid, vname, city FROM venues");
    $stmtV->execute();
    $stmtV->bind_result($vid, $vname, $city);
    while ($stmtV->fetch()) {
        $one_venue = array (
                            "vid"   => $vid,
                            "vname" => $vname,
                            "city"  => $city
                            ); 
        $venue_opt[] = $one_venue;
    }

    $mysqli->next_result();

    // Grab All Artist Info
    $artist_opt = array();
    $stmtA = $mysqli->prepare("SELECT artistname FROM artists");
    $stmtA->execute();
    $stmtA->bind_result($artistname);
    while ($stmtA->fetch()) {
        $artist_opt[] = $artistname;
    }

    $mysqli->next_result();

    // Grab All Concert Info
    $concert_op = array();
    $stmtCP = $mysqli->prepare("SELECT C.cid, C.artistname, C.start_time, V.city FROM concerts AS C JOIN venues AS V ON C.vid = V.vid AND C.start_time > NOW() ORDER BY C.start_time ASC");
    $stmtCP->execute();
    $stmtCP->bind_result($cid, $artistname, $start_time, $city);
    while ($stmtCP->fetch()) {
        $one_concert = array(
                            "cid" => $cid,
                            "artistname" => $artistname,
                            "start_time" => $start_time,
                            "city" => $city
                            );
        $concert_op[] = $one_concert;
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
        <div class="main-nav navbar-fixed-top">
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
    <!--/#header--> 

<!-- the main scrollable page -->
<div id="user_page" class="container" >

    <!-- User Information -->
    <div class="user-info panel panel-default">
        <div class="panel-body">

            <div class="row">
                <div class="col-md-4">
                      <img src="http://api.randomuser.me/portraits/men/47.jpg" alt="" class="center-block img-circle img-responsive">
                </div><!-- .col-md-4 --> 

                <div class="col-md-8">

                    <h2>
                        <?php echo $username_up; ?>
                        <?php 
                            if($_SESSION["is_star_usr"]) {echo "<span class=\"fa fa-star\"></span>";}
                        ?>
                    </h2>
                    <h3><strong><?php echo $city_up.", ".$state_up?></strong></h3>

                    <div class="media">
                        <div class="media-body">
                            <h4 class="media-heading">Taste: </h4>
                            <!-- use php loop to grab information -->
                            <?php
                                foreach ($tastes as $tag) {
                                    echo "<span class=\"label label-info tags\">".$tag."</span>";
                                    echo "<br>";
                                }
                            ?>
                        </div>
                        <div class="media-right" >
                            <form class="btn-group pull-right" role="form" action="add_genre.php" method="POST">
                                <select  name="genre" class="selectpicker pull-left" data-dropdown-auto="false" data-style="btn-success" >
                                    <?php 
                                        foreach ($genre_opt as $sub) {
                                             echo "<option>".$sub."</option>";
                                         } 
                                    ?>
                                </select>
                                <button type="submit" class="btn btn-info"><span class="fa fa-music"></span> I like it!</button>
                            </form>
                        </div>
                    </div>
                        
                    <div class="row">
                        <div class="col-md-4">
                            <h2><strong> <?php echo $follower_up;?></strong></h2>                    
                            <p><small>Followers</small></p>
                            <!-- <button class="btn btn-success btn-block"><span class="fa fa-plus-circle"></span>  </button> -->
                        </div><!--/col-->
                        <div class="col-md-4">
                            <h2><strong><?php echo $following_up;?></strong></h2>                    
                            <p><small>Following</small></p>
                            <!-- <button class="btn btn-info btn-block"><span class="fa fa-user"></span> View Profile </button> -->
                        </div><!--/col-->
                        <div class="col-md-4">
                            <h2><strong><?php echo $reviews;?></strong></h2>                    
                            <p><small>Reviews</small></p>  
                        </div>
                    </div> <!-- .row -->

                </div><!-- .col-md-8 -->          

            </div><!--/row-->

        </div><!-- panel-body -->
    </div> <!-- .user-info.panel -->
    <!-- User Information -->

    <!-- Plan to Go -->
            <div class="user-plan panel panel-primary">
                        <div class="panel-heading text-center"><h3>Plan To Go</h3></div>
                            <div class="panel-body">
                            <!-- php loop to show all plan to concert -->
                               <?php
                                    foreach ($plan_to as $con) {
                                        echo "<div class=\"col-md-4\">"; // shows 3 concerts in a row at once
                                        echo "<div class=\"date-and-name\">";
                                        echo "<h4><span class=\"fa fa-calendar fa-lg\"></span>   ".$con["start_time"]."</h4>";
                                        echo "<h3><a href=\"artist_public.php?link=",urlencode($con["artistname"]),"\">".$con["artistname"]."</a></h3>";
                                        echo "</div>";
                                        echo "<div class=\"location\"><h4>".$con["vname"]."</h4><p>";
                                        echo "<span class=\"addr\">";
                                        echo "<span class=\"street\">  ".$con["street"]."</span>,";
                                        echo "<br>";
                                        echo "<span class=\"city\">  ".$con["city"]."</span>,";
                                        echo "<br>";
                                        echo "<span class=\"state\">  ".$con["state"]."</span>,";
                                        echo "<span class=\"zipcode\">  ".$con["zipcode"]."</span>";
                                        echo "<a href=concert_info.php?link=".$con["cid"]."><h4>Details</h4></a>";
                                        echo "</span></p></div></div>";
                                    }
                                ?>
                                </div> <!-- panel-body -->
          </div><!-- .user-plan.panel --> 
          <!-- Plan to Go -->

    <!-- News Feed -->
            <div class="user-feed panel panel-primary">
                        <div class="panel-heading text-center"><h3>News Feed</h3></div>
                            <div class="panel-body">
                            <!-- php loop concert -->
                               <?php
                                    $spin_cnt = 0;
                                    foreach ($newCon as $con) {
                                        echo "<div class=\"col-md-4 concert_info\">"; // shows 3 concerts in a row at once
                                        echo "<div class=\"concert-caption\">";
                                        echo "<h4><span class=\"fa fa-calendar fa-lg\"></span>   ".$con["start_time"];
                                        if ($spin_cnt < $newMsg) {
                                            echo "  <h6><em>New + </em><i class=\"fa fa-spinner fa-spin\"></i></h6>";
                                            $spin_cnt++;
                                        }
                                        echo "</h4>";
                                        echo "From Star User: <a href=\"user_public.php?link=", urlencode($star), "\">".$star."</a>";
                                        echo "<h5>From the list: <a href=\"show_recomList.php?link=", urlencode($con["listname"]), "\">".$con["listname"]."</a></h5>";
                                        echo "<h3><a href=\"artist_public.php?link=", urlencode($con["artistname"]), "\">".$con["artistname"]."</a></h3>";
                                        echo "</div>";
                                        echo "<div class=\"location\"><h4>".$con["vname"]."</h4><p>";
                                        echo "<span class=\"addr\">";
                                        echo "<span class=\"street\">  ".$con["street"]."</span>,";
                                        echo "<br>";
                                        echo "<span class=\"city\">  ".$con["city"]."</span>,";
                                        echo "<br>";
                                        echo "<span class=\"state\">  ".$con["state"]."</span>,";
                                        echo "<span class=\"zipcode\">  ".$con["zipcode"]."</span>";
                                        echo "<a href=concert_info.php?link=".$con["cid"]."><h5>Details</h5></a>";
                                        echo "</span></p></div></div>";
                                    }
                                ?>
                            </div>
          </div><!-- .user-feed.panel--> 
    <!-- News Feed -->

    
    <!-- Live Sense -->
            <div class="sys-guess panel panel-primary">
                        <div class="panel-heading text-center"><h3>Concerts From <em>Live-Sense</em></h3></div>
                            <div class="panel-body">
                            <!-- php loop -->
                               <?php
                                    foreach ($VibeSense as $con) {
                                        echo "<div class=\"col-md-3 concert_info\">"; // shows 4 concerts in a row at once
                                        echo "<div class=\"concert-caption\">";
                                        echo "<h4><span class=\"fa fa-calendar fa-lg\"></span>   ".$con["start_time"]."</h4>";
                                        echo "<h3><a href=\"artist_public.php?link=", urlencode($con["artistname"]),"\">".$con["artistname"]."</a></h3>";
                                        echo "</div>";
                                        echo "<div class=\"location\"><h4>".$con["vname"]."</h4><p>";
                                        echo "<span class=\"addr\">";
                                        echo "<span class=\"street\">  ".$con["street"]."</span>,";
                                        echo "<br>";
                                        echo "<span class=\"city\">  ".$con["city"]."</span>,";
                                        echo "<br>";
                                        echo "<span class=\"state\">  ".$con["state"]."</span>,";
                                        echo "<span class=\"zipcode\">  ".$con["zipcode"]."</span>";
                                        echo "<a href=concert_info.php?link=".$con["cid"]."><h4>Details</h4></a>";
                                        echo "</span></p></div></div>";
                                    }
                                ?>
                            </div><!-- panel-body -->
          </div><!-- .sys-guess.panel --> 
    <!-- Live Sense -->

    <!-- If Star User: Can Post Additional Info -->
    <?php
    if ($_SESSION["is_star_usr"]) {

        echo "      <!-- Create Concert -->";
        echo "            <div class=\"create-con panel panel-primary\">\n"; 
        echo "                        <div class=\"panel-heading text-center\"><h3>Post A New Concert</h3></div>\n"; 
        echo "                        <!-- Post Form -->\n"; 
        echo "                        <div class=\"panel-body\">\n"; 
        echo "                        <form role=\"form\" action=\"post_con.php\" method=\"POST\">\n"; 
        echo "                            <div class=\"form-group\">\n";
        echo "                                 Select a Artist in LiveVibe: ";
        echo "                                <select  name=\"artistname\" class=\"selectpicker\" data-dropdown-auto=\"false\" data-style=\"btn-primary\" >\n"; 
                                            foreach ($artist_opt as $a) {
                                                echo "<option>".$a."</option>";
                                            }                                                 
        echo "                                </select>\n"; 
        echo "                            </div>\n";
        echo "                            <div class=\"form-group\">\n";
        echo "                                 Concert Start Date and Time: "; 
        echo "                                <input type=\"text\" placeholder=\"Date and Time\" name=\"date_time\"/>\n"; 
        echo "                            </div>\n";
        echo "                            <div class=\"form-group\">\n"; 
        echo "                                 Extra Link for Purchase Ticket: "; 
        echo "                            <input type=\"text\" placeholder=\"Link\" name=\"con_link\"/><br>\n";
        echo "                            </div>\n"; 
        echo "                            <div class=\"form-group\">\n";
        echo "                                 Select A Venue of LiveVibe: "; 
        echo "                                <select  name=\"venue\" class=\"selectpicker\" data-dropdown-auto=\"false\" data-style=\"btn-success\" >\n"; 
                                            foreach ($venue_opt as $v) {
                                                echo "<option>".$v["vid"].", ".$v["vname"].", ".$v["city"]."</option>";
                                            }                                                 
  
        echo "                                </select>\n"; 
        echo "                            </div>\n"; 
        echo "\n"; 
        echo "                            <button type=\"submit\" class=\"btn btn-primary pull-right\">Post</button>\n"; 
        echo "                         </form></div> <!-- panel-body -->\n"; 
        echo "          </div><!-- .create-con.panel --> \n"; 
        echo "    <!-- Create Concert -->\n";

        // =======================
        // Can Make Recommand List
        // =======================

        echo "    <!-- Recommendation List -->\n";
        echo "            <div class=\"rcmd-list panel panel-primary\">\n"; 
        echo "                        <div class=\"panel-heading text-center\"><h3>Make a Recommend List</h3></div>\n"; 
        echo "                        <!-- Make RL Form -->\n"; 
        echo "                        <div class=\"panel-body\">\n"; 
        echo "                        <form role=\"form\" action=\"make_recommend.php\" method=\"POST\">\n"; 
        echo "                            <div class=\"form-group\">\n";
        echo "                                 Recommendation List Name: "; 
        echo "                                <input type=\"text\" placeholder=\"List name\" name=\"listname\"/>\n"; 
        echo "                            </div>\n";
        echo "                            <div class=\"form-group\">\n";
        echo "                                 Pick a Concert: "; 
        echo "                                <select  name=\"concerts\" class=\"selectpicker\" data-dropdown-auto=\"false\" data-style=\"btn-primary\" >\n"; 
                                            foreach ($concert_op as $c) {
                                                echo "<option>".$c["cid"].", ".$c["artistname"].", ".$c["start_time"].", ".$c["city"]."</option>";
                                            }                                                 
        echo "                                </select>\n"; 
        echo "                            </div>\n"; 
        echo "\n"; 
        echo "                            <button type=\"submit\" class=\"btn btn-primary pull-right\">Add</button>\n"; 
        echo "                         </form></div>\n"; 
        echo "          </div><!-- .rcmd-list.panel --> \n"; 
        echo "    <!-- Recommendation List -->\n";
    }
    ?>

</div> <!-- .user_page.container -->

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
              width: 628px;
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
