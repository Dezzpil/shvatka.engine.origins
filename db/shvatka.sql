
-- 
-- Структура таблицы `sh_admin_msg`
-- 

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
-- Структура таблицы `sh_comands`
-- 

CREATE TABLE IF NOT EXISTS `sh_comands` (
  `n` int(4) NOT NULL AUTO_INCREMENT,
  `nazvanie` varchar(50)  NOT NULL DEFAULT '',
  `ochki` int(11) NOT NULL DEFAULT '0',
  `cmp_games` text ,
  `uroven` int(11) NOT NULL DEFAULT '0',
  `podskazka` int(11) NOT NULL DEFAULT '0',
  `dt_ur` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `dengi` binary(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`n`)
) ENGINE=MyISAM AUTO_INCREMENT=1;

-- --------------------------------------------------------

-- 
-- Структура таблицы `sh_game`
-- 

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

CREATE TABLE IF NOT EXISTS `sh_games` (
  `n` int(11) NOT NULL AUTO_INCREMENT,
  `pedistal` text ,
  `g_name` text ,
  `dt_g` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `status` set('п','т','з')  NOT NULL DEFAULT '',
  `fond` varchar(50)  NOT NULL DEFAULT '',
  `scenariy` longtext ,
  `leveltable` text ,
  `logs` longtext ,
  PRIMARY KEY (`n`)
) ENGINE=MyISAM AUTO_INCREMENT=1;

-- --------------------------------------------------------

-- 
-- Структура таблицы `sh_igroki`
-- 

CREATE TABLE IF NOT EXISTS `sh_igroki` (
  `n` int(11) NOT NULL AUTO_INCREMENT,
  `nick` varchar(50)  NOT NULL DEFAULT '',
  `komanda` varchar(50)  NOT NULL DEFAULT '',
  `status_in_cmd` varchar(64) DEFAULT 'Полевой',
  `ochki` int(11) NOT NULL DEFAULT '0',
  `ch_dengi` binary(1) NOT NULL DEFAULT '0',
  `games` text ,
  `viwestatus` char(1)  NOT NULL DEFAULT 'n',
  PRIMARY KEY (`n`),
  FULLTEXT KEY `nick` (`nick`)
) ENGINE=MyISAM AUTO_INCREMENT=1;

-- --------------------------------------------------------

-- 
-- Структура таблицы `sh_log`
-- 

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
-- Структура таблицы `sh_recrut`
-- 

CREATE TABLE IF NOT EXISTS `sh_recrut` (
  `n` int(11) NOT NULL AUTO_INCREMENT,
  `kto` varchar(50)  NOT NULL DEFAULT '',
  `kuda` int(4) NOT NULL DEFAULT '0',
  `otvet` text ,
  PRIMARY KEY (`n`)
) ENGINE=MyISAM AUTO_INCREMENT=1;
