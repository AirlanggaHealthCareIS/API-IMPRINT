<?php
	class CStr {
		//create random string
		function generateRandomString($length = 10) {
			$characters 		= '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$charactersLength 	= strlen($characters);
			$randomString = '';
			for ($i = 0; $i < $length; $i++) {
				$randomString .= $characters[rand(0, $charactersLength - 1)];
			}
			return $randomString;
		}
		
		// strip selain alfanumerik
		function cAlphaNum($item,$allow='') {
			return preg_replace('/[^a-zA-Z0-9'.$allow.']/', '$1', $item);
		}
		
		// strip selain numerik
		function cNum($item) {
			return (float)preg_replace('/[^0-9]/', '$1', $item);
		}
		
		// melakukan string stripping (untuk masalah sekuritas)
		function removeSpecial($mystr,$stripapp=true) {
			$mystr = trim($mystr);
			
			$pattern = '/[%&;\"';
			if($stripapp === false) // tidak stripping ', tapi direplace jadi '', biasanya dipakai di nama
				$mystr = str_replace("'","''",$mystr);
			else
				$pattern .= "\'";
			$pattern .= ']|--/';
			
			return preg_replace($pattern, '$1', $mystr);
		}

		// melakukan removespecial dengan mengecek array
		function removeSpecialAll($item) {
			if(is_array($item)) {
				foreach($item as $idx => $val)
					$item[$idx] = CStr::removeSpecialAll($val);
				return $item;
			}
			else
				return CStr::removeSpecial($item);
		}
		
		// mengubah format tanggal dari yyyy-mm-dd menjadi array d m y
		function splitDate($ymd,$dmy=false,$delim='-') {
			if($dmy)
				list($d,$m,$y) = explode($delim,substr($ymd,0,10));
			else
				list($y,$m,$d) = explode($delim,substr($ymd,0,10));
			
			return array($d,$m,$y);
		}
		
		// mengubah format tanggal dari yyyy-mm-dd menjadi format indonesia
		function formatDateInd($ymd,$full=true,$dmy=false,$delim='-',$intd=true) {
			if($ymd == '')
				return '';
			if($ymd == 'null')
				return 'null';
			
			list($d,$m,$y) = CStr::splitDate($ymd,$dmy,$delim);
			if($intd)
				$d = (int)$d;
			
			return $d.' '.Date::indoMonth($m,$full).' '.$y;
		}
		
		// mengubah format tanggal dari yyyy-mm-dd menjadi format inggris
		function formatDateEng($ymd,$comma=true,$dmy=false,$delim='-') {
			if($ymd == '')
				return '';
			if($ymd == 'null')
				return 'null';
			
			list($d,$m,$y) = CStr::splitDate($ymd,$dmy,$delim);
			
			$abb = 'th';
			if($d == 1)
				$abb = 'st';
			else if($d == 2)
				$abb = 'nd';
			else if($d == 3)
				$abb = 'rd';
			
			$time = mktime(0,0,0,$m,$d,$y);
			$date = date('F j, Y',$time);
			
			if($comma)
				return str_replace(',',$abb.',',$date);
			else
				return str_replace(',',$abb,$date);
		}
		
		// mengubah format tanggal dari dd-mm-yyyy menjadi yyyy-mm-dd dan sebaliknya
		function formatDate($dmy,$idelim='-',$xdelim='-') {
			if($dmy == '')
				return '';
			if($dmy == 'null')
				return 'null';
			
			list($y,$m,$d) = explode($xdelim,substr($dmy,0,10));
			
			return $d.$idelim.$m.$idelim.$y;
		}
		
		// mengubah format tanggal dari yyyy-mm-dd menjadi format indonesia, plus waktu
		function formatDateTimeInd($ymd,$full=true,$hari=false,$delim='-') {
			if($ymd == '')
				return '';
			if($ymd == 'null')
				return 'null';
			
			list($d,$m,$y) = CStr::splitDate($ymd,$dmy,$delim);
			
			$return = (int)$d.' '.Date::indoMonth($m,$full).' '.$y.', '.substr($ymd,11);
			if($hari)
				$return = Date::indoDay(date('N',mktime(0,0,0,$m,$d,$y))).', '.$return;
			
			return $return;
		}
		
		// mengubah format tanggal dari dd-mm-yyyy menjadi yyyy-mm-dd dan sebaliknya, plus waktu
		function formatDateTime($dmy,$idelim='-',$xdelim='-') {
			if($dmy == '')
				return '';
			if($dmy == 'null')
				return 'null';
			
			return CStr::formatDate($dmy,$idelim,$xdelim).' '.substr($dmy,11);
		}
		
		// mengubah format waktu 
		function formatJam($jam,$separator=':') {
			if(empty($jam))
				return '';
			
			$str = str_pad($jam,4,'0',STR_PAD_LEFT);
			return substr($str,0,2).$separator.substr($str,-2);
		}
		
		// mengubah format bilangan
		function formatNumber($num,$dec=0) {
			return number_format($num,$dec,',','.');
		}
	}
?>