<?php
    include 'inc/header.inc.php';
    include 'is_logged_in.php';
    $id = $_REQUEST['id'];
    $filename = $_REQUEST['file'];
    if(is_file(get_setting(BASE_DIRECTORY, $conn).'/'.$filename)){
        @unlink(get_setting(BASE_DIRECTORY, $conn).'/'.$filename);
    }
    $ls_queryDelete = $conn->prepare("DELETE FROM item_images WHERE id_item_image = :id_item_image");
    $ls_queryDelete->execute(array(':id_item_image' => $id));
    if($ls_queryDelete->errorCode() == '00000'){
        echo json_encode(array('status' => 'success'));
    }
    else{
        echo json_encode(array('status' => 'failed'));
    }
?>
