
<!-- Project: LiveVibe
Author: Xun Gong, Wei Yu
Date: Dec 4th, 2014 -->

<!-- PHP and manipulate with livevibe database -->
<?php
require ("connectdb.php");

if (isset($_SESSION["username"])) {
    // Set local var
    $username = $_SESSION["username"];
    $sub_in = $_GET["link"];

    // // Update lastaccesstime when page load
    $stmtLAT = $mysqli->prepare("CALL update_LAT(?,?,?)");
    $LAT = date("Y-m-d H:i:s");
    $stmtLAT->bind_param('sss', $username, $LAT, $login_type);
    $stmtLAT->execute();
    $mysqli->next_result();

    // Grab All Related Artists
    $stmtGArtist = $mysqli->prepare("SELECT artistname FROM a_sub WHERE sub = ? ORDER BY artistname ASC");
    $stmtGArtist->bind_param("s", $sub_in);
    $stmtGArtist->execute();
    $stmtGArtist->bind_result($artistname);

    $artists = array();
    while ($stmtGArtist->fetch()) {
        $artists[] = $artistname;
    }
    $mysqli->next_result();


    // Grab All Related Concert Info.
    $stmtConInfo = $mysqli->prepare("SELECT C.cid, C.artistname, C.start_time,
                                            V.vname, V.street, V.city, V.state, V.zipcode
                                     FROM a_sub AS G JOIN artists AS A JOIN concerts AS C JOIN venues AS V
                                     ON G.artistname = A.artistname AND C.artistname = A.artistname AND C.vid = V.vid
                                     WHERE G.sub = ?
                                     ORDER BY C.start_time ASC");
    $stmtConInfo->bind_param("s", $sub_in);
    $stmtConInfo->execute();
    $stmtConInfo->bind_result($cid, $artistname, $start_time, $vname, $street, $city, $state, $zipcode);
    
    $AllCon = array();
    while ($stmtConInfo->fetch()) {
        $con_info = array(
                             "cid"        => $cid,
                             "artistname" => $artistname,
                             "start_time" => $start_time,
                             "vname"      => $vname,
                             "street"     => $street,
                             "city"       => $city,
                             "state"      => $state,
                             "zipcode"    => $zipcode);
        $AllCon[] = $con_info;
    }

    $mysqli->next_result();

}
else {
    echo "<script>alert(\"Please Log in!\")</script>";
    redirect("http://localhost:8888/livevibe/index.php");
    exit();
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
    <!--/#header--> 
<section id="user_panel">


    <!-- Display All Artist of Certain Genre-->
      <div class="row">
          <div class="col-md-10">
            <div class="panel panel-default">
                <div class="panel-body">
                  <div class="concert-brief">
                    <div class="panel panel-primary">
                        <div class="panel-heading text-center"><h2><strong>Popular Artist in <?php echo $sub_in;?></strong></h2></div>
                            <table class="table">
                            <!-- php loop to show all plan to concert -->
                               <?php
                                    foreach ($artists as $a) {
                                        echo "<tr class=\"pull-left\">";
                                        echo "<td>";
                                        echo "<div class=\"container-fluid\"><div class=\"row\"><div class=\"col-md-10\">";
                                        echo "<a href=\"artist_public.php?link=", urlencode($a), "\">".$a."</a>";
                                        echo "</div></div></div></td></tr>";
                                        echo "<br>";
                                    }
                                ?>
                            </table><!-- table -->
                    </div><!-- concert-brief -->
                </div><!--/panel-body-->
            </div><!--/panel-->
          </div><!--/col--> 
      </div><!--/row--> 
    <!--Related Artist -->

    <!-- Display All Concert -->
      <div class="row">
          <div class="col-md-10">
            <div class="panel panel-default">
                <div class="panel-body">
                  <div class="concert-brief">
                    <div class="panel panel-info">
                        <div class="panel-heading text-center"><h2><strong>All Related Concerts</strong></h2></div>
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
              width: 628px;
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
                font-size: 20px;
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
