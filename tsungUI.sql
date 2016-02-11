-- phpMyAdmin SQL Dump
-- version 4.5.4.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 05, 2016 at 09:05 PM
-- Server version: 5.7.9
-- PHP Version: 7.0.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tsung`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_table` (IN `in_search` VARCHAR(50))  READS SQL DATA
BEGIN
DECLARE trunc_cmd VARCHAR(50);
DECLARE search_string VARCHAR(250);
DECLARE db,tbl,clmn CHAR(50);
DECLARE done INT DEFAULT 0;
DECLARE COUNTER INT;
DECLARE table_cur CURSOR FOR
SELECT concat('SELECT COUNT(*) INTO @CNT_VALUE FROM `',table_schema,'`.`',table_name,'` WHERE `', column_name,'` REGEXP "',in_search,'"') ,table_schema,table_name,column_name FROM information_schema.COLUMNS WHERE TABLE_SCHEMA IN ('tsung');
DECLARE CONTINUE HANDLER FOR NOT FOUND SET done=1;
PREPARE trunc_cmd FROM "TRUNCATE TABLE temp_details;";
EXECUTE trunc_cmd ; 
OPEN table_cur;
table_loop:LOOP
FETCH table_cur INTO search_string,db,tbl,clmn;
SET @search_string = search_string;
PREPARE search_string FROM @search_string;
EXECUTE search_string;
SET COUNTER = @CNT_VALUE;
IF COUNTER>0 THEN
INSERT INTO temp_details VALUES(db,tbl,clmn);
END IF;
IF done=1 THEN
LEAVE table_loop;
END IF;
END LOOP;
CLOSE table_cur;
SELECT * FROM temp_details;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `tsung_add_cookie`
--

