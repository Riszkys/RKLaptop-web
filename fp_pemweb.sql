-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 05, 2023 at 05:18 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fp_pemweb`
--

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `nama_customer` text NOT NULL,
  `alamat` varchar(50) NOT NULL,
  `no_hp` int(20) NOT NULL,
  `email` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`nama_customer`, `alamat`, `no_hp`, `email`) VALUES
('kiki', 'Rungkut', 876173914, 'kiki123@gmail.com'),
('audy', 'Rungkut', 876173914, 'audy@gmail.com'),
('Dea', 'Rungkut', 876173914, 'dea@gmail.com'),
('rendi', 'Manyar Sabrangan', 876173914, 'rendi@gmail.com'),
('ulin', 'Manyar Sabrangan', 876173914, 'ulin@gmail.com'),
('ari', 'Manyar Sabrangan', 876173914, 'ari@gmail.com'),
('bryan', 'Wonokromo', 876173914, 'ulin@gmail.com'),
('anggun', 'Wonokromo', 876173914, 'anggun@gmail.com'),
('mojo', 'Wonokromo', 876173914, 'mojo@gmail.com'),
('sutris', 'Gubeng', 876173914, 'Sutris@gmail.com'),
('palepi', 'Gubeng', 876173914, 'palepi@gmail.com'),
('saddad', 'Gubeng', 876173914, 'saddad@gmail.com'),
('Dita', 'Medokan', 876173914, 'dita@gmail.com'),
('denny', 'Medokan', 876173914, 'denni@gmail.com'),
('evan', 'Medokan', 876173914, 'evan@gmail.com'),
('rehan', 'Medokan', 876173914, 'rehan@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `form_janji`
--

CREATE TABLE `form_janji` (
  `nomor_antrian` int(30) NOT NULL,
  `nama` text NOT NULL,
  `alamat` varchar(50) NOT NULL,
  `email` varchar(30) NOT NULL,
  `no_hp` int(20) NOT NULL,
  `tanggal` date NOT NULL,
  `jam` time NOT NULL,
  `merk_laptop` text NOT NULL,
  `layanan` varchar(50) NOT NULL,
  `keluhan` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `form_janji`
--

INSERT INTO `form_janji` (`nomor_antrian`, `nama`, `alamat`, `email`, `no_hp`, `tanggal`, `jam`, `merk_laptop`, `layanan`, `keluhan`) VALUES
(3, 'kiki', 'pagerwojo', 'admin@gmail.com', 2147483647, '2023-06-20', '15:06:00', 'HP', 'Servie Baterai', 'rusak'),
(4, 'kiki', 'pagerwojo', 'admin@gmail.com', 2147483647, '2023-06-20', '15:06:00', 'HP', 'Upgrade Ram', 'rusak'),
(5, 'kiki', 'pagerwojo rt 11 rw 03 buduran sidoarjo', 'admin@gmail.com', 2147483647, '2023-06-28', '19:13:00', 'HP', 'Ganti Keyboard', 'gabisa di pencet'),
(6, 'Moch Rezeki Setiawan', 'pagerwojo rt 11 rw 03 buduran sidoarjo', 'rizkysetiawann22@gmail.com', 2147483647, '2023-06-29', '18:18:00', 'ASUS', 'Upgrade Ram, Service Kipas Laptop', 'rusak'),
(7, 'Moch Rezeki Setiawan', 'pagerwojo rt 11 rw 03 buduran sidoarjo', 'rizkysetiawann22@gmail.com', 2147483647, '2023-06-28', '15:30:00', 'ASUS', 'Upgrade SSD, Ganti Thermal Paste', 'gabisa di pencet'),
(8, 'Moch Rezeki Setiawan', 'pagerwojo rt 11 rw 03 buduran sidoarjo', 'rizkysetiawann22@gmail.com', 2147483647, '2023-07-06', '01:46:00', 'LENOVO', 'Upgrade Ram, Lainnya', 'rusak'),
(9, 'Moch Rezeki Setiawan', 'pagerwojo rt 11 rw 03 buduran sidoarjo', 'rizkysetiawann22@gmail.com', 2147483647, '2023-07-06', '01:46:00', 'MSI', 'Upgrade Ram, Lainnya', 'rusak'),
(10, 'Moch Rezeki Setiawan', 'pagerwojo rt 11 rw 03 buduran sidoarjo', 'rizkysetiawann22@gmail.com', 2147483647, '2023-07-06', '01:46:00', 'MSI', 'Upgrade Ram, Lainnya', 'rusak');

-- --------------------------------------------------------

--
-- Table structure for table `help_cust`
--

CREATE TABLE `help_cust` (
  `nama_customer` varchar(50) NOT NULL,
  `email` varchar(20) NOT NULL,
  `no_hp` int(20) NOT NULL,
  `pesan` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `help_cust`
--

INSERT INTO `help_cust` (`nama_customer`, `email`, `no_hp`, `pesan`) VALUES
('Moch Rezeki Setiawan', '21082010004@student.', 2147483647, 'test kiki');

-- --------------------------------------------------------

--
-- Table structure for table `mitra`
--

CREATE TABLE `mitra` (
  `id_mitra` int(20) NOT NULL,
  `nama_mitra` text NOT NULL,
  `alamat_mitra` varchar(30) NOT NULL,
  `domisili` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mitra`
--

INSERT INTO `mitra` (`id_mitra`, `nama_mitra`, `alamat_mitra`, `domisili`) VALUES
(1, 'Komputer Baru', 'Jl Ngagel No 14, Surabaya', 'Surabaya Timur'),
(2, 'Lapak Tekno', 'Rungkut Madya No 17, Surabaya ', 'Surabaya Timur'),
(3, 'Dokter Komputer', 'Jl Ahmad Yani no 13, Surabaya', 'Surabaya Pusat'),
(4, 'Markas Komputer', 'Jl Tunjungan no 7, Surabaya', 'Surabaya Pusat'),
(5, 'Markas Laptop', 'Jl Benowo no 41, Surabaya', 'Surabaya Barat'),
(6, 'Computer Shop', 'Jl Pakal no 50, Surabaya', 'Surabaya Barat'),
(7, 'Computer Sciens', 'jl Bulak no 5, Surabaya', 'Surabaya Utara'),
(8, 'Laptop Murah', 'Jl Semampir no 12, Surabaya', 'Surabaya Utara'),
(9, 'All Tech', 'jl Jambangan no 17, Surabaya', 'Surabaya Selatan');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `username` varchar(50) NOT NULL,
  `email` varchar(30) NOT NULL,
  `password` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`username`, `email`, `password`) VALUES
('admin', 'admin@gmail.com', 'admin'),
('rendi', 'rendi@gmail.com', 'rendi');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD UNIQUE KEY `nama_customer` (`nama_customer`) USING HASH;

--
-- Indexes for table `form_janji`
--
ALTER TABLE `form_janji`
  ADD PRIMARY KEY (`nomor_antrian`);

--
-- Indexes for table `help_cust`
--
ALTER TABLE `help_cust`
  ADD UNIQUE KEY `nama_customer` (`nama_customer`);

--
-- Indexes for table `mitra`
--
ALTER TABLE `mitra`
  ADD PRIMARY KEY (`id_mitra`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD UNIQUE KEY `username` (`username`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
