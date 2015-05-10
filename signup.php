<?php
include 'inc/header.inc.php';
$page = 'dashboard.php';
    $login_msg = "";
    $msg = "";
    if(isset($_REQUEST['mode']) && $_REQUEST['mode'] == 'account_activate'){
        include 'account_activate.php';
        exit();
    }
    if(isset($_POST['login'])){
        if(isset($_REQUEST['s'])){
            unset($_REQUEST['s']);
        }
        if(isset($_REQUEST['u'])){
            unset($_REQUEST['u']);
        }
        if(isset($_REQUEST['l'])){
            unset($_REQUEST['l']);
        }
        if(isset($_REQUEST['war'])){
            unset($_REQUEST['war']);
        }
        if($_POST['login_email'] == ''){
            $login_msg = 'Enter email address!<br/>';
        }
        else if(filter_var($_POST['login_email'], FILTER_VALIDATE_EMAIL) == FALSE){
            $login_msg = 'Enter valid email address!<br/>';
        }
        else if($_POST['pwd'] == ''){
            $login_msg = 'Enter password!';
        }
        if($login_msg == ''){
            $ls_query_owners ="
                                            SELECT 
                                                * 
                                            FROM 
                                                owners 
                                            WHERE 
                                                email = :email 
                                            AND 
                                                pwd = :pwd";
            $la_login_options = array(':email' => $_POST['login_email'], ':pwd' => $_POST['pwd']);
            $ls_result = $conn->prepare($ls_query_owners);
            $ls_result->execute($la_login_options);
            if($ls_result->rowCount() == 1){
                $obj = $ls_result->fetch(PDO::FETCH_OBJ);
                if($obj->status == 'A'){
                    $_SESSION['id_owner'] = $obj->id_owner;
                    $_SESSION['last_login_from_ip'] = $_SERVER['REMOTE_ADDR'];
                    $_SESSION['last_login_at'] = date('Y-m-d H:i:s');
                    if(isset($_SESSION['page'])){
                        $page = $_SESSION['page'].'?cert_id='.$_SESSION['cert_id'];
                    }
                    $_POST['login_email'] = '';
                    header("location:$page");
                    exit(); 
                }
                else if($obj->status == 'S'){
                    $login_msg = 'Your account has been suspened at '.$obj->suspend_at.'!'; 
                }
                else{
                    $login_msg = 'You have not varified your account!';
                }
            }
            else{
                $login_msg = 'Email or password does not match!';
            }
        }
    } 
    if(isset($_POST['submit'])){
        if($_POST['name'] == ''){
            $msg = 'Enter name!<br/>';
        }
        else if($_POST['email'] == ''){
            $msg = 'Enter email address!<br/>';
        }
        else if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) == FALSE){
            $msg = 'Enter valid email address!<br/>';
        }
        else if($_POST['pwd'] == ''){
            $msg = 'Enter password!<br/>';
        }
        else if($_POST['r_pwd'] == ''){
            $msg = 'Enter retype password!<br/>';
        }
        else if($_POST['r_pwd'] <> $_POST['pwd']){
            $msg = 'Password does not match!';
        }
        if($msg == ''){
            if(check_for_duplicates("owners","email",$_POST['email'],"id_owner","new")==true)
            {
                $msg = 'Provided email address is already exists!<br/>';
            }
            else{
                do{
                    $verification_code = generatePassword(20,2);
                }
                while(check_for_duplicates("owners","verification_code",$verification_code,"id_owner","new")==true);

                $mail_subject = "Activate your account";
                $mail_contents = 'Hi '.$_POST['name'].'<br/><br/>';
                $mail_contents .= 'Your account credentials are as follows :<br/>';
                $mail_contents .= 'Email : <strong>'.$_POST['email'].'</strong><br/>';
                $mail_contents .= 'Password : <strong>'.$_POST['pwd'].'</strong><br/><br/>';
                $mail_contents .= "Please click on the link below to activate your account <br/>";
                $link = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'?mode=account_activate&usertype=new&email='.$_POST['email'].'&varification_key='.$verification_code;            
                $mail_contents .= "<a href='$link'>Click here to activate your account</a>";

                $ls_from = get_setting(EMAIL_CONTACT,$conn);
                $ls_varification_valid_till = date('Y-m-d H:i:s', mktime(date("H"), date("i"), date("s"), date("m"), date("d") + 7, date("Y")));
                if(send_email($_POST['email'], $mail_subject, $mail_contents, true, $ls_from) == 0){
                    $ls_query_owners =  "INSERT INTO 
                                            owners(name, email, pwd, verification_code, verification_code_valid_till, status)
                                        VALUES 
                                            (:name, :email, :pwd, :verification_code, :verification_code_valid_till,'N')";
                    $la_signup_options = array(":name"=>$_POST['name'],":email"=>$_POST['email'],":pwd"=>$_POST['pwd'],":verification_code"=>$verification_code,":verification_code_valid_till"=>$ls_varification_valid_till);
                    $result_query_owners = $conn->prepare($ls_query_owners);
                    $result_query_owners->execute($la_signup_options);
                    if($result_query_owners->errorCode() == 0000){
                        $msg = 'Signup successfull. Please check your mail.';
                        $ls_smgs = 1;
                        $_POST['name'] = '';
                        $_POST['email'] = '';
                    }
                    else{
                        $la_errors = $result_query_owners->errorInfo();
                        $msg = $la_errors[0] . ': ' . $la_errors[2];
                    }
                }
                else{
                    $msg = 'There is an error regarding mail sending. Please try again later!';
                }
            }
        }
    }
