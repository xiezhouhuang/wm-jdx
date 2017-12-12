-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- 主机: 127.0.0.1
-- 生成日期: 2017 年 12 月 12 日 17:57
-- 服务器版本: 5.5.23
-- PHP 版本: 5.4.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `testing`
--

-- --------------------------------------------------------

--
-- 表的结构 `wm_order_update`
--

CREATE TABLE IF NOT EXISTS `wm_order_update` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `orderId` varchar(80) NOT NULL,
  `operator` int(3) DEFAULT NULL,
  `status` int(8) DEFAULT NULL,
  `updateTime` bigint(20) NOT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `reasonCode` varchar(20) DEFAULT NULL,
  `refundStatus` varchar(25) DEFAULT NULL,
  `type` int(1) DEFAULT NULL COMMENT '1-取消单(未收到货) 2-退单(收 到货',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
