<?php
require_once("functions/DB.php");
require_once("functions/proverki.php");
require_once("functions/path.php");
require_once("functions/auth.php");
/********************************
Общие настройки
 ********************************/
$thisPage = path_withoutGet();
$stranica  = "login";

/********************************
Если был logout
********************************/
if($_GET["logout"]):
    auth_exit();
    echo "
        <script>
            window.location = 'index.php';
        </script>";
    exit();
endif;


if(isset($_POST["method_name"])):

    switch ($_POST["method_name"]):
        case $_POST["method_name"] == "auth" && filter_var($_POST["email"], FILTER_VALIDATE_EMAIL) && $_POST["pass"]:
            $email  = strtolower(filter_var($_POST["email"]));
            $pass   = proverka1($_POST["pass"]);

            $resDb = db_row("SELECT * FROM users WHERE email='".$email."' AND pass='".md5($pass)."'", true)["item"];
            if($resDb){
                setcookie("ID", $resDb["ID"], strtotime("+1 day"), "/");
                setcookie("token", $resDb["pass"], strtotime("+1 day"), "/");

                echo "
                        <script>
                            window.location = 'index.php';
                        </script>";
                exit();

            }


            break;
    endswitch;

endif;

/*------------------------------
Достенем инфо про эту страницу
-------------------------------*/
$resInfo = db_row("SELECT * FROM page_settings WHERE stranica='".$stranica."'")["item"];
if($resInfo){ $resInfo["meta"] = json_decode($resInfo["meta"], true); }

/*------------------------------
Достенем инфо про эту register
-------------------------------*/
$resInfo2 = db_row("SELECT * FROM page_settings WHERE stranica='register'")["item"];
if($resInfo2){ $resInfo2["meta"] = json_decode($resInfo2["meta"], true); }



?>
<!DOCTYPE html>
<html>
<head>
    <title><? echo $resInfo["meta"]["title"]; ?></title>
    <link href="css/bootstrap.css" rel="stylesheet" type="text/css" media="all" />
    <!-- Custom Theme files -->
    <!--theme-style-->
    <link href="css/style.css" rel="stylesheet" type="text/css" media="all" />
    <link href="css/adm_elements.css" rel="stylesheet" type="text/css" media="all" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!--//theme-style-->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="keywords" content="<? echo @$resInfo["meta"]["keywords"]; ?>" />
    <meta name="description" content="<? echo @$resInfo["meta"]["desc"]; ?>" />

    <script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
    <!--theme-style-->
    <link href="css/style4.css" rel="stylesheet" type="text/css" media="all" />
    <!--//theme-style-->
    <script src="js/jquery.min.js"></script>
    <!--- start-rate---->
    <script src="js/jstarbox.js"></script>
    <link rel="stylesheet" href="css/jstarbox.css" type="text/css" media="screen" charset="utf-8" />
    <script type="text/javascript">
        jQuery(function() {
            jQuery('.starbox').each(function() {
                var starbox = jQuery(this);
                starbox.starbox({
                    average: starbox.attr('data-start-value'),
                    changeable: starbox.hasClass('unchangeable') ? false : starbox.hasClass('clickonce') ? 'once' : true,
                    ghosting: starbox.hasClass('ghosting'),
                    autoUpdateAverage: starbox.hasClass('autoupdate'),
                    buttons: starbox.hasClass('smooth') ? false : starbox.attr('data-button-count') || 5,
                    stars: starbox.attr('data-star-count') || 5
                }).bind('starbox-value-changed', function(event, value) {
                    if(starbox.hasClass('random')) {
                        var val = Math.random();
                        starbox.next().text(' '+val);
                        return val;
                    }
                })
            });
        });
    </script>
    <!---//End-rate---->
</head>
<body>
<!--header-->
<? include_once("blocks/face/header.php"); ?>
<!--banner-->
<div class="banner-top">
    <div class="container">
        <h1>Login</h1>
        <em></em>
        <h2><a href="index.php">Home</a><label>/</label>Login</a></h2>
    </div>
