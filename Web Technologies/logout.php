<?php
session_start();
?>
<!DOCTYPE html>
<html>
<body>

<?php
    // modulo richiamato con ajax per terminare la sessione dopo logout
    session_unset(); 
    session_destroy(); 
    header("location:index.php");
?>

</body>
</html>