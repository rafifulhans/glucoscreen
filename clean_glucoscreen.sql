SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `informasis`
--

DROP TABLE IF EXISTS `informasis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `informasis` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `judul` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kategori` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gds` double(8,2) DEFAULT NULL,
  `hasil_pemeriksaan` text COLLATE utf8mb4_unicode_ci,
  `pesan_utama` text COLLATE utf8mb4_unicode_ci,
  `gejala_klasik` tinyint(1) NOT NULL DEFAULT '0',
  `isi` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `informasis`
--

LOCK TABLES `informasis` WRITE;
/*!40000 ALTER TABLE `informasis` DISABLE KEYS */;
INSERT INTO `informasis` VALUES (1,'Pengertiannya',NULL,NULL,NULL,NULL,0,'Glukosa adalah gula sederhana (monosakarida) yang merupakan sumber energi utama bagi tubuh. Ia merupakan hasil akhir dari pemecahan karbohidrat dalam makanan dan digunakan oleh sel-sel tubuh untuk menghasilkan energi yang dibutuhkan untuk berbagai fungsi, termasuk pergerakan, pemikiran, dan menjaga suhu tubuh. apa?','2025-08-03 08:48:38','2025-08-04 05:39:46'),(2,'Tanda & Gejalanya',NULL,NULL,NULL,NULL,0,'Gejala gula darah tinggi (hiperglikemia) bisa beragam, namun beberapa tanda umum meliputi sering merasa haus dan lapar, sering buang air kecil (terutama di malam hari), kelelahan, penglihatan kabur, penurunan berat badan yang tidak dijelaskan, serta luka yang sulit sembuh. Selain itu, penderita juga mungkin mengalami infeksi berulang, mulut kering, dan masalah pada kulit. \r\nBerikut adalah beberapa gejala yang perlu diperhatikan:\r\nRasa Haus dan Lapar Berlebihan:\r\nPeningkatan rasa haus (polidipsia) dan lapar (polifagia) adalah salah satu gejala awal diabetes yang paling umum. \r\nSering Buang Air Kecil:\r\nGinjal berusaha membuang kelebihan glukosa melalui urin, sehingga menyebabkan sering buang air kecil, bahkan di malam hari (nokturia). \r\nPenurunan Berat Badan yang Tidak Biasa:\r\nMeskipun makan dengan porsi yang sama atau lebih, penderita diabetes bisa mengalami penurunan berat badan secara drastis. \r\nKelelahan dan Lemas:\r\nGlukosa adalah sumber energi utama, dan jika tubuh tidak dapat menggunakannya dengan baik, penderita akan merasa lelah dan lemas. \r\nPenglihatan Kabur:\r\nGula darah tinggi dapat mempengaruhi lensa mata dan pembuluh darah di mata, menyebabkan penglihatan kabur.\r\napa?','2025-08-03 08:49:02','2025-08-04 05:39:46'),(9,'Modul 1 (Kategori Normal)','normal',95.00,'Kadar GDS < 126 mg/dL (Tanpa Gejala Klasik)','Selamat! Kadar gula darah Anda saat ini NORMAL. Mari bersama-sama kita jaga agar tetap stabil dan terhindar dari risiko diabetes di masa depan!',0,'','2026-08-11 03:39:43','2026-08-11 03:40:56'),(10,'Modul 2 (Kategori Prediabetes)','prediabetes',150.00,'Kadar GDS 126-199 mg/dL','Waspada Siaga! Kadar Gula Darah Anda Berada DI ATAS NORMAL, Tetapi Anda BELUM Terkena Diabetes. Mari Cegah Dari Sekarang Supaya Gula Darah Anda Bisa Kembali Normal!',0,'','2026-08-11 03:39:43','2026-08-11 04:07:29'),(11,'Modul 3 (Kategori Diabetes)','diabetes',250.00,'Kadar GDS >=200 mg/dL + Disertai Gejala Klasik (Sering Kencing, Sering Haus, Cepat Lapar, Berat Badan Turun Drastis)','Kadar gula darah anda saat ini sangat tinggi, anda masuk kategori Diabetes! Kondisi Diabetes dapat dikendalikan dengan baik jika kita disiplin',1,'','2026-08-11 03:39:43','2026-08-11 04:19:47');
/*!40000 ALTER TABLE `informasis` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kaders`
--

