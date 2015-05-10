<?php
if(isset($_SESSION['id_owner'])){
    $ls_query = $conn->prepare("SELECT * FROM owners WHERE id_owner = :id_owner AND status = 'A'");
    $ls_query->execute(array(':id_owner' => $_SESSION['id_owner']));
    if($ls_query->rowCount() == 1){
            $ls_getObj = $ls_query->fetch(PDO::FETCH_OBJ);
            $last_login_from_ip = ($ls_getObj->last_login_from_ip == '')? $_SESSION['last_login_from_ip']:$ls_getObj->last_login_from_ip;
            $last_login_at = ($ls_getObj->last_login_at == '0000-00-00 00:00:00')? $_SESSION['last_login_at']:$ls_getObj->last_login_at;
    }
    else{
            $header_msg = 'Invalid user!';
    }
    if($ls_query->errorCode() != 0000){
            $ls_errors = $ls_query->errorInfo();
            $header_msg = $ls_errors[0] . ': ' . $ls_errors[2];
    }
}
$ls_queryPedigree = $conn->query("SELECT pcgs_pedigree FROM items GROUP BY pcgs_pedigree");
$row_pedigree = $ls_queryPedigree->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php echo TEXT_WEBSITE_ADMIN_PANEL_HEADING; ?></title>
    
    <link href="css/stylesheet.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="default/default.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="css/nivo-slider.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="js/validationEngine.jquery.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/humanity/jquery-ui.css" />
    <style>
        .popupContactClose{font-size:14px;line-height:14px;right:-20px;top:-20px;position:absolute;color:#6fa5fd;font-weight:700;display:block;cursor:pointer;background:url('images/close.png') no-repeat right top;width:30px;height:29px;}
        #backgroundPopup{display:none;position:fixed;_position:absolute; /* hack for internet explorer 6*/height:100%;width:100%;top:0;left:0;background:#000000;border:1px solid #cecece;z-index:1;}
        .popupContact{display:none;position:fixed;_position:absolute; /* hack for internet explorer 6*/background:#FFFFFF; border: 7px solid #357EC7;z-index:1000000;padding:10px;font-size:13px;-moz-border-radius:7px;-webkit-border-radius:7px;border-radius:7px;-khtml-border-radius:7px;}
        .print_btn{border-bottom:1px dotted #D3D3D3;padding-bottom:2px;margin:6px 0px 6px 0px;float:left;width:620px;text-align:right;}
    </style>
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/jquery.nivo.slider.js"></script>
    <script type="text/javascript" src="js/jquery.validationEngine-en.js"></script>
    <script type="text/javascript" src="js/jquery.validationEngine.js"></script>
    <script src="js/jquery-ui-1.10.0.custom.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $('#slider').nivoSlider();
            $('.error-message span').click(function(){
                $(this).parent('div').remove();
            });
            $('.success-message span').click(function(){
                $(this).parent('div').remove();
            });
            $('.warning-message span').click(function(){
                $(this).parent('div').remove();
            });
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
                if(confirm('Are you sure?') == true){
                var id = $(this).attr('id');
                var title = $(this).siblings('img').attr('name');
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
                }
            });
            $(document).delegate('.remove_auctions','click',function(){
                if(confirm('Are you sure?') == true){
                    var id = $(this).attr('id');
                    var parentDiv = $('#main_div'+id);
                    var url = 'auction_delete.php?id=' + id;
                    $.getJSON(url, function(json){
                        if(json.stats == 'success'){
                            parentDiv.remove();
                        }
                        else{
                            alert('This auctions details can not be deleted!');
                        }
                    });
                }
            });
            $(document).delegate('.edit','click',function(){
                var id = $(this).attr('id');
                var url = 'auction_edit.php?id=' + id;
                $('#id_item_auction').val(id);
                $.getJSON(url, function(json){
                    if(json.stats != 'failed'){
                        $('#auction_url').val(json.url);
                        $('#auction_notes').val(json.notes);
                        $('#add_auction').val('Edit Auction Url');
                        $('#add_auction').removeAttr('name').attr('name', 'edit_auction');
                    }
                    else{
                        alert('This action can not be performed!');
                    }
                });
            });
            $('input[type=button][name=delete]').click(function(){
                if(confirm('Are you sure?') == true){
                    var id = $(this).attr('id');
                    var url = 'delete_onwership.php?id=' + id;
                    $.getJSON(url, function(json){
                        if(json.stats == 'success'){
                            window.location = 'dashboard.php';
                        }
                        else{
                            alert('This action can not be performed!!');
                            window.location = 'dashboard.php';
                        }
                    });
                }
            });
            function isUrl(s) {
                var regexp = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/
                return regexp.test(s);
            }
            $('#add_image').submit(function (){
                var filename = $('#upload_file').val();
                var url = $('#upload_url').val();
                if(filename == '' && url == ''){
                    alert('Both the fields can not be blank!');
                    return false;
                }
                else if(url != '' && filename == '' && isUrl(url) == false){
                    $('#upload_url').addClass('validate[custom[url]]');
                    return false;
                }
                else{
                    return true;
                }
            });
            var pedigree = <?php echo json_encode($row_pedigree);?>;
            var allPedigree = [];
            for(var i = 0; i < pedigree.length; i++){
                allPedigree[i] = pedigree[i].pcgs_pedigree;
            }
            $("#pcgs_pedigree").autocomplete({
                source: allPedigree
            });
        });
    </script>
</head>
<body>
<div id="popup_loader" class="popupContact"><p style='text-align:center;'><img src='images/loader.gif'/></div> 
<div id="popup_content" class="popupContact">&nbsp;</div> 
<div id="backgroundPopup"></div>
<div id="wrapper">  <!--wrapper start-->
	<div class="signin-regs">
<?php
if(isset($_SESSION['id_owner'])){
?>	
    	<ul>
            <li><a href="dashboard.php"><img src="images/user1.png" alt="" width="18px" height="18px" /><?php echo $ls_getObj->name; ?></a></li>
            <li>|</li>
            <li><a href="change_password.php">Change Password</a></li>
            <li>|</li>
            <li><a href="logout.php">Sign out</a></li>
        </ul>
<?php
} else {
?>		
        <ul>
            <li><a href="signup.php">Registration</a></li>
            <li>|</li>
            <li><a href="signup.php">Sign in</a></li>
        </ul>
<?php
}
?>		
    </div>
    <div class="clear-div">&nbsp;</div>
    <div class="top-nav">
    	<ul>
            <li id="hww"><a href="how_we_work.php">How it works</a></li>
            <li id="abt"><a href="about.php">About</a></li>
            <li id="cnt"><a href="contact.php">Contact</a></li>
        </ul>
    </div>
    <!--content start-->
	<!--content start-->
    <div class="main-content">
    	<div class="content">
        	<div class="logo"><a href="index.php"><img src="images/logo.png" alt="" /></a></div>