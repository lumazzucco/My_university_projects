<?php
session_start();
?>

<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign-in</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="signin.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?
         family=Poppins:ital,wght@0,300;0,400;0,600;0,700;1,200&display=swap" rel="stylesheet">
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://kit.fontawesome.com/b9c50d0ec6.js" crossorigin="anonymous"></script>
    <script type="text/javascript" src="../script.js"></script>
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
        <h1>Sign In</h1>
        </section>
            <?php
            $db = pg_connect('host=localhost port=5432 dbname=postgres user=postgres password=pgsql')
                or die('Could not connect: ' . pg_last_error());
            if (!(isset($_POST['signin-button']))) header("Location: ../index.php");
            else {
                $email = $_POST['inputEmail'];
                $query = "select * from users where email= $1";
                $res = pg_query_params($db, $query, array($email));
                if ($line = pg_fetch_array($res, null, PGSQL_ASSOC)) {
                    echo      ' 
                    <section class="form">
                        <h1> Spiacenti, sei già un utente registrato.</h1><br/>
                        <a href="../login/login.html" class="hero-btn red-btn"> Clicca qui per accedere </a>
                    </section>';
                } else {
                    $nome = $_POST['name'];
                    $_SESSION['name'] = $nome;
                    $_SESSION['email'] = $email;
                    $cognome = $_POST['surname'];
                    $paese = $_POST['continent'];
                    $nascita = $_POST['birth'];
                    $pass = md5($_POST['password1']);
                    $descrizione = $_POST['description'];
                    $foto = $_POST['file'];
                    $query2 = "insert into users values ($1,$2,$3,$4,$5,$6,$7,$8)";
                    $res2 = pg_query_params($db, $query2, array($nome, $cognome, $paese, $email, $pass, $descrizione, $foto, $nascita));
                    if ($res2) {
                        echo '
                    <section class="form"> ' .
                            " <h1> Benvenuto, $nome!</h1><br/> 
                        <a href=../index.php?name=$nome" . ' class="hero-btn red-btn"> Visita il sito </a>
                    </section>';
                    }
                }
            }
            ?>
            <script src="../effects.js"></script>

    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>

</html>