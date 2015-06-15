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
		
		function simpleQuery3(){
		
			$sql = "select * from ".$this->schema.".v_riwayat";
			
			return $sql;
			
		}
		
		function nextNoUrut($conn, $iddokter, $tgl){
			$sql = "SELECT COALESCE(MAX(noantrian),0)+1 from ".$this->table()." where iddokter = ".$iddokter." and tgl = '".$tgl."'";
			
			$noantrian = $conn->GetOne($sql);
			
			return $noantrian;
		}
		
		function getCount($conn, $iddokter, $tgl){
			$sql = "select count(*) as jumlah, coalesce(min(noantrian),0) as min, coalesce(max(noantrian),0) as max from ".$this->table()." where iddokter = ".$iddokter." and tgl = '".$tgl."' and statusantrian = '1'";
			
			return $conn-> GetRow($sql);
		}
	}
?>