<?php
include_once "inc/header.inc.php";
include_once "header.php";
?>
<script>
    $(function(){
        $('#hww').addClass('active');
    });
</script>
            <div class="inner-page">
            	<h1>How It Work</h1>
                <div class="how-it-works"><?php echo get_setting(HOW_WE_WORKS,$conn); ?></div>
            </div>
            
        </div>
    </div>
     <!--content end-->
<?php
include_once "footer.php";
?>
