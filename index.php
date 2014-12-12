<!-- Project: LiveVibe
Author: Xun Gong, Wei Yu
Date: Dec 4th, 2014 -->
<?php
require ("connectdb.php");
if (isset($_SESSION["username"])) {
    // Update lastaccesstime when page load
    $stmtLAT = $mysqli->prepare("CALL update_LAT(?,?,?)");
    $submit_username = $_SESSION["username"];
    $LAT = date("Y-m-d H:i:s");
    $login_type = $_SESSION["login_type"];
    $stmtLAT->bind_param('sss', $submit_username, $LAT, $login_type);
    $stmtLAT->execute();
    // Redirect to different page
    if ($login_type == "user") {
        redirect('http://localhost:8888/livevibe/user_profile.php');
    }
    if ($login_type == "artist") {
        redirect('http://localhost:8888/livevibe/artist_profile.php');
    }
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
                            <li class="scroll"><a href="#home">Home</a></li>
                            <li class="scroll"><a href="#trend">Trend</a></li>
                            <li class="scroll"><a href="all_genre.php">Genre</a></li>
                            <li class="scroll"><a href="search.php">Search</a></li>
                            <li class="scroll"><a href="register.php">Register</a></li>
                            <li class="dropdown">
                                <a class="dropdown-toggle" href="#" data-toggle="dropdown">Log In <strong class="caret"></strong></a>
                                <div class="dropdown-menu" style="padding: 20px; padding-bottom: 0px;">
                                    <form action="login_controller.php" method="post"> 
                                        Username:
                                        <br /> 
                                        <input type="text" name="username" value="" /> 
                                        <br /> 
                                        Password:
                                        <br /> 
                                        <input type="password" name="password" value="" /> 
                                        <br />
                                        <input type="submit" class="btn btn-info" value="Login" />
                                    </form> 
                                </div>
                            </li>      
                        </ul>
                    </div>
                </div>
            </div>
        </div>                    
    </header>
    <!--/#header--> 

    <section id="home"> 
        <div id="main-slider" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators">
                <li data-target="#main-slider" data-slide-to="0" class="active"></li>
                <li data-target="#main-slider" data-slide-to="1"></li>
                <li data-target="#main-slider" data-slide-to="2"></li>
            </ol>
            <div class="carousel-inner">
                <div class="item active">
                    <img class="img-responsive" src="images/slider/bg1_justin.jpg" alt="slider">                        
                    <div class="carousel-caption">
                        <h2>Madison Square Garden </h2>
                        <h4>Justin Timberlake 2014 Tour $199</h4>
                        <a href="#login">Plan to go <i class="fa fa-angle-right"></i></a>
                    </div>
                </div>
                <div class="item">
                    <img class="img-responsive" src="images/slider/bg2_billy_joel.jpg" alt="slider">    
                    <div class="carousel-caption">
                        <h2>Music Hall of Williamsburg </h2>
                        <h4>Billy Joel: Untouchable Rim Live $159</h4>
                        <a href="#login">Plan to go <i class="fa fa-angle-right"></i></a>
                    </div>
                </div>
                <div class="item">
                    <img class="img-responsive" src="images/slider/bg3_lp.jpg" alt="slider">    
                    <div class="carousel-caption">
                        <h2>Radio City Music Hall </h2>
                        <h4>Lincoln Park Farewell Concert $280</h4>
                        <a href="#login" >Plan to go <i class="fa fa-angle-right"></i></a>
                    </div>
                </div>              
            </div>
        </div>      
    </section>
    <!--/#home-->

    <section id="trend">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-md-9">
                    <div id="trend-carousel" class="carousel slide" data-interval="false">
                        <h2 class="heading">THE ROCKING Performers</h2>
                        <a class="even-control-left" href="#trend-carousel" data-slide="prev"><i class="fa fa-angle-left"></i></a>
                        <a class="even-control-right" href="#trend-carousel" data-slide="next"><i class="fa fa-angle-right"></i></a>
                        <div class="carousel-inner">
                            <div class="item active">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="single-trend">
                                            <img class="img-responsive" src="images/trend/trend1.jpg" alt="trend-image">
                                            <h4>Chester Bennington</h4>
                                            <h5>Vocal</h5>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="single-trend">
                                            <img class="img-responsive" src="images/trend/trend2.jpg" alt="trend-image">
                                            <h4>Mike Shinoda</h4>
                                            <h5>vocals, rhythm guitar</h5>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="single-trend">
                                            <img class="img-responsive" src="images/trend/trend3.jpg" alt="trend-image">
                                            <h4>Rob Bourdon</h4>
                                            <h5>drums</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="item">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="single-trend">
                                            <img class="img-responsive" src="images/trend/trend1.jpg" alt="trend-image">
                                            <h4>Chester Bennington</h4>
                                            <h5>Vocal</h5>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="single-trend">
                                            <img class="img-responsive" src="images/trend/trend2.jpg" alt="trend-image">
                                            <h4>Mike Shinoda</h4>
                                            <h5>vocals, rhythm guitar</h5>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="single-trend">
                                            <img class="img-responsive" src="images/trend/trend3.jpg" alt="trend-image">
                                            <h4>Rob Bourdon</h4>
                                            <h5>drums</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="guitar">
                    <img class="img-responsive" src="images/guitar.png" alt="guitar">
                </div>
            </div>          
        </div>
    </section><!--/#trend-->

    <section id="genre">
        <div class="genre-section">
            <div class="container">
                    <div class="col-sm-5">
                        
                    </div>
                </div>
            </div>
        </div>      
    </section><!--/#genre-->

    <section id="about">
        <div class="guitar2">               
            <img class="img-responsive" src="images/guitar2.jpg" alt="guitar">
        </div>
        <div class="about-content">                 
                    <h2>About LiveVibe</h2>
                    <p>We believe that an amazing concert can change your life. We built LiveVibe so we’d have one place to track our favorite bands so we’d never miss them live. We want to take the hassle out of finding out when your favorite bands are coming to your city. LiveVibe works by indexing many different ticket vendors, venue websites, and local newspapers to create the most comprehensive database of upcoming concerts happening around the world. It’s our mission to have every show happening anywhere, from your friend’s show at your local bar, right up to Lady Gaga tour dates in Tokyo.</p>  
        </div>
    </section><!--/#about-->

    <footer id="footer">
        <div class="container">
            <div class="text-center">
                <p> Copyright &copy; 2014<a href="https://github.com/CS6083-DB-Project" target="_blank"> LiveVibe </a> <br> Designed by <a href="https://github.com/xun-gong" target="_blank">Xun Gong</a> + <a href="https://github.com/monofish" target="_blank">Wei Yu</a> </p>               
            </div>
        </div>
    </footer>
    <!--/#footer-->
    <style>
    .navbar-brand {
      background-color: #A30000;
      height: 80px;
      margin-bottom: 20px;
      position: relative;
      width: 469px;
      opacity: .95
    }
    </style>
  
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>
    <script type="text/javascript" src="js/gmaps.js"></script>
    <script type="text/javascript" src="js/smoothscroll.js"></script>
    <script type="text/javascript" src="js/jquery.parallax.js"></script>
    <script type="text/javascript" src="js/jquery.scrollTo.js"></script>
    <script type="text/javascript" src="js/jquery.nav.js"></script>
    <script type="text/javascript" src="js/main.js"></script>  
</body>
</html>
