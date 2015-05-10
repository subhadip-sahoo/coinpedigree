<?php
    include 'inc/header.inc.php';
    include 'header.php';
    $msg = '';
    if(isset($_POST['submit'])){
        if($_POST['your_name'] == ''){
            $msg = 'Enter name!<br/>';
        }
        else if($_POST['your_zip_code'] == ''){
            $msg = 'Enter zip code!<br/>';
        }
        else if($_POST['your_email'] == ''){
            $msg = 'Enter email address!<br/>';
        }
        else if(filter_var($_POST['your_email'], FILTER_VALIDATE_EMAIL) == FALSE){
            $msg = 'Enter valid email address!<br/>';
        }
        else if($_POST['your_comment'] == ''){
            $msg = 'Enter question/comment!';
        }
        if($msg == ''){
            if( $_SESSION['security_code'] == $_POST['security_code'] && !empty($_SESSION['security_code'] ) ) {
                $to = get_setting(EMAIL_CONTACT, $conn);
                $message = 'Dear '.$_POST['your_name'].'<br/><br/>.';
                $message .= 'Thank you for contacting us. We wil respond to you shortly.<br/>';
                $message .= 'Best regards,<br/>';
                $message .= 'CoinPedigree Admin Team';
                $subject = 'Contact Information';
                $ls_from = $_POST['your_email'];
                if(send_email($to, $subject, $message, true, $ls_from) == 0){
                    $to = $_POST['your_email'];
                    $message = 'Name: '.$_POST['your_name'].'<br/>';
                    $message .= 'Zip code: '.$_POST['your_zip_code'].'<br/>';;
                    $message .= 'Email Address: '.$_POST['your_email'].'<br/>';;
                    $message .= 'Question/Comment: '.$_POST['your_comment'].'<br/>';;
                    $subject = 'Contact mail from '.$_POST['your_name'];
                    $ls_from = get_setting(EMAIL_CONTACT, $conn);
                    if(send_email($to, $subject, $message, true, $ls_from) == 0){
                        $msg = 'Thank you for contacting us.';
                        $ls_smgs = 1;
                        $_POST['your_name'] = '';
                        $_POST['your_zip_code'] = '';
                        $_POST['your_email'] = '';
                        $_POST['your_comment'] = '';
                    }
                    $msg = 'Mail sending failed';
                }
                $msg = 'There is an error occured regarding mail sending!';
            }
            else {
                $msg = "Sorry, you have provided an invalid security code";
            }
        }   
    }
?>
    <!--content start-->
    <script>
        $(function(){
            $('#cnt').addClass('active');
            $('#contact').validationEngine();
        });
    </script>
            <div class="inner-page">
            	<h1>Contact Us</h1>
                    
                <div class="contact-form">
                	<h2>If you have a question or a suggestion to improve this site by altering/adding features, let us know via the form below: </h2>
                    <form method="post" name="contact" id="contact" action="">
                        <?php
                            if(!empty($msg)){
                        ?>
                        <div <?php echo (isset($ls_smgs) && $ls_smgs == 1)?'class="success-message"':'class="error-message"';?>>
                            <span><?php echo $msg;?></span>
                        </div>
                        <?php } ?>
                        <p>
                            <label>Your Name</label>
                            <input type="text" name="your_name" value="<?php if(isset($_POST['your_name'])){echo $_POST['your_name'];}?>" class="contact-input validate[required]">
                        </p>
                        <p>
                            <label>Zip Code</label>
                            <input type="text" name="your_zip_code" value="<?php if(isset($_POST['your_zip_code'])){echo $_POST['your_zip_code'];}?>" class="contact-input validate[required]">
                        </p>
                        <p>
                            <label>Your Email Address</label>
                            <input type="text" name="your_email" value="<?php if(isset($_POST['your_email'])){echo $_POST['your_email'];}?>" class="contact-input validate[required,custom[email]]">
                        </p>
                        <p>
                            <label>Question/Comment</label>
                            <textarea class="contact-textarea validate[required]" value="<?php if(isset($_POST['your_comment'])){echo $_POST['your_comment'];}?>" name="your_comment"></textarea>
                        </p>
                        <p>
                            <label></label>
                            <img src="captcha/CaptchaSecurityImages.php?width=100&height=40&characters=5" />
                        </p>
                        <p>
                            <label></label>
                            <input id="security_code" placeholder="Enter the code above" name="security_code" type="text" class="contact-input validate[required]"/>
                        </p>
                        <p><input type="submit" value="Send" name="submit" class="search-btn cont-submit"></p>
                    </form>
                </div>
            </div>
            
        </div>
    </div>
     <!--content end-->
<?php include 'footer.php';?>
