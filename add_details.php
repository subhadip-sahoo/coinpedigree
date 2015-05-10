<?php 
include 'inc/header.inc.php';
include 'custom_functions.php';
include 'is_logged_in.php';
if(isset($_REQUEST['id'])){
    $_SESSION['id_ownership'] = $_REQUEST['id'];
}
if(isset($_POST['add_image'])){
    if($_FILES['upload']['name'][0] == '' && $_POST['upld_url'][0] == ''){
        $ls_errorMsg = 'Both the fields can not be blank!</br>';
    }
    else if($_POST['upld_url'][0] != '' && filter_var($_POST['upld_url'][0], FILTER_VALIDATE_URL) == FALSE){
        $ls_errorMsg = 'Enter valid url!<br/>';
    }
    else{
        $image_file = multi_files_upload($_FILES['upload']);
        if($image_file[1] == 1){
                $ls_errorMsg = $image_file[0];
        }
        else{
                 foreach ($_POST['upld_url'] as $urls) {
                         if($urls == '' || $urls == 'NULL'){
                                 continue;
                         }
                         $image_file[0][] = $urls;
                 }
                 foreach ($image_file[0] as $file) {
                         if(is_file(get_setting(BASE_DIRECTORY, $conn).'/'.$file)){
                                 $filename = $file;
                                 $url = 'NULL';
                         }
                         else{
                                 $filename = 'NULL';
                                 $url = $file;
                         }
                        $ls_queryImageFileUpload = "INSERT INTO
                                                        item_images
                                                        (
                                                                id_ownership,
                                                                filename,
                                                                url
                                                        )
                                                    VALUES
                                                        (
                                                                :id_ownership,
                                                                :filename,
                                                                :url
                                                        )
                                                ";
                   $ls_image_fileObj = $conn->prepare($ls_queryImageFileUpload);
                   $ls_image_fileObj->execute(array(':id_ownership' => $_POST['id_ownership'], ':filename' => $filename, ':url' => $url));
                   if($ls_image_fileObj->errorCode() != '00000'){
                        $ls_errorInsert = $ls_image_fileObj->errorInfo();
                        $ls_errmsg = $ls_errorInsert[0] . ': ' . $ls_errorInsert[2];
                    }
                }
            }
        }
}
if(isset($_POST['add_auction'])){
    if($_POST['auction_url'][0] == ''){
        $ls_errorMsgAuction = 'Url can not be blank!</br>';
    }
    else if(filter_var($_POST['auction_url'][0], FILTER_VALIDATE_URL) == FALSE){
         $ls_errorMsgAuction = 'Enter valid url!<br/>';
    }
    else if($_POST['auction_notes'][0] == ''){
         $ls_errorMsgAuction = 'Note can not be blank!<br/>';
    }
    else{
        $a = 0;
        for($a=0; $a < count($_POST['auction_url']); $a++){
                $ls_queryAuction = "INSERT INTO
                                        item_auctions
                                        (
                                                id_ownership,
                                                url,
                                                notes
                                        )
                                VALUES 
                                        (
                                                :id_ownership,
                                                :url,
                                                :notes
                                        )
                                ";
                $ls_queryAuctionObj = $conn->prepare($ls_queryAuction);
                $ls_queryAuctionObj->execute(array(':id_ownership' => $_POST['id_ownership'], ':url' => $_POST['auction_url'][$a], ':notes' => $_POST['auction_notes'][$a]));
                if($ls_queryAuctionObj->errorCode() != '00000'){
                    $ls_errorAuction = $ls_queryAuctionObj->errorInfo();
                    $ls_errorMsgAuction = $ls_errorAuction[0] . ': ' . $ls_errorAuction[2];
                }
        }
    }
}
if(isset($_POST['edit_auction'])){
    if($_POST['auction_url'][0] == ''){
        $ls_errorMsgAuction = 'Url can not be blank!</br>';
    }
    else if(filter_var($_POST['auction_url'][0], FILTER_VALIDATE_URL) == FALSE){
         $ls_errorMsgAuction = 'Enter valid url!<br/>';
    }
    else if($_POST['auction_notes'][0] == ''){
         $ls_errorMsgAuction = 'Note can not be blank!<br/>';
    }
    else{
        $a = 0;
        for($a=0; $a < count($_POST['auction_url']); $a++){
                $ls_queryEDITAuction = "UPDATE
                                        item_auctions
                                    SET  
                                        id_ownership = :id_ownership,
                                        url = :url,
                                        notes = :notes
                                    WHERE
                                        id_item_auction = :id_item_auction
                                ";
                $ls_queryEditAuctionObj = $conn->prepare($ls_queryEDITAuction);
                $ls_queryEditAuctionObj->execute(array(':id_item_auction' => $_POST['id_item_auction'],':id_ownership' => $_POST['id_ownership'], ':url' => $_POST['auction_url'][$a], ':notes' => $_POST['auction_notes'][$a]));
                if($ls_queryEditAuctionObj->errorCode() != '00000'){
                    $ls_errorEditAuction = $ls_queryEditAuctionObj->errorInfo();
                    $ls_errorMsgAuction = $ls_errorEditAuction[0] . ': ' . $ls_errorEditAuction[2];
                }
        }
    }
}
include 'header.php';
?>
<script type='text/javascript' src="js/pop_up.js"></script>
<script type='text/javascript'>
    $(function(){
        $('#add_image').validationEngine();
        $('#add_auction_urls').validationEngine();
        $(document).delegate('.add-coin-pic img', 'click', function() {
            var id = $(this).attr('src');
            gl_popup_id = "#popup_content";
            show_loader();
            $(gl_popup_id).load("show_image.php?id=" + id, function() {
                centerPopup();
                loadPopup();
            });
        });
    });
