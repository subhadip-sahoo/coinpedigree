<?php
/*
 * last modified on
 * 2nd Sept, 2013
 */
function get_freehire_details($as_id){
	global $conn;
	$ls_query = "select *, contractors.name as cname from freehires join contractors on contractors.id_contractor = freehires.id_contractor where id_freehires = $as_id";
	$result = $conn->prepare($ls_query);
	$result->execute();
	return $result;
}

function run_query(&$conn, $query, $values_array=array()){
	
	if(!$conn) return;
	
	try{
		
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		//prepare
		$result = $conn->prepare($query);
		
		//execute
		$result->execute($values_array);
		
		return $result;
		
	} catch (PDOException $e) {
		
		$ls_errmsg = 'Error: ' .$e->getMessage();
		die($ls_errmsg);
		
	}
	
}

function check_remove($full_path,$al_size_array)
{
	$image_name = explode("/",$full_path);
	$image = $image_name[2];
	$name = explode(".",$image);
	$name = $name[0];
	//$full_path_without_extention = $image_name[0]."/".$image_name[1]."/".$name;
	$formats = array('gif','jpg','jpeg','png','bmp');
	/*foreach($formats as $format)
	{
		if(file_exists($full_path_without_extention.".".$format))
		{
			unlink($full_path_without_extention.".".$format);
		}
	}*/
	
	foreach($al_size_array as $size => $prefix)
	{
		$full_path_without_extention = $image_name[0]."/".$image_name[1]."/".$prefix.$name;
		foreach($formats as $format)
		{
			if(file_exists($full_path_without_extention.".".$format))
			{
				unlink($full_path_without_extention.".".$format);
			}
		}
	}
}

function profile_picture($id,$prefix)
{
	$files = glob("profile_picture/jobseeker/*.{gif,jpg,jpeg,png,bmp}", GLOB_BRACE);
	//echo "<pre>";
	//print_r($files);
	foreach($files as $file)
	{
		$file_array = explode("/",$file);
		$one_file = $file_array[2];
		//return $one_file;
		$file_name = explode(".",$one_file);
		$file_name = $file_name[0];
		if($id == $file_name)
		{
			//return $file;
			return $file_array[0]."/".$file_array[1]."/".$prefix.$one_file;
			exit;
		}
	}
}

function profile_picture_h($id,$prefix)
{
	$files = glob("profile_picture/employer/*.{gif,jpg,jpeg,png,bmp}", GLOB_BRACE);
	//echo "<pre>";
	//print_r($files);
	foreach($files as $file)
	{
		$file_array = explode("/",$file);
		$one_file = $file_array[2];
		//return $one_file;
		$file_name = explode(".",$one_file);
		$file_name = $file_name[0];
		if($id == $file_name)
		{
			//return $file;
			return $file_array[0]."/".$file_array[1]."/".$prefix.$one_file;
			exit;
		}
	}
}

function resize_images($as_src_path, $as_target_path, $al_size_array)
{
	foreach($al_size_array as $size => $prefix)
	{
		$size_array = explode("x",$size);
		$width = $size_array[0];
		$height = $size_array[1];
		
		$image_name = explode("/",$as_src_path);
		$image = $image_name[2];
		$new_target_path = $image_name[0]."/".$image_name[1]."/".$prefix.$image;
		
		resize_image($as_src_path, $new_target_path,$width,$height);
	}
}

function occupation($id)
{
	global $conn;
	$sql = "select name from occupation where id_occupation = :id_occupation";
	$res = $conn->prepare($sql);
	$res->execute(array(":id_occupation"=>$id));
	$res->setFetchMode(PDO::FETCH_OBJ);
	
	$d = $res->fetch();
	if(!empty($d))
	{
		return $d->name;
	}
}

function time_difference($to_time,$from_time)
{
	$to_time = strtotime($to_time);
	$from_time = strtotime($from_time);
	return round(abs($to_time - $from_time) / (60*60),2). " hours";
}

