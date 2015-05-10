<?php
include 'inc/header.inc.php';
$msg = "";
if(isset($_REQUEST['mode']) && $_REQUEST['mode'] == 'reset_password'){
    include 'reset_password.php';
    exit();
}
if(isset($_POST['submit'])){        
    if($_POST['email'] == ''){
        $msg = 'Enter email address!<br/>';
    }
    else if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) == FALSE){
        $msg = 'Enter valid email address!<br/>';
    }
    if($msg == ''){
        $ls_query_owners =  "SELECT * FROM owners WHERE email = :email";
        $ls_result = $conn->prepare($ls_query_owners);
        $ls_result->execute(array(':email' => $_POST['email']));
        $ls_obj_id = $ls_result->fetch(PDO::FETCH_OBJ);
        if($ls_result->rowCount() == 1){
            do{
                $password_reset_code = generatePassword(20,2);
            }
            while(check_for_duplicates("owners","forget_pwd_code",$password_reset_code,"id_owner","new")==true);
            $mail_subject = "Reset your password";
            $mail_contents = 'Dear user<br/>';
            $mail_contents .= "Please click on the link below to reset your password <br/>";
            $link = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'?mode=reset_password&usertype=valid&email='.$_POST['email'].'&reset_key='.$password_reset_code;
            $mail_contents .= "<a href='$link'>Click here to reset your password</a>";
            $ls_from = get_setting(EMAIL_CONTACT,$conn);
            if(send_email($_POST['email'], $mail_subject, $mail_contents, true, $ls_from) == 0){
                $ls_query_update = "
                                            UPDATE
                                                owners
                                            SET
                                                forget_pwd_code = :forget_pwd_code
                                            WHERE
                                                id_owner = :id_owner";
                $ls_updateDB = $conn->prepare($ls_query_update);
                $ls_updateDB->execute(array(':forget_pwd_code' => $password_reset_code, ':id_owner' => $ls_obj_id->id_owner));
                $msg = 'Your reset password link has been successfully sent to your mail.';
                $ls_smgs = 1;
            }
            else{
                $msg = 'There is an error with mail sending. Please try again later!';
            }
        }
        else{
            $msg = 'Invalid email address!';
        }            
    }
}
include_once 'header.php';
?>
<script>
    $(function(){
        $('#forgot_pwd').validationEngine();
    });
</script>
                <div class="inner-page">
            	<h1>Forgot Password</h1>
                    
                <div class="contact-form">
                	<h2>Enter your email address:</h2>
                        <form name="forgot_pwd" id="forgot_pwd" action="" method="post">
                            <?php
                                if(!empty($msg)){
                            ?>
                            <div <?php echo (isset($ls_smgs) && $ls_smgs == 1)?'class="success-message"':'class="error-message"';?>>
                                <span><?php echo $msg;?></span>
                            </div>
                            <?php } ?>
                            <p>
                                <label>Email address:</label>
                                <input type="text" class="contact-input validate[required,custom[email]]" name="email" id="email">
                            </p>
                            <p><input type="submit" value="Send" class="search-btn cont-submit" name="submit"></p>
                        </form>
                    </div>
            </div>
            
        </div>
    </div>
     <!--content end-->
<?php include 'footer.php';?>
