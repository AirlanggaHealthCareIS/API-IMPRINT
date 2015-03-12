<?php
	
	require_once($conf['model_dir'].'m_super.php');
	
	class mJadwal extends mSuper {
		public $schema 	= 'imprint';
		public $table 	= 'ms_jadwal';
		public $order 	= 'iddokter, nohari';
		public $key 		= 'iddokter, nohari';
		public $label 	= 'jadwal';
		
		
		function simpleQuery(){
		
			$sql = "select * 
					from ".$this->table()." j
					join imprint.ms_dokter d on d.iddokter = j.iddokter";
			
			return $sql;
			
		}
		
	}
?>