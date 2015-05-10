<?php    
    if($_REQUEST['usertype'] == 'new'){
        $result = $conn->prepare("SELECT * FROM owners 
                                WHERE 
                                    email = '".$_REQUEST['email']."' 
                                AND 
                                    verification_code = '".$_REQUEST['varification_key']."'
                                ");
        $result->execute();
        if($result->rowCount() == 1){
            $obj = $result->fetch(PDO::FETCH_OBJ);
            if(date('Y-m-d H:i:s') < $obj->verification_code_valid_till){
                $ls_queryUpdate = $conn->prepare("UPDATE 
                                                        owners 
                                                    SET 
                                                        status = 'A' 
                                                    WHERE 
                                                        email = '".$_REQUEST['email']."' 
                                                    AND 
                                                        verification_code = '".$_REQUEST['varification_key']."'
                                                    "); 
                $ls_queryUpdate->execute();
                if($ls_queryUpdate->errorCode() == 0000){
                    header('location:index.php?u=4w3er5Ar');
                    exit();
                 }
                 else{
                    $la_errors = $ls_queryUpdate->errorInfo();
                    $msg .= $la_errors[0] . ': ' . $la_errors[2];
                 }
            }
            else{
                echo 'Your validity has expired. <a href="signup.php">Signup</a> again.!';
            }
        }
        else{
            echo 'Error occured! Click here to <a href="index.php">login</a> page';
        }
    }
    else{
        echo 'Invalid Url. Please try again! Click here to <a href="index.php">login</a> page';
    }
?>

