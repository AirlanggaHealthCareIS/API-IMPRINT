<?php
	
	require_once($conf['model_dir'].'m_super.php');
	
	class mRating extends mSuper {
		public $schema 	= 'imprint';
		public $table 	= 'tr_rating';
		public $order 	= 'iddokter,userid';
		public $key 	= 'iddokter,userid';
		public $label 	= 'rating';
		
	}
?>