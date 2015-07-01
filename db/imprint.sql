/*
SQLyog Community Edition- MySQL GUI v8.0 
MySQL - 5.6.25-log : Database - imprint
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

CREATE DATABASE /*!32312 IF NOT EXISTS*/`imprint` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `imprint`;

/*Table structure for table `ms_dokter` */

DROP TABLE IF EXISTS `ms_dokter`;

CREATE TABLE `ms_dokter` (
  `nip` varchar(20) DEFAULT NULL,
  `userid` int(11) DEFAULT NULL,
  `idjenis` int(11) DEFAULT NULL,
  `alamat_praktek` varchar(500) DEFAULT NULL,
  `longi` double DEFAULT NULL,
  `lat` double DEFAULT NULL,
  `t_updatetime` varchar(100) DEFAULT NULL,
  `t_updateuser` varchar(100) DEFAULT NULL,
  `iddokter` int(20) NOT NULL AUTO_INCREMENT,
  `waktupelayanan` time DEFAULT '00:05:00',
  `suspendqueue` varchar(5) DEFAULT 'L',
  `notelp_praktek` varchar(12) DEFAULT NULL,
  PRIMARY KEY (`iddokter`),
  UNIQUE KEY `iddokter` (`iddokter`),
  UNIQUE KEY `idjenis` (`idjenis`),
  UNIQUE KEY `userid` (`userid`),
  CONSTRAINT `FK_ms_dokter` FOREIGN KEY (`idjenis`) REFERENCES `ms_jenispelayanan` (`idjenis`) ON UPDATE CASCADE,
  CONSTRAINT `FK_ms_dokter_userid` FOREIGN KEY (`userid`) REFERENCES `ms_user` (`userid`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `ms_dokter` */

/*Table structure for table `ms_jadwal` */

DROP TABLE IF EXISTS `ms_jadwal`;

CREATE TABLE `ms_jadwal` (
  `jam_awal` time DEFAULT NULL,
  `jam_akhir` time DEFAULT NULL,
  `islibur` varchar(1) DEFAULT '0',
  `t_updatetime` varchar(100) DEFAULT NULL,
  `t_updateuser` varchar(100) DEFAULT NULL,
  `iddokter` int(20) NOT NULL,
  `nohari` int(11) NOT NULL,
  PRIMARY KEY (`iddokter`,`nohari`),
  CONSTRAINT `fk_dokter` FOREIGN KEY (`iddokter`) REFERENCES `ms_dokter` (`iddokter`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `ms_jadwal` */

/*Table structure for table `ms_jenispelayanan` */

DROP TABLE IF EXISTS `ms_jenispelayanan`;

CREATE TABLE `ms_jenispelayanan` (
  `idjenis` int(11) NOT NULL AUTO_INCREMENT,
  `namajenis` varchar(50) DEFAULT NULL,
  `t_updatetime` varchar(100) DEFAULT NULL,
  `t_updateuser` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`idjenis`),
  UNIQUE KEY `idjenis` (`idjenis`)
) ENGINE=InnoDB AUTO_INCREMENT=80 DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Data for the table `ms_jenispelayanan` */

insert  into `ms_jenispelayanan`(`idjenis`,`namajenis`,`t_updatetime`,`t_updateuser`) values (1,'Bedah Umum',NULL,NULL),(2,'Kulit & Kelamin',NULL,NULL),(3,'THT',NULL,NULL),(4,'Syaraf/Neurologi',NULL,NULL),(5,'Mata',NULL,NULL),(6,'Bedah Tulang',NULL,NULL),(7,'Bedah Gigi & Mulut',NULL,NULL),(8,'Radiologi',NULL,NULL),(9,'Bedah Syaraf',NULL,NULL),(10,'Psikologi',NULL,NULL),(11,'Umum',NULL,NULL),(12,'Ibu dan Anak',NULL,NULL),(13,'Gigi & Mulut',NULL,NULL),(14,'Kebidanan & Kandungan',NULL,NULL),(15,'Anak',NULL,NULL),(16,'Penyakit Dalam',NULL,NULL),(17,'Urologi',NULL,NULL),(18,'Bedah Anak',NULL,NULL),(19,'Paru',NULL,NULL),(20,'Bedah Onkologi',NULL,NULL),(21,'Bedah Orthopedi',NULL,NULL),(22,'Rehabilitasi Medik',NULL,NULL),(23,'Kejiwaan(Psikiatri)',NULL,NULL),(24,'Neurologi',NULL,NULL),(25,'Bedah Plastik',NULL,NULL),(26,'Bedah Digestive',NULL,NULL),(27,'Bedah Urologi',NULL,NULL),(28,'Jantung & Pembuluh Darah (Kardiologi)',NULL,NULL),(29,'Anastesi',NULL,NULL),(30,'Patologi Klinik',NULL,NULL),(31,'Forensik',NULL,NULL),(32,'Imunisasi',NULL,NULL),(33,'Imunisasi BCG & Campak',NULL,NULL),(34,'Orthopedi',NULL,NULL),(35,'Fisioterapi',NULL,NULL),(36,'Bedah Saluran Kemih',NULL,NULL),(37,'Bedah Pembuluh Darah',NULL,NULL),(38,'Bedah Thorax',NULL,NULL),(39,'Bedah Tumor',NULL,NULL),(40,'Ginjal dan Hipertensi',NULL,NULL),(41,'Alergi dan Immonologi',NULL,NULL),(42,'Reumatologi',NULL,NULL),(43,'Geriatri',NULL,NULL),(44,'Gizi',NULL,NULL),(45,'Endoskopi Saluran Pencernaan',NULL,NULL),(46,'Ultrasonografi',NULL,NULL),(47,'Osteoporosis',NULL,NULL),(48,'EMG',NULL,NULL),(49,'Akupuntur',NULL,NULL),(50,'Pain Clinic',NULL,NULL),(51,'Klinik Kecantikan',NULL,NULL),(52,'Hati dan Saluran Cerna',NULL,NULL),(53,'Obesitas & Kebugaran',NULL,NULL),(54,'UGD',NULL,NULL),(55,'Apoteker',NULL,NULL),(56,'Hemodialisa',NULL,NULL),(57,'Medical Check Up',NULL,NULL),(58,'Echocardiography',NULL,NULL),(59,'Orthodonti',NULL,NULL),(60,'Infeksi & Tropik',NULL,NULL),(61,'Andrologi',NULL,NULL),(62,'HIV/AIDS',NULL,NULL),(63,'Gizi Anak',NULL,NULL),(64,'Asma & Alergi Anak',NULL,NULL),(65,'Hematologi',NULL,NULL),(66,'Terapi Wicara',NULL,NULL),(67,'Laser',NULL,NULL),(68,'Parasitologi',NULL,NULL),(69,'Endodonti Konserpasi Gigi',NULL,NULL),(70,'Pedodonti',NULL,NULL),(71,'Periodontologi',NULL,NULL),(73,'Prostodonti',NULL,NULL),(74,'Khitan',NULL,NULL),(75,'Mikrobiologi',NULL,NULL),(76,'Patalogi Anatomi',NULL,NULL),(77,'Radioterapi',NULL,NULL),(78,'Diabetes',NULL,NULL),(79,'VCT',NULL,NULL);

/*Table structure for table `ms_keluhan` */

DROP TABLE IF EXISTS `ms_keluhan`;

CREATE TABLE `ms_keluhan` (
  `idkeluhan` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `namakeluhan` varchar(255) DEFAULT NULL,
  `t_updatetime` varchar(100) DEFAULT NULL,
  `t_updateuser` varchar(100) DEFAULT NULL,
  `iddokter` int(20) DEFAULT NULL,
  PRIMARY KEY (`idkeluhan`),
  UNIQUE KEY `idkeluhan` (`idkeluhan`),
  KEY `fk_dokter_keluhan` (`iddokter`),
  CONSTRAINT `fk_dokter_keluhan` FOREIGN KEY (`iddokter`) REFERENCES `ms_dokter` (`iddokter`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `ms_keluhan` */

/*Table structure for table `ms_libur` */

DROP TABLE IF EXISTS `ms_libur`;

CREATE TABLE `ms_libur` (
  `iddokter` int(20) NOT NULL,
  `tgl` date NOT NULL,
  `t_updatetime` varchar(100) DEFAULT NULL,
  `t_updateuser` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`tgl`,`iddokter`),
  KEY `fk_dokter_libur` (`iddokter`),
  CONSTRAINT `fk_dokter_libur` FOREIGN KEY (`iddokter`) REFERENCES `ms_dokter` (`iddokter`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `ms_libur` */

/*Table structure for table `ms_notifikasi` */

DROP TABLE IF EXISTS `ms_notifikasi`;

CREATE TABLE `ms_notifikasi` (
  `idnotification` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `isi` varchar(255) DEFAULT NULL,
  `userid` int(20) DEFAULT NULL,
  `t_updateuser` varchar(100) DEFAULT NULL,
  `t_updatetime` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`idnotification`),
  UNIQUE KEY `idnotification` (`idnotification`),
  KEY `fk_user_notif` (`userid`),
  CONSTRAINT `fk_user_notif` FOREIGN KEY (`userid`) REFERENCES `ms_user` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `ms_notifikasi` */

/*Table structure for table `ms_user` */

DROP TABLE IF EXISTS `ms_user`;

CREATE TABLE `ms_user` (
  `userid` int(11) NOT NULL AUTO_INCREMENT,
  `idlevel` int(11) NOT NULL,
  `password` varchar(32) DEFAULT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `tgllahir` date DEFAULT NULL,
  `jenis_kelamin` decimal(1,0) DEFAULT NULL,
  `alamat` varchar(256) DEFAULT NULL,
  `pemberitahuan` varchar(1) DEFAULT '1',
  `showtgl` varchar(1) DEFAULT '1',
  `showalamat` varchar(1) DEFAULT '1',
  `showpicture` varchar(1) DEFAULT '1',
  `showtelp` varchar(1) DEFAULT '1',
  `isappuser` varchar(1) DEFAULT '1',
  `gcmregid` text,
  `token` varchar(32) DEFAULT NULL,
  `t_updatetime` varchar(100) DEFAULT NULL,
  `t_updateuser` varchar(100) DEFAULT NULL,
  `hintanswer` varchar(256) DEFAULT NULL,
  `hintquestion` varchar(256) DEFAULT NULL,
  `kodeverifikasi` varchar(6) DEFAULT NULL,
  `isverified` varchar(1) DEFAULT '0',
  `notelp` varchar(12) DEFAULT NULL,
  PRIMARY KEY (`userid`),
  UNIQUE KEY `userid` (`userid`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

/*Data for the table `ms_user` */

/*Table structure for table `tmp_estentertime` */

DROP TABLE IF EXISTS `tmp_estentertime`;

CREATE TABLE `tmp_estentertime` (
  `idantrian` int(11) DEFAULT NULL,
  `estentertime` time DEFAULT NULL,
  `iddokter` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `tmp_estentertime` */

/*Table structure for table `tmp_v_keluhan` */

DROP TABLE IF EXISTS `tmp_v_keluhan`;

CREATE TABLE `tmp_v_keluhan` (
  `id` int(11) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `tmp_v_keluhan` */

/*Table structure for table `tr_antrian` */

DROP TABLE IF EXISTS `tr_antrian`;

CREATE TABLE `tr_antrian` (
  `idantrian` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tgl` date NOT NULL,
  `userid` int(11) NOT NULL,
  `iddokter` int(20) NOT NULL,
  `noantrian` decimal(8,0) DEFAULT NULL,
  `waktumasuk` time DEFAULT NULL,
  `waktukeluar` time DEFAULT NULL,
  `listkeluhan` text,
  `statusantrian` varchar(1) DEFAULT NULL,
  `t_updatetime` varchar(100) DEFAULT NULL,
  `t_updateuser` varchar(100) DEFAULT NULL,
  `idle` time DEFAULT NULL,
  PRIMARY KEY (`idantrian`),
  UNIQUE KEY `idantrian` (`idantrian`),
  KEY `fk_dokter_antrian` (`iddokter`),
  KEY `fk_userpasien` (`userid`),
  CONSTRAINT `fk_dokter_antrian` FOREIGN KEY (`iddokter`) REFERENCES `ms_dokter` (`iddokter`),
  CONSTRAINT `fk_userpasien` FOREIGN KEY (`userid`) REFERENCES `ms_user` (`userid`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;

/*Data for the table `tr_antrian` */

/*Table structure for table `tr_rating` */

DROP TABLE IF EXISTS `tr_rating`;

CREATE TABLE `tr_rating` (
  `iddokter` int(20) NOT NULL,
  `userid` int(11) NOT NULL,
  `rating` int(11) DEFAULT NULL,
  `t_updatetime` varchar(100) DEFAULT NULL,
  `t_updateuser` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`iddokter`,`userid`),
  KEY `fk_user` (`userid`),
  CONSTRAINT `fk_dokter_rating` FOREIGN KEY (`iddokter`) REFERENCES `ms_dokter` (`iddokter`),
  CONSTRAINT `fk_user` FOREIGN KEY (`userid`) REFERENCES `ms_user` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `tr_rating` */

/* Trigger structure for table `ms_user` */

DELIMITER $$

/*!50003 DROP TRIGGER*//*!50032 IF EXISTS */ /*!50003 `createroleuser` */$$

/*!50003 CREATE */ /*!50017 DEFINER = 'root'@'localhost' */ /*!50003 TRIGGER `createroleuser` AFTER INSERT ON `ms_user` FOR EACH ROW BEGIN
	call imprint.f_createroleuser(new.userid, new.idlevel);
    END */$$


DELIMITER ;

/* Trigger structure for table `tr_antrian` */

DELIMITER $$

/*!50003 DROP TRIGGER*//*!50032 IF EXISTS */ /*!50003 `create_tbl_keluhan` */$$

/*!50003 CREATE */ /*!50017 DEFINER = 'root'@'localhost' */ /*!50003 TRIGGER `create_tbl_keluhan` AFTER INSERT ON `tr_antrian` FOR EACH ROW BEGIN
	call imprint.explode_listkeluhan('|', new.idantrian);
	call imprint.f_create_estentertime(new.iddokter, new.tgl);
    END */$$


DELIMITER ;

/* Trigger structure for table `tr_antrian` */

DELIMITER $$

/*!50003 DROP TRIGGER*//*!50032 IF EXISTS */ /*!50003 `create_tbl_esttime` */$$

/*!50003 CREATE */ /*!50017 DEFINER = 'root'@'localhost' */ /*!50003 TRIGGER `create_tbl_esttime` AFTER UPDATE ON `tr_antrian` FOR EACH ROW BEGIN
	call imprint.f_create_estentertime(new.iddokter, new.tgl);
    END */$$


DELIMITER ;

/* Function  structure for function  `f_getestimationtime` */

/*!50003 DROP FUNCTION IF EXISTS `f_getestimationtime` */;
DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` FUNCTION `f_getestimationtime`(i_iddokter int, i_noantrian numeric, i_keluhan text) RETURNS time
BEGIN
	declare	waktupelayanan TIME;
	
	select AVG(COALESCE((a.waktukeluar - a.waktumasuk),d.waktupelayanan)) into waktupelayanan
	from tr_antrian a
	join 	(
		SELECT noantrian,group_concat(`keluhan` separator '|') AS keluhan
		FROM   v_keluhan x1
		GROUP BY 1
		) x on x.noantrian = a.noantrian
	JOIN ms_dokter d on d.iddokter = a.iddokter
	where a.noantrian < i_noantrian
	and a.iddokter = i_iddokter 
	and keluhan = i_keluhan
	and statusantrian = '1';
	RETURN waktupelayanan;
    END */$$
DELIMITER ;

/* Procedure structure for procedure `explode_listkeluhan` */

/*!50003 DROP PROCEDURE IF EXISTS  `explode_listkeluhan` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `explode_listkeluhan`(bound VARCHAR(255),i_id INT)
BEGIN
    DECLARE id INT DEFAULT 0;
    DECLARE value TEXT;
    DECLARE occurance INT DEFAULT 0;
    DECLARE i INT DEFAULT 0;
    DECLARE splitted_value INT;
    DECLARE done INT DEFAULT 0;
    DECLARE cur1 CURSOR FOR SELECT idantrian, listkeluhan  FROM tr_antrian  where idantrian = i_id AND listkeluhan != '';
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;
    
    OPEN cur1;
      read_loop: LOOP
        FETCH cur1 INTO id, value;
        IF done THEN
          LEAVE read_loop;
        END IF;
        SET occurance = (SELECT LENGTH(value)
                                 - LENGTH(REPLACE(value, bound, ''))
                                 +1);
        SET i=1;
        WHILE i <= occurance DO
		SET splitted_value = (SELECT REPLACE(SUBSTRING(SUBSTRING_INDEX(value, bound, i),LENGTH(SUBSTRING_INDEX(value, bound, i - 1)) + 1), bound, ''));
		INSERT INTO tmp_v_keluhan VALUES (id, splitted_value);
		SET i = i + 1;
        END WHILE;
      END LOOP;
    CLOSE cur1;
  END */$$
DELIMITER ;

/* Procedure structure for procedure `f_createroleuser` */

/*!50003 DROP PROCEDURE IF EXISTS  `f_createroleuser` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `f_createroleuser`(i_userid int, i_idlevel int)
begin
	DECLARE i_iddokter INT;
	DECLARE i INT unsigned default 0;
	if(i_idlevel = 2) then
		insert into ms_dokter (userid) values (i_userid);
		
		select iddokter into i_iddokter from ms_dokter where userid=i_userid;
		while (i < 6) do
			INSERT INTO ms_jadwal(jam_awal, jam_akhir,iddokter,nohari) VALUES ('09:00','17:00',i_iddokter,i);
			set i = i+1;
		END while;
	end if;	
end */$$
DELIMITER ;

/* Procedure structure for procedure `f_create_estentertime` */

/*!50003 DROP PROCEDURE IF EXISTS  `f_create_estentertime` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `f_create_estentertime`(i_iddokter INT, i_tgl date)
begin
	DECLARE rightnow TIME DEFAULT CURTIME();
	DECLARE done INT DEFAULT 0;	
	DECLARE r_keluhan varchar(255);
	DECLARE r_idantrian int;
	declare r_estentertime time;
	declare first_queue numeric;
	declare previd INT;
	declare prevno numeric;
	declare prevtime numeric;
	DECLARE cur_id INT;
	DECLARE cur_no NUMERIC;
	DECLARE cur CURSOR FOR SELECT idantrian, noantrian FROM tr_antrian  where iddokter = i_iddokter and statusantrian = '1' and tgl = i_tgl order by noantrian ;
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;
	
	DELETE from tmp_estentertime where iddokter = i_iddokter;
	
	select MIN(noantrian) into first_queue
	from tr_antrian
	where iddokter = i_iddokter
	and tgl = i_tgl
	and statusantrian = '1';
	
	open cur;
		read_loop: LOOP
			FETCH cur INTO cur_id, cur_no;
			IF done THEN
				LEAVE read_loop;
			END IF;
			
			SELECT GROUP_concat(value separator '|') into r_keluhan FROM (SELECT value FROM tmp_v_keluhan where id = cur_id ORDER BY value) b;
		
			select MAX(noantrian) ,a.idantrian ,t.estentertime into prevno, previd, prevtime
			from tr_antrian a
			LEFT JOIN tmp_estentertime t on t.idantrian = a.idantrian 
			where a.iddokter = i_iddokter
			and tgl = i_tgl
			and statusantrian = '1'
			and noantrian < cur_no;
			
			select 	case 
					when cur_no = first_queue and j.jam_awal >= rightnow then j.jam_awal
					when cur_no = first_queue and j.jam_awal < rightnow then rightnow
					else COALESCE(f_getestimationtime(i_iddokter, previd, r_keluhan), d.waktupelayanan) + prevtime
				end into r_estentertime
			from ms_jadwal j
			join ms_dokter d on d.iddokter = j.iddokter
			where j.iddokter = i_iddokter
			and j.nohari = WEEKDAY(i_tgl);
			INSERT into tmp_estentertime values (cur_id, r_estentertime, i_iddokter);
		END LOOP;
	close cur;
end */$$
DELIMITER ;

/*Table structure for table `v_antrian` */

DROP TABLE IF EXISTS `v_antrian`;

/*!50001 DROP VIEW IF EXISTS `v_antrian` */;
/*!50001 DROP TABLE IF EXISTS `v_antrian` */;

/*!50001 CREATE TABLE `v_antrian` (
  `idantrian` bigint(20) unsigned NOT NULL DEFAULT '0',
  `iddokter` int(20) NOT NULL DEFAULT '0',
  `tgl` date NOT NULL DEFAULT '0000-00-00',
  `noantrian` decimal(8,0) DEFAULT NULL,
  `useridpasien` int(11) NOT NULL DEFAULT '0',
  `keluhan` mediumtext,
  `waktumasuk` time DEFAULT NULL,
  `waktukeluar` time DEFAULT NULL,
  `waktupelayanan` bigint(20) DEFAULT NULL,
  `estimasi_waktupelayanan` varchar(10) CHARACTER SET latin1 DEFAULT NULL,
  `estimasi_waktumasuk` varchar(10) DEFAULT NULL,
  `idle` time DEFAULT NULL,
  `rataan_idle` varchar(13) DEFAULT NULL,
  `gcmregid` mediumtext,
  `nama` varchar(50) DEFAULT NULL,
  `namakeluhan` mediumtext,
  `statusantrian` varchar(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 */;

/*Table structure for table `v_antrian_inside` */

DROP TABLE IF EXISTS `v_antrian_inside`;

/*!50001 DROP VIEW IF EXISTS `v_antrian_inside` */;
/*!50001 DROP TABLE IF EXISTS `v_antrian_inside` */;

/*!50001 CREATE TABLE `v_antrian_inside` (
  `idantrian` bigint(20) unsigned NOT NULL DEFAULT '0',
  `iddokter` int(20) NOT NULL DEFAULT '0',
  `tgl` date NOT NULL DEFAULT '0000-00-00',
  `noantrian` decimal(8,0) DEFAULT NULL,
  `useridpasien` int(11) NOT NULL DEFAULT '0',
  `keluhan` mediumtext,
  `waktumasuk` time DEFAULT NULL,
  `waktukeluar` time DEFAULT NULL,
  `waktupelayanan` bigint(20) DEFAULT NULL,
  `estimasi_waktupelayanan` varchar(10) CHARACTER SET latin1 DEFAULT NULL,
  `estimasi_waktumasuk` varchar(10) DEFAULT NULL,
  `idle` time DEFAULT NULL,
  `rataan_idle` varchar(13) DEFAULT NULL,
  `gcmregid` mediumtext,
  `nama` varchar(50) DEFAULT NULL,
  `namakeluhan` mediumtext,
  `statusantrian` varchar(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 */;

/*Table structure for table `v_keluhan` */

DROP TABLE IF EXISTS `v_keluhan`;

/*!50001 DROP VIEW IF EXISTS `v_keluhan` */;
/*!50001 DROP TABLE IF EXISTS `v_keluhan` */;

/*!50001 CREATE TABLE `v_keluhan` (
  `idantrian` bigint(20) unsigned NOT NULL DEFAULT '0',
  `noantrian` decimal(8,0) DEFAULT NULL,
  `keluhan` varchar(255) DEFAULT NULL,
  `tgl` date NOT NULL,
  `iddokter` int(20) NOT NULL DEFAULT '0',
  `nip` varchar(20) DEFAULT NULL,
  `userid_dokter` int(11) DEFAULT NULL,
  `userid_pasien` int(11) NOT NULL DEFAULT '0',
  `namakeluhan` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 */;

/*Table structure for table `v_keluhan_inside` */

DROP TABLE IF EXISTS `v_keluhan_inside`;

/*!50001 DROP VIEW IF EXISTS `v_keluhan_inside` */;
/*!50001 DROP TABLE IF EXISTS `v_keluhan_inside` */;

/*!50001 CREATE TABLE `v_keluhan_inside` (
  `idantrian` bigint(20) unsigned NOT NULL DEFAULT '0',
  `noantrian` decimal(8,0) DEFAULT NULL,
  `keluhan` varchar(255) DEFAULT NULL,
  `tgl` date NOT NULL,
  `iddokter` int(20) NOT NULL DEFAULT '0',
  `nip` varchar(20) DEFAULT NULL,
  `userid_dokter` int(11) DEFAULT NULL,
  `userid_pasien` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 */;

/*Table structure for table `v_keluhan_inside_riwayat` */

DROP TABLE IF EXISTS `v_keluhan_inside_riwayat`;

/*!50001 DROP VIEW IF EXISTS `v_keluhan_inside_riwayat` */;
/*!50001 DROP TABLE IF EXISTS `v_keluhan_inside_riwayat` */;

/*!50001 CREATE TABLE `v_keluhan_inside_riwayat` (
  `noantrian` decimal(8,0) DEFAULT NULL,
  `iddokter` int(20) NOT NULL DEFAULT '0',
  `userid_pasien` int(11) NOT NULL DEFAULT '0',
  `tgl` date NOT NULL,
  `keluhan` text,
  `namakeluhan` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8 */;

/*Table structure for table `v_rataan_idle` */

DROP TABLE IF EXISTS `v_rataan_idle`;

/*!50001 DROP VIEW IF EXISTS `v_rataan_idle` */;
/*!50001 DROP TABLE IF EXISTS `v_rataan_idle` */;

/*!50001 CREATE TABLE `v_rataan_idle` (
  `iddokter` int(20) NOT NULL,
  `rataan_idle` decimal(11,4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 */;

/*Table structure for table `v_rating` */

DROP TABLE IF EXISTS `v_rating`;

/*!50001 DROP VIEW IF EXISTS `v_rating` */;
/*!50001 DROP TABLE IF EXISTS `v_rating` */;

/*!50001 CREATE TABLE `v_rating` (
  `rating` decimal(13,2) DEFAULT NULL,
  `iddokter` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 */;

/*Table structure for table `v_rating_user` */

DROP TABLE IF EXISTS `v_rating_user`;

/*!50001 DROP VIEW IF EXISTS `v_rating_user` */;
/*!50001 DROP TABLE IF EXISTS `v_rating_user` */;

/*!50001 CREATE TABLE `v_rating_user` (
  `iddokter` int(20) NOT NULL,
  `rating` decimal(14,4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 */;

/*Table structure for table `v_riwayat` */

DROP TABLE IF EXISTS `v_riwayat`;

/*!50001 DROP VIEW IF EXISTS `v_riwayat` */;
/*!50001 DROP TABLE IF EXISTS `v_riwayat` */;

/*!50001 CREATE TABLE `v_riwayat` (
  `idantrian` bigint(20) unsigned NOT NULL DEFAULT '0',
  `iddokter` int(20) NOT NULL,
  `namadokter` varchar(50) DEFAULT NULL,
  `tgl` date NOT NULL,
  `noantrian` decimal(8,0) DEFAULT NULL,
  `useridpasien` int(11) NOT NULL,
  `keluhan` text,
  `waktumasuk` time DEFAULT NULL,
  `waktukeluar` time DEFAULT NULL,
  `waktupelayanan` int(9) DEFAULT NULL,
  `namakeluhan` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8 */;

/*Table structure for table `v_user` */

DROP TABLE IF EXISTS `v_user`;

/*!50001 DROP VIEW IF EXISTS `v_user` */;
/*!50001 DROP TABLE IF EXISTS `v_user` */;

/*!50001 CREATE TABLE `v_user` (
  `userid` int(11) NOT NULL DEFAULT '0',
  `idlevel` int(11) NOT NULL,
  `password` varchar(32) DEFAULT NULL,
  `token` varchar(32) DEFAULT NULL,
  `gcmregid` text,
  `nama` varchar(50) DEFAULT NULL,
  `kodeverifikasi` varchar(6) DEFAULT NULL,
  `isverified` varchar(1) DEFAULT NULL,
  `tgllahir` date DEFAULT NULL,
  `notelp` varchar(12) DEFAULT NULL,
  `jenis_kelamin` decimal(1,0) DEFAULT NULL,
  `alamatuser` varchar(256) DEFAULT NULL,
  `pemberitahuan` varchar(1) DEFAULT NULL,
  `showtgl` varchar(1) DEFAULT NULL,
  `showalamat` varchar(1) DEFAULT NULL,
  `showpicture` varchar(1) DEFAULT NULL,
  `showtelp` varchar(1) DEFAULT NULL,
  `isappuser` varchar(1) DEFAULT NULL,
  `hintanswer` varchar(256) DEFAULT NULL,
  `hintquestion` varchar(256) DEFAULT NULL,
  `nip` varchar(20) DEFAULT NULL,
  `idjenis` int(11) DEFAULT NULL,
  `namajenis` varchar(50) DEFAULT NULL,
  `rating` decimal(14,4) DEFAULT NULL,
  `alamat_praktek` varchar(500) DEFAULT NULL,
  `notelp_praktek` varchar(12) DEFAULT NULL,
  `waktupelayanan` time DEFAULT NULL,
  `suspendqueue` varchar(5) DEFAULT NULL,
  `longi` double DEFAULT NULL,
  `lat` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 */;

/*View structure for view v_antrian */

/*!50001 DROP TABLE IF EXISTS `v_antrian` */;
/*!50001 DROP VIEW IF EXISTS `v_antrian` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_antrian` AS select `v_antrian_inside`.`idantrian` AS `idantrian`,`v_antrian_inside`.`iddokter` AS `iddokter`,`v_antrian_inside`.`tgl` AS `tgl`,`v_antrian_inside`.`noantrian` AS `noantrian`,`v_antrian_inside`.`useridpasien` AS `useridpasien`,`v_antrian_inside`.`keluhan` AS `keluhan`,`v_antrian_inside`.`waktumasuk` AS `waktumasuk`,`v_antrian_inside`.`waktukeluar` AS `waktukeluar`,`v_antrian_inside`.`waktupelayanan` AS `waktupelayanan`,`v_antrian_inside`.`estimasi_waktupelayanan` AS `estimasi_waktupelayanan`,`v_antrian_inside`.`estimasi_waktumasuk` AS `estimasi_waktumasuk`,`v_antrian_inside`.`idle` AS `idle`,`v_antrian_inside`.`rataan_idle` AS `rataan_idle`,`v_antrian_inside`.`gcmregid` AS `gcmregid`,`v_antrian_inside`.`nama` AS `nama`,`v_antrian_inside`.`namakeluhan` AS `namakeluhan`,`v_antrian_inside`.`statusantrian` AS `statusantrian` from `v_antrian_inside` order by `v_antrian_inside`.`statusantrian`,`v_antrian_inside`.`tgl`,`v_antrian_inside`.`noantrian` */;

/*View structure for view v_antrian_inside */

/*!50001 DROP TABLE IF EXISTS `v_antrian_inside` */;
/*!50001 DROP VIEW IF EXISTS `v_antrian_inside` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_antrian_inside` AS select `a`.`idantrian` AS `idantrian`,`a`.`iddokter` AS `iddokter`,`a`.`tgl` AS `tgl`,`a`.`noantrian` AS `noantrian`,`a`.`userid` AS `useridpasien`,`x`.`keluhan` AS `keluhan`,`a`.`waktumasuk` AS `waktumasuk`,`a`.`waktukeluar` AS `waktukeluar`,(`a`.`waktukeluar` - `a`.`waktumasuk`) AS `waktupelayanan`,coalesce(`f_getestimationtime`(`a`.`iddokter`,`a`.`noantrian`,`x`.`keluhan`),`d`.`waktupelayanan`) AS `estimasi_waktupelayanan`,`et`.`estentertime` AS `estimasi_waktumasuk`,`a`.`idle` AS `idle`,`y`.`rataan_idle` AS `rataan_idle`,`u`.`gcmregid` AS `gcmregid`,`u`.`nama` AS `nama`,`x`.`namakeluhan` AS `namakeluhan`,`a`.`statusantrian` AS `statusantrian` from ((((((`tr_antrian` `a` left join `v_keluhan_inside_riwayat` `x` on(((`x`.`noantrian` = `a`.`noantrian`) and (`x`.`iddokter` = `a`.`iddokter`) and (`x`.`userid_pasien` = `a`.`userid`) and (`x`.`tgl` = `a`.`tgl`)))) join `ms_dokter` `d` on((`d`.`iddokter` = `a`.`iddokter`))) join `ms_jadwal` `j` on(((`j`.`iddokter` = `d`.`iddokter`) and (`j`.`nohari` = weekday(`a`.`tgl`))))) join `v_rataan_idle` `y` on((`y`.`iddokter` = `d`.`iddokter`))) join `tmp_estentertime` `et` on((`et`.`idantrian` = `a`.`idantrian`))) left join `ms_user` `u` on((`u`.`userid` = `a`.`userid`))) where (`a`.`statusantrian` = '1') union select `a`.`idantrian` AS `idantrian`,`a`.`iddokter` AS `iddokter`,`a`.`tgl` AS `tgl`,`a`.`noantrian` AS `noantrian`,`a`.`userid` AS `useridpasien`,`x`.`keluhan` AS `keluhan`,`a`.`waktumasuk` AS `waktumasuk`,`a`.`waktukeluar` AS `waktukeluar`,(`a`.`waktukeluar` - `a`.`waktumasuk`) AS `waktupelayanan`,'00:00:00' AS `estimasi_waktupelayanan`,'00:00:00' AS `estimasi_waktumasuk`,`a`.`idle` AS `idle`,'00:00:00' AS `rataan_idle`,`u`.`gcmregid` AS `gcmregid`,`u`.`nama` AS `nama`,`x`.`namakeluhan` AS `namakeluhan`,`a`.`statusantrian` AS `statusantrian` from ((`tr_antrian` `a` left join `v_keluhan_inside_riwayat` `x` on(((`x`.`noantrian` = `a`.`noantrian`) and (`x`.`iddokter` = `a`.`iddokter`) and (`x`.`userid_pasien` = `a`.`userid`) and (`x`.`tgl` = `a`.`tgl`)))) left join `ms_user` `u` on((`u`.`userid` = `a`.`userid`))) where (`a`.`statusantrian` = '2') */;

/*View structure for view v_keluhan */

/*!50001 DROP TABLE IF EXISTS `v_keluhan` */;
/*!50001 DROP VIEW IF EXISTS `v_keluhan` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_keluhan` AS select `a1`.`idantrian` AS `idantrian`,`a1`.`noantrian` AS `noantrian`,`a1`.`keluhan` AS `keluhan`,`a1`.`tgl` AS `tgl`,`a1`.`iddokter` AS `iddokter`,`a1`.`nip` AS `nip`,`a1`.`userid_dokter` AS `userid_dokter`,`a1`.`userid_pasien` AS `userid_pasien`,`k`.`namakeluhan` AS `namakeluhan` from (`v_keluhan_inside` `a1` join `ms_keluhan` `k` on((`k`.`idkeluhan` = `a1`.`keluhan`))) */;

/*View structure for view v_keluhan_inside */

/*!50001 DROP TABLE IF EXISTS `v_keluhan_inside` */;
/*!50001 DROP VIEW IF EXISTS `v_keluhan_inside` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_keluhan_inside` AS select `a`.`idantrian` AS `idantrian`,`a`.`noantrian` AS `noantrian`,`tmp_k`.`value` AS `keluhan`,`a`.`tgl` AS `tgl`,`d`.`iddokter` AS `iddokter`,`d`.`nip` AS `nip`,`d`.`userid` AS `userid_dokter`,`u`.`userid` AS `userid_pasien` from (((`tr_antrian` `a` join `ms_dokter` `d` on((`d`.`iddokter` = `a`.`iddokter`))) join `ms_user` `u` on((`u`.`userid` = `a`.`userid`))) join `tmp_v_keluhan` `tmp_k` on((`a`.`idantrian` = `tmp_k`.`id`))) order by `a`.`idantrian`,`tmp_k`.`value` */;

/*View structure for view v_keluhan_inside_riwayat */

/*!50001 DROP TABLE IF EXISTS `v_keluhan_inside_riwayat` */;
/*!50001 DROP VIEW IF EXISTS `v_keluhan_inside_riwayat` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_keluhan_inside_riwayat` AS select `x1`.`noantrian` AS `noantrian`,`x1`.`iddokter` AS `iddokter`,`x1`.`userid_pasien` AS `userid_pasien`,`x1`.`tgl` AS `tgl`,group_concat(`x1`.`keluhan` separator '|') AS `keluhan`,group_concat(`x1`.`namakeluhan` separator ', ') AS `namakeluhan` from `v_keluhan` `x1` group by `x1`.`noantrian`,`x1`.`iddokter`,`x1`.`userid_pasien`,`x1`.`tgl` */;

/*View structure for view v_rataan_idle */

/*!50001 DROP TABLE IF EXISTS `v_rataan_idle` */;
/*!50001 DROP VIEW IF EXISTS `v_rataan_idle` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_rataan_idle` AS select `a`.`iddokter` AS `iddokter`,coalesce(avg(`a`.`idle`),0) AS `rataan_idle` from `tr_antrian` `a` group by `a`.`iddokter` */;

/*View structure for view v_rating */

/*!50001 DROP TABLE IF EXISTS `v_rating` */;
/*!50001 DROP VIEW IF EXISTS `v_rating` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_rating` AS select round(avg(`tr_rating`.`rating`),2) AS `rating`,`tr_rating`.`iddokter` AS `iddokter` from `tr_rating` group by `tr_rating`.`iddokter` */;

/*View structure for view v_rating_user */

/*!50001 DROP TABLE IF EXISTS `v_rating_user` */;
/*!50001 DROP VIEW IF EXISTS `v_rating_user` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_rating_user` AS select `tr_rating`.`iddokter` AS `iddokter`,avg(`tr_rating`.`rating`) AS `rating` from `tr_rating` group by `tr_rating`.`iddokter` */;

/*View structure for view v_riwayat */

/*!50001 DROP TABLE IF EXISTS `v_riwayat` */;
/*!50001 DROP VIEW IF EXISTS `v_riwayat` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_riwayat` AS select `a`.`idantrian` AS `idantrian`,`a`.`iddokter` AS `iddokter`,`u2`.`nama` AS `namadokter`,`a`.`tgl` AS `tgl`,`a`.`noantrian` AS `noantrian`,`a`.`userid` AS `useridpasien`,`x`.`keluhan` AS `keluhan`,`a`.`waktumasuk` AS `waktumasuk`,`a`.`waktukeluar` AS `waktukeluar`,(`a`.`waktukeluar` - `a`.`waktumasuk`) AS `waktupelayanan`,`x`.`namakeluhan` AS `namakeluhan` from ((((`tr_antrian` `a` left join `v_keluhan_inside_riwayat` `x` on(((`x`.`noantrian` = `a`.`noantrian`) and (`x`.`iddokter` = `a`.`iddokter`) and (`x`.`userid_pasien` = `a`.`userid`) and (`x`.`tgl` = `a`.`tgl`)))) left join `ms_user` `u` on((`u`.`userid` = `a`.`userid`))) left join `ms_dokter` `d` on((`d`.`iddokter` = `a`.`iddokter`))) left join `ms_user` `u2` on((`u2`.`userid` = `d`.`userid`))) where (`a`.`statusantrian` = '4') order by `a`.`tgl` */;

/*View structure for view v_user */

/*!50001 DROP TABLE IF EXISTS `v_user` */;
/*!50001 DROP VIEW IF EXISTS `v_user` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_user` AS select `u`.`userid` AS `userid`,`u`.`idlevel` AS `idlevel`,`u`.`password` AS `password`,`u`.`token` AS `token`,`u`.`gcmregid` AS `gcmregid`,`u`.`nama` AS `nama`,`u`.`kodeverifikasi` AS `kodeverifikasi`,`u`.`isverified` AS `isverified`,`u`.`tgllahir` AS `tgllahir`,`u`.`notelp` AS `notelp`,`u`.`jenis_kelamin` AS `jenis_kelamin`,`u`.`alamat` AS `alamatuser`,`u`.`pemberitahuan` AS `pemberitahuan`,`u`.`showtgl` AS `showtgl`,`u`.`showalamat` AS `showalamat`,`u`.`showpicture` AS `showpicture`,`u`.`showtelp` AS `showtelp`,`u`.`isappuser` AS `isappuser`,`u`.`hintanswer` AS `hintanswer`,`u`.`hintquestion` AS `hintquestion`,`d`.`nip` AS `nip`,`d`.`idjenis` AS `idjenis`,`jp`.`namajenis` AS `namajenis`,coalesce(`r`.`rating`,0) AS `rating`,`d`.`alamat_praktek` AS `alamat_praktek`,`d`.`notelp_praktek` AS `notelp_praktek`,`d`.`waktupelayanan` AS `waktupelayanan`,`d`.`suspendqueue` AS `suspendqueue`,`d`.`longi` AS `longi`,`d`.`lat` AS `lat` from (((`ms_user` `u` left join `ms_dokter` `d` on((`d`.`userid` = `u`.`userid`))) left join `v_rating_user` `r` on((`r`.`iddokter` = `d`.`iddokter`))) left join `ms_jenispelayanan` `jp` on((`jp`.`idjenis` = `d`.`idjenis`))) */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
