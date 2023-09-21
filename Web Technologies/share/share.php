<?php
    session_start();
    if(!(isset($_SESSION['email']))) //necessario il check per vedere se l'email è registrata al sito
    header("location:../login/login.html");
?>

<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Share Your Ideas</title>
        <link rel="stylesheet" href="../style.css">
        <link rel="stylesheet" href="share.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link rel=”stylesheet” href=”../css/bootstrap.min.css”>
        <script src=”../js/bootstrap.min.js”></script>
        <script src="https://kit.fontawesome.com/b9c50d0ec6.js" crossorigin="anonymous"></script>
        <script type="text/javascript" src="../script.js"></script>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,600;0,700;1,200&display=swap" 
            rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <link rel="icon" type="image/x-icon" href="../images/robot.png">
        <script src="https://kit.fontawesome.com/b9c50d0ec6.js" crossorigin="anonymous"></script>
        
    </head>
    <body>

    <section class="sub-header">
            <nav>

        <a href="../index.php"><img src="../imgs/white-stencil.png"></a>

        <div class="nav-links" id="navLinks">

            <i class="fa fa-times" onclick="hideMenu()"></i>

            <ul>
                <li><a href="../index.php">HOME</a></li>
                <li><a href="../explore.php">EXPLORE</a></li>
                <li><a href="../lnp/quiz.php?q=0">PLAY</a></li>
                <li><a href="../articles.php">ARTICLES</a></li>
                <li><a href="../contact.php">CONTACT</a></li>
                <li><a href=".././profile/profile.php">PROFILE</a></li>
                <li>
                <div class="dropdown fullscreen">
                    <button class="dropbtn"><ion-icon name="person-sharp"></ion-icon>
                    </button>
                    <div class="dropdown-content fullscreen">
                        <?php
                        if (!isset($_SESSION['email'])){
                            echo '
                    <a class = "fullscreen" href="../login/login.html">LOGIN</a>
                    <a class = "fullscreen" href="../signin/signin.html">SIGN IN</a>
                        ';}
                        else
                        echo '<p>Ciao ' . $_SESSION['name'] . '!</p>
                    <a class = "fullscreen" href="../logout.php">LOGOUT</a>';
                    ?>
                    </div>
                </div>     
                </li>
                <?php
                    if (!isset($_SESSION['email'])){
                        echo '
                        <li>
                        <a class ="smallscreen" href="../login/login.html">LOGIN</a>
                        </li>
                        <li>
                        <a class = "smallscreen" href="../signin/signin.html">SIGN IN</a>
                        </li>
                    ';}
                    else
                        echo '<li>
                        <a class = "smallscreen" href="../logout.php">LOGOUT</a>
                        </li>';
                ?>
            </ul>
        </div>

        <i class="fa fa-bars" onclick="showMenu()"></i> 

        </nav>
        <?php
            $db = pg_connect('host=localhost port=5432 dbname=postgres user=postgres password=pgsql')
            or die('Could not connect: ' . pg_last_error());
            $email = $_SESSION['email'];
            $nome = $_SESSION['name'];
            // fetching data from table "users"
            $q = "select * from users where email=$1";
            $res = pg_query_params($db, $q, array($email));
            $line = pg_fetch_array($res, null, PGSQL_ASSOC);
            $profilo = "../images/" . $line['photoURL'];
            echo "<h1>Share</h1>";
            ?>
    </section>
        <section class="share row">
        <?php
            if(isset($_GET['posted'])){
                $q="insert into commenti values ($1,$2,$3)";
                $res=pg_query_params($db,$q,array($email,$_POST['post'],0));
                echo '<h2>Posted :)</h2>
                      <a href=../profile/profile.php><button class="hero-btn-1">Visualizza il tuo profilo</button></a>';
            }
            else{
                echo "<div class=\"sections row\">
                        <div class=\"firstSection col-xs-6 col-lg-6\">
                            <div class='profile'>
                            <img src=\"$profilo\" id=img>
                            </div>
                            </div>
                            <div class=\"secondSection col-xs-6 col-lg-6\">
                                    <h2>Scrivi qui la tua idea</h2>
                                    <div class=post col-xs-6 col-lg-6>
                                        <form name=shareForm method=post action=\"./share.php?posted=yes\">
                                            <input type=textarea name=post placeholder=\"Write something...\" maxlenght=200 required/>
                                            <button type=submit class=\"hero-btn-1\">Post</button>
                                        </form>
                                    </div>
                            </div>
                        </div>";
                }
           
        ?>
        </section>
    <script text="text/javascript" src="../effects.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    </body>
</html>