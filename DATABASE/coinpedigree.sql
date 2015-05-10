-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 26, 2013 at 04:00 AM
-- Server version: 5.5.24-log
-- PHP Version: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `coinpedigree`
--

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE IF NOT EXISTS `items` (
  `id_item` int(11) NOT NULL AUTO_INCREMENT,
  `source` varchar(100) NOT NULL,
  `pcgs_ver_id` varchar(100) NOT NULL,
  `pcgs_coin_no` varchar(100) NOT NULL,
  `pcgs_date_mintmark` varchar(100) NOT NULL,
  `pcgs_denomination` varchar(100) NOT NULL,
  `pcgs_country` varchar(100) NOT NULL,
  `pcgs_grade` varchar(100) NOT NULL,
  `pcgs_mintage` varchar(100) NOT NULL,
  `pcgs_price_guide_value` decimal(12,2) NOT NULL,
  `pcgs_holder_type` varchar(100) NOT NULL,
  `pcgs_population` int(11) NOT NULL,
  `status` char(1) NOT NULL,
  `suspend_at` datetime DEFAULT NULL,
  `suspend_reason` varchar(100) DEFAULT NULL,
  `pcgs_variety` varchar(100) DEFAULT NULL,
  `cac` varchar(100) DEFAULT NULL,
  `pcgs_minor_variety` varchar(100) NOT NULL,
  `pcgs_mint_error` varchar(100) NOT NULL,
  `pcgs_pedigree` varchar(100) NOT NULL,
  PRIMARY KEY (`id_item`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id_item`, `source`, `pcgs_ver_id`, `pcgs_coin_no`, `pcgs_date_mintmark`, `pcgs_denomination`, `pcgs_country`, `pcgs_grade`, `pcgs_mintage`, `pcgs_price_guide_value`, `pcgs_holder_type`, `pcgs_population`, `status`, `suspend_at`, `suspend_reason`, `pcgs_variety`, `cac`, `pcgs_minor_variety`, `pcgs_mint_error`, `pcgs_pedigree`) VALUES
(2, 'pcgs', '2059282', '7130', '1881-S', '$1', 'The United States of America', 'MS67', '12,760,000', '850.00', 'Standard', 1674, 'A', NULL, NULL, '', 'Gold', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `item_auctions`
--

CREATE TABLE IF NOT EXISTS `item_auctions` (
  `id_item_auction` int(11) NOT NULL AUTO_INCREMENT,
  `id_ownership` int(11) NOT NULL,
  `url` varchar(1000) NOT NULL,
  `notes` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id_item_auction`),
  KEY `FK_relationship_4` (`id_ownership`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `item_images`
--

CREATE TABLE IF NOT EXISTS `item_images` (
  `id_item_image` int(11) NOT NULL AUTO_INCREMENT,
  `id_ownership` int(11) NOT NULL,
  `filename` varchar(250) DEFAULT NULL,
  `url` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`id_item_image`),
  KEY `FK_relationship_5` (`id_ownership`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `login_history`
--

CREATE TABLE IF NOT EXISTS `login_history` (
  `id_login_history` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `login_time` datetime NOT NULL,
  `login_ip` varchar(20) NOT NULL,
  PRIMARY KEY (`id_login_history`),
  KEY `FK_relationship_91` (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `owners`
--

CREATE TABLE IF NOT EXISTS `owners` (
  `id_owner` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `pwd` varchar(100) NOT NULL,
  `verification_code` varchar(20) NOT NULL,
  `verification_code_valid_till` datetime NOT NULL,
  `forget_pwd_code` varchar(20) NOT NULL,
  `last_login_from_ip` varchar(100) NOT NULL,
  `last_login_at` datetime NOT NULL,
  `status` char(1) NOT NULL,
  `suspend_reason` varchar(100) DEFAULT NULL,
  `suspend_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_owner`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `owners`
--

INSERT INTO `owners` (`id_owner`, `name`, `email`, `pwd`, `verification_code`, `verification_code_valid_till`, `forget_pwd_code`, `last_login_from_ip`, `last_login_at`, `status`, `suspend_reason`, `suspend_at`) VALUES
(1, 'subhadip sahoo', 'subhadip.sahoo@indiasoftwareteam.com', '1234', 'GSMfhABwWrt0C36bJDOE', '2013-12-02 10:57:09', '', '127.0.0.1', '2013-11-25 18:57:13', 'A', NULL, NULL),
(2, 'qss', 'test@qss.in', '1234', '', '0000-00-00 00:00:00', '', '127.0.0.1', '2013-11-25 18:55:42', 'A', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ownerships`
--

CREATE TABLE IF NOT EXISTS `ownerships` (
  `id_ownership` int(11) NOT NULL AUTO_INCREMENT,
  `id_owner` int(11) NOT NULL,
  `id_item` int(11) NOT NULL,
  `entry_date` datetime NOT NULL,
  `postcode` varchar(100) NOT NULL,
  `notes` text,
  PRIMARY KEY (`id_ownership`),
  KEY `FK_relationship_2` (`id_owner`),
  KEY `FK_relationship_3` (`id_item`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `ownerships`
--

INSERT INTO `ownerships` (`id_ownership`, `id_owner`, `id_item`, `entry_date`, `postcode`, `notes`) VALUES
(3, 2, 2, '2013-11-25 18:54:18', '700001', 'testing ');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `option_key` varchar(50) NOT NULL,
  `option_value` text NOT NULL,
  PRIMARY KEY (`option_key`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`option_key`, `option_value`) VALUES
('ABOUT_US', '																<img src="images/rarest-coins-public-domain-photo.PNG" alt="">\n        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry''s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\n        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry''s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.</p>\n        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry''s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. </p>\n        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry''s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\n        <img src="images/rarest-coins-public-domain-photo.PNG" alt="">\n        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry''s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\n        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry''s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.</p>\n        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry''s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. </p>																'),
('BASE_DIRECTORY', 'D:/wamp/www/CoinPedigree'),
('BASE_URL', 'http://localhost/CoinPedigree'),
('COMMUNICATIONS_FROM_EMAIL_ADDRESS', 'subhadip.sahoo@indiasoftwareteam.com'),
('COMMUNICATIONS_FROM_NAME', 'Appropriate Alternative'),
('EMAIL_CONTACT', 'donotreply@viewthedemo.com'),
('FILE_UPLOAD_FOLDER', 'ap/uploads'),
('FILE_UPLOAD_URL', 'http://localhost/CoinPedigree/ap/uploads'),
('HOMEPAGE', '																																<h1>Lorem Ipsum is simply dummy text of the printing and typesetting industry. </h1>\n    <div class=\\"con-details\\">\n		<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\\''s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets contain</p>\n	</div>																														'),
('HOW_WE_WORKS', '<img src="images/rarest-coins-public-domain-photo.PNG" alt="">\n        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry''s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\n        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry''s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.</p>\n        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry''s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. </p>\n        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry''s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\n        <img src="images/rarest-coins-public-domain-photo.PNG" alt="">\n        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry''s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\n        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry''s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.</p>\n        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry''s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `user_fullname` varchar(50) NOT NULL,
  `user_status` char(1) NOT NULL,
  `user_type` char(1) NOT NULL,
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `user_name`, `password`, `email`, `user_fullname`, `user_status`, `user_type`) VALUES
(1, 'admin', 'admin', 'admin_mail@domain.com', 'Administrator', 'A', 'A');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `item_auctions`
--
ALTER TABLE `item_auctions`
  ADD CONSTRAINT `FK_relationship_4` FOREIGN KEY (`id_ownership`) REFERENCES `ownerships` (`id_ownership`);

--
-- Constraints for table `item_images`
--
ALTER TABLE `item_images`
  ADD CONSTRAINT `FK_relationship_5` FOREIGN KEY (`id_ownership`) REFERENCES `ownerships` (`id_ownership`);

--
-- Constraints for table `login_history`
--
ALTER TABLE `login_history`
  ADD CONSTRAINT `FK_relationship_91` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`);

--
-- Constraints for table `ownerships`
--
ALTER TABLE `ownerships`
  ADD CONSTRAINT `FK_relationship_2` FOREIGN KEY (`id_owner`) REFERENCES `owners` (`id_owner`),
  ADD CONSTRAINT `FK_relationship_3` FOREIGN KEY (`id_item`) REFERENCES `items` (`id_item`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
