<?php
if($_REQUEST['usertype'] == 'valid'){
        $result = $conn->prepare("SELECT * FROM owners 
                                WHERE 
                                    email = :email 
                                AND 
                                    forget_pwd_code = :forget_pwd_code
                                ");
        $result->execute(array(':email' => $_REQUEST['email'], ':forget_pwd_code' => $_REQUEST['reset_key']));
        if($result->rowCount() == 1){
            if(isset($_POST['submit'])){
                if($_POST['new_password'] == ''){
                    $msg .= 'New password can not be blank!<br/>';
                }
                if($_POST['confirm_password'] == ''){
                    $msg .= 'Confirm password can not be blank!<br/>';
                }
                if($_POST['new_password'] <> $_POST['confirm_password']){
                    $msg .= 'Password does not match!';
                }
                if($msg == ''){
                    $ls_query_update ="
                                                UPDATE
                                                    owners
                                                SET
                                                    pwd = :new_password
                                                WHERE
                                                    email = :email
                                                AND
                                                    forget_pwd_code = :reset_key";
                    $ls_updateDB = $conn->prepare($ls_query_update);
                    $ls_updateDB->execute(array(':new_password' => $_POST['new_password'], ':email' => $_REQUEST['email'], ':reset_key' => $_REQUEST['reset_key']));
                    header('location:index.php?s=75fhtrEW');
                    exit();
                }
            }
              $display_form = 1;  
        }
        else{
            $ls_errorReset =  'Error occured!';
        }
    }
    else{
        $ls_errorReset = 'Invalid Url.';
    }
include_once 'header.php';
?>
<script>
    $(function(){
        $('#reset_pwd').validationEngine();
    });
</script>
                <div class="inner-page">
            	<h1>Reset Password</h1>
                <?php if(isset($display_form) && $display_form == 1){?>  
                <div class="contact-form">
                	<h2>Reset your password:</h2>
                        <form name="reset_pwd" id="reset_pwd" action="" method="post">
                            <?php
                                if(!empty($msg)){
                            ?>
                            <div <?php echo (isset($ls_smgs) && $ls_smgs == 1)?'class="success-message"':'class="error-message"';?>>
                                <span><?php echo $msg;?></span>
                            </div>
                            <?php } ?>
                             <p>
                                 <label>New Password:</label>
                                 <input type="password" class="contact-input validate[required]" name="new_password" id="new_password">
                             </p>
                             <p>
                                 <label>Confirm Password:</label>
                                 <input type="password" class="contact-input validate[required, equals[new_password]]" name="confirm_password" id="confirm_password">
                             </p>
                             <p><input type="submit" value="Set Password" class="search-btn cont-submit" name="submit"></p>
                         </form>
                        <?php } else { ?>
                            <div class="error-message">
                                <span><?php echo $ls_errorReset;?></span>
                            </div>
                        <?php } ?>
                    </div>
                </div>
        </div>
    </div>
     <!--content end-->
<?php include 'footer.php';?>
