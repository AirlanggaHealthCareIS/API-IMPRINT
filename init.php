<?php
	require_once('config.php');
	
	// gunakan helper
	require_once($conf['helpers_dir'].'cstr.class.php');
	require_once($conf['helpers_dir'].'date.class.php');
	require_once($conf['helpers_dir'].'connection.class.php');
	require_once($conf['helpers_dir'].'mail.class.php');
	require_once($conf['helpers_dir'].'notification.class.php');
	require_once($conf['helpers_dir'].'picture.class.php');	
	
	//gunakan model
	require_once($conf['model_dir'].'m_antrian.php');
	require_once($conf['model_dir'].'m_dokter.php');
	require_once($conf['model_dir'].'m_jadwal.php');
	require_once($conf['model_dir'].'m_jenispelayanan.php');
	require_once($conf['model_dir'].'m_keluhan.php');
	require_once($conf['model_dir'].'m_libur.php');
	require_once($conf['model_dir'].'m_notifikasi.php');
	require_once($conf['model_dir'].'m_pasien.php');
	require_once($conf['model_dir'].'m_rating.php');
	require_once($conf['model_dir'].'m_realisasi.php');
	require_once($conf['model_dir'].'m_sms.php');
	require_once($conf['model_dir'].'m_user.php');
	
	// koneksi database
	$conn = Connection::create($conf);
		
	//debug
	$conn->debug = true;
?>