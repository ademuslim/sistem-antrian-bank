

CREATE TABLE `admin` (
  `id_admin` int NOT NULL AUTO_INCREMENT,
  `nik` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `nama` varchar(60) COLLATE utf8mb4_general_ci NOT NULL,
  `no_hp` varchar(13) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(60) COLLATE utf8mb4_general_ci NOT NULL,
  `foto` varchar(120) COLLATE utf8mb4_general_ci NOT NULL,
  `role` varchar(5) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'admin',
  PRIMARY KEY (`id_admin`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO admin VALUES("3","1234123412341234","ade muslim","080012341234","$2y$10$k0ITrEiyY/EhYcudNe92J.FAoHap7tnDOIysdgB10iPWFZlEXXpN2","foto_658fe35f4894c.png","admin");



CREATE TABLE `antrian` (
  `id_antrian` int NOT NULL AUTO_INCREMENT,
  `kode_antrian` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `tanggal` date NOT NULL,
  `waktu_mulai` time DEFAULT NULL,
  `waktu_selesai` time DEFAULT NULL,
  `status_antrian` enum('menunggu','proses','batal','selesai') COLLATE utf8mb4_general_ci DEFAULT 'menunggu',
  `id_loket` int DEFAULT NULL,
  `id_nasabah` int DEFAULT NULL,
  PRIMARY KEY (`id_antrian`),
  KEY `id_loket` (`id_loket`),
  KEY `id_nasabah` (`id_nasabah`),
  CONSTRAINT `antrian_ibfk_1` FOREIGN KEY (`id_loket`) REFERENCES `loket` (`id_loket`),
  CONSTRAINT `antrian_ibfk_2` FOREIGN KEY (`id_nasabah`) REFERENCES `nasabah` (`id_nasabah`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;




CREATE TABLE `karyawan` (
  `id_karyawan` int NOT NULL AUTO_INCREMENT,
  `nik` varchar(16) COLLATE utf8mb4_general_ci NOT NULL,
  `nama` varchar(60) COLLATE utf8mb4_general_ci NOT NULL,
  `bagian` enum('teller','cs') COLLATE utf8mb4_general_ci NOT NULL,
  `no_hp` varchar(13) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(60) COLLATE utf8mb4_general_ci NOT NULL,
  `foto` varchar(120) COLLATE utf8mb4_general_ci NOT NULL,
  `role` varchar(8) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'karyawan',
  PRIMARY KEY (`id_karyawan`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO karyawan VALUES("1","1234567890123457","esya mulyana tarmedi","teller","080022222222","$2y$10$SdW.0SsXhi936cREjTvf5OAc6aSCrNj5NrMiuZLmRwOMOGGh9wz12","foto_658fde99bc71c.png","karyawan");
INSERT INTO karyawan VALUES("2","1111222211112222","adi sobari","teller","080066666666","$2y$10$/Btce95BZ2kyA1w.OUEcPe9ZYF/sKSOkuYe..h0stVzSklw.cWN62","foto_65900b9adaddd.png","karyawan");
INSERT INTO karyawan VALUES("5","0000000000000","arsyila","cs","080098989898","$2y$10$.8L.9xgz6NJGXcpoQsjpGe9dnwgSCMId6bJ3mTF6QBRv/UUoe7Fi2","foto_6590e80ab1ef1.png","karyawan");
INSERT INTO karyawan VALUES("6","9090990909090","krisna listiyanti","cs","080012121212","$2y$10$Qk/pVbicScWk6yvsn/y8EOdiZAxCSCiQj5vX4TOfwCxnJxTVzifRK","foto_65943a00e65f2.jpg","karyawan");



CREATE TABLE `loket` (
  `id_loket` int NOT NULL AUTO_INCREMENT,
  `kode_loket` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `nama_loket` varchar(60) COLLATE utf8mb4_general_ci NOT NULL,
  `status_loket` enum('aktif','tutup') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'tutup',
  `id_karyawan` int DEFAULT NULL,
  PRIMARY KEY (`id_loket`),
  KEY `id_karyawan` (`id_karyawan`),
  CONSTRAINT `loket_ibfk_1` FOREIGN KEY (`id_karyawan`) REFERENCES `karyawan` (`id_karyawan`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO loket VALUES("5","t1","teller 1","tutup","1");
INSERT INTO loket VALUES("6","t2","teller 2","tutup","2");
INSERT INTO loket VALUES("7","cs1","customer service 1","tutup","5");
INSERT INTO loket VALUES("8","cs2","customer service 2","tutup","6");



CREATE TABLE `nasabah` (
  `id_nasabah` int NOT NULL AUTO_INCREMENT,
  `nik` varchar(16) COLLATE utf8mb4_general_ci NOT NULL,
  `nama` varchar(60) COLLATE utf8mb4_general_ci NOT NULL,
  `jenis_kelamin` enum('laki-laki','perempuan') COLLATE utf8mb4_general_ci NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `alamat` varchar(120) COLLATE utf8mb4_general_ci NOT NULL,
  `no_hp` varchar(13) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(60) COLLATE utf8mb4_general_ci NOT NULL,
  `foto` varchar(120) COLLATE utf8mb4_general_ci NOT NULL,
  `role` varchar(7) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'nasabah',
  PRIMARY KEY (`id_nasabah`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO nasabah VALUES("1","1234567890123456","irvanda nur arifin","laki-laki","2000-02-29","cikarang","080033333333","$2y$10$J5ntdNyc7HP9vaIdKfXGCOE6M9o0N05OoqN.MVLZsjEWvHKbvNYPO","","nasabah");
INSERT INTO nasabah VALUES("2","1234567890098765","arsyila","perempuan","2022-01-30","cikarang","080044444444","$2y$10$SbbensGmwnulAV12sto/bORFGGdf77S5HOvYNfPHLuctLXahTFR1m","","nasabah");
INSERT INTO nasabah VALUES("3","1234567890567890","de gea","laki-laki","2023-12-30","cikarang","080055555555","$2y$10$gZfBbqUej/8U3.c5DfRLDuSV9JVvkUzNWfWsu4iDHx0Df0Lqy/CK.","","nasabah");

