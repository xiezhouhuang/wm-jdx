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
-- 表的结构 `wm_order`
--

CREATE TABLE IF NOT EXISTS `wm_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `activities` text COMMENT '活动详情',
  `book` tinyint(1) DEFAULT '0' COMMENT '是否预订单',
  `comment` varchar(255) DEFAULT NULL COMMENT '备注',
  `daySeq` int(11) NOT NULL COMMENT '当天流水号',
  `deliveryTime` bigint(20) NOT NULL COMMENT '流转时间',
  `dinnerNumber` int(11) NOT NULL COMMENT '菜品编号',
  `dishes` text NOT NULL COMMENT '菜品详情',
  `erpShopId` varchar(50) NOT NULL COMMENT 'erp门店ID',
  `invoiceTitle` varchar(255) DEFAULT NULL COMMENT '抬头',
  `needInvoice` tinyint(1) NOT NULL COMMENT '是否需要发票',
  `onlinePaid` tinyint(1) NOT NULL COMMENT '是否在线支付',
  `orderId` varchar(80) NOT NULL COMMENT '订单ID',
  `orderTime` bigint(20) NOT NULL COMMENT '订单推送时间',
  `orderViewId` varchar(50) NOT NULL COMMENT '订单展示号',
  `originAmount` decimal(9,2) NOT NULL COMMENT '菜品原价',
  `originStatus` varchar(25) NOT NULL COMMENT '菜品状态',
  `shippingType` varchar(20) NOT NULL,
  `packageFee` decimal(9,2) NOT NULL COMMENT '餐盒费',
  `payAmount` decimal(9,2) NOT NULL COMMENT '用户实际支付价',
  `poiReceiveDetails` text NOT NULL COMMENT '对账详情',
  `recipientAddress` varchar(255) NOT NULL COMMENT '收货人地址',
  `recipientName` varchar(50) NOT NULL COMMENT '收货人姓名',
  `recipientPhone` varchar(25) NOT NULL COMMENT '收货人电话',
  `shippingFee` decimal(9,2) NOT NULL COMMENT '运输费用',
  `status` int(5) NOT NULL COMMENT '状态',
  `taxPayerId` varchar(25) NOT NULL COMMENT '税号',
  `wmShopId` varchar(25) NOT NULL COMMENT '外卖门店ID',
  `wmType` int(1) NOT NULL COMMENT '外卖类型2美团3饿了么',
  `erp_no` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
