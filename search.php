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
}
else {
    echo "<script>alert(\"Please Login!\")</script>";
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
                                Search on LiveVibe
                            </h2>
                        </div>
                            <ul class="nav nav-tabs">
                                <li class="active"><a data-toggle="tab" href="#genre">by Genre</a></li>
                                <li><a data-toggle="tab" href="#user">by Username</a></li>
                                <li><a data-toggle="tab" href="#artist">by Artistname</a></li>
                                <li><a data-toggle="tab" href="#date">by Time Slot</a></li>                                
                            </ul> 
                            <br>
                            <!-- Tab Content -->
                            <div class="tab-content">
                            <!-- #genre -->
                            <div id="genre" class="tab-pane fade in active">
                                <form role="form" action="search_genre.php" method="POST">
                                        <div class="form-group">
                                            <input type="text" name="genre" id="genre" class="form-control input-md" placeholder="Enter a Genre You Like">
                                        </div>
                                        <input type="submit" value="GO" class="btn btn-info btn-block">
                                </form>
                            </div>
                            <!-- #user -->
                            <div id="user" class="tab-pane fade in ">
                                <form role="form" action="search_user.php" method="POST">
                                        <div class="form-group">
                                            <input type="text" name="user" id="user" class="form-control input-md" placeholder="Enter a Username You Known (Exactly username, case-sensitive)">
                                        </div>
                                        <input type="submit" value="GO" class="btn btn-info btn-block">
                                </form>
                            </div>
                            <!-- #artist -->
                            <div id="artist" class="tab-pane fade in ">
                                <form role="form" action="search_artist.php" method="POST">
                                        <div class="form-group">
                                            <input type="text" name="artist" id="artist" class="form-control input-md" placeholder="Enter a Artist You Like (Exactly artistname, case-sensitive)">
                                        </div>
                                        <input type="submit" value="GO" class="btn btn-info btn-block">
                                </form>
                            </div>

                            <!-- #date -->
                            <div id="date" class="tab-pane fade in ">
                                <form role="form" action="search_date.php" method="POST">
                                        <div class="form-group">
                                            <input type="text" name="start_date" id="start_date" class="form-control input-md" placeholder="Start Date">
                                            <input type="text" name="end_date" id="end_date" class="form-control input-md" placeholder="End Date">

                                        </div>
                                        <input type="submit" value="GO" class="btn btn-info btn-block">
                                </form>
                            </div>
                            </div>
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
