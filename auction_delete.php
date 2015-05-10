<?php
    include 'inc/header.inc.php';
    include 'is_logged_in.php';
    $id = $_REQUEST['id'];
    $ls_queryDelete = $conn->prepare("DELETE FROM item_auctions WHERE id_item_auction = :id_item_auction");
    $ls_queryDelete->execute(array(':id_item_auction' => $id));
    if($ls_queryDelete->errorCode() == '00000'){
        echo json_encode(array('stats' => 'success'));
    }
    else{
        echo json_encode(array('stats' => 'failed'));
    }
?>