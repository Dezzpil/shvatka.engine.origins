-- phpMyAdmin SQL Dump
-- version 2.6.1
-- http://www.phpmyadmin.net
-- 
-- Хост: localhost
-- Время создания: Авг 10 2016 г., 19:35
-- Версия сервера: 5.1.66
-- Версия PHP: 5.3.3-7+squeeze16
-- 
-- БД: `shvatka`
-- 

-- --------------------------------------------------------

-- 
-- Структура таблицы `ibf_sh_admin_msg`
-- 

CREATE TABLE `ibf_sh_admin_msg` (
  `n` int(11) NOT NULL AUTO_INCREMENT,
  `msg` text COLLATE cp1251_general_cs NOT NULL,
  `starttime` int(11) DEFAULT '0',
  `endtime` int(11) DEFAULT '0',
  `komand` text COLLATE cp1251_general_cs,
  `autor` text COLLATE cp1251_general_cs,
  `hash` varchar(16) COLLATE cp1251_general_cs NOT NULL DEFAULT '',
  `readed` text COLLATE cp1251_general_cs,
  PRIMARY KEY (`n`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=cp1251 COLLATE=cp1251_general_cs PACK_KEYS=0;

-- --------------------------------------------------------

-- 
-- Структура таблицы `ibf_sh_comands`
-- 

CREATE TABLE `ibf_sh_comands` (
  `n` int(4) NOT NULL AUTO_INCREMENT,
  `nazvanie` varchar(50) COLLATE cp1251_general_cs NOT NULL DEFAULT '',
  `ochki` int(11) NOT NULL DEFAULT '0',
  `cmp_games` text COLLATE cp1251_general_cs,
  `uroven` int(11) NOT NULL DEFAULT '0',
  `podskazka` int(11) NOT NULL DEFAULT '0',
  `dt_ur` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `dengi` binary(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`n`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=cp1251 COLLATE=cp1251_general_cs PACK_KEYS=0;

-- --------------------------------------------------------

-- 
-- Структура таблицы `ibf_sh_game`
-- 

CREATE TABLE `ibf_sh_game` (
  `n` int(11) NOT NULL AUTO_INCREMENT,
  `uroven` int(11) NOT NULL DEFAULT '0',
  `n_podskazki` int(11) NOT NULL DEFAULT '0',
  `p_time` int(11) NOT NULL DEFAULT '0',
  `text` text COLLATE cp1251_general_cs,
  `keyw` varchar(100) COLLATE cp1251_general_cs DEFAULT NULL,
  `b_keyw` varchar(100) COLLATE cp1251_general_cs NOT NULL,
  PRIMARY KEY (`n`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=cp1251 COLLATE=cp1251_general_cs PACK_KEYS=0;

-- --------------------------------------------------------

-- 
-- Структура таблицы `ibf_sh_games`
-- 

CREATE TABLE `ibf_sh_games` (
  `n` int(11) NOT NULL AUTO_INCREMENT,
  `pedistal` text COLLATE cp1251_general_cs,
  `g_name` text COLLATE cp1251_general_cs,
  `dt_g` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `status` set('п','т','з') COLLATE cp1251_general_cs NOT NULL DEFAULT '',
  `fond` varchar(50) COLLATE cp1251_general_cs NOT NULL DEFAULT '',
  `scenariy` longtext COLLATE cp1251_general_cs,
  `leveltable` text COLLATE cp1251_general_cs,
  `logs` longtext COLLATE cp1251_general_cs,
  PRIMARY KEY (`n`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=cp1251 COLLATE=cp1251_general_cs PACK_KEYS=0;

-- --------------------------------------------------------

-- 
-- Структура таблицы `ibf_sh_igroki`
-- 

CREATE TABLE `ibf_sh_igroki` (
  `n` int(11) NOT NULL AUTO_INCREMENT,
  `nick` varchar(50) COLLATE cp1251_general_cs NOT NULL DEFAULT '',
  `komanda` varchar(50) COLLATE cp1251_general_cs NOT NULL DEFAULT '',
  `status_in_cmd` set('Капитан','Мозг','Полевой','Бегунок','Радист','Радистка','Властелин фонарика','Водитель','Водила','Герой асфальта','Человек-компас','Экстрасенс','Грузчик','Грузчица','Мобильный мозг','Доктор','Почти СэнСэй','Сапёр','Спонсор','Клоун','Рекрут','Стажер','НаёМник','СэнСэй','СэнСэй клоунов','Резиновый друг','Гутманша','Боцман','Матрос','Юнга','Впередсмотрящий','Навигатор','Квартмейстер','Плотник','Канонир','Судовой врач','Картограф','Пороховая обезьяна','Казначей','Капелан','Мастер парусов','Кок','Атаман','Эльф','Молодец','Лоцман','Штурман','БОГ') COLLATE cp1251_general_cs DEFAULT 'Полевой',
  `ochki` int(11) NOT NULL DEFAULT '0',
  `ch_dengi` binary(1) NOT NULL DEFAULT '0',
  `games` text COLLATE cp1251_general_cs,
  `viwestatus` char(1) COLLATE cp1251_general_cs NOT NULL DEFAULT 'n',
  PRIMARY KEY (`n`),
  FULLTEXT KEY `nick` (`nick`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=cp1251 COLLATE=cp1251_general_cs PACK_KEYS=0;

-- --------------------------------------------------------

-- 
-- Структура таблицы `ibf_sh_log`
-- 

CREATE TABLE `ibf_sh_log` (
  `n` int(11) NOT NULL AUTO_INCREMENT,
  `comanda` varchar(50) COLLATE cp1251_general_cs NOT NULL DEFAULT '',
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `keytext` varchar(250) COLLATE cp1251_general_cs DEFAULT NULL,
  `levdone` tinyint(4) NOT NULL DEFAULT '0',
  `autor` varchar(50) COLLATE cp1251_general_cs NOT NULL,
  PRIMARY KEY (`n`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=cp1251 COLLATE=cp1251_general_cs PACK_KEYS=0;

-- --------------------------------------------------------

-- 
-- Структура таблицы `ibf_sh_log_ochkov`
-- 

CREATE TABLE `ibf_sh_log_ochkov` (
  `n` int(11) NOT NULL AUTO_INCREMENT,
  `komu` text COLLATE cp1251_general_cs,
  `skolko` smallint(6) NOT NULL DEFAULT '0',
  `kogda` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ktopoctavil` text COLLATE cp1251_general_cs,
  `komand` binary(1) DEFAULT '0',
  PRIMARY KEY (`n`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=cp1251 COLLATE=cp1251_general_cs PACK_KEYS=0;

-- --------------------------------------------------------

-- 
-- Структура таблицы `ibf_sh_recrut`
-- 

CREATE TABLE `ibf_sh_recrut` (
  `n` int(11) NOT NULL AUTO_INCREMENT,
  `kto` varchar(50) COLLATE cp1251_general_cs NOT NULL DEFAULT '',
  `kuda` int(4) NOT NULL DEFAULT '0',
  `otvet` text COLLATE cp1251_general_cs,
  PRIMARY KEY (`n`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=cp1251 COLLATE=cp1251_general_cs PACK_KEYS=0;
