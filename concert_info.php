<!-- Project: LiveVibe
Author: Xun Gong, Wei Yu
Date: Dec 4th, 2014 -->

<!-- PHP and manipulate with livevibe database -->
<?php
require ("connectdb.php");

if (isset($_SESSION["username"])) {
// Set local var
    $username = $_SESSION["username"];
    $type = $_SESSION["login_type"];
    $cid_info = $_GET["link"];
$_SESSION["go_to_cid"] = $cid_info; // session var for later use

// // Update lastaccesstime when page load
$stmtLAT = $mysqli->prepare("CALL update_LAT(?,?,?)");
$LAT = date("Y-m-d H:i:s");
$stmtLAT->bind_param('sss', $username, $LAT, $login_type);
$stmtLAT->execute();
$mysqli->next_result();

// Grab Concert Info.
$stmtConInfo = $mysqli->prepare("SELECT C.cid, C.artistname, C.start_time,
    V.vname, V.street, V.city, V.state, V.zipcode
    FROM concerts AS C JOIN venues AS V ON C.vid = V.vid AND C.cid = ?");
$stmtConInfo->bind_param('s', $cid_info);
$stmtConInfo->execute();
$stmtConInfo->bind_result($cid, $artistname, $start_time, $vname, $street, $city, $state, $zipcode);
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
}


$mysqli->next_result();

// Grab Who Posted This Concert
$poster = "LiveVibe"; // By Default
$stmt1 = $mysqli->prepare("CALL who_post(?)");
$stmt1->bind_param("s", $cid_info);
$stmt1->execute();
$stmt1->bind_result($p);
while ($stmt1->fetch()) {
    $poster = $p;
}

$mysqli->next_result();

// Grab Average Rating
$avgRating = 0;
$stmt2 = $mysqli->prepare("SELECT cid, AVG(rating) AS avgR FROM attendance GROUP BY cid HAVING cid = ?");
$stmt2->bind_param("s", $cid_info);
$stmt2->execute();
$stmt2->bind_result($cid, $avgR);
while ($stmt2->fetch()) {
    $avgRating = $avgR;
    $avgRating = intval($avgRating);
}

$mysqli->next_result();

// Grab All the Comments
$Comments = array();
$stmtCM = $mysqli->prepare("SELECT username, content, c_time FROM ucomments WHERE cid = ? AND content IS NOT NULL ORDER BY c_time ASC");
$stmtCM->bind_param("s", $cid_info);
$stmtCM->execute();
$stmtCM->bind_result($username, $content, $c_time);
while ($stmtCM->fetch()) {
    $one_comment = array(
        "username" => $username,
        "content"  => $content,
        "c_time"   => $c_time
        );
    $Comments[] = $one_comment;
}
$mysqli->next_result();

// Grab ALL the Reviews
$Reviews = array();
$stmtRWL = $mysqli->prepare("SELECT username, rating, review, rv_time FROM attendance WHERE cid = ? AND rating IS NOT NULL AND review IS NOT NULL ORDER BY rv_time ASC");
$stmtRWL->bind_param("s", $cid_info);
$stmtRWL->execute();
$stmtRWL->bind_result($username, $rating, $review, $rv_time);
while ($stmtRWL->fetch()) {
    $one_review = array(
        "username" => $username,
        "rating"   => $rating,
        "review"   => $review,
        "rv_time"   => $rv_time
        );
    $Reviews[] = $one_review;
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

    <div id="concert_page" class="container">
        <div class="panel panel-default">

            <div class="panel-heading text-center">
            <div class="concert-brief">
                <div class="concert-caption">
                    <h3><?php echo $con_info["start_time"];?></h3>
                    <h1><strong>
                        <?php  echo "<a href=\"artist_public.php?link=", urlencode($con_info["artistname"]), "\">".$con_info["artistname"]."</a>"; ?>
                    </strong></h1>
                </div>
                <div class="location">
                    <h4>
                        <?php echo $con_info["vname"];?>
                    </h4>
                    <p>
                        <span class="addr">
                            <span class="street"><?php echo $con_info["street"]; echo ", ";?></span>
                            <span class="city"><?php echo $con_info["city"]; echo ", ";?></span>
                            <span class="zipcode"><?php echo $con_info["zipcode"]; ?></span>
                            <br>
                            <h6><span class="Poster"><?php echo "Posted by: ".$poster;?></span></h6>
                            Ratings: <?php echo $avgRating;?>
                        </span>
                    </p>
                </div>
</div>
                <div class="row concert-btns">
                    <div class="col-md-6 text-center">
                        <form action="rating.php" method="POST">
                            <select class="" name="score">
                                <?php
                                for ($i=1; $i <= 10; $i++) { 
                                    echo "<option value=\"".$i."\">".$i."</option>";
                                }
                                ?>
                            </select> 
                            <input type="submit" value="Rating" class="btn btn-warning">
                        </form>

                    </div>

                    <div class="col-md-6">
                        <a class="btn btn-info" href="plan_to.php" role="button">Plan to Go</a>
                    </div>

                </div> <!-- row -->

            </div> <!-- .panel-heading -->


            <div class="panel-body">

                <div class="jumbotron">
                    <div class="row">
                        <div class="col-md-7 artist-bio">
                            <h3> Who is 
                                <?php  echo "<a href=\"artist_public.php?link=", urlencode($con_info["artistname"]), "\">".$con_info["artistname"]."</a>"; ?>?
                            </h3>
                            <div class="artist-biography">
                                <div class="standfirst">
                                    <p class="text-justify">
                                        <?php  echo $con_info["{PHP variable}"]; ?> <!-- need PHP variable -->
                                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aut neque quas necessitatibus doloribus quibusdam sapiente amet, dolore ab rerum beatae omnis nostrum sequi ducimus harum? Esse a quis sit, eos, voluptatibus rem nihil explicabo quisquam unde debitis nulla nesciunt commodi iusto id voluptatum facere magni vel totam quo provident tenetur!
                                        <span class="read-more-link-container">
                                            <?php  echo "<a href=\"artist_public.php?link=", urlencode($con_info["artistname"]), "\">Read more</a>"; ?>
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-5 concert-media">
                            <iframe src="//player.vimeo.com/video/79764317?title=0&amp;byline=0&amp;badge=0&amp;title=0&amp;portrait=0&amp;color=ffffff" height="250" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                            <div class="caption">
                            </div>
                        </div>

                    </div> <!-- row -->
                </div> <!-- jumbotron -->


                <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" href="#comment">Comment</a></li>
                    <li><a data-toggle="tab" href="#review">Review</a></li>
                </ul>
                <br>

                <!-- Tab Content -->
                <div class="tab-content">
                    <div id="comment" class="tab-pane fade in active">
                        <?php
                        foreach ($Comments as $c) {
                        echo "<div class=\"media\">";
                        echo    "<div class=\"media-body\">";
                        echo        "<span class=\"media-heading\">";
                        echo            "<a href=\"user_public.php?link=", urlencode($c["username"]), "\">";
                        echo                "<img src=\"http://api.randomuser.me/portraits/men/47.jpg\" class=\"img-circle img-responsive media-top pull-left\">";
                        echo                $c["username"];
                        echo            "</a>: </span>";
                        echo            $c["content"];
                        echo    "<h4 class=\"pull-right\">".$c["c_time"]."</h4>";
                        echo    "</div>";    
                        echo "</div>";
                        }
                        ?>

                        <br>
                        <form class="form-group" role="form" action="ucomment.php" method="POST">
                            <textarea row="3" type="text" name="comment" id="comment" class="form-control input-lg" placeholder="Wanna tell others something?"></textarea>
                            <input type="submit" value="Submit" class="btn btn-info btn-block btn-sm">
                        </form>

                    </div> <!-- #comment.tab-pane -->

                    <div id="review" class="tab-pane fade in">

                            <?php
                            foreach ($Reviews as $r) {
                                echo "<div class=\"media\">";
                                echo    "<a class=\"pull-left\" href=\"user_public.php?link=", urlencode($r["username"]), "\">";
                                echo        "<img src=\"http://api.randomuser.me/portraits/men/47.jpg\" class=\"img-circle\"></a>";
                                echo    "<div class=\"media-body\"><div class=\"media-heading\">";
                                echo        "<a class=\"\" href=\"user_public.php?link=", urlencode($r["username"]), "\">";
                                echo                $r["username"];
                                echo            "</a>";
                                echo                " <small>".$r["rv_time"];
                                echo            "</small><div class=\"pull-right\">Rating: <em>".$r["rating"]."</em>/10</div></div><p>";
                                echo            $r["review"];
                                echo    "</p></div> <!-- .media-body -->" ;    
                                echo "</div> <!-- .media -->";
                            }
                            ?>
                            <br>

                            <form class="form-group" role="form" action="ureview.php" method="POST">
                                <textarea row="3" type="text" name="review" id="review" class="form-control input-lg" placeholder="How do you feel about the concert?"></textarea>
                                <input type="submit" value="Submit" class="btn btn-primary btn-block btn-sm">
                            </form>

                    </div> <!-- #review.tab-pane -->

                </div> <!-- .tab-content -->

            </div> <!-- .panel-body -->

        </div> <!-- .panel -->
    </div> <!-- #concert_page -->

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
            /*padding-top: 100px;*/
            /*height: 100%;*/
            /*width: 100%;*/
        }

        .navbar-brand {
            background-color: #A30000;
            height: 80px;
            margin-bottom: 20px;
            position: relative;
            width: 613px;
            opacity: .95
        }

        #concert_page {
            padding-top: 100px;
            color: #03695E;
        }
        
        #concert_page .panel {
            border: 0;
        }

        #concert_page .panel-heading {
            background-image: url("./images/slider/bg2.jpg");
            /*-webkit-background-size: 100%;*/
            background-size: cover;
            padding-top: 40px;
            padding-bottom: 40px;
        }

        .concert-brief {
            min-height: 300px;
            color: white;
            text-shadow: 1px 0px 1px #222;
        }

        .concert-caption strong{
            font-weight: 1200;
        }

        #comment img {
            height: 60px;
            margin-right: 15px;
        }

        #review img {
            height: 120px;
            margin-right: 15px;
        }

        .media-heading a {
            text-transform: uppercase;
            font-weight: 120;
        }

        .media-body {
            /*width: 100%;*/
        }

        iframe {
            width: 100%;
        }

        .btn {
            border-radius:5px;
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