DROP TABLE IF EXISTS `kaders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kaders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `pemimpin_user_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `kaders_user_id_foreign` (`user_id`),
  KEY `kaders_pemimpin_user_id_foreign` (`pemimpin_user_id`),
  CONSTRAINT `kaders_pemimpin_user_id_foreign` FOREIGN KEY (`pemimpin_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `kaders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kaders`
--

LOCK TABLES `kaders` WRITE;
/*!40000 ALTER TABLE `kaders` DISABLE KEYS */;
INSERT INTO `kaders` VALUES (4,11,8,'2026-08-11 07:12:13','2026-08-11 07:12:13');
/*!40000 ALTER TABLE `kaders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `materis`
--

DROP TABLE IF EXISTS `materis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `materis` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `informasi_id` bigint unsigned NOT NULL,
  `urutan` int NOT NULL DEFAULT '0',
  `isi` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `materis_informasi_id_foreign` (`informasi_id`),
  CONSTRAINT `materis_informasi_id_foreign` FOREIGN KEY (`informasi_id`) REFERENCES `informasis` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `materis`
--

LOCK TABLES `materis` WRITE;
/*!40000 ALTER TABLE `materis` DISABLE KEYS */;
INSERT INTO `materis` VALUES (31,9,1,'<b>Yuk, Atur Isi Piringmu!</b><br><ol><li>\r\nBagi piring makanmu menjadi 3 bagian: 1/2 piring berisi sayur dan buah-buahan segar, 1/4 piring berisi karbohidrat (nasi/jagung/ubi), dan 1/4 piring berisi lauk pauk berprotein (ikan, tempe, tahu, atau dada ayam).</li><li>\r\nKurangi konsumsi minuman kemasan manis, boba, teh manis berlebih, dan makanan olahan tinggi gula.</li></ol>','2026-08-11 03:40:56','2026-08-11 03:40:56'),(32,9,2,'<b>Ayo Bergerak Aktif Setiap Hari!</b><br><ol><li>Sempatkan beraktivitas fisik minimal 30 menit sehari (atau total 150 menit dalam seminggu).</li><li>\r\nPilih kegiatan yang menyenangkan: jalan santai di pagi hari, berkebun, menyapu, atau bersepeda di sekitar lingkungan.</li></ol>','2026-08-11 03:40:56','2026-08-11 03:40:56'),(33,9,3,'<b>Jaga Berat Badan Tetap Ideal</b><div>Pantau berat badanmu agar tetap seimbang. Targetkan Indeks Massa Tubuh (IMT) ideal berada pada rentang 18,5 - 22,9 Kg/m2.</div>','2026-08-11 03:40:56','2026-08-11 03:40:56'),(34,9,4,'<b>Lakukan Pemeriksaan Rutin</b><br>\r\nMeskipun hasil saat ini normal, tetap lakukan cek gula darah ulang bersama Kader Kesehatan minimal 1 bulan sekali.','2026-08-11 03:40:56','2026-08-11 03:40:56'),(35,10,1,'<b>Targetkan Penurunan Berat Badan (Bagi yang memiliki BB berlebih)</b><br>\r\nTurunkan berat badan secara bertahap sebesar 5% - 7% dari berat badan saat ini dalam waktu 6 bulan.','2026-08-11 04:07:29','2026-08-11 04:07:29'),(36,10,2,'<b>Kelola Kalori</b><br><ol><li>\r\nKurangi porsi makanan harian sebesar 500 - 1.000 kkal/hari dari porsi biasanya.</li><li>Ganti total minuman manis (es teh manis, kopi sachet, sirup) dengan Air Putih Murni.</li></ol>','2026-08-11 04:07:29','2026-08-11 04:07:29'),(37,10,3,'<b>Olahraga Terstruktur</b><br><ol><li>Lakukan olahraga aerobik sedang sebanyak 5 kali seminggu (30 menit per sesi).</li><li>Pilihan olahraga terbaik: jalan cepat, senam aerobik, bersepeda, atau berenang. Pastikan tubuh berkeringat dan denyut jantung meningkat sedang.</li></ol>','2026-08-11 04:07:29','2026-08-11 04:07:29'),(38,10,4,'<b>Stop Merokok!</b><br>\r\nBahan kimia dalam rokok memperburuk kerja insulin dalam tubuh. Segera kurangi dan hentikan kebiasaan merokok demi melindungi pembuluh darah Anda.','2026-08-11 04:07:29','2026-08-11 04:07:29'),(44,11,1,'<b>Pilar 1: Pahami dan Jangan Takut</b><br>\r\nPahami bahwa diabetes bukan akhir dari segalanya. Dengan pola hidup yang tepat, penderita diabetes tetap bisa beraktivitas secara produktif dan sehat.','2026-08-11 04:25:32','2026-08-11 04:25:32'),(45,11,2,'<b>Pilar 2: Disiplin Pola Makan 3J (Jumlah, Jenis, Jadwal)</b><br>\r\nBagi porsi makan menjadi 3 kali makan besar dan 2-3 kali selingan ringan.<br>\r\nKurangi nasi putih, ganti dengan karbohidrat kompleks (nasi merah/oatmeal), dan hindari makanan manis/gorengan.','2026-08-11 04:25:32','2026-08-11 04:25:32'),(46,11,3,'<b>Pilar 3: Latihan Fisik Teratur</b><br>\r\nTetap aktif bergerak 30 menit sehari. Pilih olahraga ringan-sedang yang tidak memicu cedera pada kaki.','2026-08-11 04:25:32','2026-08-11 04:25:32'),(47,11,4,'<b>Pilar 4: Segera Konsultasi Medis &amp; Pengobatan</b><br>\r\nDatang ke fasilitas kesehatan untuk mendapatkan konfirmasi pemeriksaan dokter dan petunjuk pengobatan yang tepat. Patuhi minum obat sesuai dosis yang dianjurkan tenaga medis.','2026-08-11 04:25:32','2026-08-11 04:25:32'),(48,11,5,'<b>PERINGATAN KESELAMATAN!</b><br>\r\nKenali tanda bahaya gula darah rendah (hipoglikemia &lt;70 mg/dL) dengan ciri-ciri keringat dingin, badan gemetar, pusing, jantung berdebar, mata berkunang-kunang sampai lemas. Segera berikan pertolongan dengan menggunakan 2-3 butir permen manis atau minum 1 gelas air gula.<br>\r\nPeriksa telapak dan sela-sela jari kaki setiap hari, jangan biarkan ada luka sekecil apa pun, lecet atau kapalan.','2026-08-11 04:25:32','2026-08-11 04:25:32'),(49,11,6,'<b>Petunjuk Rujukan</b><div><ol><li><span lang=\"SV\" style=\"text-align: justify; text-indent: -18pt;\">Dampingi warga yang masuk dalam Kategori Diabetes (GDS&nbsp;</span><img width=\"9\" height=\"21\" src=\"data:image/emf;base64,iVBORw0KGgoAAAANSUhEUgAAAAoAAAAkCAYAAACwlKv7AAAAAXNSR0IArs4c6QAAAHhlWElmTU0AKgAAAAgABQESAAMAAAABAAEAAAEaAAUAAAABAAAASgEbAAUAAAABAAAAUgEoAAMAAAABAAIAAIdpAAQAAAABAAAAWgAAAAAAAACQAAAAAQAAAJAAAAABAAKgAgAEAAAAAQAAAAqgAwAEAAAAAQAAACQAAAAA4GLx2AAAAAlwSFlzAAAWJQAAFiUBSVIk8AAAAN1JREFUOBHt0b0OwVAYxvHThsEikYhBDAa3IDaJWzCYfMzCFRh8dDObJEZXIHYXYRMXYDSJiOD/NCqk2los4k1+PW3fJz15Ty1jzBVDBNWAhh3j4mAUlPLe295N1KpgEl2kwsIKnlHCFnNUYOGlFDyggTxWcLBBD1k86t0gBbpLXDBRUlM/V4aHOlo4oY0cjLaWKhZYI48miphCM7hfTLN2MEMNR/hKW+8xvnfKvoQxcb1TMAFtFVQ7r/Fuaq+nVf/aHURrZGnij+qnghYjX+GEjN6np9y//ifw7RO4AbafHKOljQ+PAAAAAElFTkSuQmCC\" v:shapes=\"_x0000_i1025\" style=\"\">&nbsp;<span style=\"text-align: justify; text-indent: -18pt;\">200 mg/dL).</span></li><li>Tunjukkan ringkasan riwayat pemeriksaan pada aplikasi <span style=\"text-align: justify; text-indent: -18pt;\">GlucoScreen</span><span style=\"text-align: justify; text-indent: -18pt;\"> kepada petugas kesehatan.</span></li><li>Antarkan/arahkan warga untuk melakukan konfirmasi pemeriksaan diagnosis laboratorium (GDP/G2PP/HbA1c) resmi di Puskesmas.</li></ol></div><div><span lang=\"SV\" style=\"color: rgb(0, 0, 0); font-size: 12pt; line-height: 24px; font-family: &quot;Times New Roman&quot;, serif;\"><br clear=\"all\" style=\"break-before: page;\"></span><span style=\"color: rgb(0, 0, 0); font-size: medium;\"></span></div>','2026-08-11 04:25:32','2026-08-11 04:25:32');
/*!40000 ALTER TABLE `materis` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2019_08_19_000000_create_failed_jobs_table',1),(2,'2019_12_14_000001_create_personal_access_tokens_table',1),(3,'2025_08_02_111844_glucocare',1),(4,'2025_08_11_000000_add_kategori_to_informasis_table',2),(5,'2025_08_11_001000_add_gejala_klasik_to_informasis_table',3),(6,'2025_08_11_002000_add_gds_to_informasis_table',4),(7,'2025_08_11_003000_add_module_content_to_informasis_table',5),(8,'2025_08_11_004000_create_materis_table',6),(9,'2026_08_11_180105_add_kategori_and_gejala_to_pengunjungs_table',7);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pengunjungs`
--

DROP TABLE IF EXISTS `pengunjungs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pengunjungs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nik` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_kunjungan` date NOT NULL,
  `gds` double(8,2) NOT NULL,
  `kategori` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gejala_klasik` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kader_id` bigint unsigned NOT NULL,
  `posyandu_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pengunjungs_kader_id_foreign` (`kader_id`),
  KEY `pengunjungs_posyandu_id_foreign` (`posyandu_id`),
  CONSTRAINT `pengunjungs_kader_id_foreign` FOREIGN KEY (`kader_id`) REFERENCES `kaders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pengunjungs_posyandu_id_foreign` FOREIGN KEY (`posyandu_id`) REFERENCES `posyandus` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pengunjungs`
--

LOCK TABLES `pengunjungs` WRITE;
/*!40000 ALTER TABLE `pengunjungs` DISABLE KEYS */;
INSERT INTO `pengunjungs` VALUES (1,'han','3201094995','nor','2026-08-12',19.00,NULL,NULL,4,4,'2026-08-11 19:20:06','2026-08-11 19:20:06'),(2,'Handoko','3320199495969','Demaan','2026-08-12',200.00,NULL,NULL,4,4,'2026-08-11 19:39:24','2026-08-11 19:39:24'),(3,'Yusril','3320188847694876','Jakarta','2026-08-13',90.00,NULL,NULL,4,4,'2026-08-11 20:14:53','2026-08-11 20:14:53');
/*!40000 ALTER TABLE `pengunjungs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
INSERT INTO `personal_access_tokens` VALUES (1,'App\\Models\\Kader',1,'kader-token','36752df049d939a38a601922f3f0015ee535159b8209f53215d2f5125d8cef5b','[\"*\"]','2025-08-05 10:17:42',NULL,'2025-08-03 21:44:46','2025-08-05 10:17:42'),(2,'App\\Models\\Kader',1,'kader-token','ae34ca5151c37e444b9caba6179c2d99a07fff558f1868a1c27ec4683639ba69','[\"*\"]',NULL,NULL,'2025-08-05 09:30:36','2025-08-05 09:30:36'),(3,'App\\Models\\Kader',1,'kader-token','4128a4ccfeffa72227b1d9be97f3a490ce3379da9ea2040ff97e2b83398c5e6d','[\"*\"]',NULL,NULL,'2025-08-05 09:33:28','2025-08-05 09:33:28'),(4,'App\\Models\\Kader',1,'kader-token','9c3c36d02cd8f66d7bdc2c34edd672596dc41b3594844ce1212781525e5f7a07','[\"*\"]',NULL,NULL,'2025-08-05 09:35:42','2025-08-05 09:35:42'),(5,'App\\Models\\Kader',1,'kader-token','ee2974bef22747bb3be8123814584bef525b381fb1c09600aadb795c5d165853','[\"*\"]',NULL,NULL,'2025-08-05 09:38:55','2025-08-05 09:38:55'),(6,'App\\Models\\Kader',1,'kader-token','864f057b1a96d952f2341d22344ba322b6f4bc5bb61b613e9de2a5e11dc71b2c','[\"*\"]',NULL,NULL,'2025-08-05 09:39:17','2025-08-05 09:39:17'),(7,'App\\Models\\Kader',1,'kader-token','1903381c90561b1b341798f3dd4b51c6f484b1e3db8f2b80a630b7f8e9343847','[\"*\"]',NULL,NULL,'2025-08-05 09:40:40','2025-08-05 09:40:40'),(8,'App\\Models\\Kader',4,'kader-token','e1a4f391ca7897a3b0df5c4d8996b2af618be1618538a771774bf37ce8874395','[\"*\"]',NULL,NULL,'2026-08-11 07:12:37','2026-08-11 07:12:37'),(9,'App\\Models\\Kader',4,'kader-token','e6e715d48c770c55fa807d9065288d2928b9df78833460b18be9461a7dc2101c','[\"*\"]',NULL,NULL,'2026-08-11 07:12:46','2026-08-11 07:12:46'),(10,'App\\Models\\Kader',4,'kader-token','b35870a5f6437e6f83db30823f3d51f6f052fc06b9caa087850e29bed4272e68','[\"*\"]',NULL,NULL,'2026-08-11 09:37:52','2026-08-11 09:37:52'),(11,'App\\Models\\Kader',4,'kader-token','4c0e80a98158cb54107ade6664641fa0a78556f942146b14237bb9acceeeb2d5','[\"*\"]','2026-08-11 17:06:05',NULL,'2026-08-11 11:38:49','2026-08-11 17:06:05'),(12,'App\\Models\\Kader',4,'kader-token','b470de979743fcc66226cdd9dfdc8ebc9f3f62e571da012ba8fd1f8ef160d648','[\"*\"]','2026-08-11 19:20:33',NULL,'2026-08-11 17:08:29','2026-08-11 19:20:33'),(13,'App\\Models\\Kader',4,'kader-token','0ad1225e97382108a0df72a283d442ab822b09c92fefb35b2e62c4c4618071fd','[\"*\"]','2026-08-11 20:15:37',NULL,'2026-08-11 19:37:58','2026-08-11 20:15:37');
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `posyandus`
--

DROP TABLE IF EXISTS `posyandus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `posyandus` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_kader` int NOT NULL DEFAULT '0',
  `total_pengunjung` int NOT NULL DEFAULT '0',
  `user_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `posyandus_user_id_foreign` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `posyandus`
--

LOCK TABLES `posyandus` WRITE;
/*!40000 ALTER TABLE `posyandus` DISABLE KEYS */;
INSERT INTO `posyandus` VALUES (4,'Mardi Utama Mana','jl siliwangi no.56',2,0,8,'2025-08-05 20:49:45','2026-08-11 07:12:13'),(5,'Asyifa','asdafdaf',0,0,9,'2025-08-07 06:50:21','2025-08-07 06:51:01');
/*!40000 ALTER TABLE `posyandus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `readable_password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','pemimpin','kader') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_username_unique` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

INSERT INTO `users` VALUES (1,'admin','admin','$2y$10$Zc7lUB6w6afJgyxZr.ags.laZRj3PxTeMtkC0A1TsEA04OYK9Tz1m','12345678','admin','2025-08-03 15:47:25','2025-08-03 15:47:25'),(8,'Muh Hans','hans','$2y$10$pNCrxDhH9ynPd/aiPkufyOe8x6Eq2Svib7.DHqJsQpmSaPx9FHmTu','123123123','pemimpin','2025-08-07 06:41:39','2025-08-07 06:57:36'),(9,'Muh Fallah','fallah','$2y$10$s4baYpJUtBAHaD6OuA6NFuGp6IZg4wMcSFdR5jHvFF3x/JRS80rpi','54785478','pemimpin','2025-08-07 06:51:01','2025-08-07 06:54:02'),(11,'Shang Ryu','shangryu','$2y$10$UWgdUPQS.jdh3uhebu3I9OsGRwYnmJcxbJkoydG1NvrWFE7AS4N86','123123123','kader','2026-08-11 07:12:13','2026-08-11 07:19:43');

