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


        <h1>Explore</h1>

    </section>

    <!--------- ABOUT US CONTENT -------->

    <section class="about-us">
        
        <div class="row1">
            <div class="about-col">
                <h1>Partecipa ai nostri progetti</h1>
            <div class="paragraph">
            <p>Contattaci e collabora con altri membri del team Cadmus per ideare e progettare
               tecnologie innovative. <br> Dai sfogo alla tua creatività ed entra a contatto con
               altri appassionati di robotica!
            </p>

            </div>
            <a href="" class="hero-btn">EXPLORE NOW</a>
            </div>
            
            <div class="about-col">
                <img src="images/robot.jpg">
            </div>
        </div>
    
    </section>
    
    <!--------- PROJECTS CONTENT -------->
    
    <section class="portfolio">
        
        
            <h1>Progetti</h1>

            <div class="paragraph">
                <p>Ecco alcuni dei progetti realizzati dal nostro team di appassionati da tutto il mondo</p>
            </div>
        
        
        <div class="portfolios">

            <div class="portfolio-item">

                <div class="image">
                    <img src="archive/robotcat.jpg" alt="">
                </div>
                
                <div class="hover-items">

                    <h3>Petoi Robotic Cat</h3>

                    <div class="icons">
                        <a href="https://www.youtube.com/watch?v=ZX17mcpGfp8" class="icon">
                            <i class="fab fa-youtube"></i>
                        </a>
                    </div>

                </div>
            </div>

            <div class="portfolio-item">

                <div class="image">
                    <img src="archive/devastator.png" alt="">
                </div>
                
                <div class="hover-items">

                    <h3>Raspberry Pi Devastator Robot</h3>

                    <div class="icons">
                        <a href="https://www.youtube.com/watch?v=j6mglfhWZrQ" class="icon">
                            <i class="fab fa-youtube"></i>
                        </a>
                    </div>

                </div>
            </div>

            <div class="portfolio-item">

                <div class="image">
                    <img src="archive/quadruped.jpg" alt="">
                </div>
                
                <div class="hover-items">

                    <h3>Quadruped Robot</h3>

                    <div class="icons">
                        <a href="https://www.youtube.com/watch?v=TBHhbgf6zaQ" class="icon">
                            <i class="fab fa-youtube"></i>
                        </a>
                    </div>

                </div>
            </div>

            <div class="portfolio-item">

                <div class="image">
                    <img src="archive/hexapod.png" alt="">
                </div>
                
                <div class="hover-items">

                    <h3>Hexapod</h3>

                    <div class="icons">
                        <a href="https://youtu.be/VwTd5cWJx2M" class="icon">
                            <i class="fab fa-youtube"></i>
                        </a>
                    </div>

                </div>
            </div>

            <div class="portfolio-item">

                <div class="image">
                    <img src="archive/drone.jpg" alt="">
                </div>
                
                <div class="hover-items">

                    <h3>Micro Drone with Proximity Senor</h3>

                    <div class="icons">
                        <a href="https://youtu.be/cBl3gQlt3qs" class="icon">
                            <i class="fab fa-youtube"></i>
                        </a>
                    </div>

                </div>
            </div>

            <div class="portfolio-item">

                <div class="image">
                    <img src="archive/arm.jpg" alt="">
                </div>
                
                <div class="hover-items">

                    <h3>Programmable Robotic Arm</h3>

                    <div class="icons">
                        <a href="https://youtu.be/7qnQh85r_AM" class="icon">
                            <i class="fab fa-youtube"></i>
                        </a>
                    </div>

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