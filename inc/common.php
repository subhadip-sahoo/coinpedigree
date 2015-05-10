<?php
function save_upload_file($as_id) {
	global $conn;
	if ($_FILES['banner_image']['size'] > 0) {
		@mkdir(get_setting(FILE_UPLOAD_FOLDER,$conn) . "portfolios");
		@mkdir(get_setting(FILE_UPLOAD_FOLDER,$conn) . "portfolios/" . $as_id);
		$ls_filename = get_setting(FILE_UPLOAD_FOLDER,$conn) . "portfolios/" . $as_id . '/' . basename($_FILES['banner_image']['name']);
		// first remove existing file
		if (file_exists($ls_filename)) {
			unlink($ls_filename);
		}
		if (move_uploaded_file($_FILES['banner_image']['tmp_name'], $ls_filename)) {
			// if successfully moved to upload folder then update file uploads table
			$sql = "
					update 
						portfolio_cat 
					set 
						banner_image = :banner_image
					where 
						id_portfolio_cat = :id_portfolio_cat
					";

			$ls_banner_image = basename($_FILES['banner_image']['name']);
			$result = $conn->prepare($sql);
			$result->execute(array(":banner_image"=>$ls_banner_image,":id_portfolio_cat"=>$as_id));
		}
	}
}
?>