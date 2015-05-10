<?php
    include 'inc/header.inc.php';
    include 'is_logged_in.php';
    $ls_updateDB = $conn->prepare("UPDATE owners SET last_login_from_ip = :last_login_from_ip, last_login_at = :last_login_at WHERE id_owner = :id_owner AND status = 'A'");
    $ls_updateDB->execute(array(':last_login_from_ip' => $_SESSION['last_login_from_ip'], 
                                ':last_login_at' => $_SESSION['last_login_at'], 
                                ':id_owner' => $_SESSION['id_owner']));
    if($ls_updateDB->errorCode() != 0000){
        $ls_error = $ls_updateDB->errorInfo();
        $header_msg .= $ls_error[0] . ': ' . $ls_error[2];
    }
    session_destroy();
    header("location:index.php");
    exit();
?>