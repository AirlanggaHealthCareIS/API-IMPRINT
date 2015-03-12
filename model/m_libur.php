<?php
	
	require_once($conf['model_dir'].'m_super.php');
	
	class mLibur extends mSuper {
		public $schema 	= 'imprint';
		public $table 	= 'ms_libur';
		public $order 	= 'iddokter';
		public $key 		= 'iddokter, tgl';
		public $label 	= 'liburl';
		
		
		function simpleQuery(){
		
			$sql = "select *, extract(dow from tgl) as day
					from ".$this->table()." l
					join imprint.ms_dokter d on d.iddokter = l.iddokter";
			
			return $sql;
			
		}
		
	}
?>