include 'header.php';    
?>
<script type="text/javascript">
    $(function(){
        $('#signup').validationEngine();
        $('#login').validationEngine();
    });
</script>
            <div class="inner-page">
            	<h1>Registration</h1>
                    
                <div class="registation">
                	<h2><img src="images/registation.png" alt="" />Sign Up</h2>
                    <h3>It is simple.. Just fill in the fields below, and press the Register Button. </h3>
                    <form name="signup" id="signup" action="" method="post">
                        <?php
                        if(!empty($msg)){
                        ?>
                        <div <?php echo (isset($ls_smgs) && $ls_smgs == 1)?'class="success-message"':'class="error-message"';?>>
                            <span><?php echo $msg;?></span>
                        </div>
                        <?php } ?>
                        <p>
                            <label>Name:</label>
                            <input type="text" class="contact-input validate[required]" name="name" id="name" value="<?php if(isset($_POST['name'])) {echo $_POST['name'];}?>">
                        </p>
                        <p>
                            <label>E-mail Address:</label>
                            <input type="text" class="contact-input validate[required,custom[email]]" name="email" id="email" value="<?php if(isset($_POST['email'])) {echo $_POST['email'];}?>">
                        </p>
                        <p>
                            <label>Password:</label>
                            <input type="password" class="contact-input validate[required]" name="pwd" id="pwd" value="">
                        </p>
                        <p>
                            <label>Re-enter Password:</label>
                            <input type="password" class="contact-input validate[required, equals[pwd]]" name="r_pwd" id="r_pwd" value="">
                        </p>
                        <p><input type="submit" value="Register" class="search-btn" name="submit"></p>
                    </form>
                </div>
                <div class="sign-in">
                	<h2><img src="images/sign-icon.png" alt="" />Sign In</h2>
                    <h3></h3>
                    <form name="login" id="login" action="" method="post">
                        <?php if(!empty($login_msg)){?>
                        <div <?php echo (isset($ls_login_msg) && $ls_login_msg == 1)?'class="success-message"':'class="error-message"';?>>
                            <span><?php echo $login_msg;?></span>
                        </div>
                        <?php } ?>  

                        <?php if(isset($_REQUEST['s']) && $_REQUEST['s'] == '75fhtrEW'){?>
                        <div class="success-message">
                            <span>Your password has been successfully changed.</span>
                        </div>
                        <?php } ?>

                        <?php if(isset($_REQUEST['u']) && $_REQUEST['u'] == '4w3er5Ar'){?>
                        <div class="success-message">
                            <span>Your account is activated successfully.</span>
                        </div>
                        <?php } ?>

                        <?php if(isset($_REQUEST['l']) && $_REQUEST['l'] == 'hdfhg45QRE'){?>
                        <div class="success-message">
                            <span>You have successfully logged out.</span>
                        </div>
                        <?php } ?>
                         <?php if(isset($_REQUEST['war']) && $_REQUEST['war'] == 1){?>
                        <div class="warning-message">
                            <span>You have to sign in to continue.</span>
                        </div>
                        <?php } ?>
                        <p>
                            <label>E-mail Address:</label>
                            <input type="text" name="login_email" id="login_email" value="<?php if(isset($_POST['login_email'])) {echo $_POST['login_email'];}?>" class="contact-input validate[required,custom[email]]">
                        </p>
                        <p>
                            <label>Password:</label>
                            <input type="password" name="pwd" id="pwd" class="contact-input validate[required]">
                        </p>
                        <p><input type="submit" value="Sign In" name="login" class="search-btn"></p>
                    </form>
                    <div class="forgot-password"><a href="forgotPwd.php"><img src="images/forgotpasswordimg.png" alt="" />Forgot Password?</a></div>
                </div>
            </div>
            
        </div>
    </div>
     <!--content end-->
<?php
include 'footer.php';
?>