</script>
            <div class="inner-page">
            	<h1>Add Coin Details</h1>
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
$ls_query_item_id = $conn->prepare("SELECT * FROM ownerships WHERE id_ownership = :id_ownership");
    $ls_query_item_id->execute(array(':id_ownership' => $_REQUEST['id']));
    if($ls_query_item_id->errorCode() == '00000'){
        if($ls_query_item_id->rowCount() > 0){
            $ls_item_id = $ls_query_item_id->fetch(PDO::FETCH_OBJ);
            $ls_queryMain = "SELECT * FROM items WHERE id_item = :id_item";
            $ls_queryMainObj = $conn->prepare($ls_queryMain);
            $ls_queryMainObj->execute(array(':id_item' => $ls_item_id->id_item));
            if($ls_queryMainObj->errorCode() == '00000'){
                if($ls_queryMainObj->rowCount() > 0){
                    $row = $ls_queryMainObj->fetch(PDO::FETCH_ASSOC);
?>                    
                    <div class="addpage-coin-dets">
                        	<div class="con-bt">
                            	<div class="con-bt-left"><img src="images/botton.png" alt="" /></div>
                                <div class="con-bt-right"><img src="images/botton.png" alt="" /></div>
                            </div>
                            <div class="addpage-con-details">
                            	<table>
                                    <tr>
                                            <td>Cert Verification #:</td>
                                            <td class="coin-info-label"><?php echo $row['pcgs_ver_id'];?></td>
                                    </tr>
                                    <tr>
                                            <td>PCGS Coin #:</td>
                                            <td class="coin-info-label"><?php echo $row['pcgs_coin_no'];?></td>
                                    </tr>
                                    <tr>
                                            <td>Date, mintmark:</td>
                                            <td class="coin-info-label"><?php echo $row['pcgs_date_mintmark'];?></td>
                                    </tr>
                                    <tr>
                                            <td>Denomination:</td>
                                            <td class="coin-info-label"><?php echo $row['pcgs_denomination'];?></td>
                                    </tr>
                                    <tr>
                                            <td>Variety:</td>
                                            <td class="coin-info-label"><?php echo $row['pcgs_variety'];?></td>
                                    </tr>
                                    <tr>
                                            <td>Minor Variety:</td>
                                            <td class="coin-info-label"><?php echo $row['pcgs_minor_variety'];?></td>
                                    </tr>
                                    <tr>
                                            <td>Mint Error:</td>
                                            <td class="coin-info-label"><?php echo $row['pcgs_mint_error'];?></td>
                                    </tr>
                                    <tr>
                                            <td>Pedigree:</td>
                                            <td class="coin-info-label"><?php echo $row['pcgs_pedigree'];?></td>
                                    </tr>
                                    <tr>
                                            <td>Country:</td>
                                            <td class="coin-info-label"><?php echo $row['pcgs_country'];?></td>
                                    </tr>
                                    <tr>
                                            <td>Grade:</td>
                                            <td class="coin-info-label"><?php echo $row['pcgs_grade'];?></td>
                                    </tr>
                                    <tr>
                                            <td>Mintage:</td>
                                            <td class="coin-info-label"><?php echo $row['pcgs_mintage'];?></td>
                                    </tr>
                                    <tr>
                                            <td>PCGS Price GuideSM Value:</td>
                                            <td class="coin-info-label">$<?php echo $row['pcgs_price_guide_value'];?></td>
                                    </tr>
                                    <tr>
                                            <td>Holder Type:</td>
                                            <td class="coin-info-label"><?php echo $row['pcgs_holder_type'];?></td>
                                    </tr>
                                    <tr>
                                            <td>Population:</td>
                                            <td class="coin-info-label"><?php echo $row['pcgs_population'];?></td>
                                    </tr>
                            </table>
                                
                            </div>
                            <div class="con-bt">
                            	<div class="con-bt-left"><img src="images/botton.png" alt="" /></div>
                                <div class="con-bt-right"><img src="images/botton.png" alt="" /></div>
                            </div>
                        </div>
<?php 
                }
            }
        }
    }
    
