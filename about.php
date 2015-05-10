<?php
include_once "inc/header.inc.php";
include_once "header.php";
?>
<script>
    $(function(){
        $('#abt').addClass('active');
    });
</script>
            <div class="inner-page">
            	<h1>About Us</h1>
                <div class="how-it-works"><?php echo get_setting(ABOUT_US,$conn); ?></div>
            </div>
            
        </div>
    </div>
     <!--content end-->
<?php
include_once "footer.php";
?>     
