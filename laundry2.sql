-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Waktu pembuatan: 27 Des 2021 pada 08.28
-- Versi server: 5.6.38
-- Versi PHP: 7.4.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tgs_laundry_2`
--

DELIMITER $$
--
-- Prosedur
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `cekLogin` (IN `usrnm` VARCHAR(255))  BEGIN

SELECT * FROM tb_user WHERE username = usrnm;

END$$

--
-- Fungsi
--
CREATE DEFINER=`root`@`localhost` FUNCTION `cekPaket` (`idOutlet` VARCHAR(20), `jns` VARCHAR(25)) RETURNS INT(11) NO SQL
BEGIN
DECLARE hasil INT;
SELECT COUNT(*) FROM tb_paket WHERE id_outlet = idOutlet AND jenis = jns INTO hasil;
RETURN hasil;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_outlet`
--

CREATE TABLE `tb_outlet` (
  `id_outlet` varchar(20) NOT NULL,
  `nama_outlet` varchar(35) NOT NULL,
  `alamat_outlet` text NOT NULL,
  `telp` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `tb_outlet`
--

INSERT INTO `tb_outlet` (`id_outlet`, `nama_outlet`, `alamat_outlet`, `telp`) VALUES
('OTL0001', 'Outlet 1', 'Jl. Satu no.12', '08563621676'),
('OTL0002', 'Outlet 2', 'Jl. Dua', '085265454343'),
('OTL0003', 'Outlet 3', 'Jl . Tiga', '086525454343'),
('OTL0004', 'Outlet 4', 'Jl. Sudirman', '086275565453');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_pakaian`
--

CREATE TABLE `tb_pakaian` (
  `id_pakaian` int(15) NOT NULL,
  `id_transaksi` varchar(20) NOT NULL,
  `jenis_pakaian` varchar(35) NOT NULL,
  `jumlah` int(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `tb_pakaian`
--

INSERT INTO `tb_pakaian` (`id_pakaian`, `id_transaksi`, `jenis_pakaian`, `jumlah`) VALUES
(3, 'TRX20210817000835', 'Kaos', 3),
(4, 'TRX20210817000835', 'Celana', 2),
(5, 'TRX20210817000835', 'Jaket', 1),
(7, 'TRX20210817212151', 'Kaos', 3),
(11, 'TRX20210819004737', 'Kos', 6),
(12, 'TRX20211225183451', 'Kaos', 3),
(13, 'TRX20211225183451', 'Kemeja', 5);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_paket`
--

CREATE TABLE `tb_paket` (
  `id_paket` int(15) NOT NULL,
  `id_outlet` varchar(20) NOT NULL,
  `jenis` enum('reguler','kilat') NOT NULL,
  `harga` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `tb_paket`
--

INSERT INTO `tb_paket` (`id_paket`, `id_outlet`, `jenis`, `harga`) VALUES
(6, 'OTL0001', 'reguler', 5000),
(7, 'OTL0001', 'kilat', 7000),
(8, 'OTL0002', 'reguler', 6000),
(9, 'OTL0002', 'kilat', 9000),
(10, 'OTL0003', 'reguler', 11000),
(11, 'OTL0004', 'kilat', 8000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_pelanggan`
--

CREATE TABLE `tb_pelanggan` (
  `id_pelanggan` varchar(20) NOT NULL,
  `nama_pelanggan` varchar(35) NOT NULL,
  `alamat` text NOT NULL,
  `telp` varchar(15) NOT NULL,
  `id_outlet` varchar(20) NOT NULL,
  `jenis_langganan` enum('reguler','member') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `tb_pelanggan`
--

INSERT INTO `tb_pelanggan` (`id_pelanggan`, `nama_pelanggan`, `alamat`, `telp`, `id_outlet`, `jenis_langganan`) VALUES
('CST0001', 'Budi Santoso', 'Jl. Parangtritis Km.19', '085455654323', 'OTL0001', 'member'),
('CST0002', 'Ayu Dewi', 'Jl. Jendral Sudirman No.15', '086576555545', 'OTL0002', 'reguler'),
('CST0003', 'Agus Suhartono', 'Jl. Senopati Km.19', '086543212345', 'OTL0003', 'reguler'),
('CST0005', 'Harun Sanjaya', 'Jl. Senopati', '085613442456', 'OTL0001', 'member'),
('CST0006', 'Galih Syaputra', 'Jl. Jklmn', '086242725177', 'OTL0003', 'reguler');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_transaksi`
--

CREATE TABLE `tb_transaksi` (
  `id_transaksi` varchar(25) NOT NULL,
  `id_user` varchar(50) NOT NULL,
  `id_outlet` varchar(20) NOT NULL,
  `id_pelanggan` varchar(20) NOT NULL,
  `id_paket` int(15) NOT NULL,
  `tgl_masuk` date NOT NULL,
  `tgl_selesai` date NOT NULL,
  `tgl_bayar` date DEFAULT NULL,
  `berat` double NOT NULL,
  `total_bayar` int(20) NOT NULL,
  `status_bayar` enum('belum','lunas') NOT NULL,
  `status_transaksi` enum('proses','selesai','diambil') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `tb_transaksi`
--

INSERT INTO `tb_transaksi` (`id_transaksi`, `id_user`, `id_outlet`, `id_pelanggan`, `id_paket`, `tgl_masuk`, `tgl_selesai`, `tgl_bayar`, `berat`, `total_bayar`, `status_bayar`, `status_transaksi`) VALUES
('TRX20210817000835', '60fa32a8db785', 'OTL0001', 'CST0001', 7, '2021-08-17', '2021-08-18', '2021-08-17', 3.6, 25200, 'lunas', 'diambil'),
('TRX20210817212151', '60fa32a8db785', 'OTL0003', 'CST0003', 10, '2021-08-17', '2021-08-19', '2021-08-17', 5, 55000, 'lunas', 'selesai'),
('TRX20210819004737', '60fa32a8db785', 'OTL0002', 'CST0002', 9, '2021-08-19', '2021-08-20', '2021-08-19', 6, 54000, 'lunas', 'selesai'),
('TRX20211225183451', '60fa32a8db785', 'OTL0002', 'CST0002', 8, '2021-12-25', '2021-12-27', '2021-12-25', 2, 12000, 'lunas', 'proses');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_user`
--

CREATE TABLE `tb_user` (
  `id_user` varchar(50) NOT NULL,
  `nama_user` varchar(35) NOT NULL,
  `username` varchar(35) NOT NULL,
  `password` varchar(255) NOT NULL,
  `id_outlet` varchar(20) DEFAULT NULL,
  `foto_profile` varchar(255) DEFAULT NULL,
  `level` enum('admin','kasir','owner') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `tb_user`
--

INSERT INTO `tb_user` (`id_user`, `nama_user`, `username`, `password`, `id_outlet`, `foto_profile`, `level`) VALUES
('60fa32a8db785', 'administrator', 'admin', '$2y$10$9vmM3poVvi/Fo73XHmBDj.1YJG6j//vCZVlNyYEwmK6dDOX2GHHIK', 'OTL0001', '60faecdd56410.jpg', 'admin'),
('60fa330f35f5f', 'kasir', 'kasir', '$2y$10$DEV03BqUjK5s49.JRWgZZeCa/Sg.4CfgaYHjPdbRhV/4pnrde7d/G', 'OTL0002', '60fe0ceee3490.png', 'kasir'),
('610899242c766', 'Owber', 'owner', '$2y$10$h3CMLWs2PCt7amBuimIoiu7Snq4Fh5wNcHpQGkP0iqIRzCuS70I1e', NULL, '6108992463493.jpg', 'owner');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `tb_outlet`
--
ALTER TABLE `tb_outlet`
  ADD PRIMARY KEY (`id_outlet`),
  ADD UNIQUE KEY `telp` (`telp`);

--
-- Indeks untuk tabel `tb_pakaian`
--
ALTER TABLE `tb_pakaian`
  ADD PRIMARY KEY (`id_pakaian`),
  ADD KEY `id_transaksi` (`id_transaksi`);

--
-- Indeks untuk tabel `tb_paket`
--
ALTER TABLE `tb_paket`
  ADD PRIMARY KEY (`id_paket`),
  ADD KEY `paket_outlet` (`id_outlet`);

--
-- Indeks untuk tabel `tb_pelanggan`
--
ALTER TABLE `tb_pelanggan`
  ADD PRIMARY KEY (`id_pelanggan`),
  ADD UNIQUE KEY `telp` (`telp`),
  ADD KEY `id_outlet` (`id_outlet`);

--
-- Indeks untuk tabel `tb_transaksi`
--
ALTER TABLE `tb_transaksi`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD KEY `id_user` (`id_user`,`id_outlet`,`id_pelanggan`,`id_paket`),
  ADD KEY `trx_paket` (`id_paket`),
  ADD KEY `trx_outlet` (`id_outlet`),
  ADD KEY `trx_pelanggan` (`id_pelanggan`);

--
-- Indeks untuk tabel `tb_user`
--
ALTER TABLE `tb_user`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `id_outlet` (`id_outlet`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `tb_pakaian`
--
ALTER TABLE `tb_pakaian`
  MODIFY `id_pakaian` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT untuk tabel `tb_paket`
--
ALTER TABLE `tb_paket`
  MODIFY `id_paket` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `tb_pakaian`
--
ALTER TABLE `tb_pakaian`
  ADD CONSTRAINT `pakaian_trx` FOREIGN KEY (`id_transaksi`) REFERENCES `tb_transaksi` (`id_transaksi`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `tb_paket`
--
ALTER TABLE `tb_paket`
  ADD CONSTRAINT `paket_outlet` FOREIGN KEY (`id_outlet`) REFERENCES `tb_outlet` (`id_outlet`) ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `tb_pelanggan`
--
ALTER TABLE `tb_pelanggan`
  ADD CONSTRAINT `pelanggan_outlet` FOREIGN KEY (`id_outlet`) REFERENCES `tb_outlet` (`id_outlet`) ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `tb_transaksi`
--
ALTER TABLE `tb_transaksi`
  ADD CONSTRAINT `trx_outlet` FOREIGN KEY (`id_outlet`) REFERENCES `tb_outlet` (`id_outlet`),
  ADD CONSTRAINT `trx_paket` FOREIGN KEY (`id_paket`) REFERENCES `tb_paket` (`id_paket`) ON UPDATE CASCADE,
  ADD CONSTRAINT `trx_pelanggan` FOREIGN KEY (`id_pelanggan`) REFERENCES `tb_pelanggan` (`id_pelanggan`) ON UPDATE CASCADE,
  ADD CONSTRAINT `trx_user` FOREIGN KEY (`id_user`) REFERENCES `tb_user` (`id_user`) ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `tb_user`
--
ALTER TABLE `tb_user`
  ADD CONSTRAINT `user_outlet` FOREIGN KEY (`id_outlet`) REFERENCES `tb_outlet` (`id_outlet`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
