<?php
session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Progetto LTW Website</title>

        <link rel="stylesheet" href="style.css">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin> 
        <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,600;0,700;1,200&display=swap" 
        rel="stylesheet">

        <script src="https://kit.fontawesome.com/b9c50d0ec6.js" crossorigin="anonymous"></script>
        
    </head>

    <body>

    <!--------- SUB HEADER -------->   

    <section class="sub-header">

<!--------- NAVIGATION BAR -------->

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
                    <li><a href="./profile/profile.php">PROFILE</a></li>
                    <li>
                    <div class="dropdown fullscreen">
                        <button class="dropbtn"><ion-icon name="person-sharp"></ion-icon>
                        </button>
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
        <h1>Articles</h1>
    </section>

    <!--------- SWIPER -------->

    <!--------- BLOG CONTENT -------->
    
    <section class="blog-content">
        
        <div class="blog-row">

            <div class="archive-title">
                <h1>Archive</h1>
            </div>
            
            <div class="blogs">

                <div class="blog">

                    <img src="archive/article1.jpg" alt="">
                    
                    <div class="blog-text">
                        <h4>
                            Nel "domani" di Boston Dynamics robot e uomo instaureranno un legame
                        </h4>
                        <p>
                            Serve prima di tutto umiltà per riuscire nel campo della robotica, 
                            la capacità di digerire...
                        </p>
                    </div>

                    <a class="keep-reading" href="https://www.hdblog.it/robotica/articoli/n555683/boston-dynamics-futuro-robot-uomo/" target="_blank">
                        Continua a leggere
                    </a>

                </div>
                
                <div class="blog">
                    <img src="archive/article2.jpeg" alt="">
                    
                    <div class="blog-text">
                        <h4>
                            Arduino: cos'è e perché rappresenta il futuro
                        </h4>
                        <p>
                            Arduino non è un dispositivo elettronico utile solo 
                            per ingegneri ed esperti del settore che passano i loro giorni
                            a...
                        </p>
                    </div>

                    <a class="keep-reading" href="https://www.pirelli.com/global/it-it/life/arduino-cos-e-e-perche-rappresenta-il-futuro" target="_blank">
                        Continua a leggere
                    </a>
                </div>

                <div class="blog">

                    <img src="archive/article3.jpg" alt="">

                    <div class="blog-text">
                        <h4>
                            Intelligenza artificiale nella robotica industriale
                        </h4>
                        <p>
                            Le vendite dei robot industriali crescono e sarà un trend sempre più dominante. 
                            Il fattore AI è essenziale...
                        </p>
                    </div>

                    <a class="keep-reading" href="https://tech4future.info/intelligenza-artificiale-robotica-industriale-2022/" target="_blank">
                        Continua a leggere
                    </a>

                </div>

                <div class="blog">

                    <img src="archive/article4.jpg" alt="">

                    <div class="blog-text">
                        <h4>
                            Joseph Engelberger – Il padre della robotica
                        </h4>
                        <p>
                            Nato negli Stati Uniti, a lui si deve l’invenzione del primo robot industriale, 
                            l’Unimate#001. Gli incontri con Isaac Asimov e George Devol, lo sviluppo...
                        </p>
                    </div>
                    
                    <a class="keep-reading" href="https://www.ai4business.it/intelligenza-artificiale/gli-inventori-dellai/joseph-engelberger-il-padre-della-robotica/" target="_blank">
                        Continua a leggere
                    </a>

                </div>

                <div class="blog">

                    <img src="archive/article5.jpg" alt="">

                    <div class="blog-text">
                        <h4>
                            Domotica: la casa diventa sempre più intelligente
                        </h4>
                        <p>
                            La domotica semplifica la vita domestica, consentendo di gestire
                            in maniera intelligente tutti gli impianti nell’ottica della smart home...
                        </p>
                    </div>

                    <a class="keep-reading" href="https://www.infobuild.it/approfondimenti/domotica-la-casa-intelligente/" target="_blank">
                        Continua a leggere
                    </a>

                </div>

                <div class="blog">

                    <img src="archive/article6.jpg" alt="">

                    <div class="blog-text">
                        <h4>
                            BattleBots: dove i robot si danno battaglia per puro divertimento
                        </h4>
                        <p>
                            Sul ring di BattleBots, macchine armate e corazzate guidate al telecomando si battono 
                            in un torneo ad eliminazione...
                        </p>
                    </div>

                    <a class="keep-reading" href="https://www.technologyreview.it/battlebots-dove-i-robot-si-danno-battaglia-per-puro-divertimento/" target="_blank">
                        Continua a leggere
                    </a>

                </div>

            </div>

        </div>
    
    </section>
    
    <!--------- FOOTER SECTION -------->
    
    <section class="footer">


        <div class="paragraph">
        <p>“Il robot riconosce la realtà meglio dell’uomo, sa più di noi sul futuro, 
    perché lo calcola, <br/> non fa speculazioni e non sogna 
    ma viene guidato dai propri risultati (feedback) e non può sbagliarsi.” <br/>
    Max Frisch</p>
        </div>
        
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