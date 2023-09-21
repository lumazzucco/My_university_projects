<?php
session_start();
if (!(isset($_SESSION['email']))) //necessario il check per vedere se l'email è registrata al sito
    header("location:../login/login.html");
?>
<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Results</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="lnp.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,600;0,700;1,200&display=swap" 
        rel="stylesheet">
    <link rel=”stylesheet” href=”../css/bootstrap.min.css”>
    <script src=”../js/bootstrap.min.js”></script>
    <script src="https://kit.fontawesome.com/b9c50d0ec6.js" crossorigin="anonymous"></script>
    <script type="text/javascript" src="../script.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="jquery.vnm.confettiButton.min.js"></script>
    <link rel="stylesheet" href="jquery.vnm.confettiButton.css" type="text/css" media="screen" />
    <script type="text/javascript" src="../vue.js"></script>
    <link rel="icon" type="image/x-icon" href="../images/robot.png"></head>
    <script src="https://kit.fontawesome.com/b9c50d0ec6.js" crossorigin="anonymous"></script>

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
                    <li><a href="#">PLAY</a></li>
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
        <h1>Results</h1>
<?php
    $db = pg_connect('host=localhost port=5432 dbname=postgres user=postgres password=pgsql')
    or die('Could not connect: ' . pg_last_error());
    $email = $_SESSION['email'];
    $q1 = "SELECT * FROM curr_score WHERE email = $1";
    $r1 = pg_query_params($db, $q1, array($email));
    $line = pg_fetch_array($r1, null, PGSQL_ASSOC);
    if (!$line || $line['lastans'] < 10)
        header("location: quiz.php?q=0");
    $ans = $line['ans'];
    $q2 = "DELETE FROM curr_score WHERE email = $1";
    $r2 = pg_query_params($db, $q2, array($email));
    if (!$r2)
        die("Errore nell'eliminazione utente");
    $score = $line['score'];
    echo '<div class="results" v-on:click="update(' . $score . ')">';
    ?>
    <h2 id="ris"> Clicca qui per scoprire i tuoi risultati!</h2>
    <h2 class="punti">
        <span v-if="n<5">{{punteggio}}</span>
        <span v-else-if="n>4 && n<9">{{punteggio}}</span>
        <span v-else class="good">{{punteggio}}</span>
    </h2>
    <h2 class="ris">{{risultato}}</h2>
    </div></section>
    <div id="hide" style="display: none;">
    
    <?php
    $q4 = "INSERT INTO history VALUES ($1,$2,$3)";
    $r4 = pg_query_params($db, $q4, array($email, $score, 'NOW()'));
    if (!$r4)
        die("Errore nell'inserimento in carriera");
    for ($id = 1; $id <= 10; $id++) {
        $q3 = "SELECT * FROM corrispondenze WHERE domanda=$1";
        $r3 = pg_query_params($db, $q3, array($id));
        if (!$r3)
            die("Errore ricerca corrispondenze");
        $cor = pg_fetch_array($r3, null, PGSQL_ASSOC);
        $sol = $cor['soluzione'];
        $query = "SELECT * FROM domande WHERE id=$1";
        $res = pg_query_params($db, $query, array($id));
        if (!$row = pg_fetch_array($res, null, PGSQL_ASSOC))
            die('Errore lettura dal database');
        $domanda = $row['testo'];
        $risposta1 = $row['a'];
        $risposta2 = $row['b'];
        $risposta3 = $row['c'];
        $risposta4 = $row['d'];
        $spiegazione = $row['spiegazione'];
        $img = $row['url'];
        echo '<script>
            $(document).ready(function(){
                $("#' . $ans[$id - 1] . '-' . $id . '").prop("checked", true);
                $("#' . $ans[$id - 1] . 's-' . $id . ' label").css("border-color", "red");
                $("#' . $sol . 's-' . $id . ' label").css("border-color","green");
                $(".good").confettiButton();
                $("#retry").click( function(){
                   $.get("learn.php");
                   location.replace("update.php?q=0");
                } );
            });
            </script>
                <div class="container-fluid">
                    <div class="panel">
                        <h2> ' . $domanda . ' </h2>
                        <div class="wrapper row">
                            <div class="button off" id="1s-' . $id . '"><input type="radio" id="1-' . $id . '" value="1" disabled> <label for="1-' . $id . '"> ' . $risposta1 . '</label></div>
                            <div class="button off" id="2s-' . $id . '"><input type="radio" id="2-' . $id . '" value="2" disabled> <label for="2-' . $id . '"> ' . $risposta2 . '</label></div>
                            <div class="button off" id="3s-' . $id . '"><input type="radio" id="3-' . $id . '" value="3" disabled> <label for="3-' . $id . '"> ' . $risposta3 . '</label></div>
                            <div class="button off" id="4s-' . $id . '"><input type="radio" id="4-' . $id . '" value="4" disabled> <label for="4-' . $id . '"> ' . $risposta4 . '</label></div>
                        </div>
                        <div class="why row">
                            <div class="col-9 spiegazione">' . $spiegazione . '</div>
                            <div class="col-3 media">';
                            if($id==4 || $id==8){
                                echo '
                                <video class="video" width="320" height="240" autoplay muted>
                                    <source src="img/'. $img . '" type="video/mp4" />
                                </video>';
                            }
                            else{
                                    echo '<img class="img" src="img/' . $img . '" ';
                            }
                            echo
                            '</div>
                        </div>
                    </div>
                </div>
                    ';
    }
    ?>
    <button id="retry" class="bottone hero-btn red-btn">Retry</button>
    </div>
    <div style="height: 10px;"></div>
    <script type="text/javascript" src="learn.js"></script>
    
    <section class="footer">


        <p>“Il robot riconosce la realtà meglio dell’uomo, sa più di noi sul futuro, 
        perché lo calcola, <br/> non fa speculazioni e non sogna 
        ma viene guidato dai propri risultati (feedback) e non può sbagliarsi.” <br/>
        Max Frisch</p>

        <img src="../images/sapienza-big.png">
        <p>Made with <i class="fa fa-heart-o"></i> by Fabiola, Enrico e Ludovica</p>
        <div class="icons">
            <i class="fa fa-facebook"></i>
            <i class="fa fa-twitter"></i>
            <i class="fa fa-instagram"></i>
            <i class="fa fa-linkedin"></i>
        </div>

        <div class="light-btn">
            <i class="fas fa-adjust" onclick="changeMode()"></i>
        </div>
        <script type="text/javascript" src="../effects.js"></script>

    </section>

    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>

</html>