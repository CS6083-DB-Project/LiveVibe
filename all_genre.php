<!-- Project: LiveVibe
Author: Xun Gong, Wei Yu
Date: Dec 4th, 2014 -->

<!-- PHP and manipulate with livevibe database -->
<?php
// Session start in connectdb.php file
require ("connectdb.php");

$stmtAllGenre = $mysqli->prepare("SELECT main, sub FROM genres ORDER BY main ASC");
$stmtAllGenre->execute();
$stmtAllGenre->bind_result($main, $sub);

$genre = array();
while ($stmtAllGenre->fetch()) {
    $item = array (
                    "main" => $main,
                    "sub"  => $sub
                    );
    $genre[] = $item;
}
$mysqli->next_result();
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

    <div id="list_page" class="container">
        <div class="panel panel-primary">
            <div class="panel-heading text-center"><h3>Genres</h3></div>
            <div class="list-group">
               <?php
                    foreach ($genre as $g) {
                        echo "<a href=\"genre_concert.php?link=", urlencode($g["sub"]), "\" class=\"list-group-item\"><h4>".$g["main"]." &ndash; ".$g["sub"]."</h4></a>";
                    }
                ?>
            </div> <!-- .list-group -->
        </div> <!-- .panel -->
    </div> <!-- #list_page.container -->

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

        #list_page {
            max-width: 730px;
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