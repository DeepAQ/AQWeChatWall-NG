SET NAMES utf8mb4;

CREATE TABLE `wall_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `active` tinyint(4) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL,
  `background` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;


CREATE TABLE `wall_message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wallid` int(11) NOT NULL DEFAULT '1',
  `time` int(11) NOT NULL DEFAULT '-1',
  `ip` varchar(16) NOT NULL DEFAULT '',
  `openid` varchar(64) NOT NULL,
  `content` text NOT NULL,
  `type` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;


CREATE TABLE `wall_user` (
  `openid` varchar(64) NOT NULL,
  `nickname` varchar(255) DEFAULT NULL,
  `sex` int(11) NOT NULL DEFAULT '-1',
  `province` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `headimgurl` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`openid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;
