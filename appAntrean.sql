-- --------------------------------------------------------
-- Host:                         10.20.30.26
-- Server version:               8.0.37 - MySQL Community Server - GPL
-- Server OS:                    Linux
-- HeidiSQL Version:             11.3.0.6295
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping structure for procedure pendaftaran.getKunjunganFarmasi
DELIMITER //
CREATE PROCEDURE `getKunjunganFarmasi`(
	IN `PKUNJUNGAN` CHAR(10),
	IN `PSTATUS` CHAR(5)
)
BEGIN
	SELECT k.NOPEN, DATE_FORMAT(k.MASUK, "%d-%m-%Y %H:%i:%s") AS TANGGAL, p.NORM, p2.NAMA 
	FROM pendaftaran.kunjungan k 
	LEFT JOIN `pendaftaran`.pendaftaran p ON p.NOMOR = k.NOPEN 
	LEFT JOIN `master`.pasien p2 ON p.NORM = p2.NORM 
	LEFT JOIN `master`.ruangan r ON r.ID = k.RUANGAN
	WHERE k.RUANGAN = PKUNJUNGAN AND k.STATUS = PSTATUS
	AND DATE(k.MASUK) = DATE(NOW())
    ORDER BY k.MASUK ASC;
END//
DELIMITER ;

-- Dumping structure for procedure pendaftaran.getOrderFarmasi
DELIMITER //
CREATE PROCEDURE `getOrderFarmasi`(
	IN `PKUNJUNGAN` CHAR(10),
	IN `PSTATUS` CHAR(5)
)
BEGIN
	SELECT k.NOPEN, DATE_FORMAT(or2.TANGGAL, "%d-%m-%Y %H:%i:%s") AS TANGGAL, p.NORM, p2.NAMA 
	FROM layanan.order_resep or2 
	LEFT JOIN `pendaftaran`.kunjungan k ON k.NOMOR = or2.KUNJUNGAN
	LEFT JOIN `pendaftaran`.pendaftaran p ON p.NOMOR = k.NOPEN 
	LEFT JOIN `master`.pasien p2 ON p.NORM = p2.NORM 
	LEFT JOIN `master`.ruangan r ON r.ID = k.RUANGAN
	WHERE or2.TUJUAN = PKUNJUNGAN AND or2.STATUS = PSTATUS
	AND DATE(or2.TANGGAL) = DATE(NOW())
	ORDER BY k.MASUK ASC;
END//
DELIMITER ;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