?>
                    <div class="coin-bill-track">
                        <div class="con-bt">
                            <div class="con-bt-left"><img src="images/botton.png" alt="" /></div>
                            <div class="con-bt-right"><img src="images/botton.png" alt="" /></div>
                        </div>
                        
                        <div class="add-coin-2">
                            <h2>Images</h2>
                            <div class="add-coin-right">
<?php
$ls_queryImage = $conn->prepare("SELECT 
                                    GROUP_CONCAT(id_item_image) AS id_item_image, 
                                    GROUP_CONCAT(filename) AS filename, 
                                    GROUP_CONCAT(url) AS url 
                                FROM 
                                    item_images 
                                WHERE 
                                    id_ownership = :id_ownership");
$ls_queryImage->execute(array(':id_ownership' => $_REQUEST['id']));
if($ls_queryImage->errorCode() == '00000'){
    if($ls_queryImage->rowCount() > 0){
        $imageFiles = array();
        $imageFilesId = array();
        foreach ($ls_queryImage->fetchAll(PDO::FETCH_ASSOC) as $row) {
                $files = explode(',', $row['filename']);
                foreach($files as $file){
                    if($file == 'NULL' || $file == ''){
                        continue;
                    }
                    $imageFiles[] = $file;
                }   
                $images = explode(',', $row['url']);
                foreach($images as $image){
                    if($image == 'NULL' || $image == ''){
                        continue;
                    }
                    $imageFiles[] = $image;
                } 
                $id_item_image = explode(',', $row['id_item_image']);
                foreach($id_item_image as $id){
                    $imageFilesId[] = $id;
                } 
        } 
    $k = 0;
//    echo '<pre>';
//    print_r($imageFiles);
//    echo '</pre>';
//    die();
    foreach ($imageFiles as $img) {
    if(is_file(get_setting(BASE_DIRECTORY, $conn).'/'.$img)){
            $image_src = get_setting(BASE_URL, $conn).'/'.$img;
    }
    else{
            $image_src = $img;
    }
?>
                                <div class="add-coin-pic">
                                    <img src="<?php echo $image_src;?>" alt="" class="show_image" name="<?php echo $img; ?>"/>
                                    <a href="javascript:void(0);" id="<?php echo $imageFilesId[$k];?>" class="imageDel">Delete</a>
                                </div>
<?php $k++; 
        }
    }
}
?>
                        </div>
                        <form action="" method="post" name="add_image" id="add_image" enctype="multipart/form-data">
                             <?php if(isset($ls_errorMsg)){?>
                                <div class="error-message">
                                    <span><?php echo $ls_errorMsg; ?></span>
                                </div>
                            <?php } ?>
                            <div class="add-con-dets">
                                <div class="add-con-rept">
                                    <label>Upload File</label>
                                    <input type="file" name="upload[]" value="" id="upload_file" class="brows-file-2" />
                                </div>
                            </div>
                            <div class="add-con-dets">
                                <div class="add-con-rept">
                                    <label>Enter Url</label>
                                    <input type="text" name="upld_url[]" value=""  id="upload_url" class="input-text-2" />
                                </div>
                            </div>
                            <input type="hidden" name="id_ownership" id="id_ownership" value="<?php if(isset($_REQUEST['id'])){echo $_REQUEST['id'];}?>"/>
                            <div class="add-con-dets"><input type="submit" value="Add Images" name="add_image" class="search-btn" /></div>
                        </form>
                        </div>
                        
                        
                        <div class="add-coin-2">
                            <h2>Auction URLS</h2>
                            <div class="add-url">
<?php
$ls_queryAuctions = $conn->prepare("SELECT 
                                        * 
                                    FROM 
                                        item_auctions 
                                    WHERE 
                                        id_ownership = :id_ownership");
$ls_queryAuctions->execute(array(':id_ownership' => $_REQUEST['id']));
if($ls_queryAuctions->errorCode() == '00000'){
    if($ls_queryAuctions->rowCount() > 0){
        foreach ($ls_queryAuctions->fetchAll(PDO::FETCH_ASSOC) as $row1) {
?>
                                <div class="add-url-link" id="main_div<?php echo $row1['id_item_auction'];?>">
                                    <div class="add-link">
                                        <a href="<?php echo $row1['url']; ?>"><?php echo $row1['notes']; ?></a>
                                    </div>
                                    <div class="delete-ed">
                                        <span class="remove_auctions" id="<?php echo $row1['id_item_auction']; ?>">
                                            <a href="javascript:void(0);">Delete</a>
                                        </span>
                                        <span class="edit" id="<?php echo $row1['id_item_auction']; ?>">
                                            <a href="javascript:void(0);">Edit</a>
                                        </span>
                                    </div>
                                </div>
<?php
         } 
    }
}
?>
                            </div>
                            <form action="" name="add_auction" id="add_auction_urls" method="post">
                                <?php if(isset($ls_errorMsgAuction)){?>
                                    <div class="error-message">
                                        <span><?php echo $ls_errorMsgAuction; ?></span>
                                    </div>
                                <?php } ?>
                                <div class="add-con-dets">
                                    <div class="add-con-rept">
                                        <label>Enter Url</label>
                                        <input type="text" value="" name="auction_url[]" id="auction_url" class="input-text-2 validate[required, custom[url]]" />
                                    </div>
                                </div>
                                <div class="add-con-dets">
                                    <div class="add-con-rept">
                                        <label>Note</label>
                                        <input type="text" value=""  name="auction_notes[]" id="auction_notes" class="input-text-2 validate[required]" />
                                    </div>
                                </div>
                                  <input type="hidden" name="id_ownership" id="id_ownership" value="<?php if(isset($_REQUEST['id'])){echo $_REQUEST['id'];}?>"/>
                                  <input type="hidden" name="id_item_auction" id="id_item_auction" value=""/>
                                <div class="add-con-dets"><input type="submit" value="Add Auction Urls" name="add_auction" id="add_auction" class="search-btn" /></div>
                            </form>
                        </div>
                        
                        
                    	<div class="con-bt">
                            <div class="con-bt-left"><img src="images/botton.png" alt="" /></div>
                            <div class="con-bt-right"><img src="images/botton.png" alt="" /></div>
                        </div>
                    </div>
                    
                    
                    <div class="coin-bill-track">
                        <div class="con-bt">
                            <div class="con-bt-left"><img src="images/botton.png" alt="" /></div>
                            <div class="con-bt-right"><img src="images/botton.png" alt="" /></div>
                        </div>
                        <div class="addpage-con-det">
                            <input onclick="window.location='dashboard.php'" type="button" value="Finish" class="search-btn" />                  
                        </div>
                    	<div class="con-bt">
                            <div class="con-bt-left"><img src="images/botton.png" alt="" /></div>
                            <div class="con-bt-right"><img src="images/botton.png" alt="" /></div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
     <!--content end-->
<?php include 'footer.php' ?>;
