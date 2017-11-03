--
-- Table structure for table `webchat_lines`
--

CREATE TABLE `webchat_lines` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(40) NOT NULL,
  `message` varchar(255) NOT NULL,
  `ts` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`),
  KEY `ts` (`ts`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `webchat_users`
--

CREATE TABLE `webchat_users` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(16) NOT NULL,
  `password` varchar(32) NOT NULL,
  `email` varchar(64) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