CREATE TABLE `tsung_add_cookie` (
  `id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL COMMENT 'Should be called "key" but key is a reserved mysql word',
  `value` varchar(255) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `parent_id` int(11) NOT NULL,
  `parent_table` enum('tsung_http') NOT NULL DEFAULT 'tsung_http'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tsung_arrivalphase`
--

CREATE TABLE `tsung_arrivalphase` (
  `id` int(11) NOT NULL,
  `phase` int(11) NOT NULL,
  `duration` int(11) NOT NULL,
  `unit` enum('second','minute') NOT NULL,
  `parent_id` int(11) NOT NULL,
  `parent_table` enum('tsung_load') NOT NULL DEFAULT 'tsung_load',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tsung_arrivalphase`
--

INSERT INTO `tsung_arrivalphase` (`id`, `phase`, `duration`, `unit`, `parent_id`, `parent_table`, `timestamp`) VALUES
(1, 1, 1, 'minute', 1, 'tsung_load', '2016-02-04 17:28:22'),
(2, 2, 2, 'minute', 1, 'tsung_load', '2016-02-04 17:28:22'),
(3, 3, 2, 'minute', 1, 'tsung_load', '2016-02-04 17:29:25');

-- --------------------------------------------------------

--
-- Table structure for table `tsung_client`
--

CREATE TABLE `tsung_client` (
  `id` int(11) NOT NULL,
  `host` varchar(60) NOT NULL,
  `port` int(5) DEFAULT NULL,
  `type` enum('batch') DEFAULT NULL,
  `use_controler_vm` tinyint(1) DEFAULT NULL,
  `weight` int(2) DEFAULT NULL,
  `cpu` int(2) DEFAULT NULL,
  `batch` enum('torque','LSF','OAR','') DEFAULT NULL,
  `maxusers` int(20) DEFAULT NULL,
  `scan_intf` varchar(8) DEFAULT NULL,
  `parent_id` int(11) NOT NULL,
  `parent_table` enum('tsung_clients') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tsung_client`
--

INSERT INTO `tsung_client` (`id`, `host`, `port`, `type`, `use_controler_vm`, `weight`, `cpu`, `batch`, `maxusers`, `scan_intf`, `parent_id`, `parent_table`) VALUES
(1, 'localhost', NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, 1, 'tsung_clients');

-- --------------------------------------------------------

--
-- Table structure for table `tsung_clients`
--

CREATE TABLE `tsung_clients` (
  `id` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `parent_id` int(11) NOT NULL,
  `parent_table` enum('tsung_config_templates') NOT NULL DEFAULT 'tsung_config_templates'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tsung_clients`
--

INSERT INTO `tsung_clients` (`id`, `timestamp`, `parent_id`, `parent_table`) VALUES
(1, '2016-02-04 17:34:50', 1, 'tsung_config_templates');

-- --------------------------------------------------------

--
-- Table structure for table `tsung_client_ip`
--

CREATE TABLE `tsung_client_ip` (
  `id` int(11) NOT NULL,
  `ip_value` varchar(50) NOT NULL,
  `scan` tinyint(1) DEFAULT NULL,
  `value` varchar(20) DEFAULT NULL,
  `parent_id` int(11) NOT NULL,
  `parent_table` enum('tsung_client') NOT NULL DEFAULT 'tsung_client',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tsung_config_templates`
--

CREATE TABLE `tsung_config_templates` (
  `id` int(11) NOT NULL,
  `name` varchar(40) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `profile` varchar(250) DEFAULT NULL,
  `loglevel` enum('emergency','critical','error','warning','notice','info','debug') DEFAULT 'notice',
  `backend` enum('default','json') DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tsung_config_templates`
--

INSERT INTO `tsung_config_templates` (`id`, `name`, `created`, `profile`, `loglevel`, `backend`, `timestamp`) VALUES
(1, 'apirt.synapsys.us-weather', '2016-01-29 10:07:15', '3 phases (1: 100 per 4 sec for 1 minute; 2: 100 per 1 sec for 2 minutes; 3: 100 every 10 seconds for 2 minutes.)', 'info', NULL, '2016-02-04 17:15:43');

-- --------------------------------------------------------

--
-- Table structure for table `tsung_dyn_variable`
--

CREATE TABLE `tsung_dyn_variable` (
  `id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL COMMENT 'Should be called "key" but key is a reserved mysql word',
  `header` varchar(255) NOT NULL,
  `re` varchar(255) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `parent_id` int(11) NOT NULL,
  `parent_column` enum('tsung_request') NOT NULL DEFAULT 'tsung_request'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tsung_http`
--

CREATE TABLE `tsung_http` (
  `id` int(11) NOT NULL,
  `url` varchar(255) NOT NULL,
  `method` enum('GET','HEAD','POST','PUT','DELETE','TRACE','OPTIONS','CONNECT','PATCH') NOT NULL,
  `version` varchar(10) NOT NULL,
  `contents` varchar(255) DEFAULT NULL,
  `contents_from_file` varchar(255) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `parent_id` int(11) NOT NULL,
  `parent_table` enum('tsung_request') NOT NULL DEFAULT 'tsung_request'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tsung_http`
--

INSERT INTO `tsung_http` (`id`, `url`, `method`, `version`, `contents`, `contents_from_file`, `timestamp`, `parent_id`, `parent_table`) VALUES
(1, '/index.php', 'GET', '1.1', 'widget=national-weathers&amp;wid=8&amp;skip=0&amp;limit=1', '', '2016-02-04 18:21:59', 1, 'tsung_request');

-- --------------------------------------------------------

--
-- Table structure for table `tsung_http_header`
--

CREATE TABLE `tsung_http_header` (
  `id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `value` varchar(255) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `parent_id` int(11) NOT NULL,
  `parent_table` enum('tsung_http') NOT NULL DEFAULT 'tsung_http'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tsung_load`
--

CREATE TABLE `tsung_load` (
  `id` int(11) NOT NULL,
  `durration` int(11) DEFAULT NULL,
  `unit` enum('second','minute','hour','days') DEFAULT NULL,
  `loop` int(11) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `parent_id` int(11) NOT NULL,
  `parent_table` enum('tsung_config_templates') NOT NULL DEFAULT 'tsung_config_templates'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tsung_load`
--

INSERT INTO `tsung_load` (`id`, `durration`, `unit`, `loop`, `timestamp`, `parent_id`, `parent_table`) VALUES
(1, NULL, NULL, NULL, '2016-02-04 17:26:57', 1, 'tsung_config_templates');

-- --------------------------------------------------------

--
-- Table structure for table `tsung_match`
--

CREATE TABLE `tsung_match` (
  `id` int(11) NOT NULL,
  `do` enum('continue','log','abort','restart','loop','dump') DEFAULT NULL,
  `when` enum('match','no_match') DEFAULT NULL,
  `content` varchar(255) DEFAULT NULL,
  `sleep_loop` int(11) DEFAULT NULL,
  `max_loop` int(11) DEFAULT NULL,
  `skip_headers` varchar(8) DEFAULT NULL,
  `apply_to_contents` varchar(255) DEFAULT NULL,
  `subst` tinyint(1) DEFAULT NULL,
  `name` varchar(20) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `parent_id` int(11) NOT NULL,
  `parent_table` enum('tsung_add_cookie','tsung_arrivalphase','tsung_client','tsung_clients','tsung_client_ip','tsung_config_templates','tsung_dyn_variable','tsung_http','tsung_http_header','tsung_load','tsung_match','tsung_monitors','tsung_monitor_mysqladmin','tsung_monitor_snmp','tsung_mysql','tsung_oauth','tsung_oid','tsung_option','tsung_options','tsung_request','tsung_serverHide','tsung_session','tsung_session_setup','tsung_statusinfo','tsung_thinktime','tsung_user','tsung_users','tsung_user_agents','tsung_websocket','tsung_www_authenticate') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tsung_monitors`
--

CREATE TABLE `tsung_monitors` (
  `id` int(11) NOT NULL,
  `host` int(11) NOT NULL,
  `port` int(5) DEFAULT NULL,
  `type` enum('erlang','snmp','munin','') DEFAULT NULL,
  `batch` varchar(20) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `parent_table` enum('tsung_config_templates') NOT NULL DEFAULT 'tsung_config_templates',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tsung_monitor_mysqladmin`
--

CREATE TABLE `tsung_monitor_mysqladmin` (
  `id` int(11) NOT NULL,
  `port` int(5) DEFAULT NULL,
  `username` varchar(60) NOT NULL,
  `password` varchar(60) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `parent_table` enum('tsung_monitors') NOT NULL DEFAULT 'tsung_monitors',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tsung_monitor_snmp`
--

CREATE TABLE `tsung_monitor_snmp` (
  `id` int(11) NOT NULL,
  `port` int(5) DEFAULT NULL,
  `community` varchar(60) DEFAULT NULL,
  `version` varchar(10) DEFAULT NULL,
  `parent_id` int(11) NOT NULL,
  `parent_table` enum('tsung_monitors') NOT NULL DEFAULT 'tsung_monitors',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tsung_mysql`
--

CREATE TABLE `tsung_mysql` (
  `id` int(11) NOT NULL,
  `username` varchar(60) DEFAULT NULL,
  `password` varchar(60) DEFAULT NULL,
  `database` varchar(60) DEFAULT NULL,
  `querry` text,
  `type` enum('authenticate','sql','close') DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `parent_id` int(11) NOT NULL,
  `parent_table` enum('tsung_request') NOT NULL DEFAULT 'tsung_request'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tsung_oauth`
--

CREATE TABLE `tsung_oauth` (
  `id` int(11) NOT NULL,
  `consumer_key` varchar(255) DEFAULT NULL,
  `consumer_secret` varchar(255) DEFAULT NULL,
  `method` varchar(255) DEFAULT NULL,
  `access_token` varchar(255) DEFAULT NULL,
  `access_key` varchar(255) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `parent_id` int(11) NOT NULL,
  `parent_table` enum('tsung_http') NOT NULL DEFAULT 'tsung_http'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tsung_oid`
--

CREATE TABLE `tsung_oid` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL,
  `type` enum('sample','counter','sum','') NOT NULL,
  `eval` varchar(255) DEFAULT NULL,
  `parent_id` int(11) NOT NULL,
  `parent_table` enum('tsung_monitor_snmp') NOT NULL DEFAULT 'tsung_monitor_snmp',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tsung_option`
--

CREATE TABLE `tsung_option` (
  `id` int(11) NOT NULL,
  `name` varchar(30) DEFAULT NULL,
  `value` varchar(100) DEFAULT NULL,
  `min` int(11) DEFAULT NULL,
  `max` int(11) DEFAULT NULL,
  `type` varchar(20) DEFAULT NULL,
  `random` tinyint(1) DEFAULT NULL,
  `override` tinyint(1) DEFAULT NULL,
  `tsung_id` varchar(255) DEFAULT NULL COMMENT 'Is "id" in xml; only used for identifying which file to read in multiple read file situations.',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `parent_id` int(11) NOT NULL,
  `parent_table` enum('tsung_options') NOT NULL DEFAULT 'tsung_options'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tsung_option`
--

INSERT INTO `tsung_option` (`id`, `name`, `value`, `min`, `max`, `type`, `random`, `override`, `tsung_id`, `timestamp`, `parent_id`, `parent_table`) VALUES
(1, 'user_agent', NULL, NULL, NULL, 'ts_http', NULL, NULL, NULL, '2016-02-04 17:36:03', 1, 'tsung_options');

-- --------------------------------------------------------

--
-- Table structure for table `tsung_options`
--

CREATE TABLE `tsung_options` (
  `id` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `parent_id` int(11) NOT NULL,
  `parent_table` enum('tsung_config_templates') NOT NULL DEFAULT 'tsung_config_templates'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tsung_options`
--

INSERT INTO `tsung_options` (`id`, `timestamp`, `parent_id`, `parent_table`) VALUES
(1, '2016-02-04 17:34:50', 1, 'tsung_config_templates');

-- --------------------------------------------------------

--
-- Table structure for table `tsung_request`
--

CREATE TABLE `tsung_request` (
  `id` int(11) NOT NULL,
  `subst` tinyint(1) DEFAULT NULL,
  `session_order` int(2) DEFAULT NULL,
  `parent_id` int(11) NOT NULL,
  `parent_table` enum('tsung_session') NOT NULL DEFAULT 'tsung_session',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tsung_request`
--

INSERT INTO `tsung_request` (`id`, `subst`, `session_order`, `parent_id`, `parent_table`, `timestamp`) VALUES
(1, NULL, NULL, 1, 'tsung_session', '2016-02-04 18:20:38');

-- --------------------------------------------------------

--
-- Table structure for table `tsung_server`
--

CREATE TABLE `tsung_server` (
  `id` int(11) NOT NULL,
  `host` varchar(60) NOT NULL,
  `port` int(5) DEFAULT NULL,
  `type` enum('tcp','ssl','udp','tcp6','ssl6','udp6','websocket','bosh','bosh_ssl') DEFAULT NULL,
  `weight` int(2) DEFAULT NULL,
  `parent_id` int(11) NOT NULL,
  `parent_table` enum('tsung_servers') NOT NULL DEFAULT 'tsung_servers',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tsung_server`
--

INSERT INTO `tsung_server` (`id`, `host`, `port`, `type`, `weight`, `parent_id`, `parent_table`, `timestamp`) VALUES
(1, 'apirt.synapsys.us', 80, 'tcp', NULL, 1, 'tsung_servers', '2016-02-04 22:48:46');

-- --------------------------------------------------------

--
-- Table structure for table `tsung_servers`
--

CREATE TABLE `tsung_servers` (
  `id` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `parent_id` int(11) NOT NULL,
  `parent_table` enum('tsung_config_templates') NOT NULL DEFAULT 'tsung_config_templates'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tsung_servers`
--

INSERT INTO `tsung_servers` (`id`, `timestamp`, `parent_id`, `parent_table`) VALUES
(1, '2016-02-04 17:34:50', 1, 'tsung_config_templates');

-- --------------------------------------------------------

--
-- Table structure for table `tsung_session`
--

CREATE TABLE `tsung_session` (
  `id` int(11) NOT NULL,
  `name` varchar(60) NOT NULL,
  `probability` int(3) DEFAULT NULL,
  `type` enum('ts_http','ts_jabber','ts_pgsql','ts_mysql','ts_websocket','ts_amqp','ts_mqtt','ts_ldap') DEFAULT NULL,
  `weight` int(2) DEFAULT NULL,
  `parent_id` int(11) NOT NULL,
  `parent_table` enum('tsung_config_templates') NOT NULL DEFAULT 'tsung_config_templates',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tsung_session`
--

INSERT INTO `tsung_session` (`id`, `name`, `probability`, `type`, `weight`, `parent_id`, `parent_table`, `timestamp`) VALUES
(1, 'http-example', 100, 'ts_http', NULL, 1, 'tsung_config_templates', '2016-02-04 18:20:16');

-- --------------------------------------------------------

--
-- Table structure for table `tsung_sessions`
--

CREATE TABLE `tsung_sessions` (
  `id` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `parent_id` int(11) NOT NULL,
  `parent_table` enum('tsung_sessions') NOT NULL DEFAULT 'tsung_sessions'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tsung_sessions`
--

INSERT INTO `tsung_sessions` (`id`, `timestamp`, `parent_id`, `parent_table`) VALUES
(1, '2016-02-05 19:12:08', 1, 'tsung_sessions');

-- --------------------------------------------------------

--
-- Table structure for table `tsung_session_setup`
--

CREATE TABLE `tsung_session_setup` (
  `id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `probability` int(3) DEFAULT NULL,
  `parent_id` int(11) NOT NULL,
  `parent_table` enum('tsung_arrivalphase') NOT NULL DEFAULT 'tsung_arrivalphase',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tsung_statusinfo`
--

CREATE TABLE `tsung_statusinfo` (
  `id` int(11) NOT NULL,
  `status` enum('waiting','running','canceled','finished','scheduled','archived') DEFAULT NULL,
  `template` varchar(255) DEFAULT NULL,
  `comment` text,
  `starttime` varchar(16) DEFAULT NULL,
  `pgid` int(10) DEFAULT NULL,
  `started_at` timestamp NULL DEFAULT NULL,
  `finished_at` timestamp NULL DEFAULT NULL,
  `parent_id` int(11) NOT NULL,
  `parent_table` enum('tsung_config_templates') NOT NULL DEFAULT 'tsung_config_templates',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tsung_statusinfo`
--

INSERT INTO `tsung_statusinfo` (`id`, `status`, `template`, `comment`, `starttime`, `pgid`, `started_at`, `finished_at`, `parent_id`, `parent_table`, `timestamp`) VALUES
(34, 'finished', 'apirt.synapsys.us-weather', '1 of three.', '20160201-1022', 388, '2016-02-01 16:22:01', '2016-02-01 16:25:31', 1, 'tsung_config_templates', '2016-02-04 22:52:21'),
(36, 'finished', 'apirt.synapsys.us-weather', '', '20160201-1121', 388, '2016-02-01 17:21:46', '2016-02-01 17:25:10', 1, 'tsung_config_templates', '2016-02-04 22:52:21'),
(37, 'finished', 'apirt.synapsys.us-weather', '', '20160201-1129', 388, '2016-02-01 17:29:47', '2016-02-01 17:33:11', 1, 'tsung_config_templates', '2016-02-04 22:52:21'),
(38, 'finished', 'apirt.synapsys.us-weather', '', '20160201-1147', 388, '2016-02-01 17:47:26', '2016-02-01 17:50:56', 1, 'tsung_config_templates', '2016-02-04 22:52:21'),
(40, 'finished', 'apirt.synapsys.us-weather', 'Blah blah blah', '20160201-1705', 361, '2016-02-01 23:05:05', '2016-02-01 23:08:27', 1, 'tsung_config_templates', '2016-02-04 22:52:21'),
(41, 'finished', 'apirt.synapsys.us-weather', '', '20160202-0855', 361, '2016-02-02 14:55:51', '2016-02-02 14:59:13', 1, 'tsung_config_templates', '2016-02-04 22:52:21'),
(43, 'finished', 'apirt.synapsys.us-weather', 'A re-run of apirt.synapsys.us-weather at 20160201-1513', '20160202-1243', 378, '2016-02-02 18:43:41', '2016-02-02 18:47:09', 1, 'tsung_config_templates', '2016-02-04 22:52:21'),
(48, 'finished', 'apirt.synapsys.us-weather', 'A re-run of apirt.synapsys.us-weather at 20160202-1243', '20160202-1521', 378, '2016-02-02 21:21:00', '2016-02-02 21:24:21', 1, 'tsung_config_templates', '2016-02-04 22:52:21'),
(51, 'finished', 'apirt.synapsys.us-weather', 'How much CPU does it use?', '20160202-1550', 378, '2016-02-02 21:50:13', '2016-02-02 21:53:43', 1, 'tsung_config_templates', '2016-02-04 22:52:21'),
(67, 'finished', 'apirt.synapsys.us-weather', 'A re-run of apirt.synapsys.us-weather', '20160203-0847', 378, '2016-02-03 14:47:33', '2016-02-03 14:52:07', 1, 'tsung_config_templates', '2016-02-04 22:52:21'),
(69, 'finished', 'apirt.synapsys.us-weather', 'Graphics?', '20160204-1412', 1, '2016-02-04 20:12:39', '2016-02-04 20:12:39', 1, 'tsung_config_templates', '2016-02-04 22:52:21'),
(70, 'finished', 'apirt.synapsys.us-weather', '', '20160205-0850', 1, '2016-02-05 14:50:23', '2016-02-05 14:50:23', 1, 'tsung_config_templates', '2016-02-05 14:50:23'),
(71, 'finished', 'apirt.synapsys.us-weather', 'A re-run of apirt.synapsys.us-weather', '20160205-1018', 1, '2016-02-05 16:18:42', '2016-02-05 16:18:45', 1, 'tsung_config_templates', '2016-02-05 16:18:45'),
(72, 'finished', 'apirt.synapsys.us-weather', '', '20160205-1021', 1, '2016-02-05 16:21:06', '2016-02-05 16:21:09', 1, 'tsung_config_templates', '2016-02-05 16:21:09'),
(73, 'finished', 'apirt.synapsys.us-weather', 'Blah', '20160205-1058', 12877, '2016-02-05 16:58:12', '2016-02-05 17:01:32', 1, 'tsung_config_templates', '2016-02-05 17:01:32');

-- --------------------------------------------------------

--
-- Table structure for table `tsung_thinktime`
--

CREATE TABLE `tsung_thinktime` (
  `id` int(11) NOT NULL,
  `value` varchar(100) DEFAULT NULL,
  `min` int(11) DEFAULT NULL,
  `max` int(11) DEFAULT NULL,
  `random` tinyint(1) DEFAULT NULL,
  `session_order` tinyint(3) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `parent_id` int(11) NOT NULL,
  `parent_table` enum('tsung_session') NOT NULL DEFAULT 'tsung_session'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tsung_user`
--

CREATE TABLE `tsung_user` (
  `id` int(11) NOT NULL,
  `session` int(11) NOT NULL,
  `start_time` int(4) NOT NULL,
  `unit` enum('second','minute') NOT NULL,
  `parent_id` int(11) NOT NULL,
  `parent_table` enum('tsung_load') NOT NULL DEFAULT 'tsung_load',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tsung_users`
--

CREATE TABLE `tsung_users` (
  `id` int(11) NOT NULL,
  `maxnumber` int(11) DEFAULT NULL,
  `interarrival` int(11) DEFAULT NULL,
  `arrivalrate` int(11) DEFAULT NULL,
  `unit` enum('second','minute') NOT NULL,
  `parent_id` int(11) NOT NULL,
  `parent_table` enum('tsung_arrivalphase') NOT NULL DEFAULT 'tsung_arrivalphase',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tsung_users`
--

INSERT INTO `tsung_users` (`id`, `maxnumber`, `interarrival`, `arrivalrate`, `unit`, `parent_id`, `parent_table`, `timestamp`) VALUES
(1, 100, 4, NULL, 'second', 1, 'tsung_arrivalphase', '2016-02-04 17:32:17'),
(2, 100, 1, NULL, 'second', 2, 'tsung_arrivalphase', '2016-02-04 17:32:17'),
(3, 100, NULL, 10, 'second', 3, 'tsung_arrivalphase', '2016-02-04 17:33:08');

-- --------------------------------------------------------

--
-- Table structure for table `tsung_user_agents`
--

CREATE TABLE `tsung_user_agents` (
  `id` int(11) NOT NULL,
  `probability` int(3) DEFAULT NULL,
  `user_agent` varchar(255) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `parent_table` enum('tsung_option') NOT NULL DEFAULT 'tsung_option',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tsung_user_agents`
--

INSERT INTO `tsung_user_agents` (`id`, `probability`, `user_agent`, `parent_id`, `parent_table`, `timestamp`) VALUES
(1, 80, 'Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.7.8) Gecko/20050513 Galeon/1.3.21', 1, 'tsung_option', '2016-02-04 17:47:39'),
(2, 20, 'Mozilla/5.0 (Windows; U; Windows NT 5.2; fr-FR; rv:1.7.8) Gecko/20050511 Firefox/1.0.4', 1, 'tsung_option', '2016-02-04 17:47:39');

-- --------------------------------------------------------

--
-- Table structure for table `tsung_websocket`
--

CREATE TABLE `tsung_websocket` (
  `id` int(11) NOT NULL,
  `type` enum('connect','message','close') DEFAULT NULL,
  `path` varchar(255) DEFAULT NULL,
  `frame` enum('binary','text') DEFAULT NULL,
  `contents` varchar(255) DEFAULT NULL,
  `ack` varchar(10) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `parent_id` int(11) NOT NULL,
  `parent_table` enum('tsung_request') NOT NULL DEFAULT 'tsung_request'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tsung_www_authenticate`
--

CREATE TABLE `tsung_www_authenticate` (
  `id` int(11) NOT NULL,
  `userid` varchar(60) NOT NULL,
  `passwd` varchar(60) NOT NULL,
  `type` varchar(10) DEFAULT NULL,
  `nonce` varchar(20) DEFAULT NULL,
  `realm` varchar(20) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `parent_id` int(11) NOT NULL,
  `parent_table` enum('tsung_http') NOT NULL DEFAULT 'tsung_http'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tsung_add_cookie`
--
ALTER TABLE `tsung_add_cookie`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tsung_arrivalphase`
--
ALTER TABLE `tsung_arrivalphase`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tsung_client`
--
ALTER TABLE `tsung_client`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tsung_clients`
--
ALTER TABLE `tsung_clients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tsung_client_ip`
--
ALTER TABLE `tsung_client_ip`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tsung_config_templates`
--
ALTER TABLE `tsung_config_templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tsung_dyn_variable`
--
ALTER TABLE `tsung_dyn_variable`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tsung_http`
--
ALTER TABLE `tsung_http`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tsung_http_header`
--
ALTER TABLE `tsung_http_header`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tsung_load`
--
ALTER TABLE `tsung_load`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tsung_match`
--
ALTER TABLE `tsung_match`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tsung_monitors`
--
ALTER TABLE `tsung_monitors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Indexes for table `tsung_monitor_mysqladmin`
--
ALTER TABLE `tsung_monitor_mysqladmin`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Indexes for table `tsung_monitor_snmp`
--
ALTER TABLE `tsung_monitor_snmp`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Indexes for table `tsung_mysql`
--
ALTER TABLE `tsung_mysql`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Indexes for table `tsung_oauth`
--
ALTER TABLE `tsung_oauth`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Indexes for table `tsung_oid`
--
ALTER TABLE `tsung_oid`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Indexes for table `tsung_option`
--
ALTER TABLE `tsung_option`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Indexes for table `tsung_options`
--
ALTER TABLE `tsung_options`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Indexes for table `tsung_request`
--
ALTER TABLE `tsung_request`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Indexes for table `tsung_server`
--
ALTER TABLE `tsung_server`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent_id` (`parent_id`),
  ADD KEY `parent_id_2` (`parent_id`);

--
-- Indexes for table `tsung_servers`
--
ALTER TABLE `tsung_servers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Indexes for table `tsung_session`
--
ALTER TABLE `tsung_session`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Indexes for table `tsung_sessions`
--
ALTER TABLE `tsung_sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Indexes for table `tsung_session_setup`
--
ALTER TABLE `tsung_session_setup`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Indexes for table `tsung_statusinfo`
--
ALTER TABLE `tsung_statusinfo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Indexes for table `tsung_thinktime`
--
ALTER TABLE `tsung_thinktime`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Indexes for table `tsung_user`
--
ALTER TABLE `tsung_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Indexes for table `tsung_users`
--
ALTER TABLE `tsung_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Indexes for table `tsung_user_agents`
--
ALTER TABLE `tsung_user_agents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Indexes for table `tsung_websocket`
--
ALTER TABLE `tsung_websocket`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Indexes for table `tsung_www_authenticate`
--
ALTER TABLE `tsung_www_authenticate`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tsung_add_cookie`
--
ALTER TABLE `tsung_add_cookie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tsung_arrivalphase`
--
ALTER TABLE `tsung_arrivalphase`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `tsung_client`
--
ALTER TABLE `tsung_client`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `tsung_clients`
--
ALTER TABLE `tsung_clients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `tsung_client_ip`
--
ALTER TABLE `tsung_client_ip`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tsung_config_templates`
--
ALTER TABLE `tsung_config_templates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `tsung_dyn_variable`
--
ALTER TABLE `tsung_dyn_variable`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tsung_http`
--
ALTER TABLE `tsung_http`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `tsung_http_header`
--
ALTER TABLE `tsung_http_header`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tsung_load`
--
ALTER TABLE `tsung_load`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `tsung_match`
--
ALTER TABLE `tsung_match`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tsung_monitors`
--
ALTER TABLE `tsung_monitors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tsung_monitor_mysqladmin`
--
ALTER TABLE `tsung_monitor_mysqladmin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tsung_monitor_snmp`
--
ALTER TABLE `tsung_monitor_snmp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tsung_mysql`
--
ALTER TABLE `tsung_mysql`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tsung_oauth`
--
ALTER TABLE `tsung_oauth`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tsung_oid`
--
ALTER TABLE `tsung_oid`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tsung_option`
--
ALTER TABLE `tsung_option`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `tsung_options`
--
ALTER TABLE `tsung_options`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `tsung_request`
--
ALTER TABLE `tsung_request`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `tsung_server`
--
ALTER TABLE `tsung_server`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `tsung_servers`
--
ALTER TABLE `tsung_servers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `tsung_session`
--
ALTER TABLE `tsung_session`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `tsung_sessions`
--
ALTER TABLE `tsung_sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `tsung_session_setup`
--
ALTER TABLE `tsung_session_setup`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tsung_statusinfo`
--
ALTER TABLE `tsung_statusinfo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;
--
-- AUTO_INCREMENT for table `tsung_thinktime`
--
ALTER TABLE `tsung_thinktime`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tsung_user`
--
ALTER TABLE `tsung_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tsung_users`
--
ALTER TABLE `tsung_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `tsung_user_agents`
--
ALTER TABLE `tsung_user_agents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `tsung_websocket`
--
ALTER TABLE `tsung_websocket`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tsung_www_authenticate`
--
ALTER TABLE `tsung_www_authenticate`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `tsung_servers`
--
ALTER TABLE `tsung_servers`
  ADD CONSTRAINT `tsung_servers_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `tsung_config_templates` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
