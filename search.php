<?php
include 'inc/header.inc.php';
include 'custom_functions.php';
if(isset($_POST['id'])== false){
    header('location:index.php');
    exit();
}
include_once 'header.php';
?>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&amp;sensor=true&amp;libraries=geometry"></script>
            <div class="inner-page">
            	<h1>Coin traversing</h1>
                
                <div class="bill-tracking">
                	<div class="tracking-top">
                    	<div class="track-hed">Search result for <?php echo $_POST['id'];?></div>
                    </div>                    
                   
<?php
$ls_query_item_id = $conn->prepare("SELECT * FROM items WHERE pcgs_ver_id = :pcgs_ver_id AND status = 'A'");
    $ls_query_item_id->execute(array(':pcgs_ver_id' => $_POST['id']));
    if($ls_query_item_id->errorCode() == '00000'){
        if($ls_query_item_id->rowCount() > 0){
            $ls_item_id = $ls_query_item_id->fetch(PDO::FETCH_OBJ);
            $ls_queryMain = "SELECT 
                                *
                            FROM 
                                items
                            WHERE 
                                id_item = :id_item";
            $ls_queryMainObj = $conn->prepare($ls_queryMain);
            $ls_queryMainObj->execute(array(':id_item' => $ls_item_id->id_item));
            if($ls_queryMainObj->errorCode() == '00000'){
                if($ls_queryMainObj->rowCount() > 0){
                    $row = $ls_queryMainObj->fetch(PDO::FETCH_ASSOC);
?>	
                     <div class="coin-info"><img src="images/icon1.png" alt="" /> COIN INFORMATION<div class="add-btn"><a href="add_ownership.php?cert_id=<?php echo $_POST['id'];?>"><img src="images/add-btn.png"  alt=""/></a></div></div>
				<div class="user-dashboard">
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
$arr_latlang = array();
$zipcode = array();
$travell_time = array();
$ls_zipObj = $conn->query("SELECT * FROM ownerships WHERE id_item = $ls_item_id->id_item ORDER BY entry_date DESC");
if($ls_zipObj->rowCount() > 0){
?>					
				</div>
					
					
					
					
					
                    <!--bill tracking table start-->
                    <div class="coin-bill-track">
                        <div class="con-bt">
                            <div class="con-bt-left"><img src="images/botton.png" alt="" /></div>
                            <div class="con-bt-right"><img src="images/botton.png" alt="" /></div>
                        </div>
                        <div class="bill-track-dets">
                            <table>
                            <tbody>
                                <tr align="left" class="table-title">
                                    <th>Entry Time <br />(Local Time of Zip)</th>
                                    <th>Location, State/Province</th>
                                    <th>Travel Time <br />(from previous entry)</th>
                                    <th width="70px">Distance <br />(Miles)*</th>
                                    <th width="90px">Average Speed (Miles Per Day)</th>
                                </tr>
<?php
$ls_recordAll = $ls_zipObj->fetchAll(PDO::FETCH_ASSOC);
foreach ($ls_recordAll as $row) {
	$latLang = getLatLng($row['postcode']);
	$arr_latlang[] = $latLang;
	$zipcode[] = $row['postcode'];
	$travell_time[] = $row['entry_date'];
}
$start = 0;
$get_distance = array();
$get_time_diff = array();
for($start = 0; $start < count($ls_recordAll); $start++) {
	if($start == count($zipcode)-1){
		$get_distance[] = 'n/a';
	}
	else{
		$get_distance[] = getDistance($zipcode[$start], $zipcode[$start + 1], 'M');
	}
	if($start == count($travell_time)-1){
		$get_time_diff[] = 'Initial Entry';
	}
	else{
		$get_time_diff[] = getTimeDiff($travell_time[$start], $travell_time[$start + 1]);
	}
	$address = parse_address_google($ls_recordAll[$start]['postcode']);
	$city = ($address['city'] == '')?'':$address['city'];
	$state = ($address['state'] == '')?'':$address['state'];
?>								
                                <tr <?php echo ($start==0)?'class="current-int"':'class="past-int"';?>>
                                        <td><?php echo date(DISPLAY_FORMAT_DATETIME_SHORT,strtotime($ls_recordAll[$start]['entry_date']));?></td>
                                        <td><?php if($city<>''){echo $city.', ';}if($state<>''){echo $state;}?></td>
                                        <td><?php echo $get_time_diff[$start];?></td>
<?php
if($get_time_diff[$start] != 'Initial Entry'){
    $ls_getYrDay = explode(' ', $get_time_diff[$start]);
    $total_days = ($ls_getYrDay[0] * 365) + ($ls_getYrDay[2] * 30) + $ls_getYrDay[4];
}
else{
    $total_days = -1;
}
?>
                                        <td align="center"><?php echo (is_numeric($get_distance[$start]))?number_format($get_distance[$start], 0, '', ''):$get_distance[$start]; ?></td>
                                        <td align="center"><?php echo (is_numeric($get_distance[$start]) && $total_days > 0)?number_format($get_distance[$start], 0, '', '')/$total_days:'n/a'; ?></td>
                                </tr>
                                <tr class="note">
                                        <td>User's Note</td>
                                        <td colspan="5"><?php echo $ls_recordAll[$start]['notes'];?></td>
                                </tr>
<?php
}
?>                                
                            </tbody>
                            </table>
                        </div>
                        <div class="con-bt">
                            <div class="con-bt-left"><img src="images/botton.png" alt="" /></div>
                            <div class="con-bt-right"><img src="images/botton.png" alt="" /></div>
                        </div>
                    </div>
<!--<script>
    var markers = [];
    var map;
    var totdistance = 0;
   $(function(){
   function initialize(){
    var arr_latlang = <?php //echo json_encode($arr_latlang); ?>;
     var polylineCoordinates = [];
       for(var i=0;i<arr_latlang.length;i++){
           polylineCoordinates[i] = new google.maps.LatLng(arr_latlang[i].lat, arr_latlang[i].lng);
       }
      var center= new google.maps.LatLng(arr_latlang[0].lat, arr_latlang[0].lng);
      var myOptions = {
                           zoom: 5,
                           center: center,
                           dragable: true,
                           navigationControl: true,
                           mapTypeId: google.maps.MapTypeId.roadmap
      }     
     map = new google.maps.Map(document.getElementById("map"), myOptions);
     var j = 0;
     var labels = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
     while(j < polylineCoordinates.length){
           createMarker(polylineCoordinates[j], "http://maps.google.com/mapfiles/marker_green"+labels[j]+".png");
           if(j == polylineCoordinates.length - 1){
                   break;
           }
           totdistance = totdistance + google.maps.geometry.spherical.computeDistanceBetween (polylineCoordinates[j], polylineCoordinates[++j]);
    }
     var polyline = new google.maps.Polyline({
             path: polylineCoordinates,
             strokeColor: '#FF0000',
             strokeOpacity: 1.0,
             strokeWeight: 4,
             editable: false
     });
     polyline.setMap(map);    
   }
   function createMarker(latlng, icon) {
           var marker = new google.maps.Marker({ map: map, position: latlng, clickable:false, icon: icon });

           var listener = google.maps.event.addListener(map, "idle", function() {
             if (map.getZoom() > 5) map.setZoom(5); 
             google.maps.event.removeListener(listener);
           });
           markers.push(marker);
   }
   initialize();
   });
</script>					-->
                    <!--bill tracking table end-->
<!--                    <div class="coin-info"><img src="images/map-icon.png" alt="" /> Google Map Information</div>
                    <div class="map-div">
                    	<div class="map-info">
                            <div class="map-details"><div id="map" style="width: 100%; height: 100%"></div></div>
                        </div>
                    </div>-->
<?php
	}
} else{
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
    }
    else{
?>
            <div class="warning-message">
                <span>No item found.!</span>
            </div>
<?php 
    }
}
else{
    $ls_queryItemIdError = $ls_query_item_id->errorInfo();
    echo $ls_queryItemIdError[0] . ': ' . $ls_queryItemIdError[2];
}
?>                	
                </div>
            </div>
            
        </div>
    </div>
     <!--content end-->
     


<?php
include_once 'footer.php';
?>