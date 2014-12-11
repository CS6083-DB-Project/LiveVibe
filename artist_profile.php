<!-- Project: LiveVibe
Author: Xun Gong, Wei Yu
Date: Dec 4th, 2014 -->

<!-- PHP and manipulate with livevibe database -->
<?php
require ("connectdb.php");

if (isset($_SESSION["username"])) {
    // Set local var
    $artistname_ap = $_SESSION["username"];
    $login_type = $_SESSION["login_type"];
    $concert_num = 0;
    $follower_ap = 0;
    $bio_ap = NULL;

    // Update lastaccesstime when page load
    $stmtLAT = $mysqli->prepare("CALL update_LAT(?,?,?)");
    $LAT = date("Y-m-d H:i:s");
    $stmtLAT->bind_param('sss', $artistname_ap, $LAT, $login_type);
    $stmtLAT->execute();
    $mysqli->next_result();

    // Grab artist date to perform
    // Execute query
    $stmtAinfo = $mysqli->prepare("SELECT bio FROM artists WHERE artistname = ?");
    $stmtAinfo->bind_param('s', $artistname_ap);
    $stmtAinfo->execute();
    $stmtAinfo->bind_result($bio);

    while ($stmtAinfo->fetch()) {
        $bio_ap = $bio;      
    }

    $mysqli->next_result();

    // Grab Artist Genre
    $artist_genre = array();
    $stmtGenre = $mysqli->prepare("SELECT sub FROM a_sub WHERE artistname = ?");
    $stmtGenre->bind_param('s', $artistname_ap);
    $stmtGenre->execute();
    $stmtGenre->bind_result($sub);
    while ($stmtGenre->fetch()) {
        $artist_genre[] = $sub;       
    }

    // Grab Follower number
    $stmt1 = $mysqli->prepare("SELECT COUNT(DISTINCT username) AS flw FROM fans GROUP BY artistname HAVING artistname = ?");
    $stmt1->bind_param('s', $artistname_ap);
    $stmt1->execute();
    $stmt1->bind_result($flw);
    while ($stmt1->fetch()) {
        $follower_ap = $flw;
    }

    $mysqli->next_result();

    // Grab Concert number
    $stmt2 = $mysqli->prepare("SELECT COUNT(DISTINCT cid) AS cn FROM concerts WHERE artistname = ?");
    $stmt2->bind_param('s', $artistname_ap);
    $stmt2->execute();
    $stmt2->bind_result($cn);
    while ($stmt2->fetch()) {
        $concert_num = $cn;
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

    // Grab Upcoming Concert
    $UpComC = array();
    $stmtUpC = $mysqli->prepare("CALL upcoming_ap(?)");
    $stmtUpC->bind_param('s', $artistname_ap);
    $stmtUpC->execute();
    $stmtUpC->bind_result($artistname, $cid, $start_time, $vname, $street, $city, $state, $zipcode);
    while ($stmtUpC->fetch()) {
        $one_concert = array("artistname" => $artistname,
                             "cid"        => $cid,
                             "start_time" => $start_time,
                             "vname"      => $vname,
                             "street"     => $street,
                             "city"       => $city,
                             "state"      => $state,
                             "zipcode"    => $zipcode);
        $UpComC[] = $one_concert;
    }

    $mysqli->next_result();   

    // Grab All My Concerts
    $AllCon = array();
    $stmtAllC = $mysqli->prepare("CALL artist_all_con(?)");
    $stmtAllC->bind_param('s', $artistname_ap);
    $stmtAllC->execute();
    $stmtAllC->bind_result($artistname, $cid, $start_time, $vname, $street, $city, $state, $zipcode);
    while ($stmtAllC->fetch()) {
        $one_concert = array("artistname" => $artistname,
                             "cid"        => $cid,
                             "start_time" => $start_time,
                             "vname"      => $vname,
                             "street"     => $street,
                             "city"       => $city,
                             "state"      => $state,
                             "zipcode"    => $zipcode);
        $AllCon[] = $one_concert;
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
                        <img src="./images/artist/bob-dylan.jpg" alt="" class="center-block img-circle img-responsive">
                    </div><!--/col--> 
                    <div class="col-xs-12 col-sm-8">
                        <h2><?php echo $artistname_ap; ?></h2>
                        <br>
                        <h4><em><?php echo $bio_ap; ?></em></h4>
                        <br>
                        <p><strong>Genre: </strong>
                        <br>
                        <!-- use php loop to grab information -->
                            <?php
                                foreach ($artist_genre as $tag) {
                                    echo "<span class=\"label label-info tags\">".$tag."</span>";
                                    echo "<br>";
                                }
                            ?>
                        </p>
                    </div><!--/col-->          
                    <div class="clearfix"></div>
                    <div class="col-xs-12 col-sm-4">
                        <h2><strong> <?php echo $follower_ap;?></strong></h2>                    
                        <p><small>Followers</small></p>
                        <!-- <button class="btn btn-success btn-block"><span class="fa fa-plus-circle"></span>  </button> -->
                    </div><!--/col-->
                    <div class="col-xs-12 col-sm-4">
                        <h2><strong><?php echo $concert_num;?></strong></h2>                    
                        <p><small>Concerts</small></p>
                        <!-- <button class="btn btn-info btn-block"><span class="fa fa-user"></span> View Profile </button> -->
                    </div><!--/col-->
                    <div class="col-xs-12 col-sm-4">
                        <h2><strong><a href="http://www.rollingstone.com/music/artists/bob-dylan/biography"><i class="fa fa-sign-out"></i></a></strong></h3>                    
                        <p><small>Home Page</small></p>  
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
                            <button type="submit" class="btn btn-info btn-block"><span class="fa fa-music"></span>  Add Genre You Perform</button>
                            </form>
                    </div>

                  </div><!--/row-->
                  </div><!--/panel-body-->
              </div><!--/panel-->
        </div><!--/col--> 
      </div><!--/row--> 
    <!--Profile  -->

    <!-- Display Upcoming-->
      <div class="row">
          <div class="col-md-10">
            <div class="panel panel-default">
                <div class="panel-body">
                  <div class="concert-brief">
                    <div class="panel panel-primary">
                        <div class="panel-heading text-center"><h2><strong>My Upcoming Concerts</strong></h2></div>
                            <table class="table">
                            <!-- php loop to show all plan to concert -->
                               <?php
                                    foreach ($UpComC as $con) {
                                        echo "<th>";
                                        echo "<div class=\"container-fluid\"><div class=\"row\"><div class=\"col-md-10\">";
                                        echo "<div class=\"date-and-name\">";
                                        echo "<h4><span class=\"fa fa-calendar fa-lg\"></span>   ".$con["start_time"]."</h4>";
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
    <!--Upcoming Concert -->

    <!-- Display All Concert -->
      <div class="row">
          <div class="col-md-10">
            <div class="panel panel-default">
                <div class="panel-body">
                  <div class="concert-brief">
                    <div class="panel panel-info">
                        <div class="panel-heading text-center"><h2><strong>All My Concerts</strong></h2></div>
                            <table class="table">
                            <!-- php loop concert -->
                                <?php
                                    foreach ($AllCon as $con) {
                                        echo "<th>";
                                        echo "<div class=\"container-fluid\"><div class=\"row\"><div class=\"col-md-10\">";
                                        echo "<div class=\"date-and-name\">";
                                        echo "<h4><span class=\"fa fa-calendar fa-lg\"></span>   ".$con["start_time"]."</h4>";
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
    <!-- Show All Concerts -->

    <!-- Post New Concert -->
      <div class="row">
          <div class="col-md-10">
            <div class="panel panel-default">
                <div class="panel-body">
                  <div class="concert-brief">
                    <div class="panel panel-success">
                        <div class="panel-heading text-center"><h2><strong>Post A New Concert</strong></h2></div>
                        <!-- Post Form -->
                        <br>
                        <form role="form" action="post_con.php" method="POST">
                            <div class="form-group">
                                <input type="text" placeholder="Date and Time" name="date_time"/>
                            </div>
                            <div class="form-group">
                            <input type="text" placeholder="Link" name="con_link"/><br>
                                <select  name="venue" class="selectpicker" data-dropdown-auto="false" data-style="btn-success" >
                                    <?php 
                                        foreach ($venue_opt as $v) {
                                            echo "<option>".$v["vid"].", ".$v["vname"].", ".$v["city"]."</option>";
                                        } 
                                    ?>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary pull-right">Post</button>
                         </form>
                    </div><!-- concert-brief -->
                </div><!--/panel-body-->
            </div><!--/panel-->
          </div><!--/col--> 
      </div><!--/row--> 
    <!-- Post Con-->

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
