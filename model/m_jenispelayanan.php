<?php
	
	require_once($conf['model_dir'].'m_super.php');
	
	class mJenisPelayanan extends mSuper {
		public $schema 	= 'imprint';
		public $table 	= 'ms_jenispelayanan';
		public $order 	= 'idjenis';
		public $key 	= 'idjenis';
		public $label 	= 'jenis pelayanan';
	}
?>