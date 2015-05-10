<?php
/*
QSS PHP LIB
(C) Quintessential Software Solutions Pvt Ltd 2003-2012. All rights reserved.
Version: 3.14
Last updated: September 16, 2013 by Vipin
*/

//get lat lng from zipcode
function getLatLng($zip){
	$url = "http://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($zip)."&sensor=false";
	$result_string = file_get_contents($url);
	$result = json_decode($result_string, true);

	if($result['status'] == "OK"){
		$result1[]=$result['results'][0];
		$result2[]=$result1[0]['geometry'];
		$result3[]=$result2[0]['location'];
		return $result3[0];
	} else {
		return "error";
	}
}

function clean($string) {
	$string = str_replace('', '-', $string); // Replaces all spaces with hyphens.
	return preg_replace('/[^A-Za-z0-9\-]/', '_', $string); // Removes special chars.
}

function tokenTruncate($string, $your_desired_width) {
	$parts = preg_split('/([\s\n\r]+)/', $string, null, PREG_SPLIT_DELIM_CAPTURE);
	$parts_count = count($parts);

	$length = 0;
	$last_part = 0;
	for (; $last_part < $parts_count; ++$last_part) {
		$length += strlen($parts[$last_part]);
		if ($length > $your_desired_width) { break; }
	}

	return implode(array_slice($parts, 0, $last_part));
}

function send_all_defined_variables($email, $subject) {
	mail($email,$subject,print_r(get_defined_vars(),true));
}

function write_to_error_log($as_msg) {
	$fp = fopen("C:/projects/catalog/HTML/logs/" . "error_log.txt","a");
	fputs($fp, "\r\n" . date('r') . "," . $_SERVER["REMOTE_ADDR"] . "," . $as_msg);
	fclose($fp);
}

function folder_remove_trailing_slash($as_folder) {
	return ((strrpos($as_folder, '/') + 1) == strlen($as_folder)) ? substr($as_folder, 0, - 1) : $as_folder; 
}

function autocomplete_list($as_sql, $as_id, $as_label, $as_value, $al_list_count = 12) {
	global $conn;
	$la_list = array();
	$result = mysql_query($as_sql,$conn);
	$ctr=0;
	while ($row = mysql_fetch_assoc($result)) {
		array_push($la_list, array("id"=>$row[$as_id], "label"=>$row[$as_label], "value" => strip_tags($row[$as_value])));
		$ctr++;
		if ($ctr == $al_list_count) {
			break;
		}
	}
	return array_to_json($la_list);
}

function array_to_json( $array ){

    if( !is_array( $array ) ){
        return false;
    }

    $associative = count( array_diff( array_keys($array), array_keys( array_keys( $array )) ));
    if( $associative ){

        $construct = array();
        foreach( $array as $key => $value ){

            // We first copy each key/value pair into a staging array,
            // formatting each key and value properly as we go.

            // Format the key:
            if( is_numeric($key) ){
                $key = "key_$key";
            }
            $key = "\"".addslashes($key)."\"";

            // Format the value:
            if( is_array( $value )){
                $value = array_to_json( $value );
            } else if( !is_numeric( $value ) || is_string( $value ) ){
                $value = "\"".addslashes($value)."\"";
            }

            // Add to staging array:
            $construct[] = "$key: $value";
        }

        // Then we collapse the staging array into the JSON form:
        $result = "{ " . implode( ", ", $construct ) . " }";

    } else { // If the array is a vector (not associative):

        $construct = array();
        foreach( $array as $value ){

            // Format the value:
            if( is_array( $value )){
                $value = array_to_json( $value );
            } else if( !is_numeric( $value ) || is_string( $value ) ){
                $value = "'".addslashes($value)."'";
            }

            // Add to staging array:
            $construct[] = $value;
        }

        // Then we collapse the staging array into the JSON form:
        $result = "[ " . implode( ", ", $construct ) . " ]";
    }

    return $result;
}

function _mime_content_type($filename)
{
	$finfo = finfo_open(FILEINFO_MIME);
	$mimetype = finfo_file($finfo, $filename);
    finfo_close($finfo);
    return $mimetype;
}

function getUrlMimeType($url) {
    $buffer = file_get_contents($url);
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    return $finfo->buffer($buffer);
}

function get_time_difference( $start, $end )
{
    $uts['start']      =    $start;
    $uts['end']        =    $end ;
    if( $uts['end'] >= $uts['start'] )
    {
        $diff    =    $uts['end'] - $uts['start'];
        if( $days=intval((floor($diff/86400))) )
            $diff = $diff % 86400;
        if( $hours=intval((floor($diff/3600))) )
            $diff = $diff % 3600;
        if( $minutes=intval((floor($diff/60))) )
            $diff = $diff % 60;
        $diff    =    intval( $diff );            
        return( array('days'=>$days, 'hours'=>$hours, 'minutes'=>$minutes, 'seconds'=>$diff) );
    }
    else
    {
        trigger_error( "Ending date/time is earlier than the start date/time", E_USER_WARNING );
    }
    return( false );
}

