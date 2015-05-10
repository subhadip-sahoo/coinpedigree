<?php
    include 'inc/header.inc.php';
     if(empty($_SESSION['id_owner']) || $_SESSION['id_owner'] == '' || $_SESSION['id_owner'] == 'NULL'){        
        header('location:index.php');
        exit();
    }
        $ls_queryID = $conn->query("SELECT * FROM items WHERE pcgs_ver_id = ".$_REQUEST['id']." AND status = 'A'");
        $ls_itemID = $ls_queryID->fetch(PDO::FETCH_OBJ);
        $ls_ownership_id = $conn->query("SELECT * FROM ownerships WHERE id_item = $ls_itemID->id_item AND id_owner = ".$_SESSION['id_owner']);
        if($ls_ownership_id->rowCount() == 1){
            $ls_ownership = $ls_ownership_id->fetch(PDO::FETCH_OBJ);
            $ls_queryFileDelete = $conn->query("SELECT * FROM item_images WHERE id_ownership = $ls_ownership->id_ownership");
            foreach ($ls_queryFileDelete->fetchAll(PDO::FETCH_ASSOC) as $row) {
                if(is_file(get_setting(BASE_DIRECTORY, $conn).'/'.$row['filename'])){
                    @unlink(get_setting(BASE_DIRECTORY, $conn).'/'.$row['filename']);
                }
            }
            $conn->query("DELETE FROM item_images WHERE id_ownership = $ls_ownership->id_ownership");
            $conn->query("DELETE FROM item_auctions WHERE id_ownership = $ls_ownership->id_ownership");
            $conn->query("DELETE FROM ownerships WHERE id_ownership = $ls_ownership->id_ownership");
            echo json_encode(array('stats' => 'success'));
        }
        else{
            echo json_encode(array('stats' => 'failed'));
        }
?>
