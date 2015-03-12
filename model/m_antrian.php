<?php
	
	require_once($conf['model_dir'].'m_super.php');
	
	class mAntrian extends mSuper {
		public $schema 		= 'imprint';
		public $table 		= 'tr_antrian';
		public $order 		= 'idantrian';
		public $key 		= 'idantrian';
		public $label 		= 'antrian';
		public $sequence	= 'tr_antrian_idantrian_seq';
		
		function simpleQuery(){
		
			$sql = "select * from ".$this->schema.".v_antrian";
			
			return $sql;
			
		}
		
		function simpleQuery2(){
		
			$sql = "select * from ".$this->table();
			
			return $sql;
			
		}
		
		function nextNoUrut($conn, $iddokter, $tgl){
			$sql = "SELECT COALESCE(MAX(noantrian),0)+1 from ".$this->table()." where iddokter = ".$iddokter." and tgl = '".$tgl."'";
			
			$noantrian = $conn->GetOne($sql);
			
			return $noantrian;
		}
		
		function activateQueue($conn, $iddokter, $noantrian, $tgl, $metode, &$data = ""){
			
			$key		= $iddokter."|".$noantrian."|".$tgl;
			$colkey		= "iddokter,noantrian,tgl";
			$antrian	= $this->getData($conn, $key, $colkey, '', $this->simpleQuery2());
			
			$metode 	= explode('-', $metode);
			$current	= self::getCount($conn, $iddokter, $tgl);
			
			if($metode[0] == 'F'){
				$nourut	= $current['min'];
				$skip	= $current['jumlah'];
			}else if($metode[0] == 'M'){
				$nourut	= $current['min'] + $metode[1];
				$skip	= $current['jumlah'] - $metode[1];
			}else if($metode[0] == 'L'){
				$nourut	= $current['max']+1;
				$skip	= 0;
			}
			
			$conn->StartTrans();
			
			$sql = "UPDATE ".$this->table." SET noantrian = noantrian+".$skip." WHERE iddokter = ".$iddokter." and tgl = '".$tgl."' and noantrian >= ".$nourut." and statusantrian = '1'";
			$conn->Execute($sql);
			
			$err = $conn->ErrorNo();
			
			if($err == 0){
				
				$record					= array();
				$record['noantrian']	= $nourut;
				$record['statusantrian']= '1';
				
				list($err, $msg)	= $this->update($conn, $record, $antrian['idantrian']);
				
				if($err == 0){
					$data	= $this->getData($conn, $antrian['idantrian']);
				}
			}
			
			$conn -> CompleteTrans();
			
			return array($err, $msg);
		}
		
		static function getCount($conn, $iddokter, $tgl){
			$sql = "select count(*) as jumlah, min(noantrian) as min, max(noantrian) as max from ".$this->table." where iddokter = ".$iddokter." and tgl = '".$tgl."' and statusantrian = '1'";
			
			return $conn-> GetRow($sql);
		}
		
		function getEstimationTime($userid_dokter, $arr_keluhan){
			
		}
	}
?>