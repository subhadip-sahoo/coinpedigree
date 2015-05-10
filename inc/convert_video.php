<?php
class media_handler
{
	function convert_media($filename, $rootpath, $inputpath, $outputpath)
	{
		$outfile = "";
		$f = "";
		
		// root directory path, where FFMPEG folder exist in your application.
		$rPath = $rootpath."\ffmpeg";
		
		// remove origination extension from file adn add .mp4 extension
		$outfile =$filename;
		$out=explode(".",$outfile);
		
		$counter = count($out);
		for($i = 0; $i < $counter - 1 ; $i++){
			$f .= $out[$i].".";
		}

		$outfile_mp4 = $f."mp4";
		$outfile_Webm = $f."Webm";
		
		//execute the following FFMPEG Command and convert video to mp4 format.
		$ffmpegcmd_mp4 = "$rootpath/ffmpeg -i ".$inputpath."/".$filename." -f mp4 -s 320×240 ".$outputpath."/".$outfile_mp4;
		$ffmpegcmd_Webm = "$rootpath/ffmpeg -i ".$inputpath."/".$filename." -f Webm -s 320×240 ".$outputpath."/".$outfile_Webm;

		$ret_mp4 = shell_exec($ffmpegcmd_mp4);
		$ret_Webm = shell_exec($ffmpegcmd_Webm);
		
		// return output file name for other operations
		return $outfile_mp4;
	}

}
?>