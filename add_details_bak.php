<?php
if(isset($_POST['submit_details'])){
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
								status = 'A'
							WHERE
								pcgs_ver_id = :pcgs_ver_id
						";
		}
		else{
			$ls_query = "INSERT INTO
									items
									(
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
										status
									)
								VALUES
									(
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
										'A'
									)
							";
		}
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
			$image_file = multi_files_upload($_FILES['upload']);
			if($image_file[1] == 1){
				echo $image_file[0];
			}
			else{
				 foreach ($_POST['upld_url'] as $urls) {
					 if($urls == '' || $urls == 'NULL'){
						 continue;
					 }
					 $image_file[0][] = $urls;
				 }
				 foreach ($image_file[0] as $file) {
					 if(is_file(get_setting(BASE_DIRECTORY, $conn).'/'.get_setting(FILE_UPLOAD_FOLDER, $conn).'/'.$file)){
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
                                                                                id_item,
                                                                                filename,
                                                                                url
                                                                        )
                                                                VALUES
                                                                        (
                                                                                :id_item,
                                                                                :filename,
                                                                                :url
                                                                        )
                                                                ";
				   $ls_image_fileObj = $conn->prepare($ls_queryImageFileUpload);
				   $ls_image_fileObj->execute(array(':id_item' => $ls_item_id, ':filename' => $filename, ':url' => $url));
				   if($ls_image_fileObj->errorCode() != '00000'){
						$ls_errorInsert = $ls_image_fileObj->errorInfo();
						echo $ls_errorInsert[0] . ': ' . $ls_errorInsert[2];
						$queryImageFileError = 1;
					}
				}
				// delete first and then insert. 
				if(!isset($queryImageFileError) || $queryImageFileError != 1){
					$conn->query("DELETE FROM item_auctions WHERE id_item = $ls_item_id");
					$a = 0;
					for($a=0; $a < count($_POST['auction_url']); $a++){
						$ls_queryAuction = "INSERT INTO
												item_auctions
												(
													id_item,
													url,
													notes
												)
											VALUES 
												(
													:id_item,
													:url,
													:notes
												)
											";
						$ls_queryAuctionObj = $conn->prepare($ls_queryAuction);
						$ls_queryAuctionObj->execute(array(':id_item' => $ls_item_id, ':url' => $_POST['auction_url'][$a], ':notes' => $_POST['auction_notes'][$a]));
						if($ls_queryAuctionObj->errorCode() != '00000'){
							$ls_errorAuction = $ls_queryAuctionObj->errorInfo();
							echo $ls_errorAuction[0] . ': ' . $ls_errorAuction[2];
							$queryAuctionError = 1;
						}
					}
					if(!isset($queryAuctionError) || $queryAuctionError != 1){
						$ls_checkOwnership = $conn->query("SELECT * FROM ownerships WHERE id_owner = ".$_SESSION['id_owner']."");
						if($ls_checkOwnership->rowCount() > 0){
							$ls_getItemId = $ls_checkOwnership->fetchAll(PDO::FETCH_ASSOC);
							foreach ($ls_getItemId as $item) {
								if($item['id_item'] == $ls_item_id && $item['id_owner'] == $_SESSION['id_owner']){
									$ls_setUpdate = TRUE;
								}
							}
						}
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
							header('location:dashboard.php');
							exit();
						}
					}
					else{
						echo 'Submission failed! : Auction Details';
					}
				}
				else{
					echo 'Submission failed!';
				}
			}
		}
	}
	else{
		$ls_errorSelect = $ls_querySearchItem->errorInfo();
		echo $ls_errorSelect[0] . ': ' . $ls_errorSelect[2];
	}
}
include_once 'header.php';
?>
<script type="text/javascript">
    $(document).ready(function(){
        var fileCounter = 2;
        var urlCounter = 2;
        var auctionCounter = 2;
        $('#add_files').bind('click',function(){
            var dynamicHtmlforFiles = $(document.createElement('div')).attr('id', 'files'+fileCounter).addClass('add-con-rept');
            dynamicHtmlforFiles.html('<input type="file" name="upload[]" id="upld_file' + fileCounter +'" class="brows-file" style="margin-top:2px;">'+'<span class="remove"><a href="javascript:void(0);">Remove</a></span>');
            dynamicHtmlforFiles.appendTo('#uploadFilesDiv');
            fileCounter++;
        });
        $('#add_urls').click(function(){
            var dynamicHtmlforurls = $(document.createElement('div')).attr('id', 'urls'+urlCounter).addClass('add-con-rept');
            dynamicHtmlforurls.html('<input type="text" name="upld_url[]" id="upld_url'+ urlCounter +'" class="input-text">'+'<span class="remove"><a href="javascript:void(0);">Remove</a></span>');
            dynamicHtmlforurls.appendTo('#enterUrlDiv');
            urlCounter++;
        });
        $('#add_auction_urls').click(function(){
            var dynamicHtmlforAucurls = $(document.createElement('div')).attr('id', 'auction'+auctionCounter).addClass('add-con-rept');
            dynamicHtmlforAucurls.html('<label>Enter Previous Auction URL:</label>'+'<input type="text" name="auction_url[]" id="auction_url'+ auctionCounter +'" class="input-text">'+'<label>Note:</label>'+'<textarea name="auction_notes[]" id="auction_notes'+ auctionCounter +'" class="text-box">'+'</textarea>'+'<span class="remove"><a href="javascript:void(0);">Remove</a></span>');
            dynamicHtmlforAucurls.appendTo('#prev_auction');
            auctionCounter++;
        });
        $(document).delegate('.remove','click',function(){
            $(this).parent('div').remove();
        });
        $(document).delegate('.imageDel','click',function(){
            var id = $(this).attr('id');
            var title = $(this).attr('title');
            var parentDiv = $(this).parent('div');
            var url = 'image_delete.php?id=' + id + '&file=' + title;
            $.getJSON(url, function(json){
                if(json.status == 'success'){
                    parentDiv.remove();
                }
                else{
                    alert('Image can not be deleted!');
                }
            });
        });
        $(document).delegate('.remove_auctions','click',function(){
            var id = $(this).attr('id');
            var parentDiv = $(this).parent('div');
            var url = 'auction_delete.php?id=' + id;
            $.getJSON(url, function(json){
                if(json.stats == 'success'){
                    parentDiv.remove();
                }
                else{
                    alert('This auctions details can not be deleted!');
                }
            });
        });
    });
</script>