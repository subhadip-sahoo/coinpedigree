<?php 
include 'inc/header.inc.php';
include 'custom_functions.php';
include 'is_logged_in.php';
if(!isset($_POST['id']) && !isset($_REQUEST['cert_id'])){
    header('location:coin_info.php');
    exit();
}
if(isset($_POST['id']) && $_POST['id'] == ''){
    header('location:coin_info.php?err=1');
    exit();
}
if(isset($_POST['submit_details'])){
    if($_POST['pcgs_pedigree'] == ''){
        $ls_errorMsg = 'Pedigree can not be blank.<br/>';
    }
     else if($_POST['owner_postcode'] == ''){
        $ls_errorMsg = 'Zip code can not be blank.<br/>';
    }
    else{
        $data = getTableValues($_POST['pcgs_ver_id']);
        $ls_querySearchItem = $conn->prepare("SELECT * FROM items WHERE pcgs_ver_id = :pcgs_ver_id AND status = 'A'");
        $ls_querySearchItem->execute(array(':pcgs_ver_id' => $_POST['pcgs_ver_id']));
        if($ls_querySearchItem->errorCode() == "00000"){
                if($ls_querySearchItem->rowCount() > 0){
                        $ls_itemObj = $ls_querySearchItem->fetch(PDO::FETCH_OBJ);
                        $ls_item_id = $ls_itemObj->id_item;
                        $ls_query = "UPDATE 
                                            items
                                    SET
                                            pcgs_coin_no = :pcgs_coin_no,
                                            pcgs_date_mintmark = :pcgs_date_mintmark,
                                            pcgs_denomination = :pcgs_denomination,
                                            pcgs_variety = :pcgs_variety,
                                            pcgs_minor_variety = :pcgs_minor_variety,
                                            pcgs_mint_error = :pcgs_mint_error,
                                            pcgs_pedigree = :pcgs_pedigree,
                                            pcgs_country = :pcgs_country,
                                            pcgs_grade = :pcgs_grade,
                                            pcgs_mintage = :pcgs_mintage,
                                            pcgs_price_guide_value = :pcgs_price_guide_value,
                                            pcgs_holder_type = :pcgs_holder_type,
                                            pcgs_population = :pcgs_population,
                                            cac = :cac,
                                            status = 'A'
                                    WHERE
                                            pcgs_ver_id = :pcgs_ver_id
                            ";
                }
                else{
                        $ls_query = "INSERT INTO
                                            items
                                            (
                                                    source,
                                                    pcgs_ver_id,
                                                    pcgs_coin_no,
                                                    pcgs_date_mintmark,
                                                    pcgs_denomination,
                                                    pcgs_variety,
                                                    pcgs_minor_variety,
                                                    pcgs_mint_error,
                                                    pcgs_pedigree,
                                                    pcgs_country,
                                                    pcgs_grade,
                                                    pcgs_mintage,
                                                    pcgs_price_guide_value,
                                                    pcgs_holder_type,
                                                    pcgs_population,
                                                    cac,
                                                    status
                                            )
                                    VALUES
                                            (
                                                    'pcgs',
                                                    :pcgs_ver_id,
                                                    :pcgs_coin_no,
                                                    :pcgs_date_mintmark,
                                                    :pcgs_denomination,
                                                    :pcgs_variety,
                                                    :pcgs_minor_variety,
                                                    :pcgs_mint_error,
                                                    :pcgs_pedigree,
                                                    :pcgs_country,
                                                    :pcgs_grade,
                                                    :pcgs_mintage,
                                                    :pcgs_price_guide_value,
                                                    :pcgs_holder_type,
                                                    :pcgs_population,
                                                    :cac,
                                                    'A'
                                            )
                            ";
                }
                array_push_associative($data[0], array(':cac' => $_POST['cac'], ':pcgs_pedigree' => $_POST['pcgs_pedigree']));
                $ls_queryObj = $conn->prepare($ls_query);
                $ls_queryObj->execute($data[0]);
                if($ls_queryObj->errorCode() != '00000'){
                        $ls_error = $ls_queryObj->errorInfo();
                        echo $ls_error[0] . ': ' . $ls_error[2];
                }
                else{
                    if(!isset($ls_item_id)){
                        $ls_item_id = $conn->lastInsertId();
                    }
                }
            }
            $ls_checkOwnership = $conn->query("SELECT * FROM ownerships WHERE id_owner = ".$_SESSION['id_owner']."");
            if($ls_checkOwnership->rowCount() > 0){
                    $ls_getItemId = $ls_checkOwnership->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($ls_getItemId as $item) {
                            if($item['id_item'] == $ls_item_id && $item['id_owner'] == $_SESSION['id_owner']){
                                    $ls_setUpdate = TRUE;
                                    $ls_id_ownership = $item['id_ownership'];
                            }
                    }
            }
            if(isset($ls_setUpdate) && $ls_setUpdate == TRUE){
                    $ls_queryOwnerships = "
                                            UPDATE
                                                ownerships
                                            SET
                                               id_owner = :id_owner, 
                                               id_item = :id_item, 
                                               postcode = :postcode, 
                                               notes = :notes
                                            WHERE
                                                id_owner = :id_owner
                                            AND
                                                id_item = :id_item
                                    ";
            }
            else{
                    $ls_queryOwnerships = "INSERT INTO
                                        ownerships
                                        (
                                            id_owner,
                                            id_item,
                                            entry_date,
                                            postcode,
                                            notes
                                        )
                                VALUES 
                                        (
                                            :id_owner,
                                            :id_item,
                                            NOW(),
                                            :postcode,
                                            :notes
                                        )
                                ";
            }
            $ls_queryOwnershipsObj = $conn->prepare($ls_queryOwnerships);
            $ls_queryOwnershipsObj->execute(
                            array(
                                    ':id_owner' => $_SESSION['id_owner'], 
                                    ':id_item' => $ls_item_id, 
                                    ':postcode' => $_POST['owner_postcode'], 
                                    ':notes' => $_POST['owner_notes']
                            )
                    );
            if($ls_queryOwnershipsObj->errorCode() != '00000'){
                    $ls_errorOwnerships = $ls_queryOwnershipsObj->errorInfo();
                    echo $ls_errorOwnerships[0] . ': ' . $ls_errorOwnerships[2];
            }
            else{
                if(!isset($ls_id_ownership)){
                    $ls_id_ownership = $conn->lastInsertId();
                }
                header("location:add_details.php?id=".$ls_id_ownership);
                exit();
            }
    }
}
include 'header.php';
?>
<script type="text/javascript">
    $(function(){
        $('#add_ownership').validationEngine();
    });
