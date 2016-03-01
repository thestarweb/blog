-- phpMyAdmin SQL Dump
-- version 4.4.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 2015-07-09 04:18:32
-- 服务器版本： 5.6.25
-- PHP Version: 5.6.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `blog`
--

-- --------------------------------------------------------

--
-- 表的结构 `admins`
--

DROP TABLE IF EXISTS `admins`;
CREATE TABLE IF NOT EXISTS `admins` (
  `id` mediumint(8) unsigned NOT NULL,
  `uid` mediumint(8) unsigned NOT NULL,
  `pid` tinyint(3) unsigned NOT NULL,
  `type` tinyint(3) unsigned NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `admin_pages`
--

DROP TABLE IF EXISTS `admin_pages`;
CREATE TABLE IF NOT EXISTS `admin_pages` (
  `id` smallint(5) unsigned NOT NULL,
  `pid` smallint(5) unsigned NOT NULL,
  `title` varchar(10) NOT NULL,
  `is_menu` tinyint(1) NOT NULL,
  `src` varchar(200) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 插入之前先把表清空（truncate） `admin_pages`
--

TRUNCATE TABLE `admin_pages`;
--
-- 转存表中的数据 `admin_pages`
--

INSERT INTO `admin_pages` (`id`, `pid`, `title`, `is_menu`, `src`) VALUES
(1, 0, '文章管理', 1, NULL),
(11, 1, '发布文章', 0, NULL),
(12, 1, '文章列表', 0, NULL),
(15, 1, '导入/导出', 0, NULL),
(2, 0, '分类管理', 0, NULL),
(3, 0, '评论管理', 1, NULL),
(14, 3, '所有评论', 0, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `essay`
--

DROP TABLE IF EXISTS `essay`;
CREATE TABLE IF NOT EXISTS `essay` (
  `id` int(10) unsigned NOT NULL,
  `title` varchar(20) COLLATE utf8_bin NOT NULL,
  `content` mediumtext COLLATE utf8_bin NOT NULL,
  `time` int(10) unsigned NOT NULL,
  `sender` mediumint(8) unsigned NOT NULL,
  `display` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- 表的结构 `remark`
--

DROP TABLE IF EXISTS `remark`;
CREATE TABLE IF NOT EXISTS `remark` (
  `id` int(11) NOT NULL,
  `eid` int(11) NOT NULL,
  `uid` mediumint(9) NOT NULL,
  `time` int(11) NOT NULL,
  `content` varchar(500) COLLATE utf8_bin NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- 表的结构 `type`
--

DROP TABLE IF EXISTS `type`;
CREATE TABLE IF NOT EXISTS `type` (
  `id` tinyint(4) NOT NULL,
  `name` varchar(20) COLLATE utf8_bin NOT NULL,
  `info` varchar(1000) COLLATE utf8_bin DEFAULT NULL,
  `pid` tinyint(4) NOT NULL DEFAULT '0',
  `ename` varchar(10)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- 表的结构 `type_link`
--

DROP TABLE IF EXISTS `type_link`;
CREATE TABLE IF NOT EXISTS `type_link` (
  `tid` tinyint(4) NOT NULL,
  `eid` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin_pages`
--
ALTER TABLE `admin_pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `essay`
--
ALTER TABLE `essay`
  ADD PRIMARY KEY (`id`),
  ADD KEY `time` (`time`);

--
-- Indexes for table `remark`
--
ALTER TABLE `remark`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uid` (`uid`);

--
-- Indexes for table `type`
--
ALTER TABLE `type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `type_link`
--
ALTER TABLE `type_link`
  ADD KEY `tid` (`tid`,`eid`),
  ADD KEY `tid_2` (`tid`,`eid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `admin_pages`
--
ALTER TABLE `admin_pages`
  MODIFY `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `essay`
--
ALTER TABLE `essay`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `remark`
--
ALTER TABLE `remark`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `type`
--
ALTER TABLE `type`
  MODIFY `id` tinyint(4) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