function zip_folder($as_zipfilename, $as_folder) {
	// images array
	$files = array();
	if (is_dir($as_folder)) {
		$objects = scandir($as_folder);
		foreach ($objects as $object) {
			if ($object != "." && $object != "..") {
				if (filetype($as_folder."/".$object) == "file") {
					$files[] = $as_folder."/".$object;
				}
			}
		}
		return create_zip($files,$as_zipfilename,true);
	}
	return false;
}

function rrmdir($dir) {
	if (is_dir($dir)) {
		$objects = scandir($dir);
		foreach ($objects as $object) {
			if ($object != "." && $object != "..") {
				if (filetype($dir."/".$object) == "dir") rrmdir($dir."/".$object); else unlink($dir."/".$object);
			}
		}
		reset($objects);
		rmdir($dir);
	}
}

function resize_image($as_src_path, $as_target_path, $al_max_width, $al_max_height) {
	// create thumbnail
	list($img_width, $img_height) = @getimagesize($as_src_path);
	if (!$img_width || !$img_height) {
		return false;
	}
	$scale = min(
		$al_max_width / $img_width,
		$al_max_height / $img_height
		);
	if ($scale > 1) {
		$scale = 1;
	}
	$new_width = $img_width * $scale;
	$new_height = $img_height * $scale;
	$new_img = @imagecreatetruecolor($new_width, $new_height);
	switch (strtolower(substr(strrchr($as_src_path, '.'), 1))) {
		case 'jpg':
		case 'jpeg':
			$src_img = @imagecreatefromjpeg($as_src_path);
			$write_image = 'imagejpeg';
			break;
		case 'gif':
			$src_img = @imagecreatefromgif($as_src_path);
			$write_image = 'imagegif';
			break;
		case 'png':
			$src_img = @imagecreatefrompng($as_src_path);
			$write_image = 'imagepng';
			break;
		default:
			$src_img = $image_method = null;
	}
	$success = $src_img && @imagecopyresampled(
		$new_img,
		$src_img,
		0, 0, 0, 0,
		$new_width,
		$new_height,
		$img_width,
		$img_height
		) && $write_image($new_img, $as_target_path);
	// Free up memory (imagedestroy does not delete files):
	@imagedestroy($src_img);
	@imagedestroy($new_img);
	return true;
}

function get_program_page_setting($al_id_inst, $as_page_name, $as_code) {
	global $conn;
	$sql = "select value
				from program_page_settings join program_pages on program_page_settings.id_program_page = program_pages.id_program_page
					join sections on program_pages.id_section = sections.id_section
				where sections.id_inst = $al_id_inst
					and program_pages.page_name = '$as_page_name'
					and program_page_settings.code = '$as_code'";
	$ls_value = executeScaler($sql,$conn);
	return $ls_value;
}

function isPostBack() {
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		return true;
	}
	return false;
}

function get_program_page_contentarea($al_id_inst, $as_page_name, $as_contentarea) {
	global $conn;
	$sql = "select content_text 
				from program_page_contentareas join program_pages on program_page_contentareas.id_program_page = program_pages.id_program_page
					join sections on program_pages.id_section = sections.id_section
				where sections.id_inst = $al_id_inst
					and program_pages.page_name = '$as_page_name'
					and program_page_contentareas.pp_contentarea = '$as_contentarea'
					and pp_contentarea_type='T'";
	$ls_content_text = executeScaler($sql,$conn);
	return $ls_content_text;
}

function send_email($as_email_address, $as_email_subject, $as_email_text, $ab_html_email = true, $as_email_address_from = "", $as_from_name = "", $files = array()) {
	global $conn;
	// get sender if not passed
	if ($as_email_address_from == "") {
		$ls_email_address_from = get_setting(COMMUNICATIONS_FROM_EMAIL_ADDRESS,$conn);
	} else {
		$ls_email_address_from = $as_email_address_from;
	}
	if ($as_from_name == "") {
		$ls_from_name = get_setting(COMMUNICATIONS_FROM_NAME,$conn);
	} else {
		$ls_from_name = $as_from_name;
	}

    // boundary 
    $semi_rand = md5(time()); 
    $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x"; 

	$mail_headers = "From: $ls_from_name <$ls_email_address_from>\r\n";
    // headers for attachment 
    $mail_headers .= "MIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\""; 

	if ($ab_html_email == true) {
		$ls_content_type = "text/html";
	} else {
		$ls_content_type = "text/plain";
	}
    // multipart boundary 
    $ls_message = "--{$mime_boundary}\n" . "Content-Type: " . $ls_content_type . "; charset=\"iso-8859-1\"\n" .
    "Content-Transfer-Encoding: 7bit\n\n" . $as_email_text . "\n\n"; 

    // preparing attachments
    for($i=0;$i<count($files);$i++){
        if(is_file($files[$i])){
            $ls_message .= "--{$mime_boundary}\n";
            $fp =    @fopen($files[$i],"rb");
			$data =    @fread($fp,filesize($files[$i]));
			@fclose($fp);
            $data = chunk_split(base64_encode($data));
            $ls_message .= "Content-Type: application/octet-stream; name=\"".basename($files[$i])."\"\n" . 
            "Content-Description: ".basename($files[$i])."\n" .
            "Content-Disposition: attachment;\n" . " filename=\"".basename($files[$i])."\"; size=".filesize($files[$i]).";\n" . 
            "Content-Transfer-Encoding: base64\n\n" . $data . "\n\n";
		}
	}
	
    $ls_message .= "--{$mime_boundary}--";

	// send email
	$ok = @mail($as_email_address, $as_email_subject, $ls_message, $mail_headers);
	if($ok){ return $i; } else { return 0; }
}

