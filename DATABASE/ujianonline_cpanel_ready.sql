/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-11.4.13-MariaDB, for Win64 (AMD64)
--
-- Host: 127.0.0.1    Database: ujianonline
-- ------------------------------------------------------
-- Server version	11.4.13-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;

--
-- Table structure for table `cbt_jawaban`
--

DROP TABLE IF EXISTS `cbt_jawaban`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cbt_jawaban` (
  `jawaban_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `jawaban_soal_id` bigint(20) unsigned NOT NULL,
  `jawaban_detail` text NOT NULL,
  `jawaban_benar` tinyint(1) NOT NULL DEFAULT 0,
  `jawaban_aktif` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`jawaban_id`),
  KEY `p_answer_question_id` (`jawaban_soal_id`),
  CONSTRAINT `cbt_jawaban_ibfk_1` FOREIGN KEY (`jawaban_soal_id`) REFERENCES `cbt_soal` (`soal_id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=626 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cbt_jawaban`
--

LOCK TABLES `cbt_jawaban` WRITE;
/*!40000 ALTER TABLE `cbt_jawaban` DISABLE KEYS */;
INSERT INTO `cbt_jawaban` VALUES
(186,57,'<p>1 Syawal</p>\r\n',1,1),
(187,57,'<p>1 Agustus</p>',0,1),
(188,57,'<p>1 Januari</p>',0,1),
(189,57,'<p>1 Desember</p>',0,1),
(190,57,'<p>14 Februari</p>',0,1),
(191,56,'<p>Nazril Irham</p>',1,1),
(192,56,'<p>Joko Susilo</p>',0,1),
(193,56,'<p>Wahyu Saputra</p>\r\n',0,1),
(194,56,'<p>Aril Piterpen</p>',0,1),
(195,56,'Joko Wow',0,1),
(196,55,'<p>Soekarno</p>',1,1),
(197,55,'<p>Soeharto</p>\r\n',0,1),
(198,55,'<p>Susilo Bambang Yudhoyono</p>\r\n',0,1),
(199,55,'<p>BJ. Habibie</p>\r\n',0,1),
(200,55,'<p>Joko Widodo</p>\r\n',0,1),
(201,54,'<p>Sun East Mall</p>',1,1),
(202,54,'<p>Matahari</p>',0,1),
(203,54,'<p>Bulan</p>',0,1),
(204,54,'<p>Tanah Abang</p>',0,1),
(205,54,'<p>Tanah Lempong</p>',0,1),
(206,53,'<p>Sekolah Menengah Kejuruan</p>',1,1),
(207,53,'<p>Sekolah Menengah Kejujuran</p>',0,1),
(208,53,'<p>Sekolah Maju Sendiri</p>',0,1),
(209,53,'<p>Sekolah Mak Ku</p>',0,1),
(210,53,'<p>Sekolah Memilih Kekasih</p>',0,1),
(211,64,'Akhirnya aku menemukanmu',1,1),
(212,64,'Akhir dirimu',0,1),
(213,64,'Susahnya jadi dia',0,1),
(214,64,'Jones',0,1),
(215,64,'Josan - Jomblo Pas Pasan',0,1),
(621,161,'<p>Aksi bela Jomblo</p>\r\n',1,1),
(622,161,'<p>Aksi bela cewek</p>\r\n',0,1),
(623,161,'<p>14 Februari</p>\r\n',0,1),
(624,161,'<p>Hari Valentine</p>\r\n',0,1),
(625,161,'<p>Turun ke jalan</p>\r\n',0,1);
/*!40000 ALTER TABLE `cbt_jawaban` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cbt_konfigurasi`
--

DROP TABLE IF EXISTS `cbt_konfigurasi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cbt_konfigurasi` (
  `konfigurasi_id` int(11) NOT NULL AUTO_INCREMENT,
  `konfigurasi_kode` varchar(50) NOT NULL,
  `konfigurasi_isi` varchar(500) NOT NULL,
  `konfigurasi_keterangan` varchar(100) NOT NULL,
  PRIMARY KEY (`konfigurasi_id`),
  UNIQUE KEY `konfigurasi_kode` (`konfigurasi_kode`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cbt_konfigurasi`
--

LOCK TABLES `cbt_konfigurasi` WRITE;
/*!40000 ALTER TABLE `cbt_konfigurasi` DISABLE KEYS */;
INSERT INTO `cbt_konfigurasi` VALUES
(1,'link_login_operator','ya','Menampilkan Link Login Operator'),
(2,'cbt_nama','Computer Based-Test','Nama Penyelenggara ZYACBT'),
(3,'cbt_keterangan','Ujian Online Berbasis Komputer','Keterangan Penyelenggara ZYACBT'),
(4,'cbt_mobile_lock_xambro','tidak','Melakukan Lock pada browser mobile agar menggunakan Xambro Saja'),
(5,'cbt_informasi','<p>Silahkan pilih Tes yang diikuti dari daftar tes yang tersedia dibawah ini. Apabila tes tidak muncul, silahkan menghubungi Operator yang bertugas.</p>\r\n','Informasi yang diberika di Dashboard peserta tes\'');
/*!40000 ALTER TABLE `cbt_konfigurasi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cbt_modul`
--

DROP TABLE IF EXISTS `cbt_modul`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cbt_modul` (
  `modul_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `modul_nama` varchar(255) NOT NULL,
  `modul_aktif` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`modul_id`),
  UNIQUE KEY `ak_module_name` (`modul_nama`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cbt_modul`
--

LOCK TABLES `cbt_modul` WRITE;
/*!40000 ALTER TABLE `cbt_modul` DISABLE KEYS */;
INSERT INTO `cbt_modul` VALUES
(9,'Default',1);
/*!40000 ALTER TABLE `cbt_modul` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cbt_sessions`
--

DROP TABLE IF EXISTS `cbt_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cbt_sessions` (
  `id` varchar(128) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) unsigned NOT NULL DEFAULT 0,
  `data` blob NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ci_sessions_timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cbt_sessions`
--

LOCK TABLES `cbt_sessions` WRITE;
/*!40000 ALTER TABLE `cbt_sessions` DISABLE KEYS */;
INSERT INTO `cbt_sessions` VALUES
('1616b9f1730cd418029b7a8567cc07b355e893ef','127.0.0.1',1793876996,'__ci_last_regenerate|i:1793876996;'),
('1bl9rt0j69ub0lrcvptp9jv93rp8mlma','::1',1644459044,'__ci_last_regenerate|i:1644459043;'),
('3p29jbime34jis7a5em13qm2epfq90na','::1',1599377882,'__ci_last_regenerate|i:1599377641;cbt_user_id|s:5:\"admin\";cbt_nama|s:12:\"Achmad Lutfi\";cbt_level|s:5:\"admin\";cbt_opsi1|s:0:\"\";cbt_opsi2|s:0:\"\";'),
('407kmuvok6a01i09j8rg7p5i1ivltps5','::1',1644459043,'__ci_last_regenerate|i:1644459043;'),
('44c2dfdc13df9419b37faf7b542015e6ee749f12','127.0.0.1',1793878232,'__ci_last_regenerate|i:1793878231;'),
('4507f2cd5eb5f115e00e897eb613297ce8a1457e','127.0.0.1',1793876979,'__ci_last_regenerate|i:1793876978;'),
('49vtfbptg1ruremgul4j1ivkej54ofvp','127.0.0.1',1604183402,'__ci_last_regenerate|i:1604183375;cbt_user_id|s:5:\"admin\";cbt_nama|s:12:\"Achmad Lutfi\";cbt_level|s:5:\"admin\";cbt_opsi1|s:0:\"\";cbt_opsi2|s:0:\"\";'),
('78fb8819a8a953e126f581414eb96e51327fcf5c','127.0.0.1',1793878303,'__ci_last_regenerate|i:1793878301;'),
('7e742n3jq70q4g577qpfdth4fdk4j02p','127.0.0.1',1604182994,'__ci_last_regenerate|i:1604182994;cbt_user_id|s:5:\"admin\";cbt_nama|s:12:\"Achmad Lutfi\";cbt_level|s:5:\"admin\";cbt_opsi1|s:0:\"\";cbt_opsi2|s:0:\"\";'),
('8pcnsa8ojtp08a04kaojglv011u1p9vp','::1',1599379422,'__ci_last_regenerate|i:1599379141;cbt_user_id|s:5:\"admin\";cbt_nama|s:12:\"Achmad Lutfi\";cbt_level|s:5:\"admin\";cbt_opsi1|s:0:\"\";cbt_opsi2|s:0:\"\";'),
('99c6e7686eb9c0e107c0c55cc97f60cb0d749cb3','127.0.0.1',1793878262,'__ci_last_regenerate|i:1793878260;'),
('ace5hlacs61k06r3bhl6dgb2kvjmkset','::1',1599379011,'__ci_last_regenerate|i:1599378720;cbt_user_id|s:5:\"admin\";cbt_nama|s:12:\"Achmad Lutfi\";cbt_level|s:5:\"admin\";cbt_opsi1|s:0:\"\";cbt_opsi2|s:0:\"\";'),
('alu2de14sll20rb2druvb7e3anth2bqf','::1',1644457890,'__ci_last_regenerate|i:1644457890;cbt_user_id|s:5:\"admin\";cbt_nama|s:12:\"Achmad Lutfi\";cbt_level|s:5:\"admin\";cbt_opsi1|s:0:\"\";cbt_opsi2|s:0:\"\";'),
('b867b05b2bf13d7b6704eca4f662cd74cffdc0ea','127.0.0.1',1793878402,'__ci_last_regenerate|i:1793878241;cbt_user_id|s:5:\"admin\";cbt_nama|s:11:\"Vicky Koroh\";cbt_level|s:5:\"admin\";cbt_opsi1|s:0:\"\";cbt_opsi2|s:0:\"\";'),
('b8ls2vctk6rsjiiuvpv1pq9apvmb5bos','::1',1599381132,'__ci_last_regenerate|i:1599381018;cbt_user_id|s:5:\"admin\";cbt_nama|s:12:\"Achmad Lutfi\";cbt_level|s:5:\"admin\";cbt_opsi1|s:0:\"\";cbt_opsi2|s:0:\"\";cbt_tes_user_id|s:5:\"lutfi\";cbt_tes_nama|s:22:\"Muhammad Lutfial Hakim\";cbt_tes_group|s:5:\"XI MM\";cbt_tes_group_id|s:1:\"5\";'),
('bbtk0oq94r7dnqkcitjidi135e2n7jet','::1',1599380892,'__ci_last_regenerate|i:1599380636;cbt_user_id|s:5:\"admin\";cbt_nama|s:12:\"Achmad Lutfi\";cbt_level|s:5:\"admin\";cbt_opsi1|s:0:\"\";cbt_opsi2|s:0:\"\";cbt_tes_user_id|s:4:\"joko\";cbt_tes_nama|s:12:\"Joko Susanto\";cbt_tes_group|s:5:\"XI MM\";cbt_tes_group_id|s:1:\"5\";'),
('bc9aejgjbhihf9rq8vm7t8j5anr7j7hc','::1',1599382972,'__ci_last_regenerate|i:1599382971;cbt_user_id|s:5:\"admin\";cbt_nama|s:12:\"Achmad Lutfi\";cbt_level|s:5:\"admin\";cbt_opsi1|s:0:\"\";cbt_opsi2|s:0:\"\";cbt_tes_user_id|s:5:\"lutfi\";cbt_tes_nama|s:22:\"Muhammad Lutfial Hakim\";cbt_tes_group|s:5:\"XI MM\";cbt_tes_group_id|s:1:\"5\";'),
('bf0333494eaedd3f1966167432aba5fc1320ebd4','127.0.0.1',1793877350,'__ci_last_regenerate|i:1793877278;'),
('e38frtjsi5nefgjao7u40ptn8gshstnk','::1',1644977783,'__ci_last_regenerate|i:1644977572;cbt_user_id|s:5:\"admin\";cbt_nama|s:12:\"Achmad Lutfi\";cbt_level|s:5:\"admin\";cbt_opsi1|s:0:\"\";cbt_opsi2|s:0:\"\";'),
('f39scdhganmqqot28k62lbc3rmo1lsce','::1',1599386485,'__ci_last_regenerate|i:1599386187;cbt_user_id|s:5:\"admin\";cbt_nama|s:12:\"Achmad Lutfi\";cbt_level|s:5:\"admin\";cbt_opsi1|s:0:\"\";cbt_opsi2|s:0:\"\";cbt_tes_user_id|s:5:\"lutfi\";cbt_tes_nama|s:22:\"Muhammad Lutfial Hakim\";cbt_tes_group|s:5:\"XI MM\";cbt_tes_group_id|s:1:\"5\";'),
('fthkcg5hasehthcqlfjmeogf3eedm9ef','127.0.0.1',1599454747,'__ci_last_regenerate|i:1599454747;cbt_user_id|s:5:\"admin\";cbt_nama|s:12:\"Achmad Lutfi\";cbt_level|s:5:\"admin\";cbt_opsi1|s:0:\"\";cbt_opsi2|s:0:\"\";'),
('h7rpp3igk9lt3tu4vbi28jia5jkfv524','::1',1599454890,'__ci_last_regenerate|i:1599454820;cbt_user_id|s:5:\"admin\";cbt_nama|s:12:\"Achmad Lutfi\";cbt_level|s:5:\"admin\";cbt_opsi1|s:0:\"\";cbt_opsi2|s:0:\"\";'),
('ha55u3amhkachu4cfa34mvkgcj97vrdi','::1',1599381616,'__ci_last_regenerate|i:1599381546;cbt_user_id|s:5:\"admin\";cbt_nama|s:12:\"Achmad Lutfi\";cbt_level|s:5:\"admin\";cbt_opsi1|s:0:\"\";cbt_opsi2|s:0:\"\";cbt_tes_user_id|s:5:\"lutfi\";cbt_tes_nama|s:22:\"Muhammad Lutfial Hakim\";cbt_tes_group|s:5:\"XI MM\";cbt_tes_group_id|s:1:\"5\";'),
('htnpqjqcfqcl9g1psdribp1dpkicv416','::1',1641784345,'__ci_last_regenerate|i:1641784069;'),
('je0iglrep3d50g8gn21lonmv6nvnjsu1','::1',1644977572,'__ci_last_regenerate|i:1644977572;cbt_user_id|s:5:\"admin\";cbt_nama|s:12:\"Achmad Lutfi\";cbt_level|s:5:\"admin\";cbt_opsi1|s:0:\"\";cbt_opsi2|s:0:\"\";'),
('kkolosfo49t3vu9of56viv7u5r25rfks','::1',1599378656,'__ci_last_regenerate|i:1599378419;cbt_user_id|s:5:\"admin\";cbt_nama|s:12:\"Achmad Lutfi\";cbt_level|s:5:\"admin\";cbt_opsi1|s:0:\"\";cbt_opsi2|s:0:\"\";'),
('ks2b8bbrgkobgrkfij4pktj9keg6vj5e','::1',1644971525,'__ci_last_regenerate|i:1644971525;cbt_user_id|s:5:\"admin\";cbt_nama|s:12:\"Achmad Lutfi\";cbt_level|s:5:\"admin\";cbt_opsi1|s:0:\"\";cbt_opsi2|s:0:\"\";'),
('mt3ddutslgt5rtbqq22i9g8qigcjdpva','::1',1599386603,'__ci_last_regenerate|i:1599386560;cbt_user_id|s:5:\"admin\";cbt_nama|s:12:\"Achmad Lutfi\";cbt_level|s:5:\"admin\";cbt_opsi1|s:0:\"\";cbt_opsi2|s:0:\"\";cbt_tes_user_id|s:5:\"lutfi\";cbt_tes_nama|s:22:\"Muhammad Lutfial Hakim\";cbt_tes_group|s:5:\"XI MM\";cbt_tes_group_id|s:1:\"5\";'),
('p5q7f07bavkq326a2ddftl0ggc9c5g6q','127.0.0.1',1604183375,'__ci_last_regenerate|i:1604183375;cbt_user_id|s:5:\"admin\";cbt_nama|s:12:\"Achmad Lutfi\";cbt_level|s:5:\"admin\";cbt_opsi1|s:0:\"\";cbt_opsi2|s:0:\"\";'),
('ph4kvrn03c7gh4pishmsg9a0vhi1i8e5','::1',1599377617,'__ci_last_regenerate|i:1599377332;cbt_user_id|s:5:\"admin\";cbt_nama|s:12:\"Achmad Lutfi\";cbt_level|s:5:\"admin\";cbt_opsi1|s:0:\"\";cbt_opsi2|s:0:\"\";'),
('qg6n9mljb7oo6a9809pcl686s9mjm87m','::1',1599455121,'__ci_last_regenerate|i:1599455115;cbt_user_id|s:5:\"admin\";cbt_nama|s:12:\"Achmad Lutfi\";cbt_level|s:5:\"admin\";cbt_opsi1|s:0:\"\";cbt_opsi2|s:0:\"\";'),
('rhafjlpmrr6dbsi05f24tl39qv9250e9','::1',1599378268,'__ci_last_regenerate|i:1599377957;cbt_user_id|s:5:\"admin\";cbt_nama|s:12:\"Achmad Lutfi\";cbt_level|s:5:\"admin\";cbt_opsi1|s:0:\"\";cbt_opsi2|s:0:\"\";'),
('ro5m2p8h2ikbdm0fltah1tkegn1vj00p','127.0.0.1',1599455011,'__ci_last_regenerate|i:1599454747;cbt_user_id|s:5:\"admin\";cbt_nama|s:12:\"Achmad Lutfi\";cbt_level|s:5:\"admin\";cbt_opsi1|s:0:\"\";cbt_opsi2|s:0:\"\";'),
('sfu4bpjieurrhjoln9he28hbhd2pviuj','::1',1644971826,'__ci_last_regenerate|i:1644971826;cbt_user_id|s:5:\"admin\";cbt_nama|s:12:\"Achmad Lutfi\";cbt_level|s:5:\"admin\";cbt_opsi1|s:0:\"\";cbt_opsi2|s:0:\"\";'),
('vrqaeb3lbmh6otg8hibq5jrr93hbblln','::1',1644977270,'__ci_last_regenerate|i:1644977270;cbt_user_id|s:5:\"admin\";cbt_nama|s:12:\"Achmad Lutfi\";cbt_level|s:5:\"admin\";cbt_opsi1|s:0:\"\";cbt_opsi2|s:0:\"\";');
/*!40000 ALTER TABLE `cbt_sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cbt_soal`
--

DROP TABLE IF EXISTS `cbt_soal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cbt_soal` (
  `soal_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `soal_topik_id` bigint(20) unsigned NOT NULL,
  `soal_detail` text NOT NULL,
  `soal_tipe` smallint(3) unsigned NOT NULL DEFAULT 1 COMMENT '1=Pilihan ganda, 2=essay, 3=jawaban singkat',
  `soal_kunci` text DEFAULT NULL COMMENT 'Kunci untuk soal jawaban singkat',
  `soal_difficulty` smallint(6) NOT NULL DEFAULT 1,
  `soal_aktif` tinyint(1) NOT NULL DEFAULT 0,
  `soal_audio` varchar(200) DEFAULT NULL,
  `soal_audio_play` int(11) NOT NULL DEFAULT 0,
  `soal_timer` smallint(10) DEFAULT NULL,
  `soal_inline_answers` tinyint(1) NOT NULL DEFAULT 0,
  `soal_auto_next` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`soal_id`),
  KEY `p_question_subject_id` (`soal_topik_id`),
  CONSTRAINT `cbt_soal_ibfk_1` FOREIGN KEY (`soal_topik_id`) REFERENCES `cbt_topik` (`topik_id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=217 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cbt_soal`
--

LOCK TABLES `cbt_soal` WRITE;
/*!40000 ALTER TABLE `cbt_soal` DISABLE KEYS */;
INSERT INTO `cbt_soal` VALUES
(53,7,'Apakah kepanjangan dari SMK ?',1,NULL,1,1,NULL,1,NULL,0,0),
(54,7,'<p>Nama salah satu Mall yang ada di kota genteng adalah ...<br></p>',1,NULL,1,1,NULL,1,NULL,0,0),
(55,7,'<p>Siapakah nama tokoh berikut ?</p><p><img src=\"[base_url]uploads/topik_7/soekarno.jpg\" style=\"max-width: 600px;\"><br></p>',1,NULL,1,1,NULL,1,NULL,0,0),
(56,7,'<p>Siapakah vokalis band NOAH ?<br></p>',1,NULL,1,1,NULL,1,NULL,0,0),
(57,7,'<p>Tanggal berapakah hari raya Idul Fitri ?</p>\r\n',1,NULL,1,1,NULL,1,NULL,0,0),
(61,7,'Jelaskan apa yang dimaksud dengan Jomblo ?',2,NULL,1,1,NULL,1,NULL,0,0),
(62,7,'<p>PT. Tiar Perkasa ingin melebarkan sayap usaha di bidang kuliner.</p><p>Dari pernyataan tersebut, sebutkan siapa kekasih mas Tiar ?</p>',2,NULL,1,1,NULL,1,NULL,0,0),
(63,7,'<p>Jelaskan kenapa Liverpool FC susah sekali untuk juara Premiere Leage !</p>\r\n',2,NULL,1,1,NULL,1,NULL,0,0),
(64,7,'<p>Apakah judul lagu berikut ini?</p>',1,NULL,1,1,'naff_-_akhirnya_ku_menemukanmu.mp3',1,NULL,0,0),
(161,7,'<p>Jelaskan arti poster dibawah ini ?</p>\r\n\r\n<p><img src=\"[base_url]uploads/topik_7/5a49b252e7aea.jpeg\" style=\"height:283px; width:300px\" /></p>\r\n',1,NULL,1,1,NULL,0,NULL,0,0),
(214,7,'<p>Berapakah 5x10 ?</p>\r\n',3,'50',1,1,NULL,0,NULL,0,0),
(215,7,'<p>asdasd</p>\r\n',1,'',1,1,NULL,0,NULL,0,0),
(216,8,'<p>sdf</p>\r\n',1,'',1,1,NULL,0,NULL,0,0);
/*!40000 ALTER TABLE `cbt_soal` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cbt_tes`
--

DROP TABLE IF EXISTS `cbt_tes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cbt_tes` (
  `tes_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tes_nama` varchar(255) NOT NULL,
  `tes_detail` text NOT NULL,
  `tes_begin_time` datetime DEFAULT NULL,
  `tes_end_time` datetime DEFAULT NULL,
  `tes_duration_time` smallint(10) unsigned NOT NULL DEFAULT 0,
  `tes_ip_range` varchar(255) NOT NULL DEFAULT '*.*.*.*',
  `tes_results_to_users` tinyint(1) NOT NULL DEFAULT 0,
  `tes_detail_to_users` tinyint(1) NOT NULL DEFAULT 0,
  `tes_score_right` decimal(10,2) DEFAULT 1.00,
  `tes_score_wrong` decimal(10,2) DEFAULT 0.00,
  `tes_score_unanswered` decimal(10,2) DEFAULT 0.00,
  `tes_max_score` decimal(10,2) NOT NULL DEFAULT 0.00,
  `tes_token` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`tes_id`),
  UNIQUE KEY `ak_test_name` (`tes_nama`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cbt_tes`
--

LOCK TABLES `cbt_tes` WRITE;
/*!40000 ALTER TABLE `cbt_tes` DISABLE KEYS */;
INSERT INTO `cbt_tes` VALUES
(5,'Tes Uji Coba','Tes Uji Coba','2020-09-06 17:03:00','2021-05-08 17:03:00',30,'*.*.*.*',1,0,1.00,0.00,0.00,10.00,0);
/*!40000 ALTER TABLE `cbt_tes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cbt_tes_soal`
--

DROP TABLE IF EXISTS `cbt_tes_soal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cbt_tes_soal` (
  `tessoal_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tessoal_tesuser_id` bigint(20) unsigned NOT NULL,
  `tessoal_user_ip` varchar(39) DEFAULT NULL,
  `tessoal_soal_id` bigint(20) unsigned NOT NULL,
  `tessoal_jawaban_text` text DEFAULT NULL,
  `tessoal_nilai` decimal(10,2) DEFAULT NULL,
  `tessoal_creation_time` datetime DEFAULT NULL,
  `tessoal_display_time` datetime DEFAULT NULL,
  `tessoal_change_time` datetime DEFAULT NULL,
  `tessoal_reaction_time` bigint(20) unsigned NOT NULL DEFAULT 0,
  `tessoal_ragu` int(1) NOT NULL DEFAULT 0 COMMENT '1=ragu, 0=tidak ragu',
  `tessoal_order` smallint(6) NOT NULL DEFAULT 1,
  `tessoal_num_answers` smallint(5) unsigned NOT NULL DEFAULT 0,
  `tessoal_comment` text DEFAULT NULL,
  `tessoal_audio_play` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`tessoal_id`),
  UNIQUE KEY `ak_testuser_question` (`tessoal_tesuser_id`,`tessoal_soal_id`),
  KEY `p_testlog_question_id` (`tessoal_soal_id`),
  KEY `p_testlog_testuser_id` (`tessoal_tesuser_id`),
  CONSTRAINT `cbt_tes_soal_ibfk_1` FOREIGN KEY (`tessoal_tesuser_id`) REFERENCES `cbt_tes_user` (`tesuser_id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `cbt_tes_soal_ibfk_2` FOREIGN KEY (`tessoal_soal_id`) REFERENCES `cbt_soal` (`soal_id`) ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cbt_tes_soal`
--

LOCK TABLES `cbt_tes_soal` WRITE;
/*!40000 ALTER TABLE `cbt_tes_soal` DISABLE KEYS */;
/*!40000 ALTER TABLE `cbt_tes_soal` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cbt_tes_soal_jawaban`
--

DROP TABLE IF EXISTS `cbt_tes_soal_jawaban`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cbt_tes_soal_jawaban` (
  `soaljawaban_tessoal_id` bigint(20) unsigned NOT NULL,
  `soaljawaban_jawaban_id` bigint(20) unsigned NOT NULL,
  `soaljawaban_selected` smallint(6) NOT NULL DEFAULT -1,
  `soaljawaban_order` smallint(6) NOT NULL DEFAULT 1,
  `soaljawaban_position` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`soaljawaban_tessoal_id`,`soaljawaban_jawaban_id`),
  KEY `p_logansw_answer_id` (`soaljawaban_jawaban_id`),
  KEY `p_logansw_testlog_id` (`soaljawaban_tessoal_id`),
  CONSTRAINT `cbt_tes_soal_jawaban_ibfk_1` FOREIGN KEY (`soaljawaban_tessoal_id`) REFERENCES `cbt_tes_soal` (`tessoal_id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `cbt_tes_soal_jawaban_ibfk_2` FOREIGN KEY (`soaljawaban_jawaban_id`) REFERENCES `cbt_jawaban` (`jawaban_id`) ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cbt_tes_soal_jawaban`
--

LOCK TABLES `cbt_tes_soal_jawaban` WRITE;
/*!40000 ALTER TABLE `cbt_tes_soal_jawaban` DISABLE KEYS */;
/*!40000 ALTER TABLE `cbt_tes_soal_jawaban` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cbt_tes_token`
--

DROP TABLE IF EXISTS `cbt_tes_token`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cbt_tes_token` (
  `token_id` int(11) NOT NULL AUTO_INCREMENT,
  `token_isi` varchar(20) NOT NULL,
  `token_user_id` int(11) NOT NULL,
  `token_ts` timestamp NOT NULL DEFAULT current_timestamp(),
  `token_aktif` int(11) NOT NULL DEFAULT 1 COMMENT 'Umur Token dalam menit, 1 = 1 hari penuh',
  `token_tes_id` bigint(20) NOT NULL DEFAULT 0,
  PRIMARY KEY (`token_id`),
  KEY `token_user_id` (`token_user_id`),
  CONSTRAINT `cbt_tes_token_ibfk_1` FOREIGN KEY (`token_user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cbt_tes_token`
--

LOCK TABLES `cbt_tes_token` WRITE;
/*!40000 ALTER TABLE `cbt_tes_token` DISABLE KEYS */;
INSERT INTO `cbt_tes_token` VALUES
(12,'299403',5,'2019-12-12 02:53:22',1,0);
/*!40000 ALTER TABLE `cbt_tes_token` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cbt_tes_topik_set`
--

DROP TABLE IF EXISTS `cbt_tes_topik_set`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cbt_tes_topik_set` (
  `tset_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tset_tes_id` bigint(20) unsigned NOT NULL,
  `tset_topik_id` bigint(20) unsigned NOT NULL,
  `tset_tipe` smallint(6) NOT NULL DEFAULT 1,
  `tset_difficulty` smallint(6) NOT NULL DEFAULT 1,
  `tset_jumlah` smallint(6) NOT NULL DEFAULT 1,
  `tset_jawaban` smallint(6) NOT NULL DEFAULT 0,
  `tset_acak_jawaban` int(11) NOT NULL DEFAULT 1,
  `tset_acak_soal` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`tset_id`),
  KEY `p_tsubset_test_id` (`tset_tes_id`),
  KEY `tsubset_subject_id` (`tset_topik_id`),
  CONSTRAINT `cbt_tes_topik_set_ibfk_1` FOREIGN KEY (`tset_tes_id`) REFERENCES `cbt_tes` (`tes_id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `cbt_tes_topik_set_ibfk_2` FOREIGN KEY (`tset_topik_id`) REFERENCES `cbt_topik` (`topik_id`) ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cbt_tes_topik_set`
--

LOCK TABLES `cbt_tes_topik_set` WRITE;
/*!40000 ALTER TABLE `cbt_tes_topik_set` DISABLE KEYS */;
INSERT INTO `cbt_tes_topik_set` VALUES
(5,5,7,0,1,10,5,1,1);
/*!40000 ALTER TABLE `cbt_tes_topik_set` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cbt_tes_user`
--

DROP TABLE IF EXISTS `cbt_tes_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cbt_tes_user` (
  `tesuser_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tesuser_tes_id` bigint(20) unsigned NOT NULL,
  `tesuser_user_id` bigint(20) unsigned NOT NULL,
  `tesuser_status` smallint(5) unsigned NOT NULL DEFAULT 0,
  `tesuser_creation_time` datetime NOT NULL,
  `tesuser_comment` text DEFAULT NULL,
  `tesuser_token` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`tesuser_id`),
  UNIQUE KEY `ak_testuser` (`tesuser_tes_id`,`tesuser_user_id`,`tesuser_status`),
  KEY `p_testuser_user_id` (`tesuser_user_id`),
  KEY `p_testuser_test_id` (`tesuser_tes_id`),
  CONSTRAINT `cbt_tes_user_ibfk_1` FOREIGN KEY (`tesuser_tes_id`) REFERENCES `cbt_tes` (`tes_id`) ON DELETE CASCADE,
  CONSTRAINT `cbt_tes_user_ibfk_2` FOREIGN KEY (`tesuser_user_id`) REFERENCES `cbt_user` (`user_id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cbt_tes_user`
--

LOCK TABLES `cbt_tes_user` WRITE;
/*!40000 ALTER TABLE `cbt_tes_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `cbt_tes_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cbt_tesgrup`
--

DROP TABLE IF EXISTS `cbt_tesgrup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cbt_tesgrup` (
  `tstgrp_tes_id` bigint(20) unsigned NOT NULL,
  `tstgrp_grup_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`tstgrp_tes_id`,`tstgrp_grup_id`),
  KEY `p_tstgrp_test_id` (`tstgrp_tes_id`),
  KEY `p_tstgrp_group_id` (`tstgrp_grup_id`),
  CONSTRAINT `cbt_tesgrup_ibfk_1` FOREIGN KEY (`tstgrp_tes_id`) REFERENCES `cbt_tes` (`tes_id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `cbt_tesgrup_ibfk_2` FOREIGN KEY (`tstgrp_grup_id`) REFERENCES `cbt_user_grup` (`grup_id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cbt_tesgrup`
--

LOCK TABLES `cbt_tesgrup` WRITE;
/*!40000 ALTER TABLE `cbt_tesgrup` DISABLE KEYS */;
INSERT INTO `cbt_tesgrup` VALUES
(5,5);
/*!40000 ALTER TABLE `cbt_tesgrup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cbt_topik`
--

DROP TABLE IF EXISTS `cbt_topik`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cbt_topik` (
  `topik_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `topik_modul_id` bigint(20) unsigned NOT NULL DEFAULT 1,
  `topik_nama` varchar(255) NOT NULL,
  `topik_detail` text DEFAULT NULL,
  `topik_aktif` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`topik_id`),
  UNIQUE KEY `ak_subject_name` (`topik_modul_id`,`topik_nama`),
  CONSTRAINT `cbt_topik_ibfk_1` FOREIGN KEY (`topik_modul_id`) REFERENCES `cbt_modul` (`modul_id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cbt_topik`
--

LOCK TABLES `cbt_topik` WRITE;
/*!40000 ALTER TABLE `cbt_topik` DISABLE KEYS */;
INSERT INTO `cbt_topik` VALUES
(7,9,'Soal Uji Coba','Kumpulan Soal untuk Uji Coba ',1),
(8,9,'Testing','testing',1);
/*!40000 ALTER TABLE `cbt_topik` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cbt_user`
--

DROP TABLE IF EXISTS `cbt_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cbt_user` (
  `user_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_grup_id` bigint(20) unsigned NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `user_email` varchar(255) DEFAULT NULL,
  `user_regdate` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_ip` varchar(39) DEFAULT NULL,
  `user_firstname` varchar(255) DEFAULT NULL,
  `user_birthdate` date DEFAULT NULL,
  `user_birthplace` varchar(255) DEFAULT NULL,
  `user_level` smallint(3) unsigned NOT NULL DEFAULT 1,
  `user_detail` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `ak_user_name` (`user_name`),
  KEY `user_groups_id` (`user_grup_id`),
  KEY `user_detail` (`user_detail`),
  CONSTRAINT `cbt_user_ibfk_1` FOREIGN KEY (`user_grup_id`) REFERENCES `cbt_user_grup` (`grup_id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cbt_user`
--

LOCK TABLES `cbt_user` WRITE;
/*!40000 ALTER TABLE `cbt_user` DISABLE KEYS */;
INSERT INTO `cbt_user` VALUES
(1,5,'lutfi','lutfi','','2018-01-11 04:38:27',NULL,'Muhammad Lutfial Hakim',NULL,NULL,1,'Ruang 1, Sesi 1'),
(2,5,'joko','joko','','2018-08-11 03:49:25',NULL,'Joko Susanto',NULL,NULL,1,'Ruang 1, Sesi 2');
/*!40000 ALTER TABLE `cbt_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cbt_user_grup`
--

DROP TABLE IF EXISTS `cbt_user_grup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cbt_user_grup` (
  `grup_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `grup_nama` varchar(255) NOT NULL,
  PRIMARY KEY (`grup_id`),
  UNIQUE KEY `group_name` (`grup_nama`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cbt_user_grup`
--

LOCK TABLES `cbt_user_grup` WRITE;
/*!40000 ALTER TABLE `cbt_user_grup` DISABLE KEYS */;
INSERT INTO `cbt_user_grup` VALUES
(5,'XI MM');
/*!40000 ALTER TABLE `cbt_user_grup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama` varchar(150) NOT NULL,
  `opsi1` varchar(75) NOT NULL,
  `opsi2` varchar(75) NOT NULL,
  `keterangan` varchar(150) NOT NULL,
  `level` varchar(50) NOT NULL,
  `ts` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `level` (`level`),
  CONSTRAINT `user_ibfk_1` FOREIGN KEY (`level`) REFERENCES `user_level` (`level`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES
(1,'admin','d033e22ae348aeb5660fc2140aec35850c4da997','Vicky Koroh','','','','admin','2015-07-29 18:12:03'),
(4,'operator','fe96dd39756ac41b74283a9292652d366d73931f','Operator','','','Operator','operator-soal','2018-03-30 12:58:55'),
(5,'joko','97c358728f7f947c9a279ba9be88308395c7cc3a','Joko','','','Haloo','admin','2019-12-12 02:53:12');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_akses`
--

DROP TABLE IF EXISTS `user_akses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_akses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `level` varchar(75) NOT NULL,
  `kode_menu` varchar(50) NOT NULL,
  `add` int(2) NOT NULL DEFAULT 1 COMMENT '0=false, 1=true',
  `edit` int(2) NOT NULL DEFAULT 1 COMMENT '0=false, 1=true',
  PRIMARY KEY (`id`),
  KEY `akses_kode_menu` (`kode_menu`),
  KEY `akses_level` (`level`),
  CONSTRAINT `user_akses_ibfk_2` FOREIGN KEY (`kode_menu`) REFERENCES `user_menu` (`kode_menu`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `user_akses_ibfk_3` FOREIGN KEY (`level`) REFERENCES `user_level` (`level`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=505 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_akses`
--

LOCK TABLES `user_akses` WRITE;
/*!40000 ALTER TABLE `user_akses` DISABLE KEYS */;
INSERT INTO `user_akses` VALUES
(254,'operator-soal','modul-daftar',1,1),
(255,'operator-soal','modul-filemanager',1,1),
(256,'operator-soal','modul-import',1,1),
(257,'operator-soal','modul-soal',1,1),
(258,'operator-soal','modul-topik',1,1),
(259,'operator-tes','tes-hasil-operator',1,1),
(260,'operator-tes','tes-token',1,1),
(481,'admin','laporan-analisis-butir-soal',1,1),
(482,'admin','peserta-kartu',1,1),
(483,'admin','peserta-group',1,1),
(484,'admin','peserta-daftar',1,1),
(485,'admin','modul-daftar',1,1),
(486,'admin','tes-daftar',1,1),
(487,'admin','tool-backup',1,1),
(488,'admin','tes-evaluasi',1,1),
(489,'admin','tool-exportimport-soal',1,1),
(490,'admin','modul-filemanager',1,1),
(491,'admin','tes-hasil',1,1),
(492,'admin','peserta-import',1,1),
(493,'admin','modul-import',1,1),
(494,'admin','modul-import-word',1,1),
(496,'admin','user_level',1,1),
(497,'admin','user_menu',1,1),
(498,'admin','user_atur',1,1),
(499,'admin','user-zyacbt',1,1),
(500,'admin','laporan-rekap',1,1),
(501,'admin','modul-soal',1,1),
(502,'admin','tes-tambah',1,1),
(503,'admin','tes-token',1,1),
(504,'admin','modul-topik',1,1);
/*!40000 ALTER TABLE `user_akses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_level`
--

DROP TABLE IF EXISTS `user_level`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_level` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `level` varchar(50) NOT NULL,
  `keterangan` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `level` (`level`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_level`
--

LOCK TABLES `user_level` WRITE;
/*!40000 ALTER TABLE `user_level` DISABLE KEYS */;
INSERT INTO `user_level` VALUES
(1,'admin','Administrator'),
(7,'operator-soal','Operator Soal'),
(8,'operator-tes','Operator Tes');
/*!40000 ALTER TABLE `user_level` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_log`
--

DROP TABLE IF EXISTS `user_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `log` varchar(250) NOT NULL,
  `ts` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_log`
--

LOCK TABLES `user_log` WRITE;
/*!40000 ALTER TABLE `user_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_menu`
--

DROP TABLE IF EXISTS `user_menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipe` int(11) NOT NULL DEFAULT 1 COMMENT '0=parent, 1=child',
  `parent` varchar(50) DEFAULT NULL,
  `kode_menu` varchar(50) NOT NULL,
  `nama_menu` varchar(100) NOT NULL,
  `url` varchar(150) NOT NULL DEFAULT '#',
  `icon` varchar(75) NOT NULL DEFAULT 'fa fa-circle-o',
  `urutan` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kode_menu` (`kode_menu`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_menu`
--

LOCK TABLES `user_menu` WRITE;
/*!40000 ALTER TABLE `user_menu` DISABLE KEYS */;
INSERT INTO `user_menu` VALUES
(1,0,'','user','Pengaturan','#','fa fa-user',20),
(3,1,'user','user_atur','Pengaturan User','manager/useratur','fa fa-circle-o',5),
(4,1,'user','user_level','Pengaturan Level','manager/userlevel','fa fa-circle-o',6),
(5,1,'user','user_menu','Pengaturan Menu','manager/usermenu','fa fa-circle-o',7),
(6,0,'','modul','Data Modul','#','fa fa-book',2),
(7,1,'modul','modul-daftar','Daftar Soal','manager/modul_daftar','fa fa-circle-o',5),
(8,1,'modul','modul-topik','Topik','manager/modul_topik','fa fa-circle-o',2),
(10,0,'','peserta','Data Peserta','#','fa fa-users',3),
(11,1,'peserta','peserta-daftar','Daftar Peserta','manager/peserta_daftar','fa fa-circle-o',2),
(12,1,'peserta','peserta-group','Daftar Group','manager/peserta_group','fa fa-circle-o',1),
(13,1,'peserta','peserta-import','Import Data Peserta','manager/peserta_import','fa fa-circle-o',3),
(14,0,'','tes','Data Tes','#','fa fa-tasks',4),
(15,1,'tes','tes-tambah','Tambah Tes','manager/tes_tambah','fa fa-circle-o',1),
(16,1,'tes','tes-daftar','Daftar Tes','manager/tes_daftar','fa fa-circle-o',2),
(17,1,'tes','tes-hasil','Hasil Tes','manager/tes_hasil','fa fa-circle-o',6),
(18,1,'modul','modul-soal','Soal','manager/modul_soal','fa fa-circle-o',3),
(19,1,'tes','tes-token','Token','manager/tes_token','fa fa-circle-o',8),
(22,1,'modul','modul-filemanager','File Manager','manager/modul_filemanager','fa fa-circle-o',6),
(24,1,'modul','modul-import','Import Soal Spreadsheet','manager/modul_import','fa fa-circle-o',4),
(25,1,'tes','tes-evaluasi','Evaluasi Tes','manager/tes_evaluasi','fa fa-circle-o',5),
(28,1,'tes','tes-hasil-operator','Hasil Tes Operator','manager/tes_hasil_operator','fa fa-circle-o',10),
(30,0,'','tool','Tool','#','fa fa-wrench',6),
(31,1,'tool','tool-backup','Database','manager/tool_backup','fa fa-database',1),
(32,1,'tes-laporan','laporan-rekap','Rekap Hasil Tes','manager/laporan_rekap_hasil','fa fa-circle-o',7),
(33,1,'tool','tool-exportimport-soal','Export / Import Soal','manager/tool_exportimport_soal','fa fa-circle-o',2),
(34,1,'user','user-zyacbt','Pengaturan UJIAN ONLINE','manager/pengaturan_zyacbt','fa fa-circle-o',1),
(37,1,'peserta','peserta-kartu','Cetak Kartu','manager/peserta_kartu','fa fa-circle-o',5),
(38,0,'','tes-laporan','Laporan','#','fa fa-print',5),
(41,1,'tes-laporan','laporan-analisis-butir-soal','Analisis Butir Soal','manager/laporan_analisis_butir_soal','fa fa-circle-o',1),
(42,1,'tes-laporan','laporan-analisis-soal','Analisis Soal','manager/laporan_analisis_soal','fa fa-circle-o',2),
(43,1,'modul','modul-import-word','Import Soal Word','manager/modul_import_word','fa fa-circle-o',4);
/*!40000 ALTER TABLE `user_menu` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2026-11-05 19:35:22
