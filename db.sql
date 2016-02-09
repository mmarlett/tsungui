CREATE TABLE `statusinfo` (
  `id` int(11) NOT NULL auto_increment,
  `status` enum('waiting','running','canceled','finished') default NULL,
  `template` varchar(255) default NULL,
  `comment` text,
  `starttime` varchar(16) default NULL,
  PRIMARY KEY  (`id`)
)