function send_sms($as_mobno, $as_msg) {
	global $conn;
	FwdSMS2Gateway(get_setting(SMS_GATEWAY_USER,$conn), get_setting(SMS_GATEWAY_PASSWORD,$conn),"91$as_mobno",$as_msg, get_setting(SMS_GATEWAY_SENDER_GSM,$conn), get_setting(SMS_GATEWAY_SENDER_CDMA,$conn));
}

/* creates a compressed zip file */
function create_zip($files = array(),$destination = '',$overwrite = false) {
	//if the zip file already exists and overwrite is false, return false
	if(file_exists($destination) && !$overwrite) { return false; }
	//vars
	$valid_files = array();
	//if files were passed in...
	if(is_array($files)) {
		//cycle through each file
		foreach($files as $file) {
			//make sure the file exists
			if(file_exists($file)) {
				$valid_files[] = $file;
			}
		}
	}
	//if we have good files...
	if(count($valid_files)) {
		//create the archive
		$zip = new ZipArchive();
		if($zip->open($destination,$overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
			return false;
		}
		//add the files
		foreach($valid_files as $file) {
			$zip->addFile($file,$file);
		}
		//debug
		//echo 'The zip archive contains ',$zip->numFiles,' files with a status of ',$zip->status;
		
		//close the zip -- done!
		$zip->close();
		
		//check to make sure the file exists
		return file_exists($destination);
	}
	else
	{
		return false;
	}
}

function convert_db_to_innodb() {
	global $conn;
	$sql = "show table status";
	$result = mysql_query($sql,$conn);
	$ctr= 0;
	while ($row = mysql_fetch_assoc($result)) {
		if ($row['Engine'] == 'MyISAM') {
			mysql_query("ALTER TABLE ".$row['Name']." ENGINE = InnoDB",$conn);
			echo "Converted " . $row['Name'] . "<br>";
			$ctr++;
		}
	}
	echo "ALL Done; Converted $ctr tables<br>";
}
function export_to_csv($as_sql, &$conn, $as_filename, $ab_output_header = true) {
	$csv = NULL;
	$r = mysql_query($as_sql, $conn);
	while ($row = mysql_fetch_assoc($r)) {
		// if header required
		if ($ab_output_header == true) {
			// generate header
			foreach($row as $key => $value) {
				$csv .= $key.',';
			}
			// take out the last ,
			$csv = substr($csv, 0, -1)."\n";
			// mark as header already generated
			$ab_output_header = false;
		}
		$csv .= '"'.join('","', str_replace('"', '""', $row))."\"\n";
	}
	header("Content-type: application/vnd.ms-excel");
	header("Content-disposition: csv; filename=$as_filename; size=".strlen($csv));
	echo $csv;
}

function http_request( 
	$verb = 'GET',             /* HTTP Request Method (GET and POST supported) */ 
	$ip,                       /* Target IP/Hostname */ 
	$port = 80,                /* Target TCP port */ 
	$uri = '/',                /* Target URI */ 
	$getdata = array(),        /* HTTP GET Data ie. array('var1' => 'val1', 'var2' => 'val2') */ 
	$postdata = array(),       /* HTTP POST Data ie. array('var1' => 'val1', 'var2' => 'val2') */ 
	$cookie = array(),         /* HTTP Cookie Data ie. array('var1' => 'val1', 'var2' => 'val2') */ 
	$custom_headers = array(), /* Custom HTTP headers ie. array('Referer: http://localhost/ */ 
	$timeout = 1000,           /* Socket timeout in milliseconds */ 
	$req_hdr = false,          /* Include HTTP request headers */ 
	$res_hdr = 0	           /* Include HTTP response headers, 0=No header, 1=complete header, 2=only content */ 
	) 
{ 
	$ret = ''; 
	$verb = strtoupper($verb); 
	$cookie_str = ''; 
	$getdata_str = count($getdata) ? '?' : ''; 
	$postdata_str = ''; 

	foreach ($getdata as $k => $v) 
		$getdata_str .= urlencode($k) .'='. urlencode($v) .'&'; 

	foreach ($postdata as $k => $v) 
		$postdata_str .= urlencode($k) .'='. urlencode($v) .'&'; 

	foreach ($cookie as $k => $v) 
		$cookie_str .= urlencode($k) .'='. urlencode($v) .'; '; 

	$crlf = "\r\n"; 
	$req = $verb .' '. $uri . $getdata_str .' HTTP/1.1' . $crlf; 
	$req .= 'Host: '. $ip . $crlf; 
	$req .= 'User-Agent: Mozilla/5.0 Firefox/3.6.12' . $crlf; 
	$req .= 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8' . $crlf; 
	$req .= 'Accept-Language: en-us,en;q=0.5' . $crlf; 
	$req .= 'Accept-Encoding: deflate' . $crlf; 
	$req .= 'Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7' . $crlf; 
	
	foreach ($custom_headers as $k => $v) 
		$req .= $k .': '. $v . $crlf; 
	
	if (!empty($cookie_str)) 
		$req .= 'Cookie: '. substr($cookie_str, 0, -2) . $crlf; 
	
	if ($verb == 'POST' && !empty($postdata_str)) 
	{ 
		$postdata_str = substr($postdata_str, 0, -1); 
		$req .= 'Content-Type: application/x-www-form-urlencoded' . $crlf; 
		$req .= 'Content-Length: '. strlen($postdata_str) . $crlf . $crlf; 
		$req .= $postdata_str; 
	} 
	else $req .= $crlf; 
	
	if ($req_hdr) 
		$ret .= $req; 
	
	if (($fp = @fsockopen($ip, $port, $errno, $errstr)) == false) 
		return "Error $errno: $errstr\n"; 
	
	stream_set_timeout($fp, 0, $timeout * 1000); 
	
	fputs($fp, $req);
	$retval = '';
	while ($line = fgets($fp)) $retval .= $line;
	fclose($fp); 
	
	if ($res_hdr == 1) {
		$ret .= $retval;
	}
	if ($res_hdr == 2) {
		// find last element 
		$ret .= substr($retval, strpos($retval, "\r\n\r\n") + 4); 
	}
	
	return $ret; 
}

function FwdSMS2Gateway ($username, $password, $phoneNoRecip, $msgText , $sender, $SenderCDMA) {
	$res = "";
	$res = http_request("GET","smsgateway.qss.in", 80, "/sendsms.php",array('user' => $username, 'password' => $password, 'PhoneNumber' => $phoneNoRecip, 'Sender' => $sender, 'SenderCDMA' => $SenderCDMA, 'Text' => $msgText ),array(),array(),array(),1000,false,2);
	
	return $res;
}

function generatePrimaryKey($as_table) {
	global $conn;
	$ll_count = executeScaler("select count('') from pkeys where tablename='$as_table'",$conn);
	if ($ll_count == 0) {
		$ll_pk = 1;
		mysql_query("insert into pkeys(tablename, last_id) values ('$as_table', $ll_pk)",$conn);
	} else {
		mysql_query("update pkeys set last_id = last_id + 1 where tablename = '$as_table'", $conn);
		$ll_pk = executeScaler("select last_id from pkeys where tablename='$as_table'",$conn);
	}
	return $ll_pk;
}

function generatePassword($length=6,$level=2){

	list($usec, $sec) = explode(' ', microtime());
	srand((float) $sec + ((float) $usec * 100000));

	$validchars[1] = "0123456789abcdfghjkmnpqrstvwxyz";
	$validchars[2] = "0123456789abcdfghjkmnpqrstvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
	$validchars[3] = "0123456789_!@#$%&*()-=+/abcdfghjkmnpqrstvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_!@#$%&*()-=+/";

	$password  = "";
	$counter   = 0;

	while ($counter < $length) {
		$actChar = substr($validchars[$level], rand(0, strlen($validchars[$level])-1), 1);

		// All character must be different
		if (!strstr($password, $actChar)) {
			$password .= $actChar;
			$counter++;
		}
	}

	return $password;

}

function fileupload_allowed_extensions($aas_allowed_extensions) {
	/*
	$allowedExtensions = array("txt","csv","htm","html","xml", 
		"css","doc","xls","rtf","ppt","pdf","swf","flv","avi", 
		"wmv","mov","jpg","jpeg","gif","png"); 
		*/
	$ls_msg = "";
	foreach ($_FILES as $file) { 
		if ($file['tmp_name'] > '') { 
			if (!in_array(end(explode(".", strtolower($file['name']))), $aas_allowed_extensions)) {
				$ls_msg = $file['name'].' is an invalid file type!<br/>'; 
			} 
		} 
	}
	return $ls_msg;
}
/**
 * Strip slashes from all elements in an array and return the array
 **/
function stripslashes_deep($value)
{
	$value = is_array($value) ? array_map('stripslashes_deep', $value) : (is_null($value))? $value : stripslashes($value);

	return $value;
}

function format_bytes($size) {
	$units = array(' B', ' KB', ' MB', ' GB', ' TB');
	for ($i = 0; $size >= 1024 && $i < 4; $i++) $size /= 1024;
	return round($size, 2).$units[$i];
}

/*
OLDER FUNCTION Replaced by above smarter one

function format_bytes($bytes) {
	if ($bytes < 1024) return $bytes.' B';
	elseif ($bytes < 1048576) return round($bytes / 1024, 2).' KB';
	elseif ($bytes < 1073741824) return round($bytes / 1048576, 2).' MB';
	elseif ($bytes < 1099511627776) return round($bytes / 1073741824, 2).' GB';
	else return round($bytes / 1099511627776, 2).' TB';
}
*/
function convert_input_date_to_mysql($as_input_date, $as_format = "d-m-y") {
	$ls_mysql_date = "";
	switch ($as_format) {
		case "d-m-y":
			// prepare date
			$d=split('[-]',$as_input_date);
			$ls_mysql_date = $d[2]."-".$d[1]."-".$d[0];
			break;
		case "d-m-y h:m":
			// split date and time
			$dt=split('[ ]',$as_input_date);
			// prepare date
			$d=split('[-]',$dt[0]);
			$ls_mysql_date = $d[2]."-".$d[1]."-".$d[0] . " " . $dt[1];
			break;
		case "m/d/y":
			// prepare date
			$d=split('[/]',$as_input_date);
			$ls_mysql_date = $d[2]."-".$d[0]."-".$d[1];
			break;
		case "m/d/y h:m":
			// split date and time
			$dt=split('[ ]',$as_input_date);
			// prepare date
			$d=split('[/]',$dt[0]);
			$ls_mysql_date = $d[2]."-".$d[0]."-".$d[1] . " " . $dt[1];
			break;
	}
	return $ls_mysql_date;
}
function check_for_duplicates($as_table, $as_unique_col, $as_unique_val, $as_id_col, $as_id, $as_additional_where = "") {
	global $conn;
	if ($as_additional_where <> "") {
		$as_additional_where .= " and ";
	}
	if($as_id == "new") {
		$sql = "select count('x') as cnt from $as_table where $as_additional_where $as_unique_col = :as_unique_val";
		$la_input_parameters = array(":as_unique_val"=>$as_unique_val);
	} else {
		$sql = "select count('x') as cnt from $as_table where $as_additional_where $as_unique_col = :as_unique_val and $as_id_col <> :as_id";
		$la_input_parameters = array(":as_unique_val"=>$as_unique_val,":as_id"=>$as_id);
	}
	
	$result_dup = $conn->prepare($sql);
	$result_dup->execute($la_input_parameters);
	$result_dup->setFetchMode(PDO::FETCH_ASSOC);
	
	$row_dup = $result_dup->fetch();
	if ($row_dup['cnt'] > 0) {
		return true;
	}
	return false;
}
function get_last_login($al_id_user) {
	global $conn;
	$ls_ret = "";
	$ls_query = "select login_time, login_ip from login_history where id_user=:id_user order by id_login_history desc limit 1";
	$result = $conn->prepare($ls_query);
	$result->execute(array(':id_user'=>$al_id_user));
	
	$result->setFetchMode(PDO::FETCH_ASSOC);
	$row = $result->fetch();
	extract($row,EXTR_PREFIX_ALL,"ls");
	$ls_ret = date("r", strtotime($ls_login_time)) . " from " . $ls_login_ip;
	return $ls_ret;
}

function fn_show_counter($as_images_dir, $as_images_ext, $al_min_digits, $al_count) {
	$ls_echo_text = "";
	$ls_images_dir = $as_images_dir; // "./images/";
	$ls_image_ext = $as_images_ext; // "gif";
	$ll_min_digits = $al_min_digits;
	// get count
	$ll_cnt = $al_count; //PAGE_VISITS
	// set count to zero if first time
	if ($ll_cnt == "") {
		$ll_cnt = 0;
	}
	// prepare output with padding as requested
	$ls_output = "";
	$ls_output = sprintf("%0" . $ll_min_digits . "s", $ll_cnt);
	// generate code
	$len = strlen($ls_output);
	for ($i=0;$i<$len;$i++)
	{
		$ls_echo_text .= '<img src="'. $ls_images_dir . substr($ls_output,$i,1) . '.' . $ls_image_ext .'" border="0" alt="Visitor count"/>';
	}
	// return code
	return $ls_echo_text;
}

function fn_page_visits($as_images_dir, $as_images_ext, $as_counter, $al_min_digits) {
	global $conn;
	$ls_echo_text = "";
	$ls_images_dir = $as_images_dir; // "./images/";
	$ls_image_ext = $as_images_ext; // "gif";
	$ll_min_digits = $al_min_digits;
	// get count
	$ll_cnt = get_setting($as_counter, $conn); //PAGE_VISITS
	// set count to zero if first time
	if ($ll_cnt == "") {
		$ll_cnt = 0;
	}
	// increment count
	$ll_cnt++;
	// save count
	set_setting($as_counter,$ll_cnt,$conn);
	// prepare output with padding as requested
	$ls_output = "";
	$ls_output = sprintf("%0" . $ll_min_digits . "s", $ll_cnt);
	// generate code
	$len = strlen($ls_output);
	for ($i=0;$i<$len;$i++)
	{
		$ls_echo_text .= '<img src="'. $ls_images_dir . substr($ls_output,$i,1) . '.' . $ls_image_ext .'" border="0" alt="Visitor count"/>';
	}
	// return code
	return $ls_echo_text;
}

function get_setting($as_setting, $conn) {
	global $conn;
	$la_execute_parameters = array(":option_key"=>$as_setting);
	$sql = "select option_value from settings where option_key=:option_key";
	$result = $conn->prepare($sql);
	$result->execute($la_execute_parameters);
	if ($result->rowCount() > 0) {
		$result->setFetchMode(PDO::FETCH_ASSOC);
		$row = $result->fetch();
		extract($row,EXTR_PREFIX_ALL,"ls");
	} else {
		$ls_option_value = "";
	}
	return $ls_option_value;
}

function set_setting($as_setting, $as_value, $conn) {
	global $conn;
	$sql = "select option_value from settings where option_key = :option_key";
	$result = $conn->prepare($sql);
	$result->execute(array(":option_key"=>$as_setting));
	
	if($result->rowCount() == 1) {
		$sql = "update settings set option_value = :as_value where option_key = :as_setting";
		$result_update = $conn->prepare($sql);
		$result_update->execute(array(":as_value"=>$as_value,":as_setting"=>$as_setting));
	} else {
		$sql = "insert into settings(option_key, option_value) values (:as_setting,:as_value)";
		$result_insert = $conn->prepare($sql);
		$result_insert->execute(array(":as_setting"=>$as_setting,":as_value"=>$as_value));
	}
}

function show_mysql_error( $doecho = false ) {
	if (mysql_errno() <> 0) {
		if($doecho == true) {
			// display the error
			echo mysql_errno() . ": " . mysql_error();
			return;
		} else {
			// return the error
			return mysql_errno() . ": " . mysql_error();
		}
	}
}

function display_array($arr, $arr_name = "") {
	echo "Displaying $arr_name Variables: <br> \n"; 
	echo "<pre>".print_r($arr, 1)."</pre>"; 
/*
	echo "<table border=1> \n"; 
	echo " <tr> \n"; 
	echo "  <td><b>result_name </b></td> \n "; 
	echo "  <td><b>result_val  </b></td> \n "; 
	echo " </tr> \n"; 
	while (list($result_nme, $result_val) = each($arr)) { 
		echo " <tr> \n"; 
		echo "  <td> $result_nme </td> \n"; 
		echo "  <td> $result_val </td> \n"; 
		echo " </tr> \n"; 
	} 
	echo "</table> \n"; 
*/
}

function display_post_get() {
	display_array($_POST, "_POST");
	display_array($_GET, "_GET");
} 

// IfNull :: returns 2nd parameter is the first parameter is null
function ifnull($TestVar,$ValueIfNull,$ValueIfNotNull = NULL)
{
	if ($ValueIfNull == "")
		$ValueIfNull = "&nbsp;";
	if ($ValueIfNotNull == "")
		$ValueIfNotNull = $TestVar;
	if (is_null($TestVar) or trim($TestVar) == "")
		return $ValueIfNull;
	else
		return $ValueIfNotNull;
}

// GetNewID :: Returns a new unique negative ID for a row in table 
//          :: using a passed connection
function getnewid(&$con, $table, $id_col) {
	// Create and open an ADO recordset object.
	$result = mysql_query("SELECT min(" . $id_col . ") FROM " . $table, $con);
	$row = mysql_fetch_row($result);
	
	$ll_new_id = $row[0];
	if ($ll_new_id < 0 ) {
		$ll_new_id = $ll_new_id - 1;
	}
	else {
		$ll_new_id = -1;
	}
	return ll_new_id;
}

// fillddlb :: Fills a drop down list box
function fillddlb(&$con, $as_sql, $as_col, $as_value_col, $as_selected) {
	if(!is_array($as_selected)) {
		$las_selections=array(0 => $as_selected);
	} else {
		$las_selections = $as_selected;
	}
	$result = $con->prepare($as_sql);
	$result->execute();
	$result->setFetchMode(PDO::FETCH_ASSOC);
	while ( $row = $result->fetch() ) {
		$ll_arr_selection_index = array_search($row["$as_value_col"], $las_selections);
		if( $ll_arr_selection_index === false) {
			echo "<option value='" . $row["$as_value_col"] . "'>" . $row["$as_col"] . "</option>";
		} else {
	 		echo "<option value='" . $row["$as_value_col"] . "' selected>" . $row["$as_col"] . "</option>";
		}
	}
}

// getID :: Get ID of passed value
function getID(&$con, $as_idcol, $as_namecol, $as_table, $as_value) {
	$result = mysql_query("SELECT " . $as_idcol . " FROM " & $as_table . " WHERE " . $as_namecol . " = '" . $as_value . "'", $con);
	if ( mysql_num_rows($result) == 1 ) {
		$row = mysql_fetch_row($result);
		$ll_id = $row[0];
	} else {
		$ll_id = -1;
	}
	return $ll_id;
}

function executeScaler($as_sql, &$con) {
	$result = $con->prepare($as_sql);
	$result->execute();
	
	if ( $result->rowCount() == 1 ) {
		$row = $result->fetch();
		$ls_var = $row[0];
	} else {
		$ls_var = "";
	}
	return $ls_var;
}

// getName :: Get Name of passed value
function getName(&$con, $as_idcol, $as_namecol, $as_table, $al_value) {
	$ls_sql = "SELECT " . $as_namecol . " FROM " . $as_table . " WHERE " . $as_idcol . " = " . $al_value;
	$ls_name = executeScaler($ls_sql,$con);
	return $ls_name;
}


/*
// show date entry field in form
function showdatefields($ad_date)
	dim ctr
	response.write "<select size='1' name='day'>"
	For Ctr = 1 to 31
		if day(ad_date) = ctr then
			response.write "<option selected value='" & ctr & "'>" & ctr & "</option>"
		else
			response.write "<option value='" & ctr & "'>" & ctr & "</option>"
		end if
	next

	response.write "</select><select size='1' name='month'>"
	For Ctr = 1 to 12
		if month(ad_date) = ctr then
			response.write "<option selected value='" & ctr & "'>" & monthname(ctr) & "</option>"
		else
			response.write "<option value='" & ctr & "'>" & monthname(ctr) & "</option>"
		end if
	next

	response.write "</select><select size='1' name='year'>"
	For Ctr = 2000 to year(date)
		if year(ad_date) = ctr then
			response.write "<option selected value='" & ctr & "'>" & ctr & "</option>"
		else
			response.write "<option value='" & ctr & "'>" & ctr & "</option>"
		end if
	next
	response.write "</select>"
end sub
*/

/*
sub acopy1dim(byref a_arc, byref a_tgt)
	dim ctr
	' redim target
	redim a_tgt(ubound(a_src))
	' copy src to tgt
	for ctr = 0 to ubound(a_src)
		a_tgt(ctr) = a_src(ctr)
	next
end sub
*/
/*
sub acopy2dim(byref a_arc,byref a_tgt)
	dim ctr, ktr
	' redim target
	redim a_tgt(ubound(a_src,1), ubound(a_src,2))
	' copy src to tgt
	for ctr = 0 to ubound(a_src,1)
		for ktr = 0 to ubound(a_src,2)
			a_tgt(ctr,ktr) = a_src(ctr,ktr)
		next
	next
end sub
*/

// getMaxID :: Get Max ID of passed table
function getMaxID(&$con, $as_table, $as_idcol) {
	$result = mysql_query("SELECT max(" . $as_idcol . ") FROM " . $as_table, $con);
	$row = mysql_fetch_row($result);
	
	if ($row[0] != NULL) {
		$ll_id = $row[0];
	} else {
		$ll_id = -1;
	}
	return ll_id;
}

// showdate :: Show date in d-mmm-yyyy
function showdate( $ad_date ) {
	if ($ad_date == "") {
		$as_date = "";
	} else {
		$as_date = date("j-M-Y", strtotime($ad_date));
	}
	return $as_date;
}

// convertdate4mysql :: converts dd/mm/yyyy date into mysql format
function convertdate4mysql( $ad_date ) {
	$ls_date = substr($ad_date,6,4) . "-" . substr($ad_date,3,2) . "-" . substr($ad_date,0,2);
	return $ls_date;
}

// validates date in dd/mm/yyyy
function isDate( $ad_date ) {
	$ls_retmsg = "";
	$strdate = $ad_date;

	//Check the length of the entered Date value
	if((strlen($strdate)<10)OR(strlen($strdate)>10)){
		$ls_retmsg = "Enter the date in 'dd/mm/yyyy' format";
	} else {
		//The entered value is checked for proper Date format
		if((substr_count($strdate,"/"))<>2){
			$ls_retmsg = "Enter the date in 'dd/mm/yyyy' format";
		} else {
			$pos=strpos($strdate,"/");
			$date=substr($strdate,0,($pos));
			$result=ereg("^[0-9]+$",$date,$trashed);
			if(!($result)){
				$ls_retmsg = "Enter a Valid Date";
			} else {
				if(($date<=0)OR($date>31)) {
					$ls_retmsg = "Enter a Valid Date";
				}
			}
			$month=substr($strdate,($pos+1),($pos));
			if(($month<=0)OR($month>12)) {
				$ls_retmsg = "Enter a Valid Month";
			} else {
				$result=ereg("^[0-9]+$",$month,$trashed);
				if(!($result)) {
					$ls_retmsg = "Enter a Valid Month";
				}
			}
			$year=substr($strdate,($pos+4),strlen($strdate));
			$result=ereg("^[0-9]+$",$year,$trashed);
			if(!($result)) {
				$ls_retmsg = "Enter a Valid year";
			} else {
				if(($year<1900)OR($year>2200)) {
					$ls_retmsg = "Enter a year between 1900-2200";
				}
			}
		}
	}
	return $ls_retmsg;
}

function pagenumber($sql_count, $conn, $max_results, $page) {
	// Figure out the total number of results in DB: 
	$total_results = mysql_result(mysql_query($sql_count,$conn),0); 
	
	// Figure out the total number of pages. Always round up using ceil() 
	$total_pages = ceil($total_results / $max_results); 
	
	// Build Page Number Hyperlinks 
	echo "<center><b>[</b> "; 
	
	// Build Previous Link 
	if($page > 1){ 
		$prev = ($page - 1); 
		echo "<a href=\"".$_SERVER['PHP_SELF']."?page=$prev\">&lt;&lt;Previous</a> "; 
	} 
	
	for($i = 1; $i <= $total_pages; $i++){ 
		if(($page) == $i){ 
			echo "$i "; 
			} else { 
				echo "<a href=\"".$_SERVER['PHP_SELF']."?page=$i\">$i</a> "; 
		} 
	} 
	
	// Build Next Link 
	if($page < $total_pages){ 
		$next = ($page + 1); 
		echo "<a href=\"".$_SERVER['PHP_SELF']."?page=$next\">Next&gt;&gt;</a>"; 
	} 
	echo " <b>]</b></center>"; 
}

function isValidEmail($email){
	return filter_var( $email, FILTER_VALIDATE_EMAIL );
	//return eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email);
}

/*
' Use this function to display dates in various formats
function dateformat( ad_date, as_format )
	dim as_date
	if IsNull(ad_date) or ad_date = "" then
		as_date = ""
	else
		if as_format = "d-mmm-yyyy" then
			as_date = datepart("d", ad_date) & "-" & monthname(datepart("m", ad_date), True) & "-" & datepart("yyyy", ad_date)
		end if
		if as_format = "d-mmm-yy" then
			as_date = datepart("d", ad_date) & "-" & monthname(datepart("m", ad_date), True) & "-" & mid(cstr(datepart("yyyy", ad_date)), 3)
		end if
		if as_format = "d-m-yy" then
			as_date = datepart("d", ad_date) & "-" & datepart("m", ad_date) & "-" & mid(cstr(datepart("yyyy", ad_date)), 3)
		end if
		if as_format = "d-m-yyyy" then
			as_date = datepart("d", ad_date) & "-" & datepart("m", ad_date) & "-" & datepart("yyyy", ad_date)
		end if
		if as_format = "d/m/yy" then
			as_date = datepart("d", ad_date) & "/" & datepart("m", ad_date) & "/" & mid(cstr(datepart("yyyy", ad_date)), 3)
		end if
		if as_format = "d/m/yyyy" then
			as_date = datepart("d", ad_date) & "/" & datepart("m", ad_date) & "/" & datepart("yyyy", ad_date)
		end if
	end if
	dateformat = as_date
end function
*/

/*
sub showdatefields1(ad_date)
	dim ctr
	response.write "<select size='1' name='day1'>"
	For Ctr = 1 to 31
		if day(ad_date) = ctr then
			response.write "<option selected>" & ctr & "</option>"
		else
			response.write "<option>" & ctr & "</option>"
		end if
	next

	response.write "</select><select size='1' name='month1'>"
	For Ctr = 1 to 12
		if month(ad_date) = ctr then
			response.write "<option selected value='" & ctr & "'>" & monthname(ctr) & "</option>"
		else
			response.write "<option value='" & ctr & "'>" & monthname(ctr) & "</option>"
		end if
	next

	response.write "</select><select size='1' name='year1'>"
	For Ctr = 2000 to year(date)
		if year(ad_date) = ctr then
			response.write "<option selected value='" & ctr & "'>" & ctr & "</option>"
		else
			response.write "<option value='" & ctr & "'>" & ctr & "</option>"
		end if
	next
	response.write "</select>"
end sub
*/

/*
sub fillddlb_from_array(las_elements, ls_selected)
dim ls_element

for each ls_element in las_elements
	if ls_selected = ls_element then
		response.write("<option selected>" & ls_element & "</option>")
	else
		response.write("<option>" & ls_element & "</option>")
	end if
next

end sub
*/

?>