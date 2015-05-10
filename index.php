<?php
include 'inc/header.inc.php';
include 'custom_functions.php';
$page = 'dashboard.php';
    $msg = "";
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
       //die(basename(__FILE__));
        if($_POST['email'] == ''){
            $msg .= 'Enter email address!<br/>';
        }
        else if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) == FALSE){
            $msg .= 'Enter valid email address!<br/>';
        }
        else if($_POST['pwd'] == ''){
            $msg .= 'Enter password!';
        }
        if($msg == ''){
            $ls_query_owners ="
                                            SELECT 
                                                * 
                                            FROM 
                                                owners 
                                            WHERE 
                                                email = :email 
                                            AND 
                                                pwd = :pwd";
            $la_login_options = array(':email' => $_POST['email'], ':pwd' => $_POST['pwd']);
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
                    header("location:$page");
                    exit(); 
                }
                else if($obj->status == 'S'){
                    $msg = 'Your account has been suspened at '.$obj->suspend_at.'!'; 
                }
                else{
                    $msg = 'You have not varified your account!';
                }
            }
            else{
                $msg = 'Email or password does not match!';
            }
        }
    }
include 'header.php';	
?>
<script>
    $(function(){
        $('#barcode_id_search').validationEngine();
        $('#login_index').validationEngine();
    });
</script>
            <div class="banner">  <!--banner start-->
                <div class="slider-wrapper theme-default">
                        <div id="slider" class="nivoSlider">
                                <img src="images/banner.jpg"  alt="" title="#htmlcaption1"/>
                                <img src="images/banner-2.jpg"  alt="" title="#htmlcaption2" />
                                <img src="images/banner.jpg" alt="" data-transition="slideInLeft" title="#htmlcaption3" />
                                <img src="images/banner-2.jpg" alt="" title="#htmlcaption4" />
                        </div>
                        <div id="htmlcaption1" class="nivo-html-caption"></div>
                        <div id="htmlcaption2" class="nivo-html-caption"></div>
                        <div id="htmlcaption3" class="nivo-html-caption"></div>
                        <div id="htmlcaption4" class="nivo-html-caption"></div>
                </div>
            </div>  <!--banner end-->
            
            <div class="cont-details">
            	<div class="border-btn">&nbsp;</div>
            <h1>How it works</h1>
            <p>Lorem Ipsum is <a href="#">simply dummy text</a> of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
            <div class="border-btn">&nbsp;</div>

            <div class="search-div">
                <h2>Enter Coins ID / Year</h2>
                <p>Change The Text Here</p>
                    <form name="barcode_id" id="barcode_id_search" action="search.php" method="post">
                        <input type="text" value="" name="id" id="id" placeholder="" class="search-textbox validate[required, minSize[7], maxSize[8], custom[onlyNumberSp]]" />
                        <input type="submit" value="Search" name="search" class="search-btn" />
                    </form>
            </div>
            <div class="search-div">
                <h2>Register new user / Sign in</h2>
                <p>Change The Text Here</p>
                <?php if(!empty($msg)){?>
                <div <?php echo (isset($ls_smgs) && $ls_smgs == 1)?'class="success-message"':'class="error-message"';?>>
                    <span><?php echo $msg;?></span>
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
                <form name="login" id="login_index" action="" method="post">
                    <input type="text" value="" placeholder="Email Address" name="email" id="email" class="search-textbox validate[required,custom[email]]" />
                    <input type="password" value="" placeholder="Password" name="pwd" id="pwd" class="search-textbox validate[required]" />
                    <input type="submit" value="Sign In" name="login" class="search-btn" />
                </form>
                <div class="forgot-password"><a href="forgotPwd.php"><img src="images/forgotpasswordimg.png" alt="" />Forgot Password?</a></div>
                <div class="reg-account">Registration on your New Account</div>
                <a href="signup.php" class="search-btn">Register Now</a>
            </div>
                
            </div>
        </div>
    </div>
     <!--content end-->
<?php
include_once 'footer.php';
?>     
