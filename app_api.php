<?php
	ini_set('display_errors',1);
	
	require_once("init.php");
	
	$conn->debug = false;
	
	$cSuper	= new cSuper;
	$cSuper->execute($conn, $_REQUEST['f'], $_REQUEST['p'],$_REQUEST['q']);
	
	
	class cSuper {
		
		function execute($conn, $f, $p, $q = ''){
			
			//helper object			
			$CStr		= new CStr;
			$Notif 		= new Notification;
			$Date		= new Date;
			$Picture 	= new Picture;
			
			//model object
			$mAntrian 	= new mAntrian;
			$mDokter	= new mDokter;
			$mJadwal 	= new mJadwal;
			$mJenis		= new mJenisPelayanan;
			$mKeluhan	= new mKeluhan;
			$mLibur		= new mLibur;
			$mNotif 	= new mNotifikasi;
			$mPasien	= new mPasien;
			$mRating	= new mRating;
			$mRealisasi	= new mRealisasi;
			$mSMS		= new mSMS;
			$mUser		= new mUser;
			
			$f = $CStr->removeSpecial($f);
			$p = $CStr->removeSpecialAll($p);			
			$q = $CStr->removeSpecial($q);
			
			
			if($f == "notify"){
				$result = $Notif->send("APA91bEgQP7RoLV1nliFuyH06vrjMWoQF67w6qmKaBlIn8pU9n39d-hhPQ34diqFxReJb5iqiHJMnypW4KcJvRHs1sMbPgEQmfx4OfMPAXcUy3gJZkHKLfRYUm6X_YY9rsx1bSP1h5XkS9hFQKUp8hR6MS2cqqHMkQ", "hai");
				$result = json_decode($result, true);
				print_r($result);
				echo $_GET['callback']."(".json_encode(array($result['failure'])).")";
			}
			else if($f == "register"){//(v)
				
				//array user
				$rec					= Array();
				$rec['notelp'] 			= $p[0];
				$rec['password']		= md5($p[1]);
				$rec['nama']			= $p[2];
				$rec['idlevel']			= $p[3];
				$rec['kodeverifikasi']	= $CStr->generateRandomString(6);
				$rec['isappuser']		= '1';
				
				
				//pengecekan email -> tidak boleh menggunakan email yang sama lebih dari sekali
				$cek 	= $mUser->getData($conn, $p[0], 'notelp');
				
				if(!empty($cek)){
					
					$err	= 1;
					$msg	= "No.Hp ".$p[0]." telah terdaftar sebelumnya. Silakan gunakan nomor lainnya.";
				
				}else{
					
					$conn->StartTrans();
					
					//insert user
					list($err, $msg) 	= $mUser->insert($conn, $rec, $userid);
					
					if($err == 0){
						$body 	= 'Halo, '.$p[2].'. Terima kasih Anda telah melakukan pendaftaran IMPRINT. Kode Verifikasi Anda : '.$rec['kodeverifikasi'];
						
						list($e, $m)	= $mSMS->kirim($conn, $rec['notelp'], $body);
					}
					
					$conn->CompleteTrans();
				}
				
				echo $_GET['callback']."(".json_encode(array($err, $msg)).")";
				
			}
			else if($f == "verifikasi"){//(v)
				
				$cek 	= $mUser->getData($conn, $p[0], 'notelp');
				
				if(empty($cek)){
					$err = true;
					$msg = "Pengguna tidak ditemukan. Silakan melakukan pendaftaran terlebih dahulu.";
				}
				else if($cek['kodeverifikasi'] != $p[1]){
					$err = true;
					$msg = "Kode Verifikasi tidak sesuai";
				}
				else{
					$record		= array();
					$record['isverified']	= '1';
					
					list($err, $msg) = $mUser->update($conn, $record, $p[0],'notelp');
					
					if($err == 0)
						$msg = "Akun berhasil diverifikasi. Silakan login.";
				}
				
				echo $_GET['callback']."(".json_encode(array($err, $msg)).")";
			}
			else if($f == "login"){//(v)
				
				$notelp		= $p[0];
				$password	= $p[1];
				$regid		= $p[2];
				
				//pengecekan no telp
				$cek 	= $mUser->getData($conn, $notelp, 'notelp');
				
				//kurang pengecekan verifikasi dari user
				if(empty($cek)){
					$err	= 1;
					$msg	= "Pengguna tidak ditemukan. Silakan mendaftar terlebih dahulu.";
				}
				else if($cek['password'] != md5($password) ){
					$err	= 1;
					$msg	= "Password Anda salah. Periksa kembali password anda";
				}
				else if($cek['isverified'] != '1' ){
					$err	= 1;
					$msg	= "Silakan konfirmasi terlebih dahulu menggunakan kode yang telah kami kirimkan ke nomor Anda.";
				}
				else{
					$err	= 0;
					$msg	= "Anda berhasil login. Selamat datang ".$cek['nama'];
					
					$record					= array();
					$record['gcmregid']		= $regid;
					$record['token']		= $mUser->generateToken();
					
					list($e, $m)	= $mUser->update($conn, $record, $notelp, 'notelp');			
				}
				
				$res	= array();
				$res[]	= $err;
				$res[]	= $msg;
				if($err == 0){
					$res[]	= $mUser->getData($conn, $notelp, 'notelp');
					unset($res[2]['password']);
					unset($res[2]['gcmregid']);
					if(is_readable("images/thumbnail/".$res[2]['userid'].".jpg"))
						$res[] 	= "http://".$_SERVER['HTTP_HOST']."/API/images/thumbnail/".$res[2]['userid'].".jpg";
					else
						$res[]	= null;
				}
				
				echo $_GET['callback']."(".json_encode($res).")";				
			}
			else if($f == "logout"){//(v)
				
				$record				= array();
				$record['gcmregid']	= 'null';
				$record['token']	= 'null';
				
				list($e, $m)	= $mUser->update($conn, $record, $p[0]);	
				
				$res	= array();
				$res[]	= $err;
				$res[]	= $msg;
				
				
				echo $_GET['callback']."(".json_encode($res).")";
				
			}
			else if($f == "gethints"){//(v)
				
				$data	= $mUser->getData($conn, $p[0],'notelp');
				
				if(empty($data)){
					$err	= 1;
					$msg	= "User tidak ditemukan";
				}
				else{
					$err	= 0;
					$msg	= "Hints berhasil diunduh";
				}
				
				$res	= array();
				$res[]	= $err;
				$res[]	= $msg;
				$res[]	= $data['hintquestion'];
				
				echo $_GET['callback']."(".json_encode($res).")";
				
			}
			else if($f == "passforget"){//(v)
				
				$data	= $mUser->getData($conn, $p[0],'notelp');
				
				if(empty($data)){
					$err = 1;
					$msg = "Pengguna tidak ditemukan";
				}
				else if(strtolower($data['hintanswer']) != strtolower($p[1])){
					$err = 1;
					$msg = "Jawaban Anda tidak sesuai";
				}
				else{
					
					$newpass	= $CStr->generateRandomString(6);
					
					$record				= array();
					$record['password']	= md5($newpass);
					
					list($e, $m)	= $mUser->update($conn, $record, $p[0],'notelp');	
					
					//kirim sms dengan isi password baru
					$body 	= 'Permintaan password baru. Password baru Anda : '.$newpass;
					
					list($e, $m)	= $mSMS->kirim($conn, $data['notelp'], $body);
					
					$err = 0;
					$msg = "Password berhasil diubah dan dikirim ke ".$data['notelp'];
					
				}
				
				$res	= array();
				$res[]	= $err;
				$res[]	= $msg;
				
				echo $_GET['callback']."(".json_encode($res).")";
				
			}
			else if($f == "updatetoken"){
				
				list($err, $msg, )	= $mUser->cekToken($conn, $p[0],$p[1],$p[2]);
				
				if($err == 0){
					
					$token	= $mUser->generateToken();
					
					$record				= array();
					$record['token']	= $token;
					
					list($err, $msg)	= $mUser->update($conn, $record, $p[1]);	
				}
				
				$res	= array();
				$res[]	= $err;
				$res[]	= $msg;
				if($err == 0)
					$res[]	= $token;
				
				echo $_GET['callback']."(".json_encode($res).")";
				
			}
			else if($f == "profiledetail"){
				
				list($err, $msg, $data)	= $mUser->cekToken($conn, $p[0],$p[1],$p[2]);
				
				if($err == 0){
					//jika tidak ada user lain yang dicari maka kembalikan dirinya sendiri
					if(!empty($p[3]))					
						$data	= $mUser->getData($conn, $p[3]);
					
					if(empty($data)){
						$err 	= 1;
						$msg	= "User tidak ditemukan";
					}
				}
				
				$res	= array();
				$res[]	= $err;
				$res[]	= $msg;
				if($err == 0){
					$res[]	= $data;
					unset($res[2]['password']);
					if(is_readable("images/thumbnail/".$res[2]['userid'].".jpg"))
						$res[] 	= "images/thumbnail/".$res[2]['userid'].".jpg";
					else
						$res[]	= null;
				}
				
				echo $_GET['callback']."(".json_encode($res).")";
				
			}
			else if($f == "listjenispelayanan"){//(v)
				
				list($err, $msg,)	= $mUser->cekToken($conn, $p[0],$p[1],$p[2]);
				
				if($err == 0){
					list($a_jenis, $err, $msg) = $mJenis->getList($conn);
				}
				$res	= array();
				$res[]	= $err;
				$res[]	= $msg;
				if($err == 0)
					$res[]	= $a_jenis;
				
				echo $_GET['callback']."(".json_encode($res).")";
				
			}
			else if($f == "updateprofile"){//(v)
				//$conn->debug = true;
				list($err, $msg, $data)	= $mUser->cekToken($conn, $p[0],$p[1],$p[2]);
				
				if($err == 0){
					$conn->StartTrans();
					
					$record					= array();
					if(!empty($p[3]))
						$record['notelp']		= $p[3];
					if(!empty($p[4]))
						$record['password']		= md5($p[4]);
					if(!empty($p[5]))
						$record['hintquestion']	= $p[5];
					if(!empty($p[6]))
						$record['hintanswer']	= $p[6];
					if(!empty($p[7]))
						$record['nama']			= $p[7];
					if(!empty($p[8]))
						$record['alamat']		= $p[8];
					
					list($err, $msg)	= $mUser->update($conn, $record, $p[1]);	
					
					if($err == 0 && $data['idlevel'] == 2){
						if(!empty($p[9]))
							$record['nip']		= $p[9];
						if(!empty($p[10]))
							$record['idjenis']	= $p[10];
						if(!empty($p[11]))
							$record['longi']	= $p[11];
						if(!empty($p[12]))
							$record['lat']		= $p[12];
						if(!empty($p[13]))
							$record['alamat_praktek']	= $p[13];
						
						list($err, $msg)	= $mDokter->update($conn, $record, $p[1],'userid');
					}
				}
				
				$conn->CompleteTrans();
				$res	= array();
				$res[]	= $err;
				$res[]	= $msg;
				if($err == 0){
					$res[]	= $mUser->getData($conn, $p[1]);
					unset($res[2]['password']);
					unset($res[2]['gcmregid']);
				}
				
				echo $_GET['callback']."(".json_encode($res).")";
			}
			else if($f == "updatephoto"){//(v)
				
				list($err, $msg, $data)	= $mUser->cekToken($conn, $p[0],$p[1],$p[2]);
				
				if($err == 0){
					//real-sized photo
					$g_source	= $_FILES['file'];
					$g_size['h']= 500;
					$g_size['w']= 500;
					$g_folder	= "images/gallery/".$p[1].".jpg";
					
					$file = 'log.txt';
					// Open the file to get existing content
					$current = file_get_contents($file);
					// Append a new person to the file
					$current .= "p ".$p."\n file";
					foreach($_FILES as $k => $v){
					$current .= $k." => ".$v."\n<br\>";
					}
					// Write the contents back to the file
					file_put_contents($file, $current);
					
					list($err, $msg) = $Picture->upload($g_source, $g_size, $g_folder);
					
					if($err == 0){
						//thumbnail untuk list
						$t_source	= $_FILES['file'];
						$t_size['h']= 100;
						$t_size['w']= 100;
						$t_folder	= "images/thumbnail/".$p[1].".jpg";
						
						list($err, $msg) = $Picture->upload($t_source, $t_size, $t_folder);
					}
				}
				
				
				echo $_GET['callback']."(".json_encode(array($err, $msg)).")";
				
			}
			else if($f == "searchdoctor"){//(v)
				//$conn->debug=true;
				list($err, $msg, $data)	= $mUser->cekToken($conn, $p[0],$p[1],$p[2]);
				
				if($err == 0){
					
					//ratingnya <- masih salah
					$filter		= array();
					$filter[]	= "userid = ".$p[1];
					
					list($_rating, $err, $msg)	= $mRating->getList($conn, $filter);
					
					$a_rating = Array();
					foreach($_rating as $rt){
						$a_rating[$rt['iddokter']] = $rt['rating'];
					}
					
					//dokternya
					$keyword	= $p[3];
					$keyword	= explode(' ',$keyword);
					
					$field		= array('nama', 'alamat_praktek', 'namajenis');
					
					$filter		= array();
					
					foreach($keyword as $k){
						foreach($field as $f){
							$filter[] = "lower(".$f.") like '%".$k."%'";
						}
					}
					
					$dow	= date('N');
					if($dow == 7) $dow = 0;
					
					$filt	= array();
					$filt[]	= "(".implode(" or ", $filter).")";
					$filt[]	= "(nohari = ".$dow." or nohari is null)";
					
					if(empty($p[4]))
						$order	= 'nama';
					else{
						if($p[4] == 'r')
							$order = 'rating desc';
						else if($p[4] == 'a')
							$order = '(banyakantrian-sekarang) desc';
						else if($p[4] == 'j')
							$order = 'distance';
					}
					
					list($a_dokter, $err, $msg)	= $mDokter->getList($conn, $filt, $order,'', '', $mDokter->simpleQuery2($p[5], $p[6]));
					
					foreach($a_dokter as $k => $v){
						if(is_readable("images/thumbnail/".$v['userid'].".jpg"))
							$a_dokter[$k]['urlphoto'] =  "http://".$_SERVER['HTTP_HOST']."/API/images/thumbnail/".$v['userid'].".jpg";
						else
							$a_dokter[$k]['urlphoto'] = null;
						
						$a_dokter[$k]['nohari'] 	= null;
						$a_dokter[$k]['ratinguser'] = $a_rating[$v['iddokter']];
					}
				}
				
				$res 	= array();
				$res[]	= $err;
				$res[]	= $msg;
				if(!empty($a_dokter))
					$res[]	= $a_dokter;
				
				echo $_GET['callback']."(".json_encode($res).")";				
			}
			else if($f == "scheduledoctor"){//(v)
				
				list($err, $msg, $data)	= $mUser->cekToken($conn, $p[0],$p[1],$p[2]);
				
				if($err == 0){
					$filter		= array();
					if(!empty($p[3]))
						$filter[]	= "j.iddokter = ".$p[3];
					
					list($a_jadwal, $err, $msg)	= $mJadwal->getList($conn, $filter);
					
					foreach($a_jadwal as $k => $v){
						$a_jadwal[$k]['namahari'] = $Date->indoDay($v['nohari']);
					}
				}
				
				$res 	= array();
				$res[]	= $err;
				$res[]	= $msg;
				if(!empty($a_jadwal))
					$res[]	= $a_jadwal;
				
				echo $_GET['callback']."(".json_encode($res).")";
				
			}
			else if($f == "queuepasien"){//(v)
				//$conn->debug = true;
				list($err, $msg, $data)	= $mUser->cekToken($conn, $p[0],$p[1],$p[2]);
				
				if($err == 0){
					
					//antriannya
					$key		= $p[1];
					$colkey		= "useridpasien";
					$a_antrian	= $mAntrian->getData($conn, $key, $colkey);
					
					if(!empty($a_antrian)){
						//dokter
						$dow 		= date('N',strtotime($a_antrian['tgl']));
						if($dow == 7)
							$dow = 0;
						$key		= $a_antrian['iddokter']."|".$dow;
						$colkey		= "iddokter,nohari";
						$a_dokter	= $mDokter->getData($conn, $key, $colkey);
						
						//keluhan
						$filter				= Array();
						$filter[]			= "k.iddokter = ".$a_antrian['iddokter'];
						list($a_keluhan,)	= $mKeluhan->getList($conn, $filter);
						
						//penggabungan
						$_keluhan		= explode('|',$a_antrian['keluhan']);
						$_namakeluhan	= Array();
						foreach($a_keluhan as $k => $v){
							if(in_array($v['idkeluhan'],$_keluhan))
								$_namakeluhan[] = $v['namakeluhan'];
						}
												
						$a_antrian['hari']			= $Date->indoDay($dow);
						$a_antrian['tgl']			= $Date->indoDate($a_antrian['tgl']);
						$a_antrian['namakeluhan']	= implode(', ',$_namakeluhan);
						$a_antrian['nama']			= $a_dokter['nama'];
						$a_antrian['sekarang']		= $a_dokter['sekarang'];
						}
				}
				
				$res 	= array();
				$res[]	= $err;
				$res[]	= $msg;
				if(!empty($a_antrian))
					$res[]	= $a_antrian;
				
				echo $_GET['callback']."(".json_encode($res).")";
				
			}
			else if($f == "queuedoctor"){//(v)
				//$conn->debug = true;
				list($err, $msg, $data)	= $mUser->cekToken($conn, $p[0],$p[1],$p[2]);
				
				if($err == 0){
					
					//dokter
					$dokter	= $mDokter->getData($conn, $p[1], 'userid');
					
					$filter	= array();
					$filter[]	= "iddokter = ".$dokter['iddokter'];
					$filter[]	= "tgl = '".$p[3]."'";
					
					list($a_antrian,)	= $mAntrian->getList($conn, $filter);
					foreach($a_antrian as $k => $v){
						if(is_readable("images/thumbnail/".$v['useridpasien'].".jpg"))
							$a_antrian[$k]['urlphoto'] =  "http://".$_SERVER['HTTP_HOST']."/API/images/thumbnail/".$v['useridpasien'].".jpg";
						else
							$a_antrian[$k]['urlphoto'] = null;
					}
				}
				
				$res 	= array();
				$res[]	= $err;
				$res[]	= $msg;
				if(!empty($a_antrian))
					$res[]	= $a_antrian;
				
				echo $_GET['callback']."(".json_encode($res).")";
				
			}
			else if($f == "doctorqueue"){//(v)
				//$conn->debug = true;
				list($err, $msg, $data)	= $mUser->cekToken($conn, $p[0],$p[1],$p[2]);
				
				if($err == 0){
					
					//antrian
					$result = $mAntrian->getCount($conn, $p[4], $p[3]);
					
					//jadwal
					$dow	= date('N',strtotime($p[3]));
					if($dow==7)	$dow = 0;
					$key	= $p[4].'|'.$dow;
					$colkey	= 'j.iddokter, nohari';
					$jadwal = $mJadwal->getData($conn, $key, $colkey);
					$result['islibur'] = $jadwal['islibur'];
				}
				
				$res 	= array();
				$res[]	= $err;
				$res[]	= $msg;
				if(!empty($result))
					$res[]	= $result;
				
				echo $_GET['callback']."(".json_encode($res).")";
				
			}
			else if($f == "listcomplaint"){//(v)
				
				list($err, $msg, $data)	= $mUser->cekToken($conn, $p[0],$p[1],$p[2]);
				
				if($err == 0){
					$filter		= array();
					if(!empty($p[3]))
						$filter[]	= "userid = ".$p[3];
					if(!empty($p[4]))
						$filter[]	= "k.iddokter = ".$p[4];
					
					list($a_keluhan, $err, $msg)	= $mKeluhan->getList($conn, $filter);
				}
				
				$res 	= array();
				$res[]	= $err;
				$res[]	= $msg;
				if(!empty($a_keluhan))
					$res[]	= $a_keluhan;
				
				echo $_GET['callback']."(".json_encode($res).")";
				
			}
			else if($f == "registerdoctor"){//(v)
				//$conn->debug = true;
				list($err, $msg, $data)	= $mUser->cekToken($conn, $p[0],$p[1],$p[2]);
				
				if($err == 0){					
					$noantrian  = $mAntrian->nextNoUrut($conn, $p[3], $p[4]);
					
					//pengecekan antrian pasien
					$filter		= array();
					$filter[]	= "useridpasien = ".$p[1];
					list($cek, $err, $msg) = $mAntrian->getList($conn, $filter);
					
					if(empty($cek)){
					
						//pengecekan jadwal dokter
						$dow		= date('N',strtotime($p[4]));
						$key		= $p[3]."|".$dow;
						$colkey		= "d.iddokter,nohari";
						$cek2 = $mJadwal->getData($conn, $key, $colkey);
						
						if(!empty($cek2) && $cek2['islibur'] != '1'){					
							$record					= array();
							$record['iddokter']		= $p[3];		
							$record['userid']		= $p[1];
							$record['tgl']			= $p[4];
							$record['listkeluhan']	= $p[5];
							$record['noantrian']	= $noantrian;
							$record['statusantrian']= '1';
							
							list($err, $msg) 		= $mAntrian->insert($conn, $record, $idantrian);
							
							if($err == 0){
								$data = $mAntrian->getData($conn, $idantrian);
								
								//notifikasi ke dokter
								$dokter = $mDokter->getData($conn,$p[3], 'iddokter');
								$user	= $mUser->getData($conn, $p[1]);
								$isi	= $user['nama']." mulai mendaftar dalam antrian Anda untuk tanggal ".$Date->indoDate($p[4]);
								$message= "Pasien baru";
								
								$r				= array();
								$r['userid']	= $dokter['userid'];
								$r['isi']		= $isi;
								
								list($e, $m) = $mNotif->insert($conn, $r, $id);								
								$result = $Notif->send($dokter['gcmregid'], $message);
							}
						}
						else{
							$err = 1;
							$msg = "Dokter yang Anda pilih tidak membuka praktek pada tanggal tersebut.";
						}
					}else{
						$err = 1;
						$msg = "Anda tidak dapat mengantri dalam dua antrian berbeda.";
					}
				}
				
				$res 	= array();
				$res[]	= $err;
				$res[]	= $msg;
				if($err == 0){
					$res[] = $data['noantrian'];
					$res[] = $data['estimasi_waktumasuk'];
				}
				$res[] = $result;
				
				echo $_GET['callback']."(".json_encode($res).")";
			}			
			else if($f == "canceldoctor"){
				
				list($err, $msg, $data)	= $mUser->cekToken($conn, $p[0],$p[1],$p[2]);
				
				if($err == 0){
					
					$record						= array();
					$record['statusantrian']	= '3';
					
					list($err, $msg)	= $mAntrian->update($conn, $record, $p[3]);
					
					if($err == 0){
						//notifikasi ke pasien selanjutnya
						$antrian 	= $mAntrian->getData($conn, $p[3],'','',$mAntrian->simpleQuery2());
						
						$filter	= array();
						$filter[]	= "iddokter = ".$antrian['iddokter'];
						$filter[]	= "tgl = '".$antrian['tgl']."'";
						$filter[]	= "noantrian > ".$antrian['noantrian'];
						
						list($after_antrian,) = $mAntrian->getList($conn, $filter);
						
						if(!empty($after_antrian)){
							$isi	= "Pelayanan Anda dipercepat dari jadwal semula karena ketidakhadiran salah satu pasien";
							$message= "Pelayanan Anda dipercepat dari jadwal semula. Silakan periksa kembali antrian Anda.";
						
							$regIds = array();
							foreach($after_antrian as $a){
																
								$r				= array();
								$r['userid']	= $a['useridpasien'];
								$r['isi']		= $isi;
								
								list($e, $m) = $mNotif->insert($conn, $r, $id);								
								
								
								$regIds[] = $a['gcmregid'];
							}
							
							$result = $Notif->send($regIds, $message);
						}
					}
				}
				
				$res 	= array();
				$res[]	= $err;
				$res[]	= $msg;
				$res[]	= $result;
				
				echo $_GET['callback']."(".json_encode($res).")";
			}
			else if($f == "suspendqueue"){
				list($err, $msg, $data)	= $mUser->cekToken($conn, $p[0],$p[1],$p[2]);
				
				if($err == 0){
					
					$record						= array();
					$record['statusantrian']	= '2';
					
					list($err, $msg)	= $mAntrian->update($conn, $record, $p[3]);
					
					if($err == 0){
						//notifikasi ke pasien selanjutnya
						$antrian 	= $mAntrian->getData($conn, $p[3],'','',$mAntrian->simpleQuery2());
						
						$filter	= array();
						$filter[]	= "iddokter = ".$antrian['iddokter'];
						$filter[]	= "tgl = '".$antrian['tgl']."'";
						$filter[]	= "noantrian > ".$antrian['noantrian'];
						
						list($after_antrian,) = $mAntrian->getList($conn, $filter);
						
						if(!empty($after_antrian)){
							$isi	= "Pelayanan Anda dipercepat dari jadwal semula karena ketidakhadiran salah satu pasien";
							$message= "Pelayanan Anda dipercepat dari jadwal semula. Silakan periksa kembali antrian Anda.";
						
							$regIds = array();
							foreach($after_antrian as $a){
																
								$r				= array();
								$r['userid']	= $a['useridpasien'];
								$r['isi']		= $isi;
								
								list($e, $m) = $mNotif->insert($conn, $r, $id);								
								
								
								$regIds[] = $a['gcmregid'];
							}
							
							$result = $Notif->send($regIds, $message);
						}
					}
				}
				
				$res 	= array();
				$res[]	= $err;
				$res[]	= $msg;
				$res[]	= $result;
				
				echo $_GET['callback']."(".json_encode($res).")";
			}
			else if($f == "activatequeue"){
				//$conn->debug = true;
				list($err, $msg, $data)	= $mUser->cekToken($conn, $p[0],$p[1],$p[2]);
				
				if($err == 0){
					//antrian
					$antrian	= $mAntrian->getData($conn, $p[3]);
					
					//dokter
					$dokter		= $mDokter->getData($conn, $antrian['iddokter'],'iddokter');
					
					//penanganan aktivasi kembali antrian berdasarkan metode
					$metode		= explode('-',$dokter['suspendqueue']);
					
					$kondition	= $mAntrian->getCount($conn, $antrian['iddokter'], $antrian['tgl']);
					$notify	= false;
					
					if($metode[0] == 'L'){
						$record	= array();
						$record['statusantrian'] 	= '1';
						$record['noantrian']		= $kondition['MAX']+1;
						
						list($err, $msg)	= $mAntrian->update($conn, $record, $p[3]);
					}
					else if($metode[0] == 'F'){
						$notify = true;
						
						if($kondition['MIN'] < $antrian['noantrian'])
							$noantrian	= $antrian['noantrian'];
						else
							$noantrian = $kondition['MIN'];
					}
					else if($metode[0] == 'M'){
						$notify = true;
						
						if(($kondition['MIN']+$metode[1]) < $antrian['noantrian'])
							$noantrian	= $antrian['noantrian'];
						else
							$noantrian = $kondition['MIN']+$metode[1];
					}
					
					if($notify){
						//update antrian selanjutnya
						$sql_update = "update ".$mAntrian->table()." set noantrian = (noantrian+1) where noantrian > ".$noantrian." and iddokter = ".$antrian['iddokter']." and tgl = '".$antrian['tgl']."'";
						$conn->execute($sql_update);
						
						$err = $conn->ErrorNo();
						if($err == 0){
							$record = array();
							$record['statusantrian']	= '1';
							$record['noantrian']		= $noantrian;
							
							list($err, $msg)	= $mAntrian->update($conn, $record, $antrian['idantrian']);
							
							if($err == 0){
								//otifikasi pengunduran
								$filter		= array();
								$filter[]	= "iddokter = ".$antrian['iddokter'];
								$filter[]	= "tgl ='".$antrian['tgl']."'";
								$filter[]	= "noantrian > ".$noantrian;
								list($a_antri, )	= $mAntrian->getList($conn, $filter);
								
								if(!empty($a_antri)){
									$conn->StartTrans();
									
									foreach($a_antri as $k => $v){
										$record	= array();
										$record['userid']	= $v['userid'];
										$record['isi']		= "Pelayanan Anda ditunda beberapa waktu dari jadwal semula karena kembalinya salah satu pasien. Silakan cek status antrian Anda. Maaf atas ketidaknyamanan yang ditimbulkan.";
										
										list($err, $msg)	= $mNotif->insert($conn,$record);
										
										if($err == 0 && !empty($v['gcmregid']))
											$result = $Notif->send($v['gcmregid'], "Penundaan pelayanan. Silakan cek status Anda.");
									}
									
									$conn->CompleteTrans();
								}
								
							}
						}
					}
										
				}
				
				$res 	= array();
				$res[]	= $err;
				$res[]	= $msg;
				
				echo $_GET['callback']."(".json_encode($res).")";
			}
			else if($f == "call"){
				list($err, $msg, $data)	= $mUser->cekToken($conn, $p[0],$p[1],$p[2]);
				
				if($err == 0){
					//antrian
					$antrian = $mAntrian->getData($conn, $p[3]);
					
					$result = $Notif->send($antrian['gcmregid'], $antrian['nama']." dipersilakan masuk ke Ruang Pelayanan.");
					$result = json_decode($result, true);
					if($result['failure'] > 0)
						$err = 1;
					else{
						$err = 0;
						$record	= array();
						$record['userid']	= $antrian['useridpasien'];
						$record['isi']		= "Panggilan kepada ".$antrian['nama']." dipersilakan masuk ke Ruang Pelayanan.";
						list($r, $m)	= $mNotif->insert($conn, $record);
					}
					
					$msg = '';
				}
				
				$res 	= array();
				$res[]	= $err;
				$res[]	= $msg;
				
				echo $_GET['callback']."(".json_encode($res).")";
			}
			else if($f == "serve"){
				list($err, $msg, $data)	= $mUser->cekToken($conn, $p[0],$p[1],$p[2]);
				
				if($err == 0){
					
					$record						= array();
					$record['waktumasuk']		= date('H:i:s');
					
					list($err, $msg)	= $mAntrian->update($conn, $record, $p[3]);
				}
				
				$res 	= array();
				$res[]	= $err;
				$res[]	= $msg;
				$res[]	= $result;
				
				echo $_GET['callback']."(".json_encode($res).")";
			}
			else if($f == "finish"){
				list($err, $msg, $data)	= $mUser->cekToken($conn, $p[0],$p[1],$p[2]);
				
				if($err == 0){
					
					$record						= array();
					$record['waktukeluar']		= date('H:i:s');
					$record['statusantrian']	= '4';
					
					list($err, $msg)	= $mAntrian->update($conn, $record, $p[3]);
				}
				
				$res 	= array();
				$res[]	= $err;
				$res[]	= $msg;
				$res[]	= $result;
				
				echo $_GET['callback']."(".json_encode($res).")";
			}
			else if($f == "historytreatment"){
				
				list($err, $msg, $data)	= $mUser->cekToken($conn, $p[0],$p[1],$p[2]);
				
				if($err == 0){
					
					list($a_keluhan, $err, $msg)	= $mKeluhan->getList($conn);
					
					$r_keluhan = array();
					foreach($a_keluhan as $k => $v){
						$r_keluhan[$v['idkeluhan']] = $v['namakeluhan'];
					}
					
					$dokter		= $mDokter->getData($conn, $p[3], 'userid');
					$pasien		= $mPasien->getData($conn, $p[4], 'userid');
					
					$filter		= array();
					$filter[]	= "iddokter = ".$dokter['iddokter'];
					$filter[]	= "idpasien = ".$pasien['idpasien'];
					
					list($a_antrian, $err, $msg)	= $mAntrian->getList($conn, $filter);
					
					foreach($a_antrian as $k => $v){
						$keluhan = explode('|',$v['keluhan']);
						
						$res_keluhan = array();
						foreach($keluhan as $nv){
							$res_keluhan[] = array('idkeluhan' => $nv, 'namakeluhan'=> $r_keluhan[$nv]);
						}
						
						$a_antrian[$k]['listkeluhan'] = $res_keluhan;
					}
				}
				
				$res 	= array();
				$res[]	= $err;
				$res[]	= $msg;
				if(!empty($a_antrian))
					$res[] = $a_antrian;
				
				echo $_GET['callback']."(".json_encode($res).")";
			}
			else if($f == "getschedule"){//(v)
				
				list($err, $msg, $data)	= $mUser->cekToken($conn, $p[0],$p[1],$p[2]);
				
				if($err == 0){
				
					$dokter	= $mDokter->getData($conn, $p[1],'userid');
					
					$filter		= array();
					$filter[]	= "j.iddokter = ".$dokter['iddokter'];
					
					list($a_jadwal, $err, $msg)	= $mJadwal->getList($conn, $filter);
					
					$f_jadwal = array();
					foreach($a_jadwal as $key => $v){
						$f_jadwal[$v['nohari']]	= $v;
					}
				}
				
				$res 	= array();
				$res[]	= $err;
				$res[]	= $msg;
				if($err == 0)
					$res[] = $f_jadwal;
				
				echo $_GET['callback']."(".json_encode($res).")";
			}
			else if($f == "setschedule"){//(v)
				
				list($err, $msg, $data)	= $mUser->cekToken($conn, $p[0],$p[1],$p[2]);
				
				if($err == 0){
					
					$conn->StartTrans();
					
					$dokter	= $mDokter->getData($conn, $p[1],'userid');
					
					foreach($p[3] as $k => $v){
						$key 	= $dokter['iddokter']."|".$v[0];
						$data	= $mJadwal->getData($conn,$key,'j.iddokter,nohari');
						
						$record				= array();
						$record['nohari']	= $v[0];
						$record['iddokter']	= $dokter['iddokter'];
						$record['jam_awal']	= $v[1];
						$record['jam_akhir']= $v[2];
						$record['islibur']	= $v[3];
						
						if(empty($data))
							list($err, $msg)	= $mJadwal->insert($conn, $record);
						else
							list($err, $msg)	= $mJadwal->update($conn, $record,$key);
					}
					
					if($err == 0){
						$filter		= array();
						$filter[]	= "j.iddokter = ".$dokter['iddokter'];
						
						list($a_jadwal, $err, $msg)	= $mJadwal->getList($conn, $filter);
						
						$f_jadwal = array();
						foreach($a_jadwal as $key => $v){
							$v['hari']				= $Date->indoDay($v['nohari'],true);
							$f_jadwal[$v['nohari']]	= $v;
						}
					}
					
					$conn->CompleteTrans();
				}
				
				$res 	= array();
				$res[]	= $err;
				$res[]	= $msg;
				if($err == 0)
					$res[] = $f_jadwal;
				
				echo $_GET['callback']."(".json_encode($res).")";
			}
			else if($f == "getholiday"){//(v)
				
				list($err, $msg, $data)	= $mUser->cekToken($conn, $p[0],$p[1],$p[2]);
				
				if($err == 0){
					
					$dokter	= $mDokter->getData($conn, $p[1],'userid');
					
					$filter		= array();
					$filter[]	= "l.iddokter = ".$dokter['iddokter'];
					
					list($a_libur, $err, $msg)	= $mLibur->getList($conn, $filter);
					foreach($a_libur as $k => $val){
						$a_libur[$k]['tanggal'] = $Date->indoDate($val['tgl']);
						$a_libur[$k]['hari'] = $Date->indoDay($val['day'],true);
					}
				}
				
				$res 	= array();
				$res[]	= $err;
				$res[]	= $msg;
				if($err == 0)
					$res[]	= $a_libur;
				
				echo $_GET['callback']."(".json_encode($res).")";
			}
			else if($f == "setholiday"){//(v)
				
				list($err, $msg, $data)	= $mUser->cekToken($conn, $p[0],$p[1],$p[2]);
				
				if($err == 0){
					
					$dokter	= $mDokter->getData($conn, $p[1],'userid');
					
					if($p[4] == 1){
						//utuk menambahkan hari libur
						$record				= array();
						$record['tgl']		= $Date->dbDate($p[3]);
						$record['iddokter']	= $dokter['iddokter'];
						
						list($err, $msg)	= $mLibur->insert($conn, $record);
					}
					else if($p[4] == 0){
						//untuk pembatalan hari libur
						$key =	$dokter['iddokter']."|".$p[3];
						list($err, $msg)	= $mLibur->delete($conn, $key);
					}	
					
					if($err == 0){
						$filter		= array();
						$filter[]	= "l.iddokter = ".$dokter['iddokter'];
						
						list($a_libur, $err, $msg)	= $mLibur->getList($conn, $filter);
						
						foreach($a_libur as $k => $val){
							$a_libur[$k]['tanggal'] = $Date->indoDate($val['tgl']);
							$a_libur[$k]['hari'] = $Date->indoDay($val['day'],true);
						}
					}
				}
				
				$res 	= array();
				$res[]	= $err;
				$res[]	= $msg;
				if($err == 0)
					$res[] = $a_libur;
				
				echo $_GET['callback']."(".json_encode($res).")";
			}
			else if($f == "getcomplaint"){//(v)
				
				list($err, $msg, $data)	= $mUser->cekToken($conn, $p[0],$p[1],$p[2]);
				
				if($err == 0){
				
					$dokter	= $mDokter->getData($conn, $p[1],'userid');
					
					$filter		= array();
					$filter[]	= "k.iddokter = ".$dokter['iddokter'];
					
					list($a_keluhan, $err, $msg)	= $mKeluhan->getList($conn, $filter);
				}
				
				$res 	= array();
				$res[]	= $err;
				$res[]	= $msg;
				if($err == 0)
					$res[]	= $a_keluhan;
				
				echo $_GET['callback']."(".json_encode($res).")";
			}
			else if($f == "setcomplaint"){//(v)
				
				list($err, $msg, $data)	= $mUser->cekToken($conn, $p[0],$p[1],$p[2]);
				
				if($err == 0){
				
					$dokter	= $mDokter->getData($conn, $p[1],'userid');
					
					$record					= array();
					$record['namakeluhan']	= $p[3];
					$record['iddokter']		= $dokter['iddokter'];
					
					list($err, $msg)	= $mKeluhan->insert($conn, $record);
					
					if($err == 0){
						$filter		= array();
						$filter[]	= "k.iddokter = ".$dokter['iddokter'];
						
						list($a_keluhan, $err, $msg)	= $mKeluhan->getList($conn, $filter);
					}
				}
				
				$res 	= array();
				$res[]	= $err;
				$res[]	= $msg;
				if($err == 0)
					$res[]	= $a_keluhan;
				
				echo $_GET['callback']."(".json_encode($res).")";
			}
			else if($f == "delcomplaint"){//(v)
				
				list($err, $msg, $data)	= $mUser->cekToken($conn, $p[0],$p[1],$p[2]);
				
				if($err == 0){
					
					list($err, $msg)	= $mKeluhan->delete($conn, $p[3]);
					
					if($err == 0){
						$dokter		= $mDokter->getData($conn, $p[1],'userid');
						
						$filter		= array();
						$filter[]	= "k.iddokter = ".$dokter['iddokter'];
						
						list($a_keluhan, $err, $msg)	= $mKeluhan->getList($conn, $filter);
					}
				}
				
				$res 	= array();
				$res[]	= $err;
				$res[]	= $msg;
				if($err == 0)
					$res[]	= $a_keluhan;
				
				echo $_GET['callback']."(".json_encode($res).")";
			}
			else if($f == "setqueue"){
				
				list($err, $msg, $data)	= $mUser->cekToken($conn, $p[0],$p[1],$p[2]);
				
				if($err == 0){
				
					$dokter	= $mDokter->getData($conn, $p[1],'userid');
					
					$record				= array();
					$record['iddokter']	= $dokter['iddokter'];
					$record['tgl']		= date('Y-m-d');
					if($p[3] == 1)
						$record['jam_mulai']	= date('h:I:s');
					else
						$record['jam_akhir']	= date('h:I:s');
					
					if($p[3] == 1)
						list($err, $msg)	= $mRealisasi->insert($conn, $record);
					else{
						$key = $record['iddokter'].'|'.$record['tgl'];
						$colkey = 'iddokter,tgl';
						list($err, $msg)	= $mRealisasi->update($conn, $record, $key, $colkey);
					}
				}
				
				$res 	= array();
				$res[]	= $err;
				$res[]	= $msg;
				
				echo $_GET['callback']."(".json_encode($res).")";
			}
			else if($f == "nextqueue"){
				
				list($err, $msg, $data)	= $mUser->cekToken($conn, $p[0],$p[1],$p[2]);
				
				if($err == 0){
					
				}
				
				$res 	= array();
				$res[]	= $err;
				$res[]	= $msg;
				
				echo $_GET['callback']."(".json_encode($res).")";
			}
			else if($f == "setting"){//(v)
				
				list($err, $msg, $data)	= $mUser->cekToken($conn, $p[0],$p[1],$p[2]);
				
				if($err == 0){
					$conn->StartTrans();
					
					$record					= array();
					$record['showtelp']		= $p[3];
					$record['pemberitahuan']= $p[4];
					
					list($err, $msg)	= $mUser->update($conn, $record, $p[1]);	
					
					if($err == 0 && $data['idlevel'] == 2){
						$record['waktupelayanan']	= $p[5];
						$record['suspendqueue']		= $p[6];
						
						list($err, $msg)	= $mDokter->update($conn, $record, $p[1],'userid');
						
					}
					
					$conn->CompleteTrans();
				}
				
				$res	= array();
				$res[]	= $err;
				$res[]	= $msg;
				if($err == 0){
					$res[] = $mUser->getData($conn, $p[1]);
					unset($res[2]['password']);
					unset($res[2]['gcmregid']);
				}
				
				echo $_GET['callback']."(".json_encode($res).")";
				
			}
			else if($f == "setrating"){//(v)
				
				list($err, $msg, $data)	= $mUser->cekToken($conn, $p[0],$p[1],$p[2]);
				
				if($err == 0){
					
					$key 	= $p[3]."|".$p[1];
					$cek	= $mRating->getData($conn, $key);
					
					$record				= array();
					$record['iddokter']	= $p[3];
					$record['userid']	= $p[1];
					$record['rating']	= $p[4];
					
					if(empty($cek)){
						list($err, $msg)	= $mRating->insert($conn, $record);
					}
					else{
						list($err, $msg)	= $mRating->update($conn, $record, $key);
					}
				}
				
				$res	= array();
				$res[]	= $err;
				$res[]	= $msg;
				
				echo $_GET['callback']."(".json_encode($res).")";
			}
			else if($f == "getNotifikasi"){//(v)
				
				list($err, $msg, $data)	= $mUser->cekToken($conn, $p[0],$p[1],$p[2]);
				
				if($err == 0){
				
					$filter		= array();
					$filter[]	= "userid = ".$p[1];
					
					list($a_notif,)	= $mNotif->getList($conn, $filter,'t_updatetime desc');
					foreach($a_notif as $key => $notif){
						$a_notif[$key]['t_updatetime'] =  $Date->indoDate($notif['t_updatetime'], true);
					}
				}
				
				$res 	= array();
				$res[]	= $err;
				$res[]	= $msg;
				if($err == 0)
					$res[]	= $a_notif;
				
				echo $_GET['callback']."(".json_encode($res).")";
			}
			else if($f == "pasienDetail"){//(v)
				
				list($err, $msg, $data)	= $mUser->cekToken($conn, $p[0],$p[1],$p[2]);
				
				if($err == 0){
				
					//user
					$user	= $mUser->getData($conn, $p[3]);
					
					//antrian user
					$filter		= array();
					$filter[]	= "useridpasien = ".$p[3];
					
					list($antrian,)	= $mAntrian->getList($conn, $filter, '','','',$mAntrian->simpleQuery3());
					
					foreach($antrian as $k=>$v){
						$antrian[$k]['tgl']	= $Date->indoDate($v['tgl']);
					}
										
					if(!empty($user)){
						$data			= array();
						$data['nama']	= $user['nama'];
						$data['notelp']	= $user['notelp'];
						$data['alamatuser']	= $user['alamatuser'];
						if(is_readable("images/thumbnail/".$p[3].".jpg"))
							$data['urlphoto'] =  "http://".$_SERVER['HTTP_HOST']."/API/images/thumbnail/".$p[3].".jpg";
						else
							$data['urlphoto'] = null;
						$data['riwayat']	= $antrian;
					}
				}
				
				$res 	= array();
				$res[]	= $err;
				$res[]	= $msg;
				if(!empty($data))
					$res[]	= $data;
				
				echo $_GET['callback']."(".json_encode($res).")";
			}
			else {
				
				return array(1, "Perintah tidak dikenali.");
				
			}
			
		}
	}	
?>