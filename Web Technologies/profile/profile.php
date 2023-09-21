<?php
    session_start();
    if(!(isset($_SESSION['email']))) //necessario il check per vedere se l'email è registrata al sito
        header("location:../login/login.html");
    class ComLike{
        public $comm;
    }

?>

<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Profile</title>
        <link rel="stylesheet" href="../style.css">
        <link rel="stylesheet" href="../signin/signin.css">
        <link rel="stylesheet" href="profile.css">
        <link rel="preconnect" href="https://fonts.googleapis.com"> 
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script> 
        <link href="https://fonts.googleapis.com/css2?
         family=Poppins:ital,wght@0,300;0,400;0,600;0,700;1,200&display=swap" rel="stylesheet">
         <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://kit.fontawesome.com/b9c50d0ec6.js" crossorigin="anonymous"></script>
        <script type="text/javascript" src="../script.js"></script>
        <script type="text/javascript" src="../vue.js"></script>
        
    </head>
    <body>
    <section class="sub-header">

<!--------- NAVIGATION BAR -------->

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
                    <li><a href="#">PROFILE</a></li>
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
                $cognome = $line['lastName'];
                $descrizione = $line['description'];
                $data = $line['birth'];
                $luogo = $line['continent'];
                // fetching data from table "commenti"
                $q = "select * from commenti where email=$1";
                $res = pg_query_params($db, $q, array($email));
                $arr = array();
                while ($line = pg_fetch_array($res, null, PGSQL_ASSOC)) {
                    $item = new ComLike();
                    $aux = $line['commento'];
                    $item->comm = str_replace(" ", "-", $aux);
                    $item->like = $line['like'];
                    $arr[] = $item;
                }
                $json = JSON_encode($arr);
                echo "<h1>$nome $cognome</h1>";
                ?>
    </section>
        <div class="container-fluid sfondo">
        <section class="data row">      <!-- data -->
            <div class="firstSection col-xs-6 col-lg-6">   <!-- firstSection -->
             <?php   
                echo "<div class=\"profile\" style=background-image:url($profilo)></div>";
            ?>
            </div>
            <div class=" secondSection col-xs-6 col-lg-6">    <!-- secondSection -->
                <h2> Informations </h2>
            <?php   echo ' <span> <i class="fa fa-gift"></i>' ." <p>Birth: </p> <div class=field> $data </div> </span>".
               ' <span> <i class="fa fa-globe"></i>'." <p>Place: </p> <div class=field> $luogo </div> </span>".
                '<span><i class="fa fa-commenting-o"></i>'." <p>Description: </p> <div class=field> $descrizione </div> </span> "
                . '<span> <i class="fa fa-book"></i> <p>Quiz History: </p>' . '
                <div id="history" class="carousel slide">';

                $q2 = "SELECT * FROM history WHERE email=$1 ORDER BY data DESC";
                $r2 = pg_query_params($db,$q2,array($email));
                if(!$r2)
                    die('errore query 2');
                if (!$u= pg_fetch_array($r2, null, PGSQL_ASSOC)){
                    echo '</div> <div class="testimonial-col"> <p> Nothing to show yet </p>';}
                
                else {
                    echo '<div class="carousel-inner">';
                    $i=0;
                    while($line= pg_fetch_array($r2, null, PGSQL_ASSOC)){
                        
                        if($i==0){
                            echo '
                            <div class="carousel-item active">';  
                        }
                        else{
                            echo '
                            <div class="carousel-item container">';
                        }
                        echo'
                                <a>'. $line['punti'] .'/10</a><br>
                                <a>'. date('H:i', strtotime($line["data"])) .'</a><br>
                                <a>'. date('d/m/Y', strtotime($line["data"])) .'</a>
                            </div>';  
                        $i++;
                    }
                    echo '</div>
                    

                        <button class="carousel-control-prev" type="button" data-bs-target="#history" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon"></span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#history" data-bs-slide="next">
                                <span class="carousel-control-next-icon"></span>
                            </button>
                            ';
                }
                echo '</div>';
            ?>
            </div>
        </section>
        <div class="row">
           <div class="col-xs-12">
                <h2 class="title"> Your comments </h2>
                <i class="fa fa-plus-circle"></i>
            </div>
         </div>
        <div class="row">
        <?php echo "<div class=\" testimonials  \" v-on:mouseenter=updateArr($json)>"; ?>     
            <div class="comments col-xs-12">
                <div v-if=" array.length==0 " class="testimonial-col"> <p>Nothing to show yet</p> </div>
                <div v-else v-for="x in array" class="testimonial-col ">
                <?php echo "<img src=$profilo>"; ?>
                <div>
                    <p>{{x.comm}}</p>
                    <?php echo "<h3>$nome</h3>"; ?>
                    <i class="fa fa-heart-o"></i>
                </div>
                </div>
            </div>
            <script type="text/javascript" src="profile.js"></script> 
            </div>  
            </div>
         </div>

        <div class="light-btn">
            <i class="fas fa-adjust" onclick="changeMode()"></i>
        </div>
         <script src="../effects.js"></script>

    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    </body>
</html>