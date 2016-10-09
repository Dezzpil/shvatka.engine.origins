
SET NAMES utf8;

-- 
-- Структура таблицы `sh_comands`
-- 

DROP TABLE IF EXISTS `sh_comands`;

CREATE TABLE IF NOT EXISTS `sh_comands` (
  `n` int(4) NOT NULL AUTO_INCREMENT,
  `nazvanie` varchar(50)  NOT NULL DEFAULT '',
  `ochki` int(11) NOT NULL DEFAULT '0',
  `cmp_games` text ,
  `uroven` int(11) NOT NULL DEFAULT '0',
  `podskazka` int(11) NOT NULL DEFAULT '0',
  `dt_ur` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `dengi` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`n`)
) ENGINE=MyISAM AUTO_INCREMENT=1;

INSERT INTO `sh_comands` (`nazvanie`, `ochki`) VALUES ('Normandia', '42');

-- --------------------------------------------------------

-- 
-- Структура таблицы `sh_igroki`
-- 

DROP TABLE IF EXISTS `sh_igroki`;

CREATE TABLE IF NOT EXISTS `sh_igroki` (
  `n` int(11) NOT NULL AUTO_INCREMENT,
  `nick` varchar(50)  NOT NULL DEFAULT '',
  `komanda` varchar(50)  NOT NULL DEFAULT '',
  `status_in_cmd` varchar(64) DEFAULT 'Полевой',
  `ochki` int(11) NOT NULL DEFAULT '0',
  `ch_dengi` tinyint(1) NOT NULL DEFAULT '0',
  `games` text ,
  `viwestatus` char(1)  NOT NULL DEFAULT 'n',
  PRIMARY KEY (`n`),
  FULLTEXT KEY `nick` (`nick`)
) ENGINE=MyISAM DEFAULT CHARACTER SET utf8 AUTO_INCREMENT=1;

INSERT INTO `sh_igroki` (`nick`, `komanda`, `status_in_cmd`, `ochki`) VALUES ('Shepard', 'Normandia', 'Капитан', '42');
INSERT INTO `sh_igroki` (`nick`, `komanda`, `status_in_cmd`, `ochki`) VALUES ('Eshli', 'Normandia', 'Радистка', '10');
INSERT INTO `sh_igroki` (`nick`, `komanda`, `status_in_cmd`, `ochki`) VALUES ('Kaiden', 'Normandia', 'Радист', '9');

-- --------------------------------------------------------

-- 
-- Структура таблицы `sh_recrut`
-- 

DROP TABLE IF EXISTS `sh_recrut`;

CREATE TABLE IF NOT EXISTS `sh_recrut` (
  `n` int(11) NOT NULL AUTO_INCREMENT,
  `kto` varchar(50)  NOT NULL DEFAULT '',
  `kuda` int(4) NOT NULL DEFAULT '0',
  `otvet` text ,
  PRIMARY KEY (`n`)
) ENGINE=MyISAM AUTO_INCREMENT=1;


INSERT INTO `sh_recrut` (`kto`, `kuda`) VALUES ('Rex', '1');
INSERT INTO `sh_recrut` (`kto`, `kuda`) VALUES ('Tali', '1');

-- --------------------------------------------------------

-- 
-- Структура таблицы `sh_game`
-- 

DROP TABLE IF EXISTS `sh_game`;

CREATE TABLE IF NOT EXISTS `sh_game` (
  `n` int(11) NOT NULL AUTO_INCREMENT,
  `uroven` int(11) NOT NULL DEFAULT '0',
  `n_podskazki` int(11) NOT NULL DEFAULT '0',
  `p_time` int(11) NOT NULL DEFAULT '0',
  `text` text ,
  `keyw` varchar(100)  DEFAULT NULL,
  `b_keyw` varchar(100)  NOT NULL,
  PRIMARY KEY (`n`)
) ENGINE=MyISAM AUTO_INCREMENT=1;

-- --------------------------------------------------------

-- 
-- Структура таблицы `sh_games`
-- 

DROP TABLE IF EXISTS `sh_games`;

CREATE TABLE IF NOT EXISTS `sh_games` (
  `n` int(11) NOT NULL AUTO_INCREMENT,
  `pedistal` text ,
  `g_name` text ,
  `dt_g` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `status` char(1)  NOT NULL DEFAULT '',
  `fond` varchar(50)  NOT NULL DEFAULT '',
  `scenariy` longtext ,
  `leveltable` text ,
  `logs` longtext ,
  PRIMARY KEY (`n`)
) ENGINE=MyISAM AUTO_INCREMENT=1;

-- --------------------------------------------------------

-- 
-- Структура таблицы `sh_log`
-- 

DROP TABLE IF EXISTS `sh_log`;

