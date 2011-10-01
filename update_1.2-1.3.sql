ALTER TABLE  `user` ADD  `canCreateDomain` TINYINT NOT NULL AFTER  `isAdmin`;
UPDATE `user` SET canCreateDomain = 1;
ALTER TABLE  `user` CHANGE  `last_login`  `lastLogin` DATETIME NOT NULL;
ALTER TABLE  `user` CHANGE  `last_ip`  `lastIp` CHAR( 15 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;

UPDATE  `webdns`.`template_newrecord` SET  `value` =  '' WHERE  `template_newrecord`.`key` =  'name';

INSERT INTO `template_records_newdomain` (`name`, `type`, `content`, `ttl`, `prio`) VALUES('', 'MX', 'mail.[DOMAIN]', 300, 0);
INSERT INTO `template_records_newdomain` (`name`, `type`, `content`, `ttl`, `prio`) VALUES('mail', 'CNAME', '[DOMAIN]', 300, 0);
INSERT INTO `template_records_newdomain` (`name`, `type`, `content`, `ttl`, `prio`) VALUES('', 'SOA', 'dns.[DOMAIN]. hostmaster.[DOMAIN]. 1 86400 86400 604800 300', 300, 0);
