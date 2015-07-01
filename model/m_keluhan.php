<?php
	
	require_once($conf['model_dir'].'m_super.php');
	
	class mKeluhan extends mSuper {
		public $schema 		= 'imprint';
		public $table 		= 'ms_keluhan';
		public $order 		= 'idkeluhan';
		public $key 		= 'idkeluhan';
		public $label 		= 'keluhan';


		function simpleQuery(){
		
			$sql = "select * 
					from ".$this->table()." k
					join imprint.ms_dokter d on d.iddokter = k.iddokter";
			
			return $sql;
			
		}
	}
?>