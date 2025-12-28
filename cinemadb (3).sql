-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 27, 2025 at 01:41 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cinemadb`
--

-- --------------------------------------------------------

--
-- Table structure for table `genres`
--

CREATE TABLE `genres` (
  `genre_id` int(11) NOT NULL,
  `genre_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `genres`
--

INSERT INTO `genres` (`genre_id`, `genre_name`) VALUES
(1, 'Action'),
(5, 'Adventure'),
(9, 'Animation'),
(2, 'Comedy'),
(8, 'Crime'),
(11, 'Dark Comedy'),
(12, 'Drama'),
(3, 'Fantasy'),
(10, 'Horror'),
(4, 'Romance'),
(6, 'Sci-fi'),
(7, 'Thriller');

-- --------------------------------------------------------

--
-- Table structure for table `movies`
--

CREATE TABLE `movies` (
  `movie_id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `duration` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `synopsis` text DEFAULT NULL,
  `poster_path` varchar(255) DEFAULT NULL,
  `trailer_url` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive','coming_soon') NOT NULL DEFAULT 'coming_soon'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `movies`
--

INSERT INTO `movies` (`movie_id`, `title`, `duration`, `start_date`, `end_date`, `synopsis`, `poster_path`, `trailer_url`, `status`) VALUES
(2, 'AVATAR: FIRE AND ASH', 197, '2025-12-17', '2026-01-14', 'Setelah perang yang dahsyat melawan RDA dan kehilangan putra sulung mereka, Jake Sully (Sam Worthington) dan Neytiri (Zoe Saldana) menghadapi ancaman baru di Pandora: Suku Ash, suku Na\'vi yang kejam dan haus kekuasaan pimpinan Varang (Oona Chaplin). Keluarga Jake harus berjuang demi kelangsungan hidup mereka dan masa depan Pandora dalam konflik yang mendorong mereka hingga batas emosional dan fisik.', 'assets/movie_poster/1765720497_47ad537994.jpg', 'https://www.youtube.com/watch?v=nb_fFj_0rq8', 'active'),
(3, 'NOW YOU SEE ME: NOW YOU DONT', 113, '2025-12-14', '2026-01-19', 'The Four Horsemen dan generasi pesulap baru berkumpul untuk menampilkan gerakan-gerakan ajaib yang membingungkan, penuh kejutan yang belum pernah terlihat sebelumnya. Mereka bersatu untuk mencuri berlian raksasa yang dijaga super ketat dan menjatuhkan Vanderberg Corporation, sebuah jaringan kriminal besar yang menciptakan semua penjahat terburuk di dunia.', 'assets/movie_poster/1765725180_8bc5ec6f1f.jpg', 'https://www.youtube.com/watch?v=-E3lMRx7HRQ', 'active'),
(4, 'ZOOTOPIA 2', 108, '2025-11-26', '2026-01-16', 'Setelah memecahkan kasus terbesar dalam sejarah Zootopia, polisi pemula Judy Hopps (Ginnifer Goodwin) dan Nick Wilde (Jason Bateman) menyadari bahwa kerjasama mereka tidak sekuat yang mereka bayangkan, saat kepala Polisi Bogo (Idris Elba) memerintahkan mereka untuk bergabung dengan program konseling. Namun, tak lama kemudian, keduanya diuji habis-habisan ketika menemukan diri mereka berada dalam sebuah misteri yang berliku-liku terkait kehadiran sosok ular berbisa di kota metropolitan hewan tersebut.', 'assets/movie_poster/1765968789_8b1769ed07.jpg', 'https://www.youtube.com/watch?v=BjkIOU5PhyQ', 'active'),
(5, 'FIVE NIGHTS AT FREDDY\'S 2', 150, '2025-12-27', '2026-01-16', 'Setahun setelah kejadian mengerikan di Freddy Fazbear’s Pizza, kisah kelam tersebut telah berubah menjadi legenda lokal yang dianggap sekadar cerita konyol dan dirayakan lewat Fazfest. Mike dan Vanessa berusaha menutup masa lalu itu dari Abby, adik Mike. Namun ketika Abby diam-diam kembali menemui Freddy dan para animatronik, rangkaian kejadian mengerikan kembali terjadi, membuka rahasia gelap tentang asal-usul Freddy dan menghadirkan teror lama yang selama ini tersembunyi.', 'assets/movie_poster/1766045766_23288a9edc.jpg', 'https://www.youtube.com/watch?v=dSDpoobO6yM', 'coming_soon'),
(6, 'READY OR NOT 2: HERE I COME', 105, '2026-04-11', '2026-05-02', 'Setelah selamat dari malam pernikahan yang brutal, Grace MacCaullay terbangun di rumah sakit dan menyadari bahwa kemenangannya justru membawanya ke tahap permainan yang lebih mengerikan, di mana ia menjadi target empat keluarga terkaya dan paling berkuasa di dunia yang berebut “High Seat of the Council”. Jika gagal membunuh Grace, mereka akan kehilangan kekayaan dan kekuasaan mereka. Meski menolak terlibat, Grace terpaksa kembali bertarung ketika mengetahui adik perempuannya, Faith, juga diburu, sehingga ia harus berjuang mati-matian untuk bertahan hidup sekaligus melindungi keluarganya dari klan elit yang kejam.', 'assets/movie_poster/1766322056_1b15e3deb2.jpg', 'https://www.youtube.com/watch?v=7K3sNRm8J0w', 'coming_soon'),
(8, 'AGAK LAEN: MENYALA PANTIKU!', 119, '2025-11-27', '2026-01-24', 'Setelah berulang kali gagal menjalankan misi, Detektif Bene, Boris, Jegel, dan Oki diberi satu kesempatan terakhir: Menyamar dan menyusup ke sebuah panti jompo, untuk mencari buronan kasus pembunuhan anak wali kota.', 'assets/movie_poster/1766812278_b04081370b.jpg', 'https://www.youtube.com/watch?v=fYjJ6zP2Cp0', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `movie_genre`
--

CREATE TABLE `movie_genre` (
  `movie_id` int(11) NOT NULL,
  `genre_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `movie_genre`
--

INSERT INTO `movie_genre` (`movie_id`, `genre_id`) VALUES
(2, 1),
(2, 3),
(2, 5),
(2, 6),
(3, 7),
(3, 8),
(4, 5),
(4, 9),
(5, 7),
(5, 10),
(6, 2),
(6, 10),
(6, 11),
(8, 12);

-- --------------------------------------------------------

--
-- Table structure for table `prices`
--

CREATE TABLE `prices` (
  `id` int(11) NOT NULL,
  `weekday_price` int(11) NOT NULL,
  `weekend_price` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `prices`
--

INSERT INTO `prices` (`id`, `weekday_price`, `weekend_price`) VALUES
(1, 45000, 50000);

-- --------------------------------------------------------

--
-- Table structure for table `seats`
--

CREATE TABLE `seats` (
  `seat_id` int(11) NOT NULL,
  `studio_id` int(11) NOT NULL,
  `seat_row` varchar(1) NOT NULL,
  `seat_column` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `seats`
--

INSERT INTO `seats` (`seat_id`, `studio_id`, `seat_row`, `seat_column`) VALUES
(1, 1, 'A', 1),
(25, 1, 'A', 2),
(26, 1, 'A', 3),
(27, 1, 'A', 4),
(28, 1, 'A', 5),
(29, 1, 'A', 6),
(30, 1, 'A', 7),
(31, 1, 'A', 8),
(32, 1, 'A', 9),
(33, 1, 'A', 10),
(34, 1, 'A', 11),
(35, 1, 'A', 12),
(622, 1, 'A', 13),
(36, 1, 'B', 1),
(37, 1, 'B', 2),
(38, 1, 'B', 3),
(39, 1, 'B', 4),
(40, 1, 'B', 5),
(41, 1, 'B', 6),
(42, 1, 'B', 7),
(43, 1, 'B', 8),
(44, 1, 'B', 9),
(45, 1, 'B', 10),
(46, 1, 'B', 11),
(47, 1, 'B', 12),
(625, 1, 'B', 13),
(48, 1, 'C', 1),
(49, 1, 'C', 2),
(50, 1, 'C', 3),
(51, 1, 'C', 4),
(52, 1, 'C', 5),
(53, 1, 'C', 6),
(54, 1, 'C', 7),
(55, 1, 'C', 8),
(56, 1, 'C', 9),
(57, 1, 'C', 10),
(58, 1, 'C', 11),
(59, 1, 'C', 12),
(628, 1, 'C', 13),
(60, 1, 'D', 1),
(61, 1, 'D', 2),
(62, 1, 'D', 3),
(63, 1, 'D', 4),
(64, 1, 'D', 5),
(65, 1, 'D', 6),
(66, 1, 'D', 7),
(67, 1, 'D', 8),
(68, 1, 'D', 9),
(69, 1, 'D', 10),
(70, 1, 'D', 11),
(71, 1, 'D', 12),
(631, 1, 'D', 13),
(72, 1, 'E', 1),
(73, 1, 'E', 2),
(74, 1, 'E', 3),
(75, 1, 'E', 4),
(76, 1, 'E', 5),
(77, 1, 'E', 6),
(78, 1, 'E', 7),
(79, 1, 'E', 8),
(80, 1, 'E', 9),
(81, 1, 'E', 10),
(82, 1, 'E', 11),
(83, 1, 'E', 12),
(84, 1, 'E', 13),
(85, 1, 'E', 14),
(86, 1, 'E', 15),
(87, 1, 'F', 1),
(88, 1, 'F', 2),
(89, 1, 'F', 3),
(90, 1, 'F', 4),
(91, 1, 'F', 5),
(92, 1, 'F', 6),
(93, 1, 'F', 7),
(94, 1, 'F', 8),
(95, 1, 'F', 9),
(96, 1, 'F', 10),
(97, 1, 'F', 11),
(98, 1, 'F', 12),
(99, 1, 'F', 13),
(100, 1, 'F', 14),
(101, 1, 'F', 15),
(102, 1, 'G', 1),
(103, 1, 'G', 2),
(104, 1, 'G', 3),
(105, 1, 'G', 4),
(106, 1, 'G', 5),
(107, 1, 'G', 6),
(108, 1, 'G', 7),
(109, 1, 'G', 8),
(110, 1, 'G', 9),
(111, 1, 'G', 10),
(112, 1, 'G', 11),
(113, 1, 'G', 12),
(114, 1, 'G', 13),
(115, 1, 'G', 14),
(116, 1, 'G', 15),
(117, 1, 'H', 1),
(118, 1, 'H', 2),
(119, 1, 'H', 3),
(120, 1, 'H', 4),
(121, 1, 'H', 5),
(122, 1, 'H', 6),
(123, 1, 'H', 7),
(124, 1, 'H', 8),
(125, 1, 'H', 9),
(126, 1, 'H', 10),
(127, 1, 'H', 11),
(128, 1, 'H', 12),
(129, 1, 'H', 13),
(130, 1, 'H', 14),
(131, 1, 'H', 15),
(133, 3, 'A', 1),
(134, 3, 'A', 2),
(135, 3, 'A', 3),
(136, 3, 'A', 4),
(137, 3, 'A', 5),
(138, 3, 'A', 6),
(139, 3, 'A', 7),
(140, 3, 'A', 8),
(141, 3, 'A', 9),
(142, 3, 'A', 10),
(143, 3, 'A', 11),
(144, 3, 'A', 12),
(623, 3, 'A', 13),
(145, 3, 'B', 1),
(146, 3, 'B', 2),
(147, 3, 'B', 3),
(148, 3, 'B', 4),
(149, 3, 'B', 5),
(150, 3, 'B', 6),
(151, 3, 'B', 7),
(152, 3, 'B', 8),
(153, 3, 'B', 9),
(154, 3, 'B', 10),
(155, 3, 'B', 11),
(156, 3, 'B', 12),
(626, 3, 'B', 13),
(157, 3, 'C', 1),
(158, 3, 'C', 2),
(159, 3, 'C', 3),
(160, 3, 'C', 4),
(161, 3, 'C', 5),
(162, 3, 'C', 6),
(163, 3, 'C', 7),
(164, 3, 'C', 8),
(165, 3, 'C', 9),
(166, 3, 'C', 10),
(167, 3, 'C', 11),
(168, 3, 'C', 12),
(629, 3, 'C', 13),
(169, 3, 'D', 1),
(170, 3, 'D', 2),
(171, 3, 'D', 3),
(172, 3, 'D', 4),
(173, 3, 'D', 5),
(174, 3, 'D', 6),
(175, 3, 'D', 7),
(176, 3, 'D', 8),
(177, 3, 'D', 9),
(178, 3, 'D', 10),
(179, 3, 'D', 11),
(180, 3, 'D', 12),
(632, 3, 'D', 13),
(181, 3, 'E', 1),
(182, 3, 'E', 2),
(183, 3, 'E', 3),
(184, 3, 'E', 4),
(185, 3, 'E', 5),
(186, 3, 'E', 6),
(187, 3, 'E', 7),
(188, 3, 'E', 8),
(189, 3, 'E', 9),
(190, 3, 'E', 10),
(191, 3, 'E', 11),
(192, 3, 'E', 12),
(193, 3, 'E', 13),
(194, 3, 'E', 14),
(195, 3, 'E', 15),
(196, 3, 'F', 1),
(197, 3, 'F', 2),
(198, 3, 'F', 3),
(199, 3, 'F', 4),
(200, 3, 'F', 5),
(201, 3, 'F', 6),
(202, 3, 'F', 7),
(203, 3, 'F', 8),
(204, 3, 'F', 9),
(205, 3, 'F', 10),
(206, 3, 'F', 11),
(207, 3, 'F', 12),
(208, 3, 'F', 13),
(209, 3, 'F', 14),
(210, 3, 'F', 15),
(211, 3, 'G', 1),
(212, 3, 'G', 2),
(213, 3, 'G', 3),
(214, 3, 'G', 4),
(215, 3, 'G', 5),
(216, 3, 'G', 6),
(217, 3, 'G', 7),
(218, 3, 'G', 8),
(219, 3, 'G', 9),
(220, 3, 'G', 10),
(221, 3, 'G', 11),
(222, 3, 'G', 12),
(223, 3, 'G', 13),
(224, 3, 'G', 14),
(225, 3, 'G', 15),
(226, 3, 'H', 1),
(227, 3, 'H', 2),
(228, 3, 'H', 3),
(229, 3, 'H', 4),
(230, 3, 'H', 5),
(231, 3, 'H', 6),
(232, 3, 'H', 7),
(233, 3, 'H', 8),
(234, 3, 'H', 9),
(235, 3, 'H', 10),
(236, 3, 'H', 11),
(237, 3, 'H', 12),
(238, 3, 'H', 13),
(239, 3, 'H', 14),
(240, 3, 'H', 15),
(260, 4, 'A', 1),
(261, 4, 'A', 2),
(262, 4, 'A', 3),
(263, 4, 'A', 4),
(264, 4, 'A', 5),
(265, 4, 'A', 6),
(266, 4, 'A', 7),
(267, 4, 'A', 8),
(268, 4, 'A', 9),
(269, 4, 'A', 10),
(270, 4, 'A', 11),
(271, 4, 'A', 12),
(624, 4, 'A', 13),
(272, 4, 'B', 1),
(273, 4, 'B', 2),
(274, 4, 'B', 3),
(275, 4, 'B', 4),
(276, 4, 'B', 5),
(277, 4, 'B', 6),
(278, 4, 'B', 7),
(279, 4, 'B', 8),
(280, 4, 'B', 9),
(281, 4, 'B', 10),
(282, 4, 'B', 11),
(283, 4, 'B', 12),
(627, 4, 'B', 13),
(284, 4, 'C', 1),
(285, 4, 'C', 2),
(286, 4, 'C', 3),
(287, 4, 'C', 4),
(288, 4, 'C', 5),
(289, 4, 'C', 6),
(290, 4, 'C', 7),
(291, 4, 'C', 8),
(292, 4, 'C', 9),
(293, 4, 'C', 10),
(294, 4, 'C', 11),
(295, 4, 'C', 12),
(630, 4, 'C', 13),
(296, 4, 'D', 1),
(297, 4, 'D', 2),
(298, 4, 'D', 3),
(299, 4, 'D', 4),
(300, 4, 'D', 5),
(301, 4, 'D', 6),
(302, 4, 'D', 7),
(303, 4, 'D', 8),
(304, 4, 'D', 9),
(305, 4, 'D', 10),
(306, 4, 'D', 11),
(307, 4, 'D', 12),
(633, 4, 'D', 13),
(308, 4, 'E', 1),
(309, 4, 'E', 2),
(310, 4, 'E', 3),
(311, 4, 'E', 4),
(312, 4, 'E', 5),
(313, 4, 'E', 6),
(314, 4, 'E', 7),
(315, 4, 'E', 8),
(316, 4, 'E', 9),
(317, 4, 'E', 10),
(318, 4, 'E', 11),
(319, 4, 'E', 12),
(320, 4, 'E', 13),
(321, 4, 'E', 14),
(322, 4, 'E', 15),
(323, 4, 'F', 1),
(324, 4, 'F', 2),
(325, 4, 'F', 3),
(326, 4, 'F', 4),
(327, 4, 'F', 5),
(328, 4, 'F', 6),
(329, 4, 'F', 7),
(330, 4, 'F', 8),
(331, 4, 'F', 9),
(332, 4, 'F', 10),
(333, 4, 'F', 11),
(334, 4, 'F', 12),
(335, 4, 'F', 13),
(336, 4, 'F', 14),
(337, 4, 'F', 15),
(338, 4, 'G', 1),
(339, 4, 'G', 2),
(340, 4, 'G', 3),
(341, 4, 'G', 4),
(342, 4, 'G', 5),
(343, 4, 'G', 6),
(344, 4, 'G', 7),
(345, 4, 'G', 8),
(346, 4, 'G', 9),
(347, 4, 'G', 10),
(348, 4, 'G', 11),
(349, 4, 'G', 12),
(350, 4, 'G', 13),
(351, 4, 'G', 14),
(352, 4, 'G', 15),
(353, 4, 'H', 1),
(354, 4, 'H', 2),
(355, 4, 'H', 3),
(356, 4, 'H', 4),
(357, 4, 'H', 5),
(358, 4, 'H', 6),
(359, 4, 'H', 7),
(360, 4, 'H', 8),
(361, 4, 'H', 9),
(362, 4, 'H', 10),
(363, 4, 'H', 11),
(364, 4, 'H', 12),
(365, 4, 'H', 13),
(366, 4, 'H', 14),
(367, 4, 'H', 15),
(387, 5, 'A', 1),
(388, 5, 'A', 2),
(389, 5, 'A', 3),
(390, 5, 'A', 4),
(391, 5, 'A', 5),
(392, 5, 'A', 6),
(393, 5, 'A', 7),
(394, 5, 'A', 8),
(395, 5, 'A', 9),
(396, 5, 'A', 10),
(397, 5, 'A', 11),
(398, 5, 'A', 12),
(637, 5, 'A', 13),
(399, 5, 'B', 1),
(400, 5, 'B', 2),
(401, 5, 'B', 3),
(402, 5, 'B', 4),
(403, 5, 'B', 5),
(404, 5, 'B', 6),
(405, 5, 'B', 7),
(406, 5, 'B', 8),
(407, 5, 'B', 9),
(408, 5, 'B', 10),
(409, 5, 'B', 11),
(410, 5, 'B', 12),
(638, 5, 'B', 13),
(411, 5, 'C', 1),
(412, 5, 'C', 2),
(413, 5, 'C', 3),
(414, 5, 'C', 4),
(415, 5, 'C', 5),
(416, 5, 'C', 6),
(417, 5, 'C', 7),
(418, 5, 'C', 8),
(419, 5, 'C', 9),
(420, 5, 'C', 10),
(421, 5, 'C', 11),
(422, 5, 'C', 12),
(639, 5, 'C', 13),
(423, 5, 'D', 1),
(424, 5, 'D', 2),
(425, 5, 'D', 3),
(426, 5, 'D', 4),
(427, 5, 'D', 5),
(428, 5, 'D', 6),
(429, 5, 'D', 7),
(430, 5, 'D', 8),
(431, 5, 'D', 9),
(432, 5, 'D', 10),
(433, 5, 'D', 11),
(434, 5, 'D', 12),
(640, 5, 'D', 13),
(435, 5, 'E', 1),
(436, 5, 'E', 2),
(437, 5, 'E', 3),
(438, 5, 'E', 4),
(439, 5, 'E', 5),
(440, 5, 'E', 6),
(441, 5, 'E', 7),
(442, 5, 'E', 8),
(443, 5, 'E', 9),
(444, 5, 'E', 10),
(445, 5, 'E', 11),
(446, 5, 'E', 12),
(447, 5, 'E', 13),
(448, 5, 'E', 14),
(449, 5, 'E', 15),
(450, 5, 'F', 1),
(451, 5, 'F', 2),
(452, 5, 'F', 3),
(453, 5, 'F', 4),
(454, 5, 'F', 5),
(455, 5, 'F', 6),
(456, 5, 'F', 7),
(457, 5, 'F', 8),
(458, 5, 'F', 9),
(459, 5, 'F', 10),
(460, 5, 'F', 11),
(461, 5, 'F', 12),
(462, 5, 'F', 13),
(463, 5, 'F', 14),
(464, 5, 'F', 15),
(465, 5, 'G', 1),
(466, 5, 'G', 2),
(467, 5, 'G', 3),
(468, 5, 'G', 4),
(469, 5, 'G', 5),
(470, 5, 'G', 6),
(471, 5, 'G', 7),
(472, 5, 'G', 8),
(473, 5, 'G', 9),
(474, 5, 'G', 10),
(475, 5, 'G', 11),
(476, 5, 'G', 12),
(477, 5, 'G', 13),
(478, 5, 'G', 14),
(479, 5, 'G', 15),
(480, 5, 'H', 1),
(481, 5, 'H', 2),
(482, 5, 'H', 3),
(483, 5, 'H', 4),
(484, 5, 'H', 5),
(485, 5, 'H', 6),
(486, 5, 'H', 7),
(487, 5, 'H', 8),
(488, 5, 'H', 9),
(489, 5, 'H', 10),
(490, 5, 'H', 11),
(491, 5, 'H', 12),
(492, 5, 'H', 13),
(493, 5, 'H', 14),
(494, 5, 'H', 15),
(514, 6, 'A', 1),
(515, 6, 'A', 2),
(516, 6, 'A', 3),
(517, 6, 'A', 4),
(518, 6, 'A', 5),
(519, 6, 'A', 6),
(520, 6, 'A', 7),
(521, 6, 'A', 8),
(522, 6, 'A', 9),
(523, 6, 'A', 10),
(524, 6, 'A', 11),
(525, 6, 'A', 12),
(644, 6, 'A', 13),
(526, 6, 'B', 1),
(527, 6, 'B', 2),
(528, 6, 'B', 3),
(529, 6, 'B', 4),
(530, 6, 'B', 5),
(531, 6, 'B', 6),
(532, 6, 'B', 7),
(533, 6, 'B', 8),
(534, 6, 'B', 9),
(535, 6, 'B', 10),
(536, 6, 'B', 11),
(537, 6, 'B', 12),
(645, 6, 'B', 13),
(538, 6, 'C', 1),
(539, 6, 'C', 2),
(540, 6, 'C', 3),
(541, 6, 'C', 4),
(542, 6, 'C', 5),
(543, 6, 'C', 6),
(544, 6, 'C', 7),
(545, 6, 'C', 8),
(546, 6, 'C', 9),
(547, 6, 'C', 10),
(548, 6, 'C', 11),
(549, 6, 'C', 12),
(646, 6, 'C', 13),
(550, 6, 'D', 1),
(551, 6, 'D', 2),
(552, 6, 'D', 3),
(553, 6, 'D', 4),
(554, 6, 'D', 5),
(555, 6, 'D', 6),
(556, 6, 'D', 7),
(557, 6, 'D', 8),
(558, 6, 'D', 9),
(559, 6, 'D', 10),
(560, 6, 'D', 11),
(561, 6, 'D', 12),
(647, 6, 'D', 13),
(562, 6, 'E', 1),
(563, 6, 'E', 2),
(564, 6, 'E', 3),
(565, 6, 'E', 4),
(566, 6, 'E', 5),
(567, 6, 'E', 6),
(568, 6, 'E', 7),
(569, 6, 'E', 8),
(570, 6, 'E', 9),
(571, 6, 'E', 10),
(572, 6, 'E', 11),
(573, 6, 'E', 12),
(574, 6, 'E', 13),
(575, 6, 'E', 14),
(576, 6, 'E', 15),
(577, 6, 'F', 1),
(578, 6, 'F', 2),
(579, 6, 'F', 3),
(580, 6, 'F', 4),
(581, 6, 'F', 5),
(582, 6, 'F', 6),
(583, 6, 'F', 7),
(584, 6, 'F', 8),
(585, 6, 'F', 9),
(586, 6, 'F', 10),
(587, 6, 'F', 11),
(588, 6, 'F', 12),
(589, 6, 'F', 13),
(590, 6, 'F', 14),
(591, 6, 'F', 15),
(592, 6, 'G', 1),
(593, 6, 'G', 2),
(594, 6, 'G', 3),
(595, 6, 'G', 4),
(596, 6, 'G', 5),
(597, 6, 'G', 6),
(598, 6, 'G', 7),
(599, 6, 'G', 8),
(600, 6, 'G', 9),
(601, 6, 'G', 10),
(602, 6, 'G', 11),
(603, 6, 'G', 12),
(604, 6, 'G', 13),
(605, 6, 'G', 14),
(606, 6, 'G', 15),
(607, 6, 'H', 1),
(608, 6, 'H', 2),
(609, 6, 'H', 3),
(610, 6, 'H', 4),
(611, 6, 'H', 5),
(612, 6, 'H', 6),
(613, 6, 'H', 7),
(614, 6, 'H', 8),
(615, 6, 'H', 9),
(616, 6, 'H', 10),
(617, 6, 'H', 11),
(618, 6, 'H', 12),
(619, 6, 'H', 13),
(620, 6, 'H', 14),
(621, 6, 'H', 15);

-- --------------------------------------------------------

--
-- Table structure for table `showtimes`
--

CREATE TABLE `showtimes` (
  `showtime_id` int(11) NOT NULL,
  `movie_id` int(11) NOT NULL,
  `studio_id` int(11) NOT NULL,
  `show_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `showtimes`
--

INSERT INTO `showtimes` (`showtime_id`, `movie_id`, `studio_id`, `show_date`, `start_time`, `end_time`) VALUES
(3, 2, 1, '2025-12-17', '16:20:00', '19:37:00'),
(7, 4, 3, '2025-12-20', '10:20:00', '12:08:00'),
(9, 2, 1, '2025-12-22', '12:10:00', '15:27:00'),
(10, 3, 1, '2025-12-23', '14:20:00', '16:13:00'),
(11, 2, 1, '2025-12-22', '16:20:00', '19:37:00'),
(12, 3, 3, '2025-12-22', '13:40:00', '15:33:00'),
(13, 4, 4, '2025-12-23', '19:00:00', '20:48:00'),
(14, 4, 4, '2025-12-23', '11:30:00', '13:18:00'),
(15, 2, 5, '2025-12-23', '18:00:00', '21:17:00'),
(16, 3, 3, '2025-12-25', '14:00:00', '15:53:00'),
(17, 4, 1, '2025-12-24', '16:00:00', '17:48:00'),
(18, 4, 4, '2025-12-25', '19:00:00', '20:48:00'),
(19, 3, 1, '2025-12-26', '15:00:00', '16:53:00'),
(20, 3, 3, '2025-12-25', '19:00:00', '20:53:00'),
(21, 2, 4, '2025-12-25', '14:00:00', '17:17:00'),
(22, 2, 4, '2025-12-26', '15:00:00', '18:17:00'),
(23, 4, 1, '2025-12-26', '10:00:00', '11:48:00'),
(24, 3, 3, '2025-12-27', '19:00:00', '20:53:00'),
(25, 2, 5, '2025-12-27', '18:10:00', '21:27:00'),
(26, 4, 4, '2025-12-27', '15:00:00', '16:48:00'),
(27, 2, 1, '2025-12-27', '12:30:00', '15:47:00'),
(28, 4, 3, '2025-12-28', '14:20:00', '16:08:00'),
(29, 4, 4, '2025-12-29', '15:00:00', '16:48:00'),
(30, 3, 1, '2025-12-28', '17:50:00', '19:43:00'),
(31, 2, 4, '2025-12-28', '17:00:00', '20:17:00'),
(32, 2, 3, '2025-12-29', '13:00:00', '16:17:00'),
(33, 3, 3, '2025-12-26', '15:00:00', '16:53:00'),
(34, 3, 5, '2025-12-29', '12:10:00', '14:03:00'),
(35, 8, 6, '2025-12-27', '15:00:00', '16:59:00'),
(36, 8, 1, '2025-12-27', '16:00:00', '17:59:00'),
(37, 8, 4, '2025-12-31', '15:00:00', '16:59:00'),
(38, 8, 4, '2026-01-01', '18:00:00', '19:59:00'),
(39, 8, 3, '2026-01-01', '13:00:00', '14:59:00'),
(40, 8, 4, '2026-01-02', '16:10:00', '18:09:00'),
(41, 8, 3, '2026-01-03', '19:00:00', '20:59:00'),
(42, 4, 1, '2026-01-02', '13:25:00', '15:13:00'),
(43, 4, 4, '2026-01-02', '14:00:00', '15:48:00'),
(44, 2, 5, '2026-01-02', '15:40:00', '18:57:00');

-- --------------------------------------------------------

--
-- Table structure for table `studios`
--

CREATE TABLE `studios` (
  `studio_id` int(11) NOT NULL,
  `studio_name` varchar(50) NOT NULL,
  `capacity` int(11) NOT NULL DEFAULT 100,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `studios`
--

INSERT INTO `studios` (`studio_id`, `studio_name`, `capacity`, `status`) VALUES
(1, 'Studio 1', 100, 'active'),
(3, 'Studio 2', 100, 'active'),
(4, 'Studio 3', 100, 'active'),
(5, 'Studio 4', 100, 'active'),
(6, 'Studio 5', 100, 'active');

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE `tickets` (
  `ticket_id` int(11) NOT NULL,
  `transaction_id` int(11) NOT NULL,
  `showtime_id` int(11) NOT NULL,
  `seat_id` int(11) NOT NULL,
  `price` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `transaction_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_price` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `showtime_id` int(11) NOT NULL,
  `tickets_qty` int(11) NOT NULL,
  `status` enum('unpaid','paid','cancelled') DEFAULT 'unpaid',
  `booking_code` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`transaction_id`, `user_id`, `total_price`, `created_at`, `showtime_id`, `tickets_qty`, `status`, `booking_code`) VALUES
(33, 4, 47500, '2025-12-24 12:01:08', 17, 1, 'paid', '9DE72'),
(34, 4, 47500, '2025-12-24 13:56:28', 17, 1, 'paid', '56353'),
(35, 4, 182500, '2025-12-25 14:26:43', 22, 4, 'paid', '699D4'),
(36, 4, 92500, '2025-12-25 15:14:58', 23, 2, 'paid', '2C307'),
(37, 4, 92500, '2025-12-25 15:15:26', 22, 2, 'paid', '13423'),
(38, 4, 92500, '2025-12-25 15:15:59', 19, 2, 'paid', '945EB'),
(39, 4, 90000, '2025-12-25 15:48:30', 22, 2, 'paid', '4F69B'),
(40, 2, 45000, '2025-12-26 16:07:16', 27, 1, 'paid', '0AF06'),
(41, 2, 135000, '2025-12-26 16:07:40', 27, 3, 'paid', '81718'),
(42, 2, 135000, '2025-12-26 16:07:42', 27, 3, 'paid', '6498A'),
(43, 2, 135000, '2025-12-26 16:07:43', 27, 3, 'paid', '88394'),
(44, 2, 135000, '2025-12-26 16:07:43', 27, 3, 'paid', '1C69E'),
(45, 2, 135000, '2025-12-26 16:07:43', 27, 3, 'paid', '022FC'),
(46, 2, 135000, '2025-12-26 16:07:43', 27, 3, 'paid', '9B77A'),
(47, 2, 135000, '2025-12-26 16:07:43', 27, 3, 'paid', '7D086'),
(48, 2, 90000, '2025-12-26 16:07:55', 27, 2, 'paid', '1AEA9'),
(49, 2, 90000, '2025-12-26 16:07:55', 27, 2, 'paid', 'A4C57'),
(50, 2, 90000, '2025-12-26 16:07:56', 27, 2, 'paid', '72AE5'),
(51, 2, 90000, '2025-12-26 16:08:14', 26, 2, 'paid', '40F8B'),
(52, 6, 90000, '2025-12-27 03:16:13', 26, 2, 'paid', '2769C'),
(53, 6, 45000, '2025-12-27 03:16:53', 26, 1, 'paid', 'FD778'),
(54, 6, 45000, '2025-12-27 03:20:31', 26, 1, 'paid', '152BF'),
(55, 6, 45000, '2025-12-27 03:21:07', 26, 1, 'paid', 'E6F95'),
(56, 6, 45000, '2025-12-27 03:25:42', 26, 1, 'paid', 'B9794'),
(57, 6, 45000, '2025-12-27 03:25:53', 26, 1, 'paid', '52A92'),
(58, 6, 45000, '2025-12-27 03:28:11', 26, 1, 'paid', '0C913'),
(59, 6, 45000, '2025-12-27 03:29:36', 26, 1, 'paid', 'D06A4'),
(60, 6, 45000, '2025-12-27 03:29:57', 26, 1, 'paid', 'E1F16'),
(61, 6, 45000, '2025-12-27 03:30:35', 26, 1, 'paid', '32063'),
(62, 6, 45000, '2025-12-27 03:38:09', 26, 1, 'paid', '6E5B8'),
(63, 6, 45000, '2025-12-27 03:41:33', 26, 1, 'paid', '0D1FE'),
(64, 6, 45000, '2025-12-27 03:47:58', 26, 1, 'paid', '05EAD'),
(65, 6, 45000, '2025-12-27 04:13:20', 26, 1, 'paid', 'AFC37'),
(66, 4, 90000, '2025-12-27 05:06:54', 24, 2, 'paid', 'FDF23'),
(67, 4, 90000, '2025-12-27 12:31:34', 32, 2, 'paid', '54477'),
(68, 4, 45000, '2025-12-27 12:32:19', 32, 1, 'paid', 'FA65E');

-- --------------------------------------------------------

--
-- Table structure for table `transaction_seats`
--

CREATE TABLE `transaction_seats` (
  `transaction_seat_id` int(11) NOT NULL,
  `transaction_id` int(11) NOT NULL,
  `seat_id` int(11) NOT NULL,
  `showtime_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaction_seats`
--

INSERT INTO `transaction_seats` (`transaction_seat_id`, `transaction_id`, `seat_id`, `showtime_id`) VALUES
(24, 33, 41, 17),
(25, 34, 42, 17),
(26, 36, 44, 23),
(27, 36, 45, 23),
(28, 38, 31, 19),
(29, 38, 32, 19),
(30, 39, 281, 22),
(31, 39, 282, 22),
(32, 40, 43, 27),
(43, 51, 278, 26),
(44, 51, 279, 26),
(45, 52, 281, 26),
(46, 52, 282, 26),
(47, 53, 280, 26),
(48, 54, 283, 26),
(49, 65, 627, 26),
(50, 66, 152, 24),
(51, 66, 153, 24),
(52, 67, 152, 32),
(53, 67, 153, 32),
(54, 68, 155, 32);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','customer') NOT NULL DEFAULT 'customer',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `full_name`, `email`, `phone`, `password`, `role`, `created_at`) VALUES
(1, 'admin1', 'Sarah', 'sarah@filmverse.ac.id', '081478994321', '$2y$10$mFaOEldF/suZ/FRtZ5u0q.eWfc4tewroLbPcR3mSOcefBsDzUR9W6', 'admin', '2025-11-29 15:55:46'),
(2, 'max1', 'Max Verstappen', 'maxverstappen@gmail.com', '081234546874', '$2y$10$razSBtoHerHQJw3KgPmfxuHm4QWWgY0yfI8bVyd9nvW9ZxScBALRy', 'customer', '2025-12-16 14:50:32'),
(4, 'carlos55', 'Carlos Sainz', 'carlossainz@gmail.com', '081446894223', '$2y$10$I/yuIzTGERnQ3nvxBVEZoug60TG621mUj/lCWqAtqphIf0qfcWoTG', 'customer', '2025-12-21 12:28:05'),
(6, 'charles16', 'Charles Leclerc', 'charlesleclerc@gmail.com', '081987443148', '$2y$10$l0Rwjn7ANz2lkqdiCfpJPexd2dclGjwAZ0jOlGagby/QVxGmWxfYW', 'customer', '2025-12-25 16:40:36'),
(7, 'lewis44', 'Lewis Hamilton', 'lewishamilton@gmail.com', '081927392828', '$2y$10$gWbSOCfI7nVVDq8kBSEy.utA2OkGvmFSv7izaPiBxWSVwiWX8VYNi', 'customer', '2025-12-27 12:37:41');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `genres`
--
ALTER TABLE `genres`
  ADD PRIMARY KEY (`genre_id`),
  ADD UNIQUE KEY `genre_name` (`genre_name`) USING BTREE;

--
-- Indexes for table `movies`
--
ALTER TABLE `movies`
  ADD PRIMARY KEY (`movie_id`);

--
-- Indexes for table `movie_genre`
--
ALTER TABLE `movie_genre`
  ADD PRIMARY KEY (`movie_id`,`genre_id`),
  ADD KEY `fk_moviegenre_genre` (`genre_id`);

--
-- Indexes for table `prices`
--
ALTER TABLE `prices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `seats`
--
ALTER TABLE `seats`
  ADD PRIMARY KEY (`seat_id`),
  ADD UNIQUE KEY `uniq_seat` (`studio_id`,`seat_row`,`seat_column`),
  ADD KEY `studio_id` (`studio_id`) USING BTREE;

--
-- Indexes for table `showtimes`
--
ALTER TABLE `showtimes`
  ADD PRIMARY KEY (`showtime_id`),
  ADD KEY `movie_id` (`movie_id`),
  ADD KEY `studio_id` (`studio_id`);

--
-- Indexes for table `studios`
--
ALTER TABLE `studios`
  ADD PRIMARY KEY (`studio_id`);

--
-- Indexes for table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`ticket_id`),
  ADD UNIQUE KEY `showtime_id` (`showtime_id`),
  ADD KEY `transaction_id` (`transaction_id`),
  ADD KEY `seat_id` (`seat_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`transaction_id`),
  ADD UNIQUE KEY `booking_code` (`booking_code`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `transaction_seats`
--
ALTER TABLE `transaction_seats`
  ADD PRIMARY KEY (`transaction_seat_id`),
  ADD UNIQUE KEY `seat_id` (`seat_id`,`transaction_id`),
  ADD UNIQUE KEY `seat_id_2` (`seat_id`,`showtime_id`),
  ADD KEY `transaction_id` (`transaction_id`),
  ADD KEY `showtime_id` (`showtime_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `genres`
--
ALTER TABLE `genres`
  MODIFY `genre_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `movies`
--
ALTER TABLE `movies`
  MODIFY `movie_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `prices`
--
ALTER TABLE `prices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `seats`
--
ALTER TABLE `seats`
  MODIFY `seat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=875;

--
-- AUTO_INCREMENT for table `showtimes`
--
ALTER TABLE `showtimes`
  MODIFY `showtime_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `studios`
--
ALTER TABLE `studios`
  MODIFY `studio_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `ticket_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `transaction_seats`
--
ALTER TABLE `transaction_seats`
  MODIFY `transaction_seat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `movie_genre`
--
ALTER TABLE `movie_genre`
  ADD CONSTRAINT `fk_moviegenre_genre` FOREIGN KEY (`genre_id`) REFERENCES `genres` (`genre_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_moviegenre_movie` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`movie_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `seats`
--
ALTER TABLE `seats`
  ADD CONSTRAINT `seats_ibfk_1` FOREIGN KEY (`studio_id`) REFERENCES `studios` (`studio_id`) ON DELETE CASCADE;

--
-- Constraints for table `showtimes`
--
ALTER TABLE `showtimes`
  ADD CONSTRAINT `showtimes_ibfk_1` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`movie_id`),
  ADD CONSTRAINT `showtimes_ibfk_2` FOREIGN KEY (`studio_id`) REFERENCES `studios` (`studio_id`);

--
-- Constraints for table `tickets`
--
ALTER TABLE `tickets`
  ADD CONSTRAINT `tickets_ibfk_1` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`transaction_id`),
  ADD CONSTRAINT `tickets_ibfk_2` FOREIGN KEY (`showtime_id`) REFERENCES `showtimes` (`showtime_id`),
  ADD CONSTRAINT `tickets_ibfk_3` FOREIGN KEY (`seat_id`) REFERENCES `seats` (`seat_id`);

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `transaction_seats`
--
ALTER TABLE `transaction_seats`
  ADD CONSTRAINT `transaction_seats_ibfk_1` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`transaction_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transaction_seats_ibfk_2` FOREIGN KEY (`seat_id`) REFERENCES `seats` (`seat_id`),
  ADD CONSTRAINT `transaction_seats_ibfk_3` FOREIGN KEY (`showtime_id`) REFERENCES `showtimes` (`showtime_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
