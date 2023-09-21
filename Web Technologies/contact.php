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
    <h1>Contact Us</h1>

    </section>
    
    <!--------- CONTACT CONTENT -------->

    <section class="location">

        <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d11879.33925184778!2d12.5209527!3d41.8964095!3m2!1i1024!2i768!
        4f13.1!3m3!1m2!1s0x0%3A0x773e8360f9a9e5e!2sEdificio%20Marco%20Polo!5e0!3m2!1sit!2ses!4v1650792974163!5m2!1sit!2ses" 
        width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    
    </section>

    <section class="contact-us">

        <div class="row1">
            <div class="contact-col">
                <div>
                    <i class="fa fa-home"></i>
                    <span>
                        <h5>Viale dello Scalo San Lorenzo 82, Edificio Marco Polo</h5>
                        <p>Roma, Italia, IT</p>
                    </span>
                </div>
                <div>
                    <i class="fa fa-phone"></i>
                    <span>
                        <h5>+39 325 667 3427</h5>
                        <p>Dal Lunedì al Venerdì, 9:00-19:00</p>
                    </span>
                </div>
                <div>
                    <i class="fa fa-envelope-o"></i>
                    <span>
                        <h5>cadmus@gmail.com</h5>
                        <p>Email us your query</p>
                    </span>
                </div>
            </div>
            
            <div class="contact-col">
                <form action="form-handler.php" method="post">
                    <input type="text" name="name" placeholder="Enter your name" required>
                    <input type="email" name="email" placeholder="Enter email address" required>
                    <input type="text" name="subject" placeholder="Enter your subject" required>
                    <textarea rows="30" name="message" placeholder="message" required></textarea>
                    <button type="submit" class="hero-btn red-btn">Send Message</button>
                </form>
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