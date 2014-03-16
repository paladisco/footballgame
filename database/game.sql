# ************************************************************
# Sequel Pro SQL dump
# Version 4096
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: 127.0.0.1 (MySQL 5.5.27)
# Database: fbgame
# Generation Time: 2013-09-09 15:59:29 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table fb_event
# ------------------------------------------------------------

DROP TABLE IF EXISTS `fb_event`;

CREATE TABLE `fb_event` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `title` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `rating` varchar(11) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `fb_event` WRITE;
/*!40000 ALTER TABLE `fb_event` DISABLE KEYS */;

INSERT INTO `fb_event` (`id`, `type`, `title`, `description`, `rating`)
VALUES
	(1,'player','Injury','###PLAYERNAME### suffers a cruel injury.','bad'),
	(2,'player','Fan','###PLAYERNAME### meets the fans!','good'),
	(3,'player','Drunk','###PLAYERNAME### has been reported to get wasted at the local pub!','bad'),
	(4,'player','Heal','###PLAYERNAME### is being healed.','good');

/*!40000 ALTER TABLE `fb_event` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table fb_event_effect
# ------------------------------------------------------------

DROP TABLE IF EXISTS `fb_event_effect`;

CREATE TABLE `fb_event_effect` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` int(11) DEFAULT NULL,
  `ref_table` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ref_field` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `delta` tinyint(11) DEFAULT NULL,
  `value` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `where` text COLLATE utf8_unicode_ci,
  `fieldname` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `fb_event_effect` WRITE;
/*!40000 ALTER TABLE `fb_event_effect` DISABLE KEYS */;

INSERT INTO `fb_event_effect` (`id`, `event_id`, `ref_table`, `ref_field`, `delta`, `value`, `where`, `fieldname`)
VALUES
	(1,1,'player_has_stat','value',0,'10','stat_id=3','Fitness'),
	(2,2,'player_has_stat','value',1,'5,10','stat_id=4','Popularity'),
	(3,3,'player_has_stat','value',1,'-5,-10','stat_id=3','Fitness'),
	(4,3,'player_has_stat','value',1,'-5,-10','stat_id=4','Popularity'),
	(5,4,'player_has_stat','value',1,'50','stat_id=3','Fitness');

/*!40000 ALTER TABLE `fb_event_effect` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table fb_event_log
# ------------------------------------------------------------

DROP TABLE IF EXISTS `fb_event_log`;

CREATE TABLE `fb_event_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `event_id` int(11) DEFAULT NULL,
  `team_id` int(11) DEFAULT NULL,
  `timestamp` int(11) DEFAULT NULL,
  `logtext` text COLLATE utf8_unicode_ci,
  `pic` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `effects` text COLLATE utf8_unicode_ci,
  `before` text COLLATE utf8_unicode_ci,
  `after` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table fb_player
# ------------------------------------------------------------

DROP TABLE IF EXISTS `fb_player`;

CREATE TABLE `fb_player` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fb_uid` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `position` tinyint(4) DEFAULT NULL,
  `team_id` int(11) DEFAULT NULL,
  `profile_pic` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `kit_no` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


# Dump of table fb_player_has_stat
# ------------------------------------------------------------

DROP TABLE IF EXISTS `fb_player_has_stat`;

CREATE TABLE `fb_player_has_stat` (
  `player_id` int(11) NOT NULL,
  `stat_id` int(11) NOT NULL,
  `value` int(11) DEFAULT NULL,
  PRIMARY KEY (`player_id`,`stat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


# Dump of table fb_player_perk
# ------------------------------------------------------------

DROP TABLE IF EXISTS `fb_player_perk`;

CREATE TABLE `fb_player_perk` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `fb_player_perk` WRITE;
/*!40000 ALTER TABLE `fb_player_perk` DISABLE KEYS */;

INSERT INTO `fb_player_perk` (`id`, `name`, `description`)
VALUES
	(1,'Nutcase','Hitzkopf, der schnell die Nerven verliert'),
	(2,'Crowd Fave','Beliebt bei Fans und Müttern'),
	(3,'Winnertyp','Can decide a match alone'),
	(4,'Injurist','Is injured often and easily');

/*!40000 ALTER TABLE `fb_player_perk` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table fb_player_stat
# ------------------------------------------------------------

DROP TABLE IF EXISTS `fb_player_stat`;

CREATE TABLE `fb_player_stat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` varchar(11) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `fb_player_stat` WRITE;
/*!40000 ALTER TABLE `fb_player_stat` DISABLE KEYS */;

INSERT INTO `fb_player_stat` (`id`, `name`, `description`, `type`)
VALUES
	(1,'Skills','Wie gut der Spieler seine Position beherrscht','primary'),
	(2,'Charisma','Vor allem neben dem Platz wichtig','primary'),
	(3,'Fitness','Ausdauer im Training und auf dem Platz','fitness'),
	(4,'Popularity','Wie kommt der Typ an?','primary');

/*!40000 ALTER TABLE `fb_player_stat` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table fb_team
# ------------------------------------------------------------

DROP TABLE IF EXISTS `fb_team`;

CREATE TABLE `fb_team` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fb_uid` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fb_name` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `founded` int(11) DEFAULT NULL,
  `profile_pic` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cash` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `fb_team` WRITE;
/*!40000 ALTER TABLE `fb_team` DISABLE KEYS */;


/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
