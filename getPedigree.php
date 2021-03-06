<?php
include 'inc/header.inc.php';
include 'is_logged_in.php';
if(!isset($_POST['set_pedigree']) || $_POST['set_pedigree'] == ''){
    header('location:index.php');
    exit();
}
$ls_additionalQuery = '';
if(isset($_POST['set_pedigree'])){
    $radioPedigreeValuw = $_POST['set_pedigree'];
    if($radioPedigreeValuw == 'all'){
        $ls_additionalQuery = ' AND i.pcgs_pedigree IN (SELECT pcgs_pedigree FROM items GROUP BY pcgs_pedigree)';
    }
    else{
        $ls_additionalQuery = " AND i.pcgs_pedigree = '$radioPedigreeValuw'";
    }
}
$ls_queryMain = "SELECT 
                        o.*, 
                        i.*,
                        ow.*,
                        (SELECT GROUP_CONCAT(m.filename) AS filename FROM item_images AS m WHERE m.id_ownership = o.id_ownership) AS file,
                        (SELECT GROUP_CONCAT(m.url) AS image_url FROM item_images AS m WHERE m.id_ownership = o.id_ownership) AS images,
                        (SELECT GROUP_CONCAT(a.url) AS urls FROM item_auctions AS a WHERE a.id_ownership = o.id_ownership) AS urls
                    FROM 
                        ownerships AS o
                        INNER JOIN items AS i ON i.id_item = o.id_item
                        INNER JOIN owners AS ow ON ow.id_owner = o.id_owner
                    WHERE 
                        o.id_owner = :id_owner
                    $ls_additionalQuery";
    $ls_queryMainObj = $conn->prepare($ls_queryMain);
    $ls_queryMainObj->execute(array(':id_owner' => $_POST['owner_id']));
    if($ls_queryMainObj->errorCode() == '00000'){
        if($ls_queryMainObj->rowCount() > 0){
            $table_start = 0;
            $pedigree = '';
            foreach ($ls_queryMainObj->fetchAll(PDO::FETCH_ASSOC) as $row) {
                $ls_current_ownership = TRUE;
                $ls_queryMaxOwnerId = $conn->prepare("SELECT MAX(id_ownership) AS id_ownership FROM ownerships WHERE id_item = :id_item");
                $ls_queryMaxOwnerId->execute(array(':id_item' => $row['id_item']));
                $ls_maxIdObj = $ls_queryMaxOwnerId->fetch(PDO::FETCH_OBJ);
                if($ls_maxIdObj->id_ownership != $row['id_ownership']){
                    $ls_current_ownership = FALSE;
                }
?>
<script type="text/javascript">
    $(document).ready(function() {
        $('#slider<?php echo $row['id_item'];?>').nivoSlider({
            controlNav: false
        });
    });
</script>                            
                    <div class="user-dashboard-tab">
						<div class="coin-div-tab">
								<div class="coin-pic">
									<div class="slider-wrapper theme-default">
										<div id="slider<?php echo $row['id_item'];?>" class="nivoSlider">
											<?php 
												$files = explode(',', $row['file']);
												foreach($files as $file){
													if($file == 'NULL' || $file == ''){
														continue;
													}
											?>
											<a href="#"><img src="<?php echo get_setting(BASE_URL, $conn).'/'.$file;?>"  alt="" title="#htmlcaption1"/></a>
											<?php } ?>
											<?php 
												$images = explode(',', $row['images']);
												foreach($images as $image){
													if($image == 'NULL' || $image == ''){
														continue;
													}
											?>
											<a href="#"><img src="<?php echo $image;?>"  alt="" data-transition="slideInLeft" title="#htmlcaption2"/></a>
											<?php } ?>
										</div>
									</div>
									<div class="edit-btn">
										<a href="add_ownership.php?cert_id=<?php echo $row['pcgs_ver_id'];?>">Edit</a>
									</div>
									
								</div>
                                                    <div class="previous-text">
<?php if(isset($ls_current_ownership) && $ls_current_ownership == FALSE){
        echo 'Previous Owner';
}else{
    echo 'Current Owner';
}
?>
                                                    </div>
						</div>

						<div class="coin-dets">
							<div class="con-bt">
							<div class="con-bt-left"><img src="images/botton.png" alt="" /></div>
							<div class="con-bt-right"><img src="images/botton.png" alt="" /></div>
						</div>
							<div class="con-details">
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
                        <?php if($table_start < ($ls_queryMainObj->rowCount()-1)){?>
						<hr />
                        <?php } ?>
					</div>
<?php
           $table_start++; 
           $pedigree = $row['pcgs_pedigree'];
        }
    }
    else{
?>
        <div class="warning-message">
            <span>No records to display!</span>
        </div>
<?php
        }
}
else{
        $ls_queryMainError = $ls_queryMainObj->errorInfo();
        echo $ls_queryMainError[0] . ': ' . $ls_queryMainError[2];
    }
?>
   
    