$obj = new media_handler();
function file_with_caption($tmp_name,$name,$id,$folder,$category,$caption)
{
	$l = 0;
	global $conn;
	
	foreach($tmp_name as $doc_temp_name)
	{
		$f = "";
		$out=explode(".",$name[$l]);
		
		$counter = count($out);
		for($i = 0; $i < $counter - 1 ; $i++){
			$f .= $out[$i].".";
		}
		
		//remove last . from string
		$f = ((strrpos($f, '.') + 1) == strlen($f)) ? substr($f, 0, - 1) : $f;
		
		//clean spaces and special chars
		$f = clean($f);
		
		$fname = $f.".".$out[$i];
		

		if(!file_exists("profile_picture/"))
		{
			mkdir("profile_picture/");
		}
		
		if(!file_exists("profile_picture/jobseeker/"))
		{
			mkdir("profile_picture/jobseeker/");
		}
		
		$doc_path = "profile_picture/jobseeker/".$id."/";
		
		if(!file_exists($doc_path))
		{
			mkdir($doc_path);
		}
		
		if(!file_exists($doc_path.$folder."/"))
		{
			mkdir($doc_path.$folder."/");
		}
		
		$doc_file = $doc_path.$folder."/".$fname;
		$doc_p_path = $doc_path.$folder."/";
		
		if(file_exists($doc_file))
		{
			$fname = time()."_".$fname;
			$doc_file = $doc_path.$folder."/".$fname;
		}
		
		if (move_uploaded_file($doc_temp_name, $doc_file)) 
		{

			
			//if category == V
			if($category == "V"){
				
				$filename = $fname;
				$rootpath = get_setting(BASE_DIRECTORY,$conn)."ffmpeg";
				$inputpath = $doc_path.$folder;
				$outputpath = $doc_path.$folder;
					
				$obj = new media_handler();
				$converted_file = $obj->convert_media($filename, $rootpath, $inputpath, $outputpath);
				$doc_file = $doc_p_path.$converted_file;
				
			}
			
			//Change the formate here.
			$file_type = mime_content_type($doc_file);
			
			$sql = "
					INSERT into
						portfolio
						(
							id_contractor,
							filename,
							caption,
							file_category,
							filetype,
							added_on
						)
						values
						(
							:id_contractor,
							:filename,
							:caption,
							:file_category,
							:filetype,
							now()
						)";
		
			$la_input_parameters = array(
					":id_contractor"=>$id,
					":filename"=>$doc_file,
					":caption"=>$caption[$l],
					":file_category"=>$category,
					":filetype"=>$file_type
					);
			
			$result = $conn->prepare($sql);
			$result->execute($la_input_parameters);
			
		}
		$l++;
	}
}


function simple_job_status($status)
{
	if($status == "O")
	{
		return "Open";
	}
	if($status == "C")
	{
		return "Closed";
	}
	if($status == "X")
	{
		return "Canceled";
	}
}

function job_title($id)
{
	global $conn;
	$sql =
			"
			select
				employers.name as ename, 
				title,
				job_closed_on,
				jobs.zipcode 
			from 
				jobs 
				join employers on jobs.id_employer = employers.id_employer
			where 
				id_job = :id_job
			";
	
	$res = $conn->prepare($sql);
	$res->execute(array(":id_job"=>$id));
	$res->setFetchMode(PDO::FETCH_OBJ);
	
	$d = $res->fetch();
	return $d;
}


function invites($id_jobseeker)
{
	global $conn;
	$canceled = 0;
	$sql = 
		"
		SELECT
			 count('X') as cnt,
			id_list
		FROM
			lists
		WHERE
			id_contractor = :id_contractor
			and invite_senton is not null
			and awarded = 'N'
			and lists.id_list not in(select id_list from responses) 
		";

	$res = $conn->prepare($sql);
	$res->execute(array(":id_contractor"=>$id_jobseeker));
	$res->setFetchMode(PDO::FETCH_OBJ);
	
	$row = $res->fetch();
	$invites = $row->cnt;
	return $invites;
}

/*
function invites($id_contractor)
{
	$canceled = 0;
	$proposed = 0;
	$sql = sprintf("
				SELECT
					 id_job,invited_on
				FROM
					invites
				WHERE
					id_contractor = %d
					",
					mysql_real_escape_string($id_contractor)
					);
	$res = mysql_query($sql) or die(mysql_error());
	$invites = mysql_num_rows($res);
	while($d = mysql_fetch_object($res))
	{
		$sql_bids = sprintf("
							SELECT
								 id_bid
							FROM
								bids
							WHERE
								id_job			= %d
								and id_contractor	= %d
							",
							mysql_real_escape_string($d->id_job),
							mysql_real_escape_string($id_contractor)
							);
		$res_bids = mysql_query($sql_bids) or die(mysql_error());
		$no_of_bids = mysql_num_rows($res_bids);
		if($no_of_bids > 0)
		{
			$proposed++;
		}
		
		$sql_jobs = sprintf("
							SELECT
								id_job
							FROM
								jobs
							WHERE
								id_job = %d
							AND
								status = '%s' or status = '%s'
							",
							mysql_real_escape_string($d->id_job),
							mysql_real_escape_string('C'),
							mysql_real_escape_string('X')
							);
		$res_jobs = mysql_query($sql_jobs) or die(mysql_error());
		$no_of_jobs = mysql_num_rows($res_jobs);
		if($no_of_jobs > 0)
		{
			$canceled++;
		}
		
	}
	
	$no_of_invites = $invites - ($canceled + $proposed);
	return $no_of_invites;
}
*/

function finalizing_terms($id_jobseeker)
{
	global $conn;
	$sql = "
			SELECT
				count(*) as ftc
			FROM
				bid_award
			INNER JOIN
				bids
			ON
				bid_award.id_bid = bids.id_bid
			WHERE
				bids.id_contractor = :id_contractor
			AND
				bid_award.status = :status
			";
	
	$res = $conn->prepare($sql);
	$res->execute(array(":id_contractor"=>$id_contractor,":status"=>"A"));
	$res->setFetchMode(PDO::FETCH_ASSOC);
	
	$d = $res->fetch();
	$no = $d['ftc'];
	return $no;
}

