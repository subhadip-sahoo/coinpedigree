<?php
include 'inc/header.inc.php';
include 'custom_functions.php';
include 'is_logged_in.php';
include_once 'header.php';
?>
<script type="text/javascript">
    $(function(){
        $('#barcode_id').validationEngine();
    });
</script>
            <div class="inner-page">
            	<h1>Search coin </h1>
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
                        <form name="barcode_id" id="barcode_id" action="add_ownership.php" method="post">
                            <?php if(isset($_REQUEST['err']) && $_REQUEST['err'] == 1){?>
                            <div class="error-message">
                                <span>Enter correct bar code id!</span>
                            </div>
                            <?php } ?>
                            <?php if(isset($_REQUEST['err']) && $_REQUEST['err'] == 2){?>
                            <div class="error-message">
                                <span>The bar code ID is not valid!</span>
                            </div>
                            <?php } ?>
                            <div class="add-con">
                                    <div class="add-con-dets">
                                        <div class="add-con-rept">
                                            <label>Enter a Cert Number and click the Verify Certification button.</label>
                                            <input type="text" value="" name="id" id="id" class="input-text validate[required, minSize[7], maxSize[8], custom[onlyNumberSp]]" />
                                            <input type="submit" value="Verify" name="submit" class="verify-btn" />
                                        </div>
                                    </div>                                                                                            
                            </div>                        
                        </form>						
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
