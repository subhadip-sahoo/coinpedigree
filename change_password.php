<?php
 include 'inc/header.inc.php';
include 'is_logged_in.php';
$msg = "";
if(isset($_POST['submit'])){
    if($_POST['current_password'] == ''){
        $msg = 'Current password can not be blank!<br/>';
    }
    else if($_POST['new_password'] == ''){
        $msg = 'New password can not be blank!<br/>';
    }
    else if($_POST['confirm_password'] == ''){
        $msg = 'Confirm password can not be blank!<br/>';
    }
    else if($_POST['new_password'] <> $_POST['confirm_password']){
        $msg = 'Password does not match!<br/>';
    }

    if($msg == ''){
        $ls_check_pwd ="
                                    SELECT
                                        *
                                    FROM
                                        owners
                                    WHERE
                                        id_owner = :id_owner
                                    AND
                                        pwd = :current_password";
        $la_cp_options = array(':id_owner' => $_SESSION['id_owner'], ':current_password' => $_POST['current_password']);
        $ls_queryObj = $conn->prepare($ls_check_pwd);
        $ls_queryObj->execute($la_cp_options);
        if($ls_queryObj->rowCount() == 1){
            $ls_query_update ="
                                    UPDATE
                                        owners
                                    SET
                                        pwd = :current_password
                                    WHERE
                                        id_owner = :id_owner";
            $la_cp_options = array(':id_owner' => $_SESSION['id_owner'], ':current_password' => $_POST['new_password']);
            $ls_updateDB = $conn->prepare($ls_query_update);
            $ls_updateDB->execute($la_cp_options);
            if($ls_updateDB->errorCode() == 0000){
                $msg = 'You have successfully changed your password.';
                $_POST['current_password'] = '';
                $ls_smgs = 1;
            }
            else{
                $ls_error = $ls_updateDB->errorInfo();
                $msg = $ls_error[0]. ': '. $ls_error[2];
            }
        }
        else{
            $msg = 'You have provided wrong current password!';
        }        
    }       
}
include_once 'header.php';
?>
<script type="text/javascript">
    $(function(){
        $('#change_pwd').validationEngine();
    });
</script>
                <div class="inner-page">
            	<h1>Change Password</h1>
                    
                <div class="contact-form">
                	<h2></h2>
                    <form name="change_pwd" id="change_pwd" action="" method="post">
                        <?php
                            if(!empty($msg)){
                        ?>
                        <div <?php echo (isset($ls_smgs) && $ls_smgs == 1)?'class="success-message"':'class="error-message"';?>>
                            <span><?php echo $msg;?></span>
                        </div>
                        <?php } ?>
                        <p>
                                <label>Current Password:</label>
                                <input type="password" value="" class="contact-input validate[required]" name="current_password" id="current_password" value="<?php if(isset($_POST['current_password'])){ echo $_POST['current_password'];}?>"/>
                        </p>
                        <p>
                            <label>New Password:</label>
                            <input type="password" name="new_password" id="new_password" class="contact-input validate[required]">
                        </p>
                        <p>
                             <label>Confirm Password:</label>
                             <input type="password" name="confirm_password" id="confirm_password" class="contact-input validate[required, equals[new_password]]"></p>
                        <p>
                        <input type="submit" value="Change Password" name="submit" id="submit" class="search-btn cont-submit" /></p>
                    </form>
                </div>
            </div>
            
        </div>
    </div>
     <!--content end-->
<?php include 'footer.php';?>
