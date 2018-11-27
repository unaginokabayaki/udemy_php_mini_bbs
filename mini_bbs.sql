-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 
-- サーバのバージョン： 10.1.37-MariaDB
-- PHP Version: 7.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mini_bbs`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `members`
--

CREATE TABLE `members` (
  `id` int(11) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `password` varchar(100) CHARACTER SET utf8mb4 NOT NULL,
  `picture` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `created` datetime NOT NULL,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- テーブルのデータのダンプ `members`
--

INSERT INTO `members` (`id`, `name`, `email`, `password`, `picture`, `created`, `modified`) VALUES
(1, 'ww', 'eee', '70c881d4a26984ddce795f6f71817c9cf4480e79', '2018', '2018-11-27 01:10:15', '2018-11-26 16:10:15'),
(2, 'aaaaa', 'ssss', 'e80f57a4867b22a5010145e61d45e7af8e2fcfd6', '2018Nov27011146mig.jpg', '2018-11-27 01:11:53', '2018-11-26 16:37:06'),
(3, '1111', '2222', 'f56d6351aa71cff0debea014d13525e42036187a', '2018Nov27152813', '2018-11-27 15:28:16', '2018-11-27 06:28:16'),
(4, '4444', '5555', '4c1b52409cf6be3896cf163fa17b32e4da293f2e', '2018Nov27011146mig.jpg', '2018-11-27 15:43:34', '2018-11-27 11:34:28'),
(5, 'PPPP', '8888', '4170ac2a2782a1516fe9e13d7322ae482c1bd594', '2018Nov27205031A.png', '2018-11-27 20:50:38', '2018-11-27 11:55:26');

-- --------------------------------------------------------

--
-- テーブルの構造 `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `message` text CHARACTER SET utf8mb4 NOT NULL,
  `member_id` int(11) NOT NULL,
  `reply_message_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- テーブルのデータのダンプ `posts`
--

INSERT INTO `posts` (`id`, `message`, `member_id`, `reply_message_id`, `created`, `modified`) VALUES
(2, 'めっせーじ', 4, 0, '2018-11-27 18:46:50', '2018-11-27 09:46:50'),
(6, 'わーい', 5, 0, '2018-11-27 21:31:39', '2018-11-27 12:31:39'),
(7, '@PPPP わーい\r\nありがとう！', 5, 6, '2018-11-27 22:08:48', '2018-11-27 13:08:48'),
(8, '@4444 めっせーじ\r\nなんだそれ', 5, 2, '2018-11-27 22:09:18', '2018-11-27 13:09:18'),
(10, 'こんにちは', 4, 0, '2018-11-27 22:58:17', '2018-11-27 13:58:17'),
(11, 'ひまですか？', 4, 0, '2018-11-27 22:58:23', '2018-11-27 13:58:23'),
(12, 'もうひとつ', 4, 0, '2018-11-27 23:24:56', '2018-11-27 14:24:56'),
(13, 'ごごごご', 5, 0, '2018-11-27 23:46:32', '2018-11-27 14:46:32'),
(14, 'あとひといき', 5, 0, '2018-11-27 23:46:39', '2018-11-27 14:46:39'),
(15, 'あとみっつかな', 5, 0, '2018-11-27 23:46:53', '2018-11-27 14:46:53'),
(16, 'あとにこ', 5, 0, '2018-11-27 23:47:03', '2018-11-27 14:47:03'),
(17, 'さいご', 5, 0, '2018-11-27 23:47:07', '2018-11-27 14:47:07'),
(18, 'ぴったしな', 5, 0, '2018-11-27 23:47:21', '2018-11-27 14:47:21');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
