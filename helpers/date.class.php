<?php
	class Date {
		// nama hari di bahasa indonesia
		function arrayDay($full=true) {
			$hari = array();
			
			if($full) {
				$hari[1] = 'Senin';
				$hari[2] = 'Selasa';
				$hari[3] = 'Rabu';
				$hari[4] = 'Kamis';
				$hari[5] = 'Jumat';
				$hari[6] = 'Sabtu';
				$hari[0] = 'Minggu';
			}
			else {
				$hari[1] = 'Sen';
				$hari[2] = 'Sel';
				$hari[3] = 'Ra';
				$hari[4] = 'Kam';
				$hari[5] = 'Jum';
				$hari[6] = 'Sab';
				$hari[0] = 'Ming';
			}
			
			return $hari;
		}
		
		function indoDay($nhari,$full=true) {
			$hari = self::arrayDay();
			
			return $hari[$nhari];
		}
		
		// nama bulan di bahasa indonesia
		function arrayMonth($full=true, $romawi = false) {
			$bulan = array();
			
			if($romawi){
				$bulan[1] = 'I';
				$bulan[2] = 'II';
				$bulan[3] = 'III';
				$bulan[4] = 'IV';
				$bulan[5] = 'V';
				$bulan[6] = 'VI';
				$bulan[7] = 'VII';
				$bulan[8] = 'VIII';
				$bulan[9] = 'IX';
				$bulan[10] = 'X';
				$bulan[11] = 'XI';
				$bulan[12] = 'XII';
			}
			else if($full) {
				$bulan[1] = 'Januari';
				$bulan[2] = 'Pebruari';
				$bulan[3] = 'Maret';
				$bulan[4] = 'April';
				$bulan[5] = 'Mei';
				$bulan[6] = 'Juni';
				$bulan[7] = 'Juli';
				$bulan[8] = 'Agustus';
				$bulan[9] = 'September';
				$bulan[10] = 'Oktober';
				$bulan[11] = 'Nopember';
				$bulan[12] = 'Desember';
			}
			else {
				$bulan[1] = 'Jan';
				$bulan[2] = 'Peb';
				$bulan[3] = 'Mar';
				$bulan[4] = 'Apr';
				$bulan[5] = 'Mei';
				$bulan[6] = 'Jun';
				$bulan[7] = 'Jul';
				$bulan[8] = 'Agu';
				$bulan[9] = 'Sep';
				$bulan[10] = 'Okt';
				$bulan[11] = 'Nop';
				$bulan[12] = 'Des';
			}
			
			return $bulan;
		}
		
		function indoMonth($nbulan,$full=true, $romawi = false) {
			$bulan = self::arrayMonth($full, $romawi);
			
			return $bulan[(int)$nbulan];
		}
		
		// dari tanggal excel
		function fromOADate($oadate) {
			return ($oadate-25510)*(60*60*24); // jam 07:00:00 
		}
				
		function indoDate($date, $time = false){
			$timestamp = '';
			if($time){
				$date = explode(" ", $date);
				$timestamp = $date[1];
				$date = $date[0];
			}
			$tanggal=explode("-",$date);
			$tahun=$tanggal[0];
			$bulan=$tanggal[1];
			$hari=$tanggal[2];
			return $hari." ".self::indoMonth($bulan)." ".$tahun."  ".$timestamp;
		}
		
		function dbDate($date){
			$tanggal=explode("-",$date);
			$tahun=$tanggal[2];
			$bulan=$tanggal[1];
			$hari=$tanggal[0];
			
			return $tahun."-".$bulan."-".$hari;
		}
		
	}
?>