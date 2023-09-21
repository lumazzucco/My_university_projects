<?php
session_start();
?>

<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log-in</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="../signin/signin.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?
         family=Poppins:ital,wght@0,300;0,400;0,600;0,700;1,200&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/b9c50d0ec6.js" crossorigin="anonymous"></script>
    <script type="text/javascript" src="../script.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="icon" type="image/x-icon" href="../images/robot.png">

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
                    <li><a href="../profile/profile.php">PROFILE</a></li>
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
        </div>
        <h1>Log In</h1>
        </section>
            <?php
            $db = pg_connect('host=localhost port=5432 dbname=postgres user=postgres password=pgsql')
                or die('Could not connect: ' . pg_last_error());
            if (!(isset($_POST['login-button']))) header("Location: ../index.php");
            else {
                $email = $_POST['logEmail'];
                $pass = md5($_POST['password']);
                $query = "select * from users where email= $1 and password= $2";
                $res = pg_query_params($db, $query, array($email, $pass));
                if ($line = pg_fetch_array($res, null, PGSQL_ASSOC)) {
                    $nome = $line['firstName'];
                    $_SESSION['name'] = $nome;
                    $_SESSION['email'] = $email;
                    $foto = "../images/" . $line['photoURL'];
                    echo ' 
                    <section class="form">  
                     <div class="profile" id="profile" style="background-image: ' . " url($foto) " . ' "></div> ' . "
                     <h1> Bentornato, $nome!</h1><br/> 
                     <a href=../index.php" . ' class="hero-btn red-btn"> Visita il sito </a>
                     </section>';
                } else {
                    echo      ' 
                    <section class="form">
                        <h1> Spiacenti, email o password errati!</h1><br/>
                        <a href="./login.html" class="hero-btn red-btn"> Ritenta </a>
                        <p>oppure</p>
                        <a href="../signin/signin.html" class="hero-btn red-btn"> Registrati </a>
                    </section>';
                }
            }
            ?>
            <script src="../effects.js"></script>

    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>

</html>