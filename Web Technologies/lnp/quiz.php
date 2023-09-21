<?php
session_start();
if (!(isset($_SESSION['email']))) //necessario il check per vedere se l'email è registrata al sito
    header("location:../login/login.html");
?>
<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Play</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="lnp.css">
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
    <div class="modal-close-overlay" data-modal-overlay style="display: none;"></div>


            <button class="modal-close-btn" data-modal-close style="display: none;">
            </button>

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
        <h1>Quiz</h1>
        </section>
    <?php if (@$_GET["q"] == 0) {
        echo '<div class="welcome"><h1>Benvenuto al quiz</h1></div>
            <form action="update.php?q=0" method="POST">
            <button type="submit" class="hero-btn red-btn bottone">Start</button></form>';
    }
    if (@$_GET["q"] > 0) {
        $db = pg_connect('host=localhost port=5432 dbname=postgres user=postgres password=pgsql')
                    or die('Could not connect: ' . pg_last_error());
        $id = @$_GET["q"];
        $query = "SELECT * FROM domande WHERE id= $1";
        $res = pg_query_params($db, $query, array($id));
        if (!$row = pg_fetch_array($res, null, PGSQL_ASSOC)){
            die('Errore lettura dal database');
        }
        $domanda = $row['testo'];
        $risposta1 = $row['a'];
        $risposta2 = $row['b'];
        $risposta3 = $row['c'];
        $risposta4 = $row['d'];
        echo '<div class="container-fluid">
                <div class="panel"><b>Domanda ' .  $id . ' : </b><form action="update.php?q=' . $id . '" method="POST" class="form-horizontal">
                <h2> ' . $domanda . ' </h2>
                <div class="wrapper row">
                    <div class="col-md button on"><input id="1" type="radio" name="ans" value="1"><label for="1" class="btn btn-default"> ' . $risposta1 . '</label></div>
                    <div class="col-md button on"><input id="2" type="radio" name="ans" value="2"><label for="2" class="btn btn-default"> ' . $risposta2 . '</label></div>
                    <div class="col-md button on"><input id="3" type="radio" name="ans" value="3"><label for="3" class="btn btn-default"> ' . $risposta3 . '</label></div>
                    <div class="col-md button on"><input id="4" type="radio" name="ans" value="4"><label for="4" class="btn btn-default"> ' . $risposta4 . '</label></div>
                    </div>
                <button type="submit" class="hero-btn red-btn bottone" id="next" ><span class="glyphicon glyphicon-lock" aria-hidden="true"></span>';
                if ($id<10)
                    echo '
                Prossima Domanda';
                else
                    echo '
                    Termina il Quiz';
                echo '</button>
                </div></form></div>
        </div>';
    }
    ?>

        <div class="light-btn">
            <i class="fas fa-adjust" onclick="changeMode()"></i>
        </div>
    <script src="../effects.js"></script>

    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>

</html>