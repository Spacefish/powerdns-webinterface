CREATE TABLE `perm` (
  `userid` int(11) NOT NULL,
  `domain_id` int(11) NOT NULL,
  PRIMARY KEY  (`userid`,`domain_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `session` (
  `sid` char(32) collate utf8_unicode_ci NOT NULL,
  `data` text collate utf8_unicode_ci NOT NULL,
  `lastchange` datetime NOT NULL,
  PRIMARY KEY  (`sid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `template_newrecord` (
  `key` varchar(255) collate utf8_unicode_ci NOT NULL,
  `value` text collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `template_newrecord` VALUES('ttl', '86400');
INSERT INTO `template_newrecord` VALUES('name', '[DOMAIN]');
INSERT INTO `template_newrecord` VALUES('prio', '0');

CREATE TABLE `template_newrecord_domain` (
  `domain_id` int(11) NOT NULL,
  `key` varchar(255) collate utf8_unicode_ci NOT NULL,
  `value` text collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`domain_id`,`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `template_records_newdomain` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) collate utf8_unicode_ci NOT NULL,
  `type` varchar(255) collate utf8_unicode_ci NOT NULL,
  `content` text collate utf8_unicode_ci NOT NULL,
  `ttl` int(11) NOT NULL,
  `prio` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

CREATE TABLE `user` (
  `id` int(11) NOT NULL auto_increment,
  `username` varchar(255) collate utf8_unicode_ci NOT NULL,
  `password` char(32) collate utf8_unicode_ci NOT NULL,
  `isAdmin` tinyint(4) NOT NULL,
  `last_login` datetime NOT NULL,
  `last_ip` char(15) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=10 ;

INSERT INTO `user` VALUES(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 1, '0000-00-00 00:00:00', '');

CREATE TABLE `actionlog` (
  `id` int(11) NOT NULL auto_increment,
  `date` datetime NOT NULL,
  `username` varchar(200) collate utf8_unicode_ci NOT NULL,
  `facility` text collate utf8_unicode_ci NOT NULL,
  `msg` text collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

ALTER TABLE  `supermasters` ADD  `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST ;
