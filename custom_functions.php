<?php
    function getTableValues($cert_id){
        $url = 'http://www.pcgs.com/Cert/'.$cert_id.'/';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);      
        curl_close($ch);
        $table = explode('<div class="block">', $response);
        $table_data = explode('</td>', strip_tags($table[1], '<td>'));
        $data_arr = array();
        foreach ($table_data as $value) {
            $data_arr[] =  preg_replace('/\s+/', ' ', strip_tags($value));
        }
        $cert_no = explode('COIN INFORMATION', $data_arr[0]);
        if(stristr(trim($cert_no[1]),'Cert Verification #:') != false && trim($data_arr[1]) == $cert_id){
            $arr_val = array(
                    ':pcgs_ver_id' => trim($data_arr[1]),
                    ':pcgs_coin_no' => trim($data_arr[3]),
                    ':pcgs_date_mintmark' => trim($data_arr[5]),
                    ':pcgs_denomination' => trim($data_arr[7]),
                    ':pcgs_variety' => trim($data_arr[9]),
                    ':pcgs_minor_variety' => trim($data_arr[11]),
                    ':pcgs_mint_error' => trim($data_arr[13]),
                    ':pcgs_pedigree' => trim($data_arr[15]),
                    ':pcgs_country' => trim($data_arr[17]),
                    ':pcgs_grade' => trim($data_arr[19]),
                    ':pcgs_mintage' => trim($data_arr[21]),
                    ':pcgs_price_guide_value' => str_replace('$', '', trim($data_arr[23])),
                    ':pcgs_holder_type' => trim($data_arr[25]),
                    ':pcgs_population' => str_replace(',', '', trim($data_arr[27]))
                );
            return array($arr_val, $table[1]);
        }
        else{
            return 0;
        }
    }
    
    function multi_files_upload($file){
        global $conn;
        $image = array();
        $i = 0;
        for($i=0; $i < count($file['name']); $i++){
            if($file['name'][$i] == ''){
                break;
            }
            if($file['size'][$i] <= 0){
                $err = INVALID_FILESIZE.' '.$file['name'][$i];
                break;
            }
            $la_allowd_ext = array('jpeg', 'jpg', 'png', 'gif');
            $file_ext = explode('.', $file['name'][$i]);
            foreach ($la_allowd_ext as $ext) {
                if(strtolower($file_ext[1]) != $ext){
                    continue;
                }
                $file_type = TRUE;
            }
            if(!isset($file_type) || $file_type != TRUE){
                $err = INVALID_FILETYPE.' '.$file['name'][$i];
                break;
            }
            $filename = time().$file['name'][$i];
            $filepath = get_setting(BASE_DIRECTORY, $conn).'/'.$filename;
            if(file_exists(get_setting(BASE_DIRECTORY, $conn)) == FALSE){
                mkdir(get_setting(BASE_DIRECTORY, $conn));
            }
            if(move_uploaded_file($file['tmp_name'][$i], $filepath)){
                $image[] = $filename;
            }
            else{
                $err = UPLOAD_FAILED;
                break;
            }
        }
        if(!isset($err)){
            return array($image, 0);
        }
        else{
            return array($err, 1);
        }
    }
    function getDistance($zip1, $zip2, $unit){
        $first_lat = getLatLng($zip1);
        $next_lat = getLatLng($zip2);
        $lat1 = $first_lat['lat'];
        $lon1 = $first_lat['lng'];
        $lat2 = $next_lat['lat'];
        $lon2 = $next_lat['lng']; 
        $theta=$lon1-$lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +
        cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
        cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);

        if ($unit == "K"){
            return ($miles * 1.609344);
        }
        else if ($unit =="N"){
            return ($miles * 0.8684);
        }
        else{
            return $miles;
        }
    }
    function calcDist($lat_A, $long_A, $lat_B, $long_B) {
        $distance = sin(deg2rad($lat_A))
                      * sin(deg2rad($lat_B))
                      + cos(deg2rad($lat_A))
                      * cos(deg2rad($lat_B))
                      * cos(deg2rad($long_A - $long_B));

        $distance = (rad2deg(acos($distance))) * 69.09;

        return $distance;
      } 
      
    function getTimeDiff($date1, $date2){
        $date_a = new DateTime($date1);
        $date_b = new DateTime($date2);
        $interval = date_diff($date_a,$date_b);
        return $interval->format('%y Yrs, %m Months, %d Days, %h Hrs, %i Mins');
    }
    function parse_address_google($address) {
        $url = 'http://maps.googleapis.com/maps/api/geocode/json?sensor=false&address='.urlencode($address);
        $results = json_decode(file_get_contents($url),1);
        $parts = array(
          'address'=>array('street_number','route'),
          'city'=>array('locality'),
          'state'=>array('administrative_area_level_1'),
          'zip'=>array('postal_code'),
        );
        if (!empty($results['results'][0]['address_components'])) {
          $ac = $results['results'][0]['address_components'];
          foreach($parts as $need=>&$types) {
            foreach($ac as &$a) {
              if (in_array($a['types'][0],$types)) $address_out[$need] = $a['long_name'];
              elseif (empty($address_out[$need])) $address_out[$need] = '';
            }
          }
        } else echo 'empty results';
        return $address_out;
    }
    function array_push_associative(&$arr) {
        $ret = 0;
        $args = func_get_args();
        foreach ($args as $arg) {
           if (is_array($arg)) {
               foreach ($arg as $key => $value) {
                   $arr[$key] = $value;
                   $ret++;
               }
           }else{
               $arr[$arg] = "";
           }
        }
        return $ret;
    }
?>
