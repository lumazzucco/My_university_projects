
<?php
    $db= pg_connect('host=localhost port=5432 dbname=postgres user=postgres password=pgsql')
    or die('Could not connect: ' . pg_last_error() );
    session_start();
    if(!(isset($_SESSION['email']))){ //necessario il check per vedere se l'email è registrata al sito
        header("location:../login/login.html");
    }
    else
    {
    $name = $_SESSION['name'];
    $email = $_SESSION['email'];
    $id=@$_GET['q'];
    $query= "SELECT * FROM curr_score WHERE email= $1";
    $res= pg_query_params($db, $query, array($email));
    $line= pg_fetch_array($res, null, PGSQL_ASSOC);
     
    if($id==0){
        if (!$line){ // un nuovo utente ha iniziato il quiz
            $q2 = "INSERT INTO curr_score VALUES ($1,$2,$3,$4)";
            $r2 = pg_query_params($db,$q2,array($email,0,0,'0000000000'));
            if(!$r2)
                die("Errore nell'inserimento utente");
            header("location: quiz.php?q=1");
        }
        else{
            if($line['lastans']==10){
                $q5 = "UPDATE curr_score SET score=0,lastans=0,ans='0000000000' WHERE email = $1"; //reset statistiche
                $r5 = pg_query_params($db,$q5,array($email));
                if(!$r5)
                    die("Errore nell'aggiornamento utente");
                header("location: quiz.php?q=1");
            }
        }
    }
    if (!$line && $id>0)
        header("location: quiz.php?q=0");
    
    if ($id!=$line['lastans']+1){
        if($line['lastans']<10) { // tentativo di accumulo punti
            $reindirizzo=$line['lastans']+1;
            sleep(5);
            header("location: quiz.php?q=". $reindirizzo);
        }
        else{ // lastans = 10 and id<10, necessario reset del quiz
        header("location: learn.php");
        }
    }
    else{ // svolgimento regolare quiz
        $ans = @$_POST['ans']; // risposta utente
        if($ans == "")
            $ans = 0;
        $q3 = "SELECT * FROM corrispondenze WHERE domanda=$1";
        $r3 = pg_query_params($db, $q3, array($id));
        $sol = pg_fetch_array($r3,null,PGSQL_ASSOC)['soluzione'];

        $q4 = "SELECT * FROM curr_score WHERE email=$1";
        $r4 = pg_query_params($db,$q4,array($email));
        if(!$line2 = pg_fetch_array($r4,null,PGSQL_ASSOC))
            die("Errore fetch per score e ans.");
        $score = $line2['score'];
        $prev = str_split($line2['ans']);
        $prev[$id-1] = $ans;
        $arr = implode($prev); // stringa
        if($sol == $ans){
            $q5 = "UPDATE curr_score SET score=$score+1,lastans=$id,ans=$arr WHERE email = $1";
            $r5 = pg_query_params($db,$q5,array($email));
            if(!$r5)
                die("Errore nell'aggiornamento utente");
        }
        else{
            $q6 = "UPDATE curr_score SET lastans=$id,ans=$arr WHERE email = $1";
            $r6 = pg_query_params($db,$q6,array($email));
            if(!$r6)
                die("Errore nell'aggiornamento utente");
        }
    if($id<10){ //la risposta è stata data a una domanda qualsiasi
        $id++;
        header('location:quiz.php?q='. $id);
        }
    else{ //la risposta è stata data all'ultima domanda
        header("location: learn.php"); //provvisorio
        }
    }
}
?>

<script type="text/javascript" src="update.js"></script> 