CREATE TABLE IF NOT EXISTS `sh_log` (
  `n` int(11) NOT NULL AUTO_INCREMENT,
  `comanda` varchar(50)  NOT NULL DEFAULT '',
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `keytext` varchar(250)  DEFAULT NULL,
  `levdone` tinyint(4) NOT NULL DEFAULT '0',
  `autor` varchar(50)  NOT NULL,
  PRIMARY KEY (`n`)
) ENGINE=MyISAM AUTO_INCREMENT=1;

-- --------------------------------------------------------

-- 
-- Структура таблицы `sh_log_ochkov`
-- 

DROP TABLE IF EXISTS `sh_log_ochkov`;

CREATE TABLE IF NOT EXISTS `sh_log_ochkov` (
  `n` int(11) NOT NULL AUTO_INCREMENT,
  `komu` text ,
  `skolko` smallint(6) NOT NULL DEFAULT '0',
  `kogda` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ktopoctavil` text ,
  `komand` binary(1) DEFAULT '0',
  PRIMARY KEY (`n`)
) ENGINE=MyISAM AUTO_INCREMENT=1;

-- --------------------------------------------------------

-- 
-- Структура таблицы `sh_admin_msg`
-- 

DROP TABLE IF EXISTS `sh_admin_msg`;

CREATE TABLE IF NOT EXISTS `sh_admin_msg` (
  `n` int(11) NOT NULL AUTO_INCREMENT,
  `msg` text NOT NULL,
  `starttime` int(11) DEFAULT '0',
  `endtime` int(11) DEFAULT '0',
  `komand` text,
  `autor` text,
  `hash` varchar(16) NOT NULL DEFAULT '',
  `readed` text,
  PRIMARY KEY (`n`)
) ENGINE=MyISAM AUTO_INCREMENT=1;

-- --------------------------------------------------------

-- 
-- Структура таблицы `groups`
-- 

DROP TABLE IF EXISTS `groups`;

CREATE TABLE IF NOT EXISTS `groups` (
  `g_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `g_title` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`g_id`))
ENGINE = MyISAM;

-- Стандартные группы в IPB 2:
-- 1: �еактивированные
-- 2: Го�ти
-- 3: Пользователи
-- 4: Главные админи�т�
-- 5: Заблокированные
-- 6: �дмини�траторы

INSERT INTO `groups` (`g_title`) VALUES ('Деактивированные');
INSERT INTO `groups` (`g_title`) VALUES ('Гости');
INSERT INTO `groups` (`g_title`) VALUES ('Пользователи');
INSERT INTO `groups` (`g_title`) VALUES ('Главные администраторы');
INSERT INTO `groups` (`g_title`) VALUES ('Заблокированные');
INSERT INTO `groups` (`g_title`) VALUES ('Администраторы');
INSERT INTO `groups` (`g_title`) VALUES ('Normandia');

-- --------------------------------------------------------

-- 
-- Структура таблицы `members`
-- 

DROP TABLE IF EXISTS `members`;

CREATE TABLE IF NOT EXISTS `members` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `mgroup` INT NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = MyISAM;

INSERT INTO `members` (`name`, `mgroup`) VALUES ('Shepard', 7);
INSERT INTO `members` (`name`, `mgroup`) VALUES ('Eshli', 7);
INSERT INTO `members` (`name`, `mgroup`) VALUES ('Kaiden', 7);
INSERT INTO `members` (`name`, `mgroup`) VALUES ('Rex', 3);
INSERT INTO `members` (`name`, `mgroup`) VALUES ('Tali', 3);
INSERT INTO `members` (`name`, `mgroup`) VALUES ('Jacob', '3');

ALTER TABLE `members` 
ADD COLUMN `password` VARCHAR(255) NOT NULL AFTER `mgroup`;

UPDATE `members` SET `password`='f55cf1f2c5d28b43e6770fb56c14a9e5' WHERE `id`='2';
UPDATE `members` SET `password`='f55cf1f2c5d28b43e6770fb56c14a9e5' WHERE `id`='1';
UPDATE `members` SET `password`='f55cf1f2c5d28b43e6770fb56c14a9e5' WHERE `id`='3';
UPDATE `members` SET `password`='f55cf1f2c5d28b43e6770fb56c14a9e5' WHERE `id`='4';
UPDATE `members` SET `password`='f55cf1f2c5d28b43e6770fb56c14a9e5' WHERE `id`='5';
UPDATE `members` SET `password`='f55cf1f2c5d28b43e6770fb56c14a9e5' WHERE `id`='6';

-- --------------------------------------------------------

-- 
-- Структура таблицы `members`
-- 

DROP TABLE IF EXISTS `pfields_content`;

CREATE TABLE `pfields_content` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `field_4` VARCHAR(45) NOT NULL COMMENT 'Поле, определяющее права на управление игрой. Если y - пользователь может загружать/редактировать сценарий игры',
  `member_id` INT NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = MyISAM
COMMENT = 'Таблица, хранящая разнообразные значения для пользователей';

-- --------------------------------------------------------
