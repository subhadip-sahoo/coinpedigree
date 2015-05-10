<?php
include 'inc/header.inc.php';
include 'custom_functions.php';
include 'is_logged_in.php';
$lb_certified = false;
?>
            <div class="inner-page">
            	<h1>Add </h1>
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
                     <div class="coin-bill-track">
                        <div class="con-bt">
                            <div class="con-bt-left"><img src="images/botton.png" alt="" /></div>
                            <div class="con-bt-right"><img src="images/botton.png" alt="" /></div>
                        </div>
<?php
if(isset($_POST['submit']) || isset($_REQUEST['cert_id'])){
    $ls_pcgs_ver_id = isset($_REQUEST['cert_id'])? trim($_REQUEST['cert_id']):trim($_POST['id']);
    if($ls_pcgs_ver_id == ''){
        die('Enter barcode id!');
    }
    $data = getTableValues($ls_pcgs_ver_id);
    if($data != 0){
?>
                                                   
                                                <div class="coin-bill-track">
                                                    <div class="addpage-coin-det">
							<div class="con-bt">
                                                            <div class="con-bt-left"><img src="images/botton.png" alt="" /></div>
                                                            <div class="con-bt-right"><img src="images/botton.png" alt="" /></div>
                                                        </div>
							<div class="addpage-con-details">
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
        header('location:add_ownership.php');
        exit();
    }
    $ls_query_item_id = $conn->prepare("SELECT * FROM items WHERE pcgs_ver_id = :pcgs_ver_id AND status = 'A'");
    $ls_query_item_id->execute(array(':pcgs_ver_id' => $ls_pcgs_ver_id));
    if($ls_query_item_id->errorCode() == '00000'){
        if($ls_query_item_id->rowCount() > 0){
            $ls_pgcs_item_id = $ls_query_item_id->fetch(PDO::FETCH_OBJ);
            $current_item_id = $ls_pgcs_item_id->id_item;
            $ls_queryMain = "SELECT 
                                i.*,
                                (SELECT GROUP_CONCAT(m.filename) AS filename FROM item_images AS m WHERE m.id_item = i.id_item) AS file,
                                (SELECT GROUP_CONCAT(m.id_item_image) AS id_item_image FROM item_images AS m WHERE m.id_item = i.id_item) AS id_item_image,
                                (SELECT GROUP_CONCAT(m.url) AS image_url FROM item_images AS m WHERE m.id_item = i.id_item) AS images,
                                (SELECT GROUP_CONCAT(a.url) AS urls FROM item_auctions AS a WHERE a.id_item = i.id_item) AS urls
                            FROM 
                                items AS i
                            WHERE 
                                i.id_item = :id_item";
            $ls_queryMainObj = $conn->prepare($ls_queryMain);
            $ls_queryMainObj->execute(array(':id_item' => $current_item_id));
            if($ls_queryMainObj->errorCode() == '00000'){
            if($ls_queryMainObj->rowCount() > 0){
                    $imageFiles = array();
                    $imageFilesId = array();
                    foreach ($ls_queryMainObj->fetchAll(PDO::FETCH_ASSOC) as $row) {
                            $files = explode(',', $row['file']);
                            foreach($files as $file){
                                if($file == 'NULL' || $file == ''){
                                    continue;
                                }
                                $imageFiles[] = $file;
                            }   
                            $images = explode(',', $row['images']);
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
            }
            else{
                
            }
        }
        else{
            $ls_queryMainError = $ls_queryMainObj->errorInfo();
            echo $ls_queryMainError[0] . ': ' . $ls_queryMainError[2];
        }
    }
    else{
        echo 'No item found!';
    }
}
else{
    $ls_queryItemIdError = $ls_query_item_id->errorInfo();
    echo $ls_queryItemIdError[0] . ': ' . $ls_queryItemIdError[2];
}
?>
                                                        <div class="coin-bill-track">
                                                            <div class="con-bt">
                                                                <div class="con-bt-left"><img src="images/botton.png" alt="" /></div>
                                                                <div class="con-bt-right"><img src="images/botton.png" alt="" /></div>
                                                            </div>
                                                            <form name="uploading" id="uploading" action="" method="post" enctype="multipart/form-data">
                                                            <div class="add-con">                                                           
								<div id="uploadFilesDiv" class="add-con-dets">
									<div id="files1" class="add-con-rept">
										<label>Upload File:</label>
										<input type="file" name="upload[]" id="upld_file1" class="brows-file">
										<span class="remove"><a href="javascript:void(0);">Remove</a></span>
									</div>
                                                                    <a href="javascript:void(0);" id="add_files">Add More Files</a>
								</div>
								 
								<div id="enterUrlDiv" class="add-con-dets"> 
									<div id="urls1" class="add-con-rept">
										<label>OR You can enter URL of the image:</label>
										<input type="text" name="upld_url[]" id="upld_url1" class="input-text">
										<span class="remove"><a href="javascript:void(0);">Remove</a></span>
									</div>
                                                                    <a href="javascript:void(0);" id="add_urls">Add More URLS</a>
								</div>
								
								<div id="prev_auction" class="add-con-dets">
               
<?php
    if(isset($current_item_id)){
        $ls_queryEdit = "SELECT 
                            o.*, 
                            i.*,
                            (SELECT GROUP_CONCAT(a.id_item_auction) AS id_item_auction FROM item_auctions AS a WHERE a.id_item = i.id_item) AS id_item_auction,
                            (SELECT GROUP_CONCAT(a.url) AS urls FROM item_auctions AS a WHERE a.id_item = i.id_item) AS urls
                        FROM 
                            items AS i
                            LEFT JOIN ownerships AS o ON i.id_item = o.id_item
                        WHERE 
                            i.id_item = :id_item
                        ";
        $ls_queryEditObj = $conn->prepare($ls_queryEdit);
        $ls_queryEditObj->execute(array(':id_item' => $current_item_id));
        if($ls_queryEditObj->errorCode() == '00000'){
            if($ls_queryEditObj->rowCount() > 0){
                $row = $ls_queryEditObj->fetch(PDO::FETCH_ASSOC);
                extract($row, EXTR_PREFIX_ALL, 'lp');
                $id_item_auction = explode(',', $lp_id_item_auction);
                foreach ($id_item_auction as $au) {
                    $ls_queryEditAuction = $conn->prepare("SELECT * FROM item_auctions WHERE id_item_auction = :id_item_auction");
                    $ls_queryEditAuction->execute(array(':id_item_auction' => $au));
                    if($ls_queryEditAuction->errorCode() == '00000'){
                        if($ls_queryEditAuction->rowCount() > 0){
                            $ls_auctionEditValue = $ls_queryEditAuction->fetch(PDO::FETCH_OBJ);
?>
								<div id="auction" class="add-con-rept">
									<label>Enter Previous Auction URL:</label>
									<input type="text" name="auction_url[]" id="auction_url1" value="<?php echo $ls_auctionEditValue->url;?>" class="input-text">
									<label>Note:</label>
									<textarea name="auction_notes[]" id="auction_notes1" class="text-box"><?php echo $ls_auctionEditValue->notes;?></textarea>
									<span class="remove_auctions" id="<?php echo $au;?>" onClick="return confirm('Are you sure?')"><a href="javascript:void(0);">Remove</a></span>
								</div> 
<?php                
                }
            }
        }
    }
    else{
 ?> 
								<div id="auction1" class="add-con-rept">
									<label>Enter Previous Auction URL:</label>
									<input type="text" name="auction_url[]" id="auction_url1" class="input-text">
									<label>Note:</label>
									<textarea name="auction_notes[]" id="auction_notes1" class="text-box"></textarea>
									<span class="remove"><a href="javascript:void(0);">Remove</a></span>
								</div>
<?php                
        }
    }
    else{
        $ls_queryEditError = $ls_queryEditObj->errorInfo();
        echo $ls_queryEditError[0] . ': ' . $ls_queryEditError[2];
    }
}
else{
?>  
							<div id="auction1" class="add-con-rept">
								<label>Enter Previous Auction URL:</label>
								<input type="text" name="auction_url[]" id="auction_url1" class="input-text">
								<label>Note:</label>
								<textarea name="auction_notes[]" id="auction_notes1" class="text-box"></textarea>
								<span class="remove"><a href="javascript:void(0);">Remove</a></span>
							</div>
<?php } ?>  
                                                                    <a href="javascript:void(0);" id="add_auction_urls">Add More Auction Details</a>
							</div>
							
							<div class="add-con-dets">
								<div class="add-con-rept">
									<label>Enter Postcode:</label>
									<input type="text" class="input-text" name="owner_postcode" id="owner_postcode" value="<?php if(isset($lp_postcode)){echo $lp_postcode;}?>">
									<label>Note:</label>
									<textarea name="owner_notes" id="owner_notes" class="text-box"><?php if(isset($lp_notes)){echo $lp_notes;}?></textarea>
								</div>
							</div>
							<input type="hidden" name="pcgs_ver_id" id="pcgs_ver_id" value="<?php if(isset($ls_pcgs_ver_id)){echo $ls_pcgs_ver_id;}?>"/>			
		                                                       
                                <div class="add-con-dets"><input type="submit" name="submit_details" value="Add Ownership" class="search-btn" /></div>                     
                        </div>
						<div class="add-coin-right">
<?php        
$k = 0;
foreach ($imageFiles as $img) {
	if(is_file(get_setting(BASE_DIRECTORY, $conn).'/'.get_setting(FILE_UPLOAD_FOLDER, $conn).'/'.$img)){
		$image_src = get_setting(BASE_URL, $conn).'/'.get_setting(FILE_UPLOAD_FOLDER, $conn).'/'.$img;
	}
	else{
		$image_src = $img;
	}
?>						                    
                        	<div class="add-coin-pic">
								<img src="<?php echo $image_src;?>" alt="" />
								<a href="javascript:void(0);" id="<?php echo $imageFilesId[$k];?>" title="<?php echo $img;?>" class="imageDel">Delete</a>
							</div>
<?php $k++; } ?>							
						</div>
						</form>
<?php
} else {
?>
                        <form name="barcode_id" id="barcode_id" action="" method="post">
                            <div class="add-con">
                                    <div class="add-con-dets">
                                        <div class="add-con-rept">
                                            <label>Enter a Cert Number and click the Verify Certification button.</label>
                                            <input type="text" value="" name="id" id="id" class="input-text" />
                                            <input type="submit" value="Verify" name="submit" class="verify-btn" />
                                        </div>
                                    </div>                                                                                            
                            </div>                        
                        </form>
<?php
}
?>						
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
<?php
include_once 'footer.php';
?>
