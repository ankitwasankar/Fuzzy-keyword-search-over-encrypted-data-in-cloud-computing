-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 18, 2015 at 05:15 AM
-- Server version: 5.5.24-log
-- PHP Version: 5.4.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `eks`
--
create database eks;
use eks;
-- --------------------------------------------------------

--
-- Table structure for table `file_keys`
--

CREATE TABLE IF NOT EXISTS `file_keys` (
  `f_id` int(11) NOT NULL,
  `key` varchar(300) NOT NULL DEFAULT '',
  PRIMARY KEY (`f_id`,`key`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `upl_files`
--

CREATE TABLE IF NOT EXISTS `upl_files` (
  `f_id` int(11) NOT NULL AUTO_INCREMENT,
  `f_title` varchar(500) NOT NULL,
  `f_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `f_loc` varchar(80) NOT NULL,
  `f_ext` varchar(30) NOT NULL,
  `f_u_id` int(11) NOT NULL,
  PRIMARY KEY (`f_id`),
  KEY `foreign_key` (`f_u_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_auth`
--

CREATE TABLE IF NOT EXISTS `user_auth` (
  `u_id` int(11) NOT NULL AUTO_INCREMENT,
  `u_name` varchar(40) NOT NULL,
  `u_pass` varchar(100) NOT NULL,
  `u_fname` varchar(20) NOT NULL DEFAULT 'NA',
  `u_lname` varchar(20) NOT NULL DEFAULT 'NA',
  `u_mbno` varchar(12) NOT NULL,
  `u_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `u_type` varchar(10) NOT NULL DEFAULT 'user',
  `s_que` varchar(100) NOT NULL,
  `s_ans` varchar(100) NOT NULL,
  PRIMARY KEY (`u_id`),
  UNIQUE KEY `u_name` (`u_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `user_auth`
--

INSERT INTO `user_auth` (`u_id`, `u_name`, `u_pass`, `u_fname`, `u_lname`, `u_mbno`, `u_time`, `u_type`, `s_que`, `s_ans`) VALUES
(7, 'admin', 'e807f1fcf82d132f9bb018ca6738a19f', 'NA', 'NA', '9999999999', '2015-01-18 04:53:59', 'admin', 'na', 'na'),
(8, 'ankit.wasankar12@gmail.com', 'e807f1fcf82d132f9bb018ca6738a19f', 'Ankit', 'Wasankar', '1111111111', '2015-01-18 05:15:04', 'user', 'Who is favourite Cricketer?', 'sachin');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `file_keys`
--
ALTER TABLE `file_keys`
  ADD CONSTRAINT `file_keys_ibfk_1` FOREIGN KEY (`f_id`) REFERENCES `upl_files` (`f_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `upl_files`
--
ALTER TABLE `upl_files`
  ADD CONSTRAINT `upl_files_ibfk_1` FOREIGN KEY (`f_u_id`) REFERENCES `user_auth` (`u_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
