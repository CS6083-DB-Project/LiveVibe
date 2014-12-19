<!-- Project: LiveVibe
Author: Xun Gong, Wei Yu
Date: Dec 4th, 2014 -->

<!-- PHP and manipulate with livevibe database -->
<?php
// Session start in connectdb.php file
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
                            <li class="scroll"><a href="index.php">Home</a></li>
                            <li class="scroll"><a href="#">Trend</a></li>
                            <li class="scroll"><a href="all_genre.php">Genre</a></li>
                            <li class="scroll"><a href="search.php">Search</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>                    
    </header>
    <!--/#header--> 

    <div class="container" id="reg_page">
        <div class="panel panel-default">

            <div class="panel-heading">
                <h4 class="text-center">Welcome to LiveVibe</h4>
            </div>

            <div class="panel-body">
                <form role="form" action="register_backend.php" method="POST">
                    <div class="form-group">
                        <input type="text" name="username" id="username" class="form-control input-md" placeholder="Nickname">
                    </div>

                    <div class="form-group">
                        <input type="password" name="password" id="password" class="form-control input-md" placeholder="Password">
                    </div>

                    <div class="form-group">
                        <input type="text" name="realname" id="realname" class="form-control input-md" placeholder="Realname">
                    </div>

                    <div class="form-group">
                        <input type="text" name="birth" id="birth" class="form-control input-md" placeholder="Birthday">
                    </div>

                    <div class="form-group">
                        <input type="text" name="city" id="city" class="form-control input-md" placeholder="City">
                    </div>

                    <div class="form-group">
                        <input type="text" name="state" id="state" class="form-control input-md" placeholder="State">
                    </div>

                    <div class="form-group">
                        <input type="zipcode" name="zipcode" id="zipcode" class="form-control input-md" placeholder="Zipcode">
                    </div>

                    <div class="form-group">
                        <input type="bio" name="bio" id="bio" class="form-control input-md" placeholder="Bio (Artist)">
                    </div>
                    <!-- Checkbox -->
                    
                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <label class="radio-inline">
                                    <input type="radio" name="type" value="user"  class="form-control input-sm"> User
                                </label>
                                
                            </div>
                        </div>

                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <label class="radio-inline">
                                    <input type="radio" name="type" value="artist" class="form-control input-sm"> Artist
                                </label> 
                            </div>
                        </div>
                    </div>

                    <input type="submit" value="Submit" class="btn btn-info btn-block">

                </form>
            </div>  <!-- .panel-body -->
        
        </div> <!-- .panel -->
    </div> <!-- #reg_page.container -->

        <style>
        body {
            background-image: url("./images/bg/register_bg.png");
            background-color: #A30000;
        }

        .navbar-brand {
          background-color: #A30000;
          height: 80px;
          margin-bottom: 20px;
          position: relative;
          width: 756px;
          opacity: .95
        }

        #reg_page {
            padding-top: 100px;
            max-width: 730px;
        }

        #reg_page .form-control {
            font-size: 14px;
            height: 34px;
            margin: 0;
            padding: 6px 12px;
        }   // fallback to Bootstrap default

        .panel {
            /*background: rgba(255, 255, 255, 0.8);*/
            box-shadow: rgba(0, 0, 0, 0.3) 20px 20px 20px;
        }

        .radio-inline {
            color: #A30000;
            font-size: 16px;
        }

        .radio-inline input {
            box-shadow: none;
        }

        </style>      

    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/smoothscroll.js"></script>
    <script type="text/javascript" src="js/jquery.parallax.js"></script>
    <script type="text/javascript" src="js/jquery.scrollTo.js"></script>
    <script type="text/javascript" src="js/jquery.nav.js"></script>
    <script type="text/javascript" src="js/main.js"></script>  
</body>
</html>
