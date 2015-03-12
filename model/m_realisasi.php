<?php
	
	require_once($conf['model_dir'].'m_super.php');
	
	class mRealisasi extends mSuper {
		public $schema 	= 'imprint';
		public $table 	= 'tr_realisasijadwal';
		public $order 	= 'iddokter';
		public $key 		= 'iddokter, tgl';
		public $label 	= 'realisasi jadwal';
		
		
		function simpleQuery(){
		
			$sql = "select * 
					from ".$this->table()." r
					join ms_dokter d on d.iddokter = r.iddokter";
			
			return $sql;
			
		}
		
	}
?>