</div>
<!--login-->
<div class="container">
    <div class="login">

        <form action="<? echo $thisPage; ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="method_name" value="auth">

            <div class="col-md-6 login-do">
                <div class="login-mail">
                    <input name="email" type="text" placeholder="Email" required="">
                    <i  class="glyphicon glyphicon-envelope"></i>
                </div>
                <div class="login-mail">
                    <input name="pass" type="password" placeholder="Password" required="">
                    <i class="glyphicon glyphicon-lock"></i>
                </div>
                <a class="news-letter " href="#">
                    <label class="checkbox1"><input type="checkbox" name="checkbox" ><i> </i>Forget Password</label>
                </a>
                <label class="hvr-skew-backward">
                    <input name="submit" type="submit" value="login">
                </label>
            </div>
            <div class="col-md-6 login-right">
                <h3><? echo $resInfo2["title"] ?></h3>

                <? echo $resInfo2["text"]; ?>

                <a href="register.php" class=" hvr-skew-backward">Register</a>

            </div>

            <div class="clearfix"> </div>
        </form>
    </div>

</div>

<!--//login-->

<!--brand-->
<div class="container">
    <div class="brand">
        <div class="col-md-3 brand-grid">
            <img src="images/ic.png" class="img-responsive" alt="">
        </div>
        <div class="col-md-3 brand-grid">
            <img src="images/ic1.png" class="img-responsive" alt="">
        </div>
        <div class="col-md-3 brand-grid">
            <img src="images/ic2.png" class="img-responsive" alt="">
        </div>
        <div class="col-md-3 brand-grid">
            <img src="images/ic3.png" class="img-responsive" alt="">
        </div>
        <div class="clearfix"></div>
    </div>
</div>
<!--//brand-->
</div>

</div>
<!--//content-->
<!--//footer-->
<div class="footer">
    <div class="footer-middle">
        <div class="container">
            <div class="col-md-3 footer-middle-in">
                <a href="index.php"><img src="images/log.png" alt=""></a>
                <p>Suspendisse sed accumsan risus. Curabitur rhoncus, elit vel tincidunt elementum, nunc urna tristique nisi, in interdum libero magna tristique ante. adipiscing varius. Vestibulum dolor lorem.</p>
            </div>

            <div class="col-md-3 footer-middle-in">
                <h6>Information</h6>
                <ul class=" in">
                    <li><a href="404.html">About</a></li>
                    <li><a href="contact.php">Contact Us</a></li>
                    <li><a href="#">Returns</a></li>
                    <li><a href="contact.php">Site Map</a></li>
                </ul>
                <ul class="in in1">
                    <li><a href="#">Order History</a></li>
                    <li><a href="wishlist.html">Wish List</a></li>
                    <li><a href="login.php">Login</a></li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="col-md-3 footer-middle-in">
                <h6>Tags</h6>
                <ul class="tag-in">
                    <li><a href="#">Lorem</a></li>
                    <li><a href="#">Sed</a></li>
                    <li><a href="#">Ipsum</a></li>
                    <li><a href="#">Contrary</a></li>
                    <li><a href="#">Chunk</a></li>
                    <li><a href="#">Amet</a></li>
                    <li><a href="#">Omnis</a></li>
                </ul>
            </div>
            <div class="col-md-3 footer-middle-in">
                <h6>Newsletter</h6>
                <span>Sign up for News Letter</span>
                <form>
                    <input type="text" value="Enter your E-mail" onfocus="this.value='';" onblur="if (this.value == '') {this.value ='Enter your E-mail';}">
                    <input type="submit" value="Subscribe">
                </form>
            </div>
            <div class="clearfix"> </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container">
            <ul class="footer-bottom-top">
                <li><a href="#"><img src="images/f1.png" class="img-responsive" alt=""></a></li>
                <li><a href="#"><img src="images/f2.png" class="img-responsive" alt=""></a></li>
                <li><a href="#"><img src="images/f3.png" class="img-responsive" alt=""></a></li>
            </ul>
            <p class="footer-class">&copy; 2016 Shopin. All Rights Reserved | Design by  <a href="http://w3layouts.com/" target="_blank">W3layouts</a> </p>
            <div class="clearfix"> </div>
        </div>
    </div>
</div>
<!--//footer-->


<? if(is_admin()): ?>
    <!--Админ панель-->
    <section id="admBar">
        <a href="#" class="tymbler"><i class="material-icons">&#xE23E;</i></a>
        <ul class="listBtns">
            <li>
                <a href="adm/page_settings.php?stranica=<? echo $stranica; ?>">Редактировать старницу</a>
            </li>
            <li>
                <a href="adm/socials.php">Соц. сети</a>
            </li>
            <li>
                <a href="adm/brands.php">Партнеры</a>
            </li>

        </ul>
    </section>
<? endif; ?>


<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->

<script src="js/simpleCart.min.js"> </script>
<!-- slide -->
<script src="js/bootstrap.min.js"></script>
<script src="js/face/admBar.js" type="text/javascript"></script>

</body>
</html>