<?php
    include 'inc/header.inc.php';
    include 'is_logged_in.php';
    $id = $_REQUEST['id'];
    $ls_queryEdit = $conn->prepare("SELECT * FROM item_auctions WHERE id_item_auction = :id_item_auction");
    $ls_queryEdit->execute(array(':id_item_auction' => $id));
    if($ls_queryEdit->errorCode() == '00000'){
        $ls_editItem = $ls_queryEdit->fetch(PDO::FETCH_OBJ);
        echo json_encode(array('url' => $ls_editItem->url, 'notes' => $ls_editItem->notes));
    }
    else{
        echo json_encode(array('stats' => 'failed'));
    }
?>