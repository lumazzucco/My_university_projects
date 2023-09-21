<?php
session_start();
?>

<!DOCTYPE html>
<html>

    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Progetto LTW Website</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="style.css">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin> 
        <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,600;0,700;1,200&display=swap" 
        rel="stylesheet">
        <script src="https://kit.fontawesome.com/b9c50d0ec6.js" crossorigin="anonymous"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="script.js" ></script>
    </head>

    <body>

    <!--------- NAVIGATION BAR -------->
    
    <div class="hero">

        <video autoplay loop muted class="back-video">
            <source src="archive/video.mp4">
        </video>

        <nav>

            <a href="index.php"><img src="imgs/white-stencil.png"></a>

            <div class="nav-links" id="navLinks">

                <i class="fa fa-times" onclick="hideMenu()"></i>

                <ul>
                    <li><a href="index.php">HOME</a></li>
                    <li><a href="explore.php">EXPLORE</a></li>
                    <li><a href="./lnp/quiz.php?q=0">PLAY</a></li>
                    <li><a href="articles.php">ARTICLES</a></li>
                    <li><a href="contact.php">CONTACT</a></li>
                    <li><a href="./profile/profile.php">PROFILE</a>
                    </li>
                    <li>
                    <div class="dropdown fullscreen">
                        <button class="dropbtn"><ion-icon name="person-sharp"></ion-icon></button>
                        <div class="dropdown-content fullscreen">
                            <?php
                            if (!isset($_SESSION['email'])){
                                echo '
                        <a class = "fullscreen" href="./login/login.html">LOGIN</a>
                        <a class = "fullscreen" href="./signin/signin.html">REGISTER</a>
                            ';}
                            else
                            echo '<p>Ciao ' . $_SESSION['name'] . '!</p>
                        <a class = "fullscreen" href="./logout.php">LOGOUT</a>';
                        ?>
                        </div>
                    </div>     
                    </li>
                    <?php
                        if (!isset($_SESSION['email'])){
                            echo '
                            <li>
                            <a class ="smallscreen" href="./login/login.html">LOGIN</a>
                            </li>
                            <li>
                            <a class = "smallscreen" href="./signin/signin.html">REGISTER</a>
                            </li>
                        ';}
                        else
                            echo '<li>
                            <a class = "smallscreen" href="./logout.php">LOGOUT</a>
                            </li>';
                    ?>
                </ul>
            </div>
            
            <i class="fa fa-bars" onclick="showMenu()"></i> 

        </nav>   

        <div class="video-content">
             
            <h1>World's Biggest Automation and Robotics Website</h1>

            <p>Noi di Cadmus siamo pronti per il cambiamento... e tu?</p>
            <?php if (!isset($_SESSION['email']))
                echo '
            <a href="/signin/signin.html" class="hero-btn">Unisciti a Cadmus</a>';
            ?>
        </div>

    </div>
    

    <!--------- COURSE SECTION -------->

    <section class="course">

        <h1>What you'll find here</h1>
        
        <div class="paragraph">
            <p>Scopri cosa ti offre il sito!</p>
        </div>
        
        <div class="row1">
        <div class="course-col" id="esplora">
            <h3>Esplora</h3>
            <p> Curioso di scoprire tutte le novità del mondo della robotica? Hai voglia di 
                partecipare ad un evento deicato alle nuove tecnologie? La sezione "Esplora"
                ha da offrirti questo e altro.</p>
        </div>
        <div class="course-col" id="divertiti">
            <h3>Divertiti</h3>
            <p> Pensi di saperne più degli altri quando si parla di automatica e robotica? 
                Vediamo se sei veramente un 'technology-addicted', mettiti alla prova con questo test! .</p>
        </div>
        <div class="course-col" id="proponi">
            <h3>Proponi</h3>
            <p> La robotica ha bisogno di menti brillanti come la tua per essere sempre rinnovata
                e al passo coi tempi, collabora con altri utenti e condividi la tua idea di progetto,
                potresti essere d'ispirazione per una prossima realizzazione.</p>
        </div>
        </div>
    </section>
    
    <!--------- FAIR SECTION -------->

    <section class="fair">

        <h1>Latest Events</h1>

        <div class="paragraph">
            <p>La robotica intorno a te...</p>
        </div>
        
        <div class="row1">

            <div class="fair-col">
                <img src="images/maker-faire-2021.jpg">
                <a href="https://makerfaire.com">
                <div class="layer">
                    <h3>MAKER FAIRE</h3>
                </div>
                </a>
            </div>
            
            <div class="fair-col">
                <img src="images/aaa.jpg">
                <a href="https://www.allaboutautomation.de/en/">
                <div class="layer">
                    <h3>ALL ABOUT AUTOMATION</h3>
                </div>
                </a>
            </div>
            
            <div class="fair-col">
                <img src="images/robotics.png">
                <a href="https://www.roboticsandautomation.co.uk">
                <div class="layer">
                    <h3>ROBOTICS & AUTOMATION</h3>
                </div>
                </a>
            </div>
        </div>
    </section>
    
    <!--------- TESTIMONIALS SECTION -------->
    
    <section class="testimonials">

        <h1>Comments</h1>

        <div class="paragraph">
            <p>Cosa dice chi è entrato a far parte del Cadmus Project</p>
        </div>
        
        <div class="row row2">
            <div class=" testimonials" >    
            <div class="comments col-xs-12"> 
        <?php 
            $db = pg_connect('host=localhost port=5432 dbname=postgres user=postgres password=pgsql')
                or die('Could not connect: ' . pg_last_error());
            $q = "select * from commenti ";
            $res = pg_query($db, $q);
            while ($line = pg_fetch_array($res, null, PGSQL_ASSOC)) {
                $comm= $line['commento'];
                $email= $line['email'];
                $q2 = "select * from users where email=$1";
                $res2 = pg_query_params($db, $q2, array($email));
                $line2 = pg_fetch_array($res2, null, PGSQL_ASSOC);
                $profilo = "../images/" . $line2['photoURL'];
                $nome= $line2['firstName'];
                $cognome = $line2['lastName'];
                echo "
                        <div class=\"testimonial-col \">
                        <img src=$profilo>
                        <div>
                            <p>$comm</p>
                            <h5 style=\"color:#2a2e35\">$nome $cognome</h5>
                            <i class=\"fa fa-heart-o\"></i>
                        </div>
                        </div>";
                 }
            
            ?>
        
        
                </div>
                </div>
        
        </div>

    </section>
    
    
    <!--------- CALL TO ACTION SECTION -------->
    
    <section class="cta">

        <h1>Hai domande?<br>Scrivici!</h1>
        <a href="contact.php" class="hero-btn-contact">CONTACT US</a>
        
    </section>
    
    <!--------- FOOTER SECTION -------->

    <section class="footer">


    
        <p>“Il robot riconosce la realtà meglio dell’uomo, sa più di noi sul futuro, 
    perché lo calcola, <br/> non fa speculazioni e non sogna 
    ma viene guidato dai propri risultati (feedback) e non può sbagliarsi.” <br/>
    Max Frisch</p>
    
        
        <img src="./images/sapienza-big.png">
        <p>Made with <i class="fa fa-heart-o"></i> by Fabiola, Enrico e Ludovica</p>
        <div class="icons">
            <i class="fa fa-facebook"></i>
            <i class="fa fa-twitter"></i>
            <i class="fa fa-instagram"></i>
            <i class="fa fa-linkedin"></i>
        </div>

    </section>

    <!--------- LIGHT/DARK MODE BUTTON -------->

    <div class="light-btn">
        <i class="fas fa-adjust" onclick="changeMode()"></i>
    </div>
    <script src="effects.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    </body>

</html>

