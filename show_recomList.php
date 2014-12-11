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
    $listname_IN = $_GET["link"];
    
    // Update lastaccesstime when page load
    $stmtLAT = $mysqli->prepare("CALL update_LAT(?,?,?)");
    $LAT = date("Y-m-d H:i:s");
    $stmtLAT->bind_param('sss', $username_up, $LAT, $login_type);
    $stmtLAT->execute();
    $mysqli->next_result();

    // Grab All Concert from Recommend List User Just Clicked
    $recomList = array();
    $stmtRL = $mysqli->prepare("CALL show_recomList(?)");
    $stmtRL->bind_param('s', $listname_IN);
    $stmtRL->execute();
    $stmtRL->bind_result($username, $cid, $rm_time, $artistname, $start_time, $vname, $city);
    while ($stmtRL->fetch()) {
        $one_concert = array("username"   => $username,
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

    <!-- Recommendation List Page -->
     <!-- Display Recommend List He Made-->
      <div class="row">
          <div class="col-md-10">
            <div class="panel panel-default">
                <div class="panel-body">
                  <div class="concert-brief">
                    <div class="panel panel-info">
                        <div class="panel-heading text-center">
                            <h2>
                            <?php echo "<strong>".$listname_IN."</strong>";?>
                            </h2>
                        </div>
                            <table class="table" class="text-center">
                                <tr>
                                   <th>
                                       <h2>Date Time</h2>
                                   </th>
                                   <th>
                                       <h2>Performer</h2>
                                   </th>
                                   <th>
                                       <h2>Venue</h2>
                                   </th> 
                                    <th>
                                       <h2>Added Time</h2>
                                   </th>
                                    <th>
                                       <h2>By</h2>
                                   </th>
                                </tr>
                            <!-- php loop concert -->
                               <?php
                                    foreach ($recomList as $con) {
                                        echo "<tr>";
                                        echo "<div class=\"container-fluid\"><div class=\"row\"><div class=\"col-md-10\">";
                                        echo "<td>";
                                        echo "<h4><span class=\"fa fa-calendar fa-lg\"></span>   ".$con["start_time"]."</h4>";
                                        echo "</td>";
                                        echo "<td>";
                                        echo "<h4><a href=\"artist_public.php?link=", urlencode($con["artistname"]), "\">".$con["artistname"]."</a></h4>";
                                        echo "</td>";
                                        echo "<td>";
                                        echo "<div class=\"location\"><h4>".$con["vname"]."</h4><p>";
                                        echo "<span class=\"addr\">";
                                        echo "<span class=\"city\">  ".$con["city"]."</span>";
                                        echo "<a href=concert_info.php?link=".$con["cid"]."><h4>Concert Details</h4></a>";
                                        echo "</span></p></div></td>";
                                        echo "<td><h4>".$con["start_time"]."</h4></td>";
                                        echo "<td><a href=\"user_public.php?link=", urlencode($con["username"]), "\">".$con["username"]."</a>";
                                        echo "</td>";
                                        echo "</div></div></div></tr>";
                                        echo "<br>";
                                    }
                                ?>
                            </table><!-- table -->
                    </div><!-- concert-brief -->
                </div><!--/panel-body-->
            </div><!--/panel-->
          </div><!--/col--> 
      </div><!--/row--> 
    <!-- SHOW Recommend List  -->

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
