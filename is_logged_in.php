<?php
    if(empty($_SESSION['id_owner']) || $_SESSION['id_owner'] == '' || $_SESSION['id_owner'] == 'NULL')
    {
        if(isset($_REQUEST['cert_id'])){
            $_SESSION['page'] = 'add_ownership.php';
            $_SESSION['cert_id'] = $_REQUEST['cert_id'];
            header('location:signup.php?war=1');
            exit();
        }
        else{
            header('location:index.php');
            exit();
        }
    }

?>
