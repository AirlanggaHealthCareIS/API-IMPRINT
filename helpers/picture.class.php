<?php
	
	class Picture{
		
		function upload($f_source, $dest_size, $dest_folder){
		
			$target_file 	= $dest_folder;
					
			$check = getimagesize($f_source["tmp_name"]);
			if($check !== false) {
				$nw	= $dest_size['w'];
				$nh	= $dest_size['h'];
				$source 		= $f_source["tmp_name"];
				$temporary 		= explode(".", $f_source["name"]);
				$file_extension = end($temporary);
				
				$size 	= getimagesize($source); // ukuran gambar
				$w 		= $size[0];
				$h 		= $size[1];
				switch($file_extension) 
				{ // format gambar
					case 'gif':
						$simg = imagecreatefromgif($source);
						break;
					case 'jpg':
						$simg = imagecreatefromjpeg($source);
						break;
					case 'png':
						$simg = imagecreatefrompng($source);
						break;
					default :
						$simg = imagecreatefromjpeg($source);
						break;
				} 
				$dimg = imagecreatetruecolor($nw, $nh); // menciptakan image baru
				$wm = $w/$nw;
				$hm = $h/$nh;
				$h_height = $nh/2;
				$w_height = $nw/2;
				if($w> $h) 
				{
					$adjusted_width = $w / $hm;
					$half_width = $adjusted_width / 2;
					$int_width = $half_width - $w_height;
					imagecopyresampled($dimg,$simg,-$int_width,0,0,0,$adjusted_width,$nh,$w,$h);
				}
				elseif(($w <$h) || ($w == $h)) 
				{
					$adjusted_height = $h / $wm;
					$half_height = $adjusted_height / 2;
					$int_height = $half_height - $h_height;
					imagecopyresampled($dimg,$simg,0,-$int_height,0,0,$nw,$adjusted_height,$w,$h);
				}
				else
				{
					imagecopyresampled($dimg,$simg,0,0,0,0,$nw,$nh,$w,$h);
				}
				imagejpeg($dimg,$target_file,100);
				
				list($err,$msg) = array(0,"Upload Berhasil.");
			} else {
				list($err,$msg) = array(1,"Upload Gagal. Silakan ulangi kembali.");
			}
			
			return array($err, $msg);
		}
	}
?>