function getjobseekerdetails($id_jobseeker)
{
	global $conn;
	// Query the database for the list of series
	$sql_getjobseekerdetails =
			"
			select 
				id_contractor,
				specialities.name as speciality,
				status,
				subscription_end_date,
				contractors.name as name,
				email,
				login_type,
				password,
				fb_id,
				twitter_id,
				google_id,
				created_at,
				last_login_at,
				verified,
				verification_code,
				verified_at,
				zipcode,
				latitude,
				longitude,
				website,
				address1,
				address2,
				address3,
				city,
				state,
				phone_no,
				about 
			from 
				contractors
				left outer join specialities on specialities.id_speciality = contractors.id_speciality
			where 
				id_contractor = :id_contractor
			";

	$result_getjobseekerdetails = $conn->prepare($sql_getjobseekerdetails);
	$result_getjobseekerdetails->execute(array(":id_contractor"=>$id_jobseeker));

	if($result_getjobseekerdetails->rowCount() <> 1){
		return "error";
	}else{
		$result_getjobseekerdetails->setFetchMode(PDO::FETCH_ASSOC);
		$row_getjobseekerdetails = $result_getjobseekerdetails->fetch();
		return $row_getjobseekerdetails;
	}
}

function listdir($dir='.') { 
	if (!is_dir($dir)) { 
		return false; 
	} 
	
	$files = array(); 
	listdiraux($dir, $files); 

	return $files; 
} 

function listdiraux($dir, &$files) { 
	$handle = opendir($dir); 
	while (($file = readdir($handle)) !== false) { 
		if ($file == '.' || $file == '..') { 
			continue; 
		} 
		$filepath = $dir == '.' ? $file : $dir . '/' . $file; 
		if (is_link($filepath)) 
			continue; 
		if (is_file($filepath)) 
			$files[] = $filepath; 
		else if (is_dir($filepath)) 
			listdiraux($filepath, $files); 
	} 
	closedir($handle); 
}

function upload_panel_icons($file_control_name){
	global $conn;
	if ($_FILES[$file_control_name]['size'] > 0) {
		if(file_exists("../images") == false){
			mkdir("../images");
		}
		$ls_filename = "../images/" . basename($_FILES[$file_control_name]['name']);
		move_uploaded_file($_FILES[$file_control_name]['tmp_name'], $ls_filename);
		return basename($_FILES[$file_control_name]['name']);
	} else {
		return false;
	}
}

/*========================== FUNCTION FOR SHOW TIME ==========================*/
function showTime($date_time_from_db)
{
	$time_diff_in_sec = strtotime(date('Y-m-d H:i:s')) - strtotime($date_time_from_db);
	$splited_current_date = explode('-', date('Y-m-d'));
	$splited_date_time_from_db = explode('-', date('Y-m-d', strtotime($date_time_from_db)));

	switch ($time_diff_in_sec) {
		case ($time_diff_in_sec <= 60):
			return 'About few seconds ago';
			break;
		case ($time_diff_in_sec > 60 && $time_diff_in_sec < 120):
			return 'About a minute ago';
			break;
		case ($time_diff_in_sec > 120 && $time_diff_in_sec < 3600):
			return 'About '.floor($time_diff_in_sec / 60).' minutes ago';
			break;
		case ($time_diff_in_sec >= 3600 && $time_diff_in_sec < 7200):
			return 'About an hour ago';
			break;
		case (($splited_current_date[2] - $splited_date_time_from_db[2]) == 0 && $splited_current_date[1] == $splited_date_time_from_db[1] && $splited_current_date[0] == $splited_date_time_from_db[0]):
			return 'About '.floor($time_diff_in_sec / 3600).' hours ago';
			break;
		case (($splited_current_date[2] - $splited_date_time_from_db[2]) == 1 && $splited_current_date[1] == $splited_date_time_from_db[1] && $splited_current_date[0] == $splited_date_time_from_db[0]):
			return 'Yesterday at '.date('H:i:s', strtotime($date_time_from_db));
			break;
		case (($splited_current_date[2] - $splited_date_time_from_db[2]) == 1 && $splited_current_date[1] != $splited_date_time_from_db[1] && $splited_current_date[0] == $splited_date_time_from_db[0]):
			return date('dS F', strtotime($date_time_from_db)).', '.date('H:i:s', strtotime($date_time_from_db));
			break;
		case (($splited_current_date[2] - $splited_date_time_from_db[2]) > 1 && $splited_current_date[0] == $splited_date_time_from_db[0]):
			return date('dS F', strtotime($date_time_from_db)).', '.date('H:i:s', strtotime($date_time_from_db));
			break;
		case ($splited_current_date[0] != $splited_date_time_from_db[0]):
			return date('d-M-Y, H:i:s', strtotime($date_time_from_db));
			break;
		default:
			return date('d-M-Y, H:i:s', strtotime($date_time_from_db));
			break;
	}
}

/*========================== END FUNCTION FOR SHOW TIME ==========================*/
?>