</script>
            <div class="inner-page">
            	<h1>Add Ownership</h1>
                <div class="bill-tracking">
                	<div class="tracking-top">
                    	<div class="track-hed">
                            <?php if(isset($header_msg)){ ?>
                            <?php echo $header_msg;?>
                            <?php } else{?>
                            Last login from IP: <span style="font-weight: bold;"><?php echo $last_login_from_ip;?></span>&nbsp;|
                            <span>Last login at: <?php echo date(DISPLAY_FORMAT_DATETIME_SHORT,strtotime($last_login_at));?></span>
                        <?php } ?>
                        </div>
                    </div>
<?php
if(isset($ls_errorMsg)){
    if(!isset($lb_coin_valid)){
        $lb_coin_valid  = TRUE;
    }
}
if(isset($_POST['id']) || isset($_REQUEST['cert_id'])){
    $ls_pcgs_ver_id = isset($_REQUEST['cert_id'])? trim($_REQUEST['cert_id']):trim($_POST['id']);
    $data = getTableValues($ls_pcgs_ver_id);
    if($data != 0){
        if(!isset($lb_coin_valid)){
            $lb_coin_valid  = TRUE;
        }
    }
    if(isset($lb_coin_valid) && $lb_coin_valid == TRUE){
?>
                    <div class="coin-bill-track">
                        <div class="addpage-coin-det">
                            <div class="con-bt">
                                <div class="con-bt-left"><img src="images/botton.png" alt="" /></div>
                                <div class="con-bt-right"><img src="images/botton.png" alt="" /></div>
                            </div>
                           
                            <div class="addpage-con-details">
                                 <h2>Coin Information</h2>
                                    <table>
                                            <tr>
                                                    <td>Cert Verification #:</td>
                                                    <td class="coin-info-label"><?php echo $data[0][':pcgs_ver_id'];?></td>
                                            </tr>
                                            <tr>
                                                    <td>PCGS Coin #:</td>
                                                    <td class="coin-info-label"><?php echo $data[0][':pcgs_coin_no'];?></td>
                                            </tr>
                                            <tr>
                                                    <td>Date, mintmark:</td>
                                                    <td class="coin-info-label"><?php echo $data[0][':pcgs_date_mintmark'];?></td>
                                            </tr>
                                            <tr>
                                                    <td>Denomination:</td>
                                                    <td class="coin-info-label"><?php echo $data[0][':pcgs_denomination'];?></td>
                                            </tr>
                                            <tr>
                                                    <td>Variety:</td>
                                                    <td class="coin-info-label"><?php echo $data[0][':pcgs_variety'];?></td>
                                            </tr>
                                            <tr>
                                                    <td>Minor Variety:</td>
                                                    <td class="coin-info-label"><?php echo $data[0][':pcgs_minor_variety'];?></td>
                                            </tr>
                                            <tr>
                                                    <td>Mint Error:</td>
                                                    <td class="coin-info-label"><?php echo $data[0][':pcgs_mint_error'];?></td>
                                            </tr>
                                            <tr>
                                                    <td>Pedigree:</td>
                                                    <td class="coin-info-label"><?php echo $data[0][':pcgs_pedigree'];?></td>
                                            </tr>
                                            <tr>
                                                    <td>Country:</td>
                                                    <td class="coin-info-label"><?php echo $data[0][':pcgs_country'];?></td>
                                            </tr>
                                            <tr>
                                                    <td>Grade:</td>
                                                    <td class="coin-info-label"><?php echo $data[0][':pcgs_grade'];?></td>
                                            </tr>
                                            <tr>
                                                    <td>Mintage:</td>
                                                    <td class="coin-info-label"><?php echo $data[0][':pcgs_mintage'];?></td>
                                            </tr>
                                            <tr>
                                                    <td>PCGS Price GuideSM Value:</td>
                                                    <td class="coin-info-label">$<?php echo $data[0][':pcgs_price_guide_value'];?></td>
                                            </tr>
                                            <tr>
                                                    <td>Holder Type:</td>
                                                    <td class="coin-info-label"><?php echo $data[0][':pcgs_holder_type'];?></td>
                                            </tr>
                                            <tr>
                                                    <td>Population:</td>
                                                    <td class="coin-info-label"><?php echo $data[0][':pcgs_population'];?></td>
                                            </tr>
                                    </table>
                            </div>
                            <div class="con-bt">
                                    <div class="con-bt-left"><img src="images/botton.png" alt="" /></div>
                                    <div class="con-bt-right"><img src="images/botton.png" alt="" /></div>
                            </div>
                        </div>
                    </div>
<?php
    }
    else{
        header('location:coin_info.php?err=2');
        exit();
    }
    $ls_query_item_id = $conn->prepare("SELECT * FROM items WHERE pcgs_ver_id = :pcgs_ver_id AND status = 'A'");
    $ls_query_item_id->execute(array(':pcgs_ver_id' => $ls_pcgs_ver_id));
    if($ls_query_item_id->errorCode() == '00000'){
        if($ls_query_item_id->rowCount() > 0){
            $ls_pgcs_item_id = $ls_query_item_id->fetch(PDO::FETCH_OBJ);
            $current_item_id = $ls_pgcs_item_id->id_item;
            $ls_getidOwnership = $conn->query("SELECT
                                                    i.*,
                                                    o.*
                                                FROM
                                                    items AS i
                                                INNER JOIN
                                                    ownerships AS o
                                                    ON i.id_item = o.id_item
                                                WHERE 
                                                    o.id_owner = ".$_SESSION['id_owner']."
                                                AND 
                                                    o.id_item = $current_item_id");
            if($ls_getidOwnership->rowCount() > 0){
                $row_fetch = $ls_getidOwnership->fetch(PDO::FETCH_ASSOC);
                extract($row_fetch, EXTR_PREFIX_ALL, 'lp');
            }
        }
  }
?>
                    <div class="coin-bill-track">
                        <div class="con-bt">
                            <div class="con-bt-left"><img src="images/botton.png" alt="" /></div>
                            <div class="con-bt-right"><img src="images/botton.png" alt="" /></div>
                        </div>
                        <form action="" name="add_ownership" id="add_ownership" method="post">
                            <?php if(isset($ls_errorMsg)){?>
                                <div class="error-message">
                                    <span><?php echo $ls_errorMsg; ?></span>
                                </div>
                            <?php } ?>
                            <div class="add-con">
                                <div class="add-con-dets">
                                    <h2>Add Additional Details</h2>
                                    <div class="add-con-rept">
                                        <label>CAC:</label>
                                        <select name="cac" id="cac"  class="select-textbox">
                                            <option <?php if(isset($lp_cac) && $lp_cac ==  ''){echo 'selected="selected"';}?> value=""></option>
                                            <option <?php if(isset($lp_cac) && $lp_cac ==  'Green'){echo 'selected="selected"';}?> value="Green">Green</option>
                                            <option <?php if(isset($lp_cac) && $lp_cac ==  'Gold'){echo 'selected="selected"';}?> value="Gold">Gold</option>
                                        </select>
                                    </div>
                                    <div class="add-con-rept">
                                        <label>Pedigree:</label>
                                        <input type="text" class="input-text validate[required]" name="pcgs_pedigree" id="pcgs_pedigree" value="<?php if(isset($lp_pcgs_pedigree)){echo $lp_pcgs_pedigree;} else{echo $data[0][':pcgs_pedigree'];}?>" />
                                    </div>
                                    <div class="add-con-rept">
                                        <label>Post Code:</label>
                                        <input type="text" class="input-text validate[required]" name="owner_postcode" id="owner_postcode" value="<?php if(isset($lp_postcode)){echo $lp_postcode;}?>" />
                                    </div>
                                    <div class="add-con-rept">
                                        <label>Note:</label>
                                        <textarea name="owner_notes" id="owner_notes" class="text-box"><?php if(isset($lp_notes)){echo $lp_notes;}?></textarea>
                                    </div>
                                </div>
                                <input type="hidden" name="pcgs_ver_id" id="pcgs_ver_id" value="<?php if(isset($ls_pcgs_ver_id)){echo $ls_pcgs_ver_id;}?>"/>
                                <input type="hidden" name="id" id="id" value="<?php if(isset($ls_pcgs_ver_id)){echo $ls_pcgs_ver_id;}?>"/>
                                <div class="add-con-dets">
                                    <input type="submit" name="submit_details" value="Confirm and Proceed" class="search-btn" />
<?php
if(isset($current_item_id)){
$ls_checkOwnership = $conn->query("SELECT * FROM ownerships WHERE id_owner = ".$_SESSION['id_owner']."");
if($ls_checkOwnership->rowCount() > 0){
        $ls_getItemId = $ls_checkOwnership->fetchAll(PDO::FETCH_ASSOC);
        foreach ($ls_getItemId as $item) {
                if($item['id_item'] == $current_item_id && $item['id_owner'] == $_SESSION['id_owner']){
                    $ls_setDelete= TRUE;
                }
        }
    }
}
    if(isset($ls_setDelete) && $ls_setDelete == TRUE){
?>
                                    <input id='<?php if(isset($_REQUEST['cert_id'])){echo $_REQUEST['cert_id'];}?>' type="button" name='delete' value="Delete" class="search-btn" />
<?php
}
?>
                                </div>
                            </div>
                        </form>
                    	<div class="con-bt">
                            <div class="con-bt-left"><img src="images/botton.png" alt="" /></div>
                            <div class="con-bt-right"><img src="images/botton.png" alt="" /></div>
                        </div>
                    </div>
<?php
}
?>
                </div>
            </div>
        </div>
    </div>
     <!--content end-->
<?php include 'footer.php';?>
