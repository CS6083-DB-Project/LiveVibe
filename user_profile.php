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
                            <li class="scroll"><a href="#">Genre</a></li>
                            <li class="scroll"><a href="#">About</a></li>
                            <li class="scroll"><a href="logout.php">Log out</a></li> 
                        </ul>
                    </div>
                </div>
            </div>
        </div>                    
    </header>
    <!--/#header--> 
<section id="user_panel">

      <div class="row">
          <div class="col-md-10">
          <div class="panel panel-default">
                <div class="panel-body">
                  <div class="row">
                  <div class="col-xs-12 col-sm-4 text-center">
                        <img src="http://api.randomuser.me/portraits/men/47.jpg" alt="" class="center-block img-circle img-responsive">
                    </div><!--/col--> 
                    <div class="col-xs-12 col-sm-8">
                        <h2><?php echo $username_up; ?>
                        <?php 
                            if($_SESSION["is_star_usr"]) {echo "<span class=\"fa fa-star\"></span>";}
                        ?>
                        </h2>

                        <p><h3><strong><?php echo $city_up.", ".$state_up?></strong></h3></p>
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
                    </div><!--/col-->          
                    <div class="clearfix"></div>
                    <div class="col-xs-12 col-sm-4">
                        <h2><strong> <?php echo $follower_up;?></strong></h2>                    
                        <p><small>Followers</small></p>
                        <!-- <button class="btn btn-success btn-block"><span class="fa fa-plus-circle"></span>  </button> -->
                    </div><!--/col-->
                    <div class="col-xs-12 col-sm-4">
                        <h2><strong><?php echo $following_up;?></strong></h2>                    
                        <p><small>Following</small></p>
                        <!-- <button class="btn btn-info btn-block"><span class="fa fa-user"></span> View Profile </button> -->
                    </div><!--/col-->
                    <div class="col-xs-12 col-sm-4">
                        <h2><strong><?php echo $reviews;?></strong></h2>                    
                        <p><small>Reviews</small></p>  
                    </div><!--/col-->

                    <!-- Add Select Music Genre -->
                    <div class="col-xs-12 col-md-4">
                    <form role="form" action="add_genre.php" method="POST">
                            <div class="row">
                                <div class="col-xs-12 col-sm-12">
                                    <select  name="genre" class="selectpicker" data-dropdown-auto="false" data-style="btn-success" >
                                        <?php 
                                            foreach ($genre_opt as $sub) {
                                                 echo "<option>".$sub."</option>";
                                             } 
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <br>
                            <button type="submit" class="btn btn-info btn-block"><span class="fa fa-music"></span>  Add Genre You Like</button>
                            </form>
                    </div>

                  </div><!--/row-->
                  </div><!--/panel-body-->
              </div><!--/panel-->
        </div><!--/col--> 
      </div><!--/row--> 
    <!--Profile  -->

    <!-- Display Plan to Go -->
      <div class="row">
          <div class="col-md-10">
            <div class="panel panel-default">
                <div class="panel-body">
                  <div class="concert-brief">
                    <div class="panel panel-primary">
                        <div class="panel-heading text-center"><h2><strong>Plan To Go</strong></h2></div>
                            <table class="table">
                            <!-- php loop to show all plan to concert -->
                               <?php
                                    foreach ($plan_to as $con) {
                                        echo "<th>";
                                        echo "<div class=\"container-fluid\"><div class=\"row\"><div class=\"col-md-10\">";
                                        echo "<div class=\"date-and-name\">";
                                        echo "<h4><span class=\"fa fa-calendar fa-lg\"></span>   ".$con["start_time"]."</h4>";
                                        echo "<h3><a href=\"artist_public.php?link=",urlencode($con["artistname"]),"\">".$con["artistname"]."</a></h3>";
                                        echo "</div>";
                                        echo "<div class=\"location\"><h4>".$con["vname"]."</h4><p>";
                                        echo "<span class=\"addr\">";
                                        echo "<span class=\"street\">  ".$con["street"]."</span>";
                                        echo "<br>";
                                        echo "<span class=\"city\">  ".$con["city"]."</span>  ,";
                                        echo "<span class=\"state\">  ".$con["state"]."</span>  ,";
                                        echo "<br>";
                                        echo "<span class=\"zipcode\">  ".$con["zipcode"]."</span>";
                                        echo "<a href=concert_info.php?link=".$con["cid"]."><h4>Concert Details</h4></a>";
                                        echo "</span></p></div></div></div></div></th>";
                                    }
                                ?>
                            </table><!-- table -->
                    </div><!-- concert-brief -->
                </div><!--/panel-body-->
            </div><!--/panel-->
          </div><!--/col--> 
      </div><!--/row--> 
    <!--Plan to go  -->

    <!-- Display You Followed Recommend List -->
      <div class="row">
          <div class="col-md-10">
            <div class="panel panel-default">
                <div class="panel-body">
                  <div class="concert-brief">
                    <div class="panel panel-info">
                        <div class="panel-heading text-center"><h2><strong>News Feed</strong></h2></div>
                            <table class="table">
                            <!-- php loop concert -->
                               <?php
                                    $spin_cnt = 0;
                                    foreach ($newCon as $con) {
                                        echo "<th>";
                                        echo "<div class=\"container-fluid\"><div class=\"row\"><div class=\"col-md-10\">";
                                        echo "<div class=\"date-and-name\">";
                                        echo "<h4><span class=\"fa fa-calendar fa-lg\"></span>   ".$con["start_time"];
                                        if ($spin_cnt < $newMsg) {
                                            echo "  <h6><em>New + </em><i class=\"fa fa-spinner fa-spin\"></i></h6>";
                                            $spin_cnt++;
                                        }
                                        echo "</h4>";
                                        echo "From Star User: <a href=\"user_public.php?link=", urlencode($star), "\">".$star."</a>";
                                        echo "<h6>Recommend List Name: <a href=\"show_recomList.php?link=", urlencode($con["listname"]), "\">".$con["listname"]."</a></h6>";
                                        echo "<h3><a href=\"artist_public.php?link=", urlencode($con["artistname"]), "\">".$con["artistname"]."</a></h3>";
                                        echo "</div>";
                                        echo "<div class=\"location\"><h4>".$con["vname"]."</h4><p>";
                                        echo "<span class=\"addr\">";
                                        echo "<span class=\"street\">  ".$con["street"]."</span>";
                                        echo "<br>";
                                        echo "<span class=\"city\">  ".$con["city"]."</span>  ,";
                                        echo "<span class=\"state\">  ".$con["state"]."</span>  ,";
                                        echo "<br>";
                                        echo "<span class=\"zipcode\">  ".$con["zipcode"]."</span>";
                                        echo "<a href=concert_info.php?link=".$con["cid"]."><h4>Concert Details</h4></a>";
                                        echo "</span></p></div></div></div></div></th>";
                                    }
                                ?>
                            </table><!-- table -->
                    </div><!-- concert-brief -->
                </div><!--/panel-body-->
            </div><!--/panel-->
          </div><!--/col--> 
      </div><!--/row--> 
    <!--Followed Recommend List  -->

    <!-- Display System Guess -->
      <div class="row">
          <div class="col-md-10">
            <div class="panel panel-default">
                <div class="panel-body">
                  <div class="concert-brief">
                    <div class="panel panel-success">
                        <div class="panel-heading text-center"><h2><strong>Concerts From <em> Live-Sense</em></strong></h2></div>
                            <table class="table">
                            <!-- php loop -->
                               <?php
                                    foreach ($VibeSense as $con) {
                                        echo "<th>";
                                        echo "<div class=\"container-fluid\"><div class=\"row\"><div class=\"col-md-10\">";
                                        echo "<div class=\"date-and-name\">";
                                        echo "<h4><span class=\"fa fa-calendar fa-lg\"></span>   ".$con["start_time"]."</h4>";
                                        echo "<h3><a href=\"artist_public.php?link=", urlencode($con["artistname"]),"\">".$con["artistname"]."</a></h3>";
                                        echo "</div>";
                                        echo "<div class=\"location\"><h4>".$con["vname"]."</h4><p>";
                                        echo "<span class=\"addr\">";
                                        echo "<span class=\"street\">  ".$con["street"]."</span>";
                                        echo "<br>";
                                        echo "<span class=\"city\">  ".$con["city"]."</span>  ,";
                                        echo "<span class=\"state\">  ".$con["state"]."</span>  ,";
                                        echo "<br>";
                                        echo "<span class=\"zipcode\">  ".$con["zipcode"]."</span>";
                                        echo "<a href=concert_info.php?link=".$con["cid"]."><h4>Concert Details</h4></a>";
                                        echo "</span></p></div></div></div></div></th>";
                                    }
                                ?>
                            </table><!-- table -->
                    </div><!-- concert-brief -->
                </div><!--/panel-body-->
            </div><!--/panel-->
          </div><!--/col--> 
      </div><!--/row--> 
    <!--System Guess -->

</section>

</section>
        <style>
            body {
                background-image: url("./images/bg/register_bg.png");
                background-color: #A30000;
                background-repeat:no-repeat;
                background-size:cover;
                background-position: top center !important;
                background-repeat: no-repeat !important;
                background-attachment: fixed;
                margin: 0;
                padding: 0;
                height: 100%;
                width: 100%;
            }

            #user_panel {
                padding-top: 100px;
                color: #03695E;
                padding-left: 140px;
            }

            .navbar-brand {
              background-color: #A30000;
              height: 80px;
              margin-bottom: 20px;
              position: relative;
              width: 640px;
              opacity: .95
            }
            .panel  {
                opacity: 0.9;
            }

            .fa-star {
                color: #FFD700;
            }

            .fa-calendar {
                color: #A30000;
            }

            #user_panel .row {
                margin-right: auto;
                margin-left: auto;
            }

            .navbar-collapse {
                padding-left: 0px;
                padding-right: 0px;
            }

            .table {
                font-size: 14px;
            }
        </style>
</section>
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
