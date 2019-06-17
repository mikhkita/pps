-- phpMyAdmin SQL Dump
-- version 4.3.0
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Янв 15 2018 г., 19:57
-- Версия сервера: 5.6.22
-- Версия PHP: 5.5.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `tlt`
--

-- --------------------------------------------------------

--
-- Структура таблицы `board`
--

CREATE TABLE IF NOT EXISTS `board` (
`id` int(10) unsigned NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `plant_id` tinyint(3) unsigned NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `board`
--

INSERT INTO `board` (`id`, `date`, `plant_id`) VALUES
(1, '2017-12-03 17:00:00', 2),
(10, '2017-12-03 17:00:00', 1),
(11, '2017-12-01 17:00:00', 1),
(12, '2017-12-03 17:00:00', 2),
(13, '2017-12-03 17:00:00', 2),
(14, '2017-12-03 17:00:00', 1),
(15, '2018-01-07 17:00:00', 1),
(16, '2018-01-07 17:00:00', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `board_item`
--

CREATE TABLE IF NOT EXISTS `board_item` (
`id` int(10) unsigned NOT NULL,
  `board_id` int(10) unsigned NOT NULL,
  `thickness` float NOT NULL,
  `width` float NOT NULL,
  `length` float NOT NULL,
  `count` float NOT NULL,
  `price` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `board_item`
--

INSERT INTO `board_item` (`id`, `board_id`, `thickness`, `width`, `length`, `count`, `price`) VALUES
(9, 13, 2.3, 123.213, 2, 2, NULL),
(23, 12, 2.2, 2.2, 2.2, 1.1, NULL),
(24, 12, 1, 1, 1, 50, NULL),
(25, 12, 2, 2.123, 0.23, 60, NULL),
(26, 12, 3, 0.1, 3, 70, NULL),
(27, 12, 4, 4, 4, 4, NULL),
(28, 1, 2.2, 0.26, 2.6, 63, NULL),
(29, 1, 2, 0.15, 3.5, 40, NULL),
(30, 1, 1.5, 0.5, 5, 20, NULL),
(31, 1, 3.7, 0.3, 4, 50, NULL),
(32, 10, 2.4, 0.21, 2.5, 50, NULL),
(33, 10, 2.1, 0.5, 5, 30, NULL),
(34, 10, 1.8, 0.3, 3, 40, NULL),
(39, 14, 1.8, 0.4, 4, 67, NULL),
(40, 14, 1.1, 0.3, 3.78, 87, NULL),
(44, 11, 2.3, 2.1, 1, 89, NULL),
(45, 11, 2.1, 0.3, 3, 12, NULL),
(46, 11, 4.3, 1, 2, 30, NULL),
(60, 16, 1, 2, 3, 5, 1800),
(61, 16, 2, 1.5, 2.3, 10, 2300),
(62, 15, 12, 2, 2.3, 10, 320),
(63, 15, 2.1, 6, 3, 20, 1900);

-- --------------------------------------------------------

--
-- Структура таблицы `branch`
--

CREATE TABLE IF NOT EXISTS `branch` (
`id` tinyint(3) unsigned NOT NULL,
  `name` varchar(64) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `branch`
--

INSERT INTO `branch` (`id`, `name`) VALUES
(1, 'Томск/Клещиха'),
(2, 'Красноярск'),
(3, 'Базаиха'),
(4, 'Тальцы');

-- --------------------------------------------------------

--
-- Структура таблицы `cargo_type`
--

CREATE TABLE IF NOT EXISTS `cargo_type` (
`id` smallint(5) unsigned NOT NULL,
  `name` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `carrier`
--

CREATE TABLE IF NOT EXISTS `carrier` (
`id` smallint(5) unsigned NOT NULL,
  `name` varchar(64) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `carrier`
--

INSERT INTO `carrier` (`id`, `name`) VALUES
(2, 'ЛФ'),
(3, 'СК-Транс'),
(4, 'Магистраль');

-- --------------------------------------------------------

--
-- Структура таблицы `cash`
--

CREATE TABLE IF NOT EXISTS `cash` (
`id` int(10) unsigned NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `type_id` tinyint(3) unsigned NOT NULL,
  `reason` varchar(512) DEFAULT NULL,
  `sum` int(11) NOT NULL,
  `comment` varchar(512) DEFAULT NULL,
  `cheque` tinyint(1) NOT NULL DEFAULT '0',
  `negative` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `cash`
--

INSERT INTO `cash` (`id`, `date`, `type_id`, `reason`, `sum`, `comment`, `cheque`, `negative`) VALUES
(1, '2017-11-25 17:00:00', 2, 'Продали доски', 12000, '5 пачек', 0, 0),
(2, '2017-11-25 17:00:00', 1, 'Дали в долг', 200000, 'Должнику', 0, 1),
(5, '2017-11-24 17:00:00', 1, 'Продали доски', 12300, '', 0, 0),
(6, '2017-11-25 17:00:00', 1, 'Зарплата', 50000, 'всем', 0, 1),
(7, '2017-11-16 17:00:00', 1, 'Ван', 60400, '', 0, 0),
(8, '2017-11-24 17:00:00', 1, 'Продали доски', 23000, '', 0, 0),
(12, '2017-11-27 17:00:00', 2, 'Бензин', 120000, 'Бензин', 0, 1),
(13, '2017-11-24 17:00:00', 1, 'Зарплата', 2000, 'Себе', 0, 1),
(14, '2017-12-11 17:00:00', 1, 'Ван', 123000, 'Новый платеж', 0, 0),
(15, '2017-11-30 17:00:00', 1, 'Дали в долг', 20000, 'Виталию', 0, 1),
(16, '2017-12-19 17:00:00', 1, 'Дали в долг', 123123, 'фыв', 0, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `cash_type`
--

CREATE TABLE IF NOT EXISTS `cash_type` (
`id` tinyint(3) unsigned NOT NULL,
  `name` varchar(64) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `cash_type`
--

INSERT INTO `cash_type` (`id`, `name`) VALUES
(1, 'Ван'),
(2, 'Финансы');

-- --------------------------------------------------------

--
-- Структура таблицы `consignee`
--

CREATE TABLE IF NOT EXISTS `consignee` (
`id` smallint(5) unsigned NOT NULL,
  `name` varchar(64) NOT NULL,
  `email` varchar(64) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `consignee`
--

INSERT INTO `consignee` (`id`, `name`, `email`) VALUES
(3, 'Тестовый грузополучатель', ''),
(4, 'Сергей', '');

-- --------------------------------------------------------

--
-- Структура таблицы `container`
--

CREATE TABLE IF NOT EXISTS `container` (
`id` int(10) unsigned NOT NULL,
  `exporter_group_id` tinyint(4) unsigned NOT NULL,
  `exporter_id` smallint(6) unsigned DEFAULT NULL,
  `station_id` tinyint(4) unsigned NOT NULL,
  `way_id` smallint(6) unsigned NOT NULL,
  `destination_id` smallint(6) unsigned NOT NULL,
  `number` varchar(15) NOT NULL,
  `owner_id` smallint(6) unsigned DEFAULT NULL,
  `stamp_type_id` tinyint(4) unsigned DEFAULT NULL,
  `stamp_num` varchar(15) DEFAULT NULL,
  `loading_date` timestamp NULL DEFAULT NULL,
  `loading_place_id` smallint(6) unsigned DEFAULT NULL,
  `carrier_id` smallint(6) unsigned DEFAULT NULL,
  `weight` int(11) unsigned DEFAULT NULL,
  `dt` varchar(30) DEFAULT NULL,
  `shipment_num` varchar(15) DEFAULT NULL,
  `railway_num` varchar(15) DEFAULT NULL,
  `issue_date` timestamp NULL DEFAULT NULL,
  `consignee_id` smallint(6) unsigned DEFAULT NULL,
  `border_date` timestamp NULL DEFAULT NULL,
  `arrival_date` timestamp NULL DEFAULT NULL,
  `container_date` timestamp NULL DEFAULT NULL,
  `container_place` varchar(5000) DEFAULT NULL,
  `kc` varchar(30) DEFAULT NULL,
  `st` varchar(30) DEFAULT NULL,
  `dhl_st` varchar(30) DEFAULT NULL,
  `dhl_fit` varchar(30) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `container`
--

INSERT INTO `container` (`id`, `exporter_group_id`, `exporter_id`, `station_id`, `way_id`, `destination_id`, `number`, `owner_id`, `stamp_type_id`, `stamp_num`, `loading_date`, `loading_place_id`, `carrier_id`, `weight`, `dt`, `shipment_num`, `railway_num`, `issue_date`, `consignee_id`, `border_date`, `arrival_date`, `container_date`, `container_place`, `kc`, `st`, `dhl_st`, `dhl_fit`) VALUES
(1, 2, 4, 1, 2, 2, 'GLLU9139618', 4, 3, '', '2017-12-07 17:00:00', 2, 4, 26000, '10611020/120117/0000438', '2207657', '51233940', '2017-12-05 17:00:00', 3, '2018-03-13 17:00:00', '2017-12-12 17:00:00', '2017-12-19 17:00:00', 'Тут может быть написано хоть что', '', '', '', ''),
(2, 1, 1, 1, 2, 2, 'G8127312F70', 3, 3, '', '2017-12-01 17:00:00', 2, 4, 0, '', '', '', '2017-12-13 17:00:00', NULL, NULL, NULL, NULL, '', '', '', '', ''),
(3, 6, 8, 3, 2, 2, 'ASD312312312', NULL, NULL, '', NULL, NULL, NULL, 0, '', '', '', NULL, NULL, NULL, NULL, NULL, '', '', '', '', '');

-- --------------------------------------------------------

--
-- Структура таблицы `destination`
--

CREATE TABLE IF NOT EXISTS `destination` (
`id` smallint(5) unsigned NOT NULL,
  `name` varchar(64) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `destination`
--

INSERT INTO `destination` (`id`, `name`) VALUES
(2, 'Маньчжурия');

-- --------------------------------------------------------

--
-- Структура таблицы `dryer`
--

CREATE TABLE IF NOT EXISTS `dryer` (
`id` int(10) unsigned NOT NULL,
  `number` varchar(128) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `dryer`
--

INSERT INTO `dryer` (`id`, `number`) VALUES
(1, '1'),
(2, '2'),
(3, '3'),
(4, '4'),
(5, '5'),
(6, '6'),
(7, '7'),
(8, '8'),
(9, '9'),
(10, '10'),
(11, '11');

-- --------------------------------------------------------

--
-- Структура таблицы `dryer_queue`
--

CREATE TABLE IF NOT EXISTS `dryer_queue` (
`id` int(10) unsigned NOT NULL,
  `dryer_id` int(10) unsigned NOT NULL,
  `start_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `size` varchar(256) NOT NULL,
  `cubage` varchar(512) NOT NULL,
  `packs` varchar(128) NOT NULL,
  `rows` varchar(128) NOT NULL,
  `comment` varchar(1024) DEFAULT NULL,
  `complete_date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `dryer_queue`
--

INSERT INTO `dryer_queue` (`id`, `dryer_id`, `start_date`, `size`, `cubage`, `packs`, `rows`, `comment`, `complete_date`) VALUES
(1, 1, '2017-11-14 17:00:00', '3213123', '312313', '3123123', '3123123123', '', '2017-11-13 17:00:00'),
(3, 1, '2017-11-13 17:00:00', '123', '3123', '3123', '3123123', 'f df dsf sdaf sdaf asdfsdfsdfsdfs dfsd fsadf', '2017-11-13 17:00:00'),
(5, 1, '2017-11-13 17:00:00', 'asda sd', 'dask dl;as;ld', 'kl;e kal;sk d;lak ldk', 'l; dkalsk ds dka', 'f asddasdasd', '2017-11-13 17:00:00'),
(6, 3, '2014-11-20 10:00:00', 'dsam m', ',m d,asm, d;lsa ;l', 'k;ldksal; k;l', 'k;l ksa;lk l;k', 'l; dkl;aks dsd', '2017-11-13 17:00:00'),
(7, 1, '2017-11-13 17:00:00', 'asd', 'das', 'das', 'das as', 'dasdasd', '2017-11-14 17:00:00'),
(9, 2, '2017-11-13 17:00:00', '43', '443', '4', '34', '34', '2017-11-13 17:00:00'),
(10, 1, '2017-11-14 17:00:00', 'фыв', '2.3', '24', '2, 4, 5', '', NULL),
(11, 3, '2017-12-04 17:00:00', '5', '22', '10', '2, 21 12', '', '2017-12-04 17:00:00');

-- --------------------------------------------------------

--
-- Структура таблицы `exporter`
--

CREATE TABLE IF NOT EXISTS `exporter` (
`id` smallint(5) unsigned NOT NULL,
  `group_id` tinyint(3) unsigned NOT NULL,
  `name` varchar(64) NOT NULL,
  `email` varchar(64) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `exporter`
--

INSERT INTO `exporter` (`id`, `group_id`, `name`, `email`) VALUES
(1, 1, 'ЛесКом', ''),
(2, 2, 'ТЛТ', ''),
(3, 2, 'ТЛП', ''),
(4, 2, 'ТЛИ', ''),
(5, 3, 'Вэйда', ''),
(6, 4, 'ЛяньМэн', ''),
(7, 5, 'ДаЧжун', ''),
(8, 6, 'ПромЛесЭкспорт', '');

-- --------------------------------------------------------

--
-- Структура таблицы `exporter_group`
--

CREATE TABLE IF NOT EXISTS `exporter_group` (
`id` tinyint(3) unsigned NOT NULL,
  `name` varchar(64) NOT NULL,
  `sort` smallint(6) NOT NULL DEFAULT '500'
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `exporter_group`
--

INSERT INTO `exporter_group` (`id`, `name`, `sort`) VALUES
(1, 'ЛесКом', 100),
(2, 'ТЛТ/ТЛП/ТЛИ', 200),
(3, 'Вэйда', 300),
(4, 'ЛяньМэн', 500),
(5, 'ДаЧжун', 500),
(6, 'ПромЛесЭкспорт', 500);

-- --------------------------------------------------------

--
-- Структура таблицы `exporter_group_branch`
--

CREATE TABLE IF NOT EXISTS `exporter_group_branch` (
  `branch_id` tinyint(3) unsigned NOT NULL,
  `exporter_group_id` tinyint(3) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `exporter_group_branch`
--

INSERT INTO `exporter_group_branch` (`branch_id`, `exporter_group_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(1, 5),
(1, 7),
(3, 6);

-- --------------------------------------------------------

--
-- Структура таблицы `incoming`
--

CREATE TABLE IF NOT EXISTS `incoming` (
`id` int(10) unsigned NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `car` varchar(16) NOT NULL,
  `cargo` varchar(64) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `incoming`
--

INSERT INTO `incoming` (`id`, `date`, `car`, `cargo`) VALUES
(1, '2017-12-01 17:00:00', 'н556во', 'Бензин'),
(2, '2017-11-30 17:00:00', 'н556во', 'Бензин'),
(3, '2017-12-01 17:00:00', 'а774ух', 'Вывоз мусора'),
(4, '2017-12-03 17:00:00', 'н556во', 'Вывоз мусора'),
(5, '2017-12-04 17:00:00', 'н556во', 'Бензин');

-- --------------------------------------------------------

--
-- Структура таблицы `loading_place`
--

CREATE TABLE IF NOT EXISTS `loading_place` (
`id` smallint(5) unsigned NOT NULL,
  `name` varchar(64) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `loading_place`
--

INSERT INTO `loading_place` (`id`, `name`) VALUES
(2, 'Советская, 2'),
(3, 'Ленинская, 1Б'),
(4, 'Колывань');

-- --------------------------------------------------------

--
-- Структура таблицы `location`
--

CREATE TABLE IF NOT EXISTS `location` (
`id` int(10) unsigned NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `name` varchar(128) NOT NULL,
  `container_id` int(10) unsigned NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `location`
--

INSERT INTO `location` (`id`, `date`, `name`, `container_id`) VALUES
(2, '2017-12-04 09:42:00', 'Киселевск', 1),
(3, '2017-12-04 09:45:15', 'Томск, пр. Ленина 186', 2),
(4, '2017-12-04 09:45:30', 'Кемерово, площадь Пушкина', 2),
(5, '2017-12-04 09:50:13', 'Томск', 3),
(6, '2017-12-04 09:50:32', 'Междуреченск', 3),
(7, '2017-12-05 05:14:50', 'Томск, пр. Ленина 190', 2);

-- --------------------------------------------------------

--
-- Структура таблицы `model_names`
--

CREATE TABLE IF NOT EXISTS `model_names` (
`id` smallint(5) unsigned NOT NULL,
  `code` varchar(128) NOT NULL,
  `name` varchar(128) NOT NULL,
  `vin_name` varchar(128) NOT NULL,
  `rod_name` varchar(128) NOT NULL,
  `rule` varchar(32) DEFAULT NULL,
  `sort` smallint(6) DEFAULT '9999',
  `parent` smallint(5) unsigned NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `model_names`
--

INSERT INTO `model_names` (`id`, `code`, `name`, `vin_name`, `rod_name`, `rule`, `sort`, `parent`) VALUES
(10, 'user', 'Пользователи', 'Пользователя', 'Пользователя', 'readUser', 9999, 0),
(15, 'dryer', 'Сушилки', 'Сушилку', 'Сушилки', 'readDryer', 450, 0),
(16, 'cash', 'Платежи', 'Платеж', 'Платежа', 'readCash', 200, 0),
(17, 'wood', 'Отгрузки', 'Отгрузку', 'Отгрузки', 'readWood', 300, 0),
(18, 'data', 'Справочники', 'Справочник', 'Справочника', NULL, 800, 0),
(19, 'woodProvider', 'Поставщики леса', 'Поставщика', 'Поставщика', 'readWood', 100, 18),
(20, 'parabelProvider', 'Поставщики (Парабель)', 'Поставщика', 'Поставщика', 'readParabel', 200, 18),
(21, 'parabel', 'Машины из Парабели', 'Отгрузку', 'Отгрузки', 'readParabel', 500, 0),
(22, 'incoming', 'Входящий транспорт', 'Запись', 'Записи', 'readIncoming', 600, 0),
(23, 'exporterGroup', 'Группы экспортеров', 'Группу', 'Группы', 'readContainer', 300, 38),
(24, 'exporter', 'Экспортеры', 'Экспортера', 'Экспортера', 'readContainer', 400, 38),
(25, 'branch', 'Филиалы', 'Филиал', 'Филиала', 'readContainer', 500, 38),
(26, 'station', 'Станции', 'Станцию', 'Станции', 'readContainer', 600, 38),
(27, 'way', 'Маршруты', 'Маршрут', 'Маршрута', 'readContainer', 700, 38),
(28, 'destination', 'Пункты назначения', 'Пункт', 'Пункта', 'readContainer', 800, 38),
(29, 'owner', 'Собственники', 'Собственника', 'Собственника', 'readContainer', 900, 38),
(30, 'stampType', 'Типы пломб', 'Тип', 'Типа', 'readContainer', 1000, 38),
(31, 'loadingPlace', 'Места погрузки', 'Место', 'Места', 'readContainer', 1100, 38),
(32, 'carrier', 'Перевозчики', 'Перевозчика', 'Перевозчика', 'readContainer', 1200, 38),
(33, 'consignee', 'Грузополучатели', 'Грузополучателя', 'Грузополучателя', 'readContainer', 1300, 38),
(34, 'ParabelType', 'Типы груза (Парабель)', 'Тип', 'Типа', 'readParabel', 250, 18),
(35, 'container', 'Контейнеры', 'Контейнер', 'Контейнера', 'readContainer', 50, 0),
(36, 'board', 'Доски Чин', 'Отгрузку', 'Отгрузки', 'readBoard', 400, 0),
(37, 'plant', 'Заводы (Доски Чин)', 'Завод', 'Завода', 'updateBoard', 280, 18),
(38, 'data', 'Справочники (контейнер)', 'Справочник', 'Справочника', NULL, 850, 0),
(39, 'sawmill', 'Пилорамы', 'Пилораму', 'Пилорамы', 'readSaw', 400, 18),
(40, 'plankGroup', 'Группы досок', 'Группу', 'Группы', 'readSaw', 500, 18),
(41, 'plank', 'Типы досок', 'Тип', 'Типа', 'readSaw', 600, 18),
(42, 'worker', 'Рабочие', 'Рабочего', 'Рабочего', 'readSaw', 700, 0),
(43, 'saw', 'Работа на пилораме', 'Рабочий день', 'Рабочего дня', 'readSaw', 650, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `owner`
--

CREATE TABLE IF NOT EXISTS `owner` (
`id` smallint(5) unsigned NOT NULL,
  `name` varchar(64) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `owner`
--

INSERT INTO `owner` (`id`, `name`) VALUES
(2, 'РИС'),
(3, 'Tez Zhol (КЗХ)'),
(4, 'СкайВэй'),
(5, 'ГКЛ');

-- --------------------------------------------------------

--
-- Структура таблицы `parabel`
--

CREATE TABLE IF NOT EXISTS `parabel` (
`id` int(10) unsigned NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `type_id` tinyint(3) unsigned NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `parabel`
--

INSERT INTO `parabel` (`id`, `date`, `type_id`) VALUES
(1, '2017-12-01 17:00:00', 1),
(2, '2017-12-02 17:00:00', 1),
(3, '2017-12-01 17:00:00', 2),
(4, '2017-12-01 17:00:00', 2),
(5, '2017-12-01 17:00:00', 1),
(6, '2017-12-04 17:00:00', 2),
(7, '2017-11-22 17:00:00', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `parabel_cargo`
--

CREATE TABLE IF NOT EXISTS `parabel_cargo` (
  `parabel_id` int(10) unsigned NOT NULL,
  `provider_id` int(10) unsigned NOT NULL,
  `cubage` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `parabel_cargo`
--

INSERT INTO `parabel_cargo` (`parabel_id`, `provider_id`, `cubage`) VALUES
(1, 1, 1),
(1, 2, 2),
(2, 1, 4),
(2, 2, 2),
(3, 1, 1),
(4, 1, 10),
(4, 2, 123.123),
(5, 1, 23),
(5, 2, 213),
(6, 1, 10),
(6, 2, 21),
(7, 1, 2),
(7, 2, 4);

-- --------------------------------------------------------

--
-- Структура таблицы `parabel_provider`
--

CREATE TABLE IF NOT EXISTS `parabel_provider` (
`id` int(10) unsigned NOT NULL,
  `name` varchar(64) NOT NULL,
  `sort` int(11) NOT NULL DEFAULT '500'
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `parabel_provider`
--

INSERT INTO `parabel_provider` (`id`, `name`, `sort`) VALUES
(1, 'Идеал', 100),
(2, 'Олег', 200);

-- --------------------------------------------------------

--
-- Структура таблицы `parabel_type`
--

CREATE TABLE IF NOT EXISTS `parabel_type` (
`id` tinyint(3) unsigned NOT NULL,
  `name` varchar(64) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `parabel_type`
--

INSERT INTO `parabel_type` (`id`, `name`) VALUES
(1, 'Доски'),
(2, 'Круглый лес');

-- --------------------------------------------------------

--
-- Структура таблицы `payment`
--

CREATE TABLE IF NOT EXISTS `payment` (
`id` tinyint(3) unsigned NOT NULL,
  `name` varchar(32) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `payment`
--

INSERT INTO `payment` (`id`, `name`) VALUES
(1, 'Покупки'),
(2, 'Поставщики');

-- --------------------------------------------------------

--
-- Структура таблицы `plank`
--

CREATE TABLE IF NOT EXISTS `plank` (
`id` smallint(5) unsigned NOT NULL,
  `name` varchar(32) NOT NULL,
  `group_id` tinyint(3) unsigned NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `plank`
--

INSERT INTO `plank` (`id`, `name`, `group_id`) VALUES
(1, '2.2/2', 1),
(2, '2.7/2', 1),
(3, '2.7/2 стандарт', 1),
(4, '3.8/2', 1),
(5, '2.2/2.5', 1),
(6, '2.7 стандарт/2.5', 1),
(7, '2.7 стандарт', 2),
(8, '2.2/2', 2),
(9, 'Брусок', 3),
(10, 'Прокладки', 3),
(11, '2/5 стандарт', 2);

-- --------------------------------------------------------

--
-- Структура таблицы `plank_group`
--

CREATE TABLE IF NOT EXISTS `plank_group` (
`id` tinyint(3) unsigned NOT NULL,
  `name` varchar(32) NOT NULL,
  `price` float NOT NULL,
  `short` varchar(8) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `plank_group`
--

INSERT INTO `plank_group` (`id`, `name`, `price`, `short`) VALUES
(1, 'Белые доски', 20, 'Бел.'),
(2, 'Черные доски', 15, 'Черн.'),
(3, 'Прокладки + брусок', 25, 'П+Б');

-- --------------------------------------------------------

--
-- Структура таблицы `plant`
--

CREATE TABLE IF NOT EXISTS `plant` (
`id` int(10) unsigned NOT NULL,
  `name` varchar(64) NOT NULL,
  `is_price` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `plant`
--

INSERT INTO `plant` (`id`, `name`, `is_price`) VALUES
(1, 'Парабель', 1),
(2, 'Завод', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `role`
--

CREATE TABLE IF NOT EXISTS `role` (
`id` int(10) unsigned NOT NULL,
  `code` varchar(20) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `role`
--

INSERT INTO `role` (`id`, `code`, `name`) VALUES
(1, 'root', 'Создатель'),
(2, 'director', 'Директор'),
(4, 'dryerManager', 'Управляющий сушилками'),
(5, 'parabelManager', 'Ответственный за Парабель'),
(6, 'cashManager', 'Управляющий финансами'),
(7, 'woodManager', 'Управляющий отгрузками'),
(8, 'incomingManager', 'Ответственный за входящий транспорт'),
(9, 'boardManager', 'Ответственный за доски Чин');

-- --------------------------------------------------------

--
-- Структура таблицы `saw`
--

CREATE TABLE IF NOT EXISTS `saw` (
`id` int(10) unsigned NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `sawmill_id` tinyint(3) unsigned NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `saw`
--

INSERT INTO `saw` (`id`, `date`, `sawmill_id`) VALUES
(1, '2017-12-03 17:00:00', 2),
(2, '2017-12-04 17:00:00', 1),
(3, '2017-12-01 17:00:00', 1),
(4, '2017-12-03 17:00:00', 1),
(5, '2017-12-02 17:00:00', 1),
(6, '2017-12-04 17:00:00', 2),
(7, '2017-12-02 17:00:00', 2),
(8, '2017-12-01 17:00:00', 2),
(9, '2017-12-05 17:00:00', 1),
(10, '2017-12-06 17:00:00', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `sawmill`
--

CREATE TABLE IF NOT EXISTS `sawmill` (
`id` tinyint(3) unsigned NOT NULL,
  `name` varchar(64) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `sawmill`
--

INSERT INTO `sawmill` (`id`, `name`) VALUES
(1, 'Ван'),
(2, 'ГАО'),
(3, 'ЧАН');

-- --------------------------------------------------------

--
-- Структура таблицы `saw_plank`
--

CREATE TABLE IF NOT EXISTS `saw_plank` (
  `saw_id` int(10) unsigned NOT NULL,
  `plank_id` tinyint(3) unsigned NOT NULL,
  `cubage` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `saw_plank`
--

INSERT INTO `saw_plank` (`saw_id`, `plank_id`, `cubage`) VALUES
(1, 1, 2),
(1, 2, 3),
(1, 3, 4),
(1, 4, 4.3),
(1, 5, 5.3),
(1, 6, 2.3),
(1, 7, 4.3),
(2, 1, 2),
(2, 2, 4),
(3, 1, 2),
(3, 2, 2),
(3, 7, 5.4),
(3, 8, 10),
(3, 10, 22),
(4, 1, 5),
(4, 2, 4),
(4, 4, 2),
(4, 5, 4),
(4, 6, 3),
(4, 9, 5),
(5, 1, 2),
(5, 2, 1.5),
(5, 4, 2),
(5, 5, 5),
(5, 6, 2),
(5, 7, 3),
(5, 9, 4),
(5, 10, 2),
(6, 1, 2),
(6, 2, 4),
(6, 3, 12),
(6, 4, 3),
(6, 5, 1),
(6, 6, 4),
(6, 7, 4),
(6, 9, 1),
(6, 10, 2),
(7, 1, 3),
(7, 2, 2),
(7, 3, 1),
(7, 4, 2),
(7, 5, 2),
(7, 6, 4),
(7, 7, 3),
(7, 8, 2),
(7, 9, 3),
(7, 10, 5),
(8, 1, 2),
(8, 2, 3),
(8, 3, 2),
(8, 4, 1),
(8, 5, 8),
(8, 6, 2),
(8, 7, 2),
(8, 8, 4),
(8, 9, 1),
(8, 10, 1),
(9, 1, 5),
(9, 2, 3),
(9, 3, 10),
(9, 11, 20),
(10, 2, 6),
(10, 7, 3),
(10, 8, 6);

-- --------------------------------------------------------

--
-- Структура таблицы `saw_worker`
--

CREATE TABLE IF NOT EXISTS `saw_worker` (
  `worker_id` smallint(5) unsigned NOT NULL,
  `saw_id` int(10) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `saw_worker`
--

INSERT INTO `saw_worker` (`worker_id`, `saw_id`) VALUES
(1, 4),
(1, 6),
(1, 7),
(1, 10),
(2, 1),
(2, 2),
(2, 3),
(2, 4),
(2, 6),
(2, 7),
(2, 9),
(3, 1),
(3, 2),
(3, 4),
(3, 6),
(3, 7),
(3, 8),
(4, 1),
(4, 2),
(4, 3),
(4, 4),
(4, 6),
(4, 7),
(4, 8),
(4, 9),
(4, 10),
(5, 1),
(5, 2),
(5, 6),
(5, 7),
(5, 8),
(5, 9),
(6, 6),
(7, 10);

-- --------------------------------------------------------

--
-- Структура таблицы `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
`id` int(10) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `value` text,
  `code` varchar(50) NOT NULL,
  `sort` int(11) NOT NULL DEFAULT '9999'
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `settings`
--

INSERT INTO `settings` (`id`, `name`, `value`, `code`, `sort`) VALUES
(4, 'Активность парсинга', 'on', 'TOGGLE', 9999),
(5, 'Время последнего действия парсинга', '1486321792', 'TIME', 9999);

-- --------------------------------------------------------

--
-- Структура таблицы `stamp_type`
--

CREATE TABLE IF NOT EXISTS `stamp_type` (
`id` smallint(5) unsigned NOT NULL,
  `name` varchar(64) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `stamp_type`
--

INSERT INTO `stamp_type` (`id`, `name`) VALUES
(3, 'Клещ 60 СЦ'),
(4, 'ВОХР');

-- --------------------------------------------------------

--
-- Структура таблицы `station`
--

CREATE TABLE IF NOT EXISTS `station` (
`id` tinyint(3) unsigned NOT NULL,
  `name` varchar(64) NOT NULL,
  `branch_id` tinyint(3) unsigned NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `station`
--

INSERT INTO `station` (`id`, `name`, `branch_id`) VALUES
(1, 'Клещиха', 1),
(2, 'Томск', 1),
(3, 'Базаиха', 3),
(4, 'Иня', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE IF NOT EXISTS `user` (
`id` int(11) NOT NULL,
  `login` varchar(128) NOT NULL,
  `password` varchar(128) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(128) NOT NULL,
  `surname` varchar(100) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `token` varchar(100) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id`, `login`, `password`, `name`, `email`, `surname`, `active`, `token`) VALUES
(1, 'root', '85676905d35fb12da70e8cb8bc8cebb0', 'Михаил', 'beatbox787@gmail.com', 'Китаев', 1, '65d65cc279793a81d4b72294f66138c4'),
(4, 'van', '85676905d35fb12da70e8cb8bc8cebb0', 'Андрей', 'test@test.ru', 'Ван', 1, NULL),
(5, 'lera', '85676905d35fb12da70e8cb8bc8cebb0', 'Лера', 'test@test.ru', 'ТомЛесПром', 1, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `user_branch`
--

CREATE TABLE IF NOT EXISTS `user_branch` (
  `user_id` int(10) unsigned NOT NULL,
  `branch_id` tinyint(3) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `user_role`
--

CREATE TABLE IF NOT EXISTS `user_role` (
  `user_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `user_role`
--

INSERT INTO `user_role` (`user_id`, `role_id`) VALUES
(1, 1),
(4, 2),
(5, 4),
(5, 5),
(5, 6),
(5, 7),
(5, 8);

-- --------------------------------------------------------

--
-- Структура таблицы `user_widget`
--

CREATE TABLE IF NOT EXISTS `user_widget` (
  `user_id` int(10) unsigned NOT NULL,
  `widget_id` smallint(5) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `user_widget`
--

INSERT INTO `user_widget` (`user_id`, `widget_id`) VALUES
(1, 1),
(4, 1),
(5, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `way`
--

CREATE TABLE IF NOT EXISTS `way` (
`id` smallint(5) unsigned NOT NULL,
  `name` varchar(128) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `way`
--

INSERT INTO `way` (`id`, `name`) VALUES
(2, 'Забайкальск-Маньчжурия');

-- --------------------------------------------------------

--
-- Структура таблицы `widget`
--

CREATE TABLE IF NOT EXISTS `widget` (
`id` smallint(5) unsigned NOT NULL,
  `code` varchar(20) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `widget`
--

INSERT INTO `widget` (`id`, `code`, `name`) VALUES
(1, 'widgetCash', 'Итого (финансы)');

-- --------------------------------------------------------

--
-- Структура таблицы `wood`
--

CREATE TABLE IF NOT EXISTS `wood` (
`id` int(10) unsigned NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `provider_id` int(10) unsigned DEFAULT NULL,
  `cubage` float NOT NULL,
  `price` float NOT NULL,
  `payment_id` tinyint(4) NOT NULL DEFAULT '0',
  `car` varchar(10) NOT NULL,
  `who` varchar(128) DEFAULT NULL,
  `paid` tinyint(1) DEFAULT NULL,
  `comment` varchar(256) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `wood`
--

INSERT INTO `wood` (`id`, `date`, `provider_id`, `cubage`, `price`, `payment_id`, `car`, `who`, `paid`, `comment`) VALUES
(1, '2017-11-30 17:00:00', 4, 20.2, 1900, 1, 'В300СА', '', 0, 'Хорошие'),
(2, '2017-11-30 17:00:00', 3, 24, 2300, 1, 'Х322КО', 'Антон Иванович', 0, ''),
(5, '2017-11-30 17:00:00', 5, 17, 2800, 1, 'У880ТК', '', 0, ''),
(6, '2017-11-30 17:00:00', 5, 56, 2500, 2, 'М070ТС', NULL, 1, ''),
(7, '2017-12-01 17:00:00', 5, 19, 2300, 1, 'А077УХ', '', NULL, 'Плохие'),
(8, '2017-12-04 17:00:00', NULL, 12.2, 1000, 1, 'А077УХ', 'Я', NULL, 'Плохие'),
(9, '2017-12-04 17:00:00', 4, 213, 1000, 2, '122', NULL, 1, ''),
(10, '2017-11-22 17:00:00', NULL, 450, 2900, 1, 'у552тп', 'Я', NULL, ''),
(11, '2017-12-23 17:00:00', 5, 12, 2300, 2, 'У880ТК', NULL, 0, 'Хорошие');

-- --------------------------------------------------------

--
-- Структура таблицы `wood_provider`
--

CREATE TABLE IF NOT EXISTS `wood_provider` (
`id` int(10) unsigned NOT NULL,
  `name` varchar(256) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(128) DEFAULT NULL,
  `sort` int(11) NOT NULL DEFAULT '500'
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `wood_provider`
--

INSERT INTO `wood_provider` (`id`, `name`, `phone`, `email`, `sort`) VALUES
(3, 'ООО "Лесоустроитель"', '', '', 100),
(4, 'Глава крестьянского (фермерского) хозяйства Сосняков Владимир Леонидович', '', '', 200),
(5, 'Индивидуальный предприниматель Медведев Павел Васильевич', '', '', 300),
(6, 'Прогресс', '', '', 400),
(7, 'Малентрейд', '', '', 500),
(8, 'ООО Алтай-Форест', '', '', 600),
(9, 'ООО "Сибирь"', '', '', 600);

-- --------------------------------------------------------

--
-- Структура таблицы `worker`
--

CREATE TABLE IF NOT EXISTS `worker` (
`id` smallint(5) unsigned NOT NULL,
  `name` varchar(64) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `worker`
--

INSERT INTO `worker` (`id`, `name`) VALUES
(1, 'Рабочий 1'),
(2, 'Рабочий 2'),
(3, 'Рабочий 3'),
(4, 'Рабочий 4'),
(5, 'Рабочий 5'),
(6, 'Рабочий 6'),
(7, 'Рабочий 7');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `board`
--
ALTER TABLE `board`
 ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `board_item`
--
ALTER TABLE `board_item`
 ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `branch`
--
ALTER TABLE `branch`
 ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `cargo_type`
--
ALTER TABLE `cargo_type`
 ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `carrier`
--
ALTER TABLE `carrier`
 ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `cash`
--
ALTER TABLE `cash`
 ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `cash_type`
--
ALTER TABLE `cash_type`
 ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `consignee`
--
ALTER TABLE `consignee`
 ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `container`
--
ALTER TABLE `container`
 ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `destination`
--
ALTER TABLE `destination`
 ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `dryer`
--
ALTER TABLE `dryer`
 ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `dryer_queue`
--
ALTER TABLE `dryer_queue`
 ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `exporter`
--
ALTER TABLE `exporter`
 ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `exporter_group`
--
ALTER TABLE `exporter_group`
 ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `exporter_group_branch`
--
ALTER TABLE `exporter_group_branch`
 ADD PRIMARY KEY (`branch_id`,`exporter_group_id`);

--
-- Индексы таблицы `incoming`
--
ALTER TABLE `incoming`
 ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `loading_place`
--
ALTER TABLE `loading_place`
 ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `location`
--
ALTER TABLE `location`
 ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `model_names`
--
ALTER TABLE `model_names`
 ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `owner`
--
ALTER TABLE `owner`
 ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `parabel`
--
ALTER TABLE `parabel`
 ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `parabel_cargo`
--
ALTER TABLE `parabel_cargo`
 ADD PRIMARY KEY (`parabel_id`,`provider_id`);

--
-- Индексы таблицы `parabel_provider`
--
ALTER TABLE `parabel_provider`
 ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `parabel_type`
--
ALTER TABLE `parabel_type`
 ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `payment`
--
ALTER TABLE `payment`
 ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `plank`
--
ALTER TABLE `plank`
 ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `plank_group`
--
ALTER TABLE `plank_group`
 ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `plant`
--
ALTER TABLE `plant`
 ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `role`
--
ALTER TABLE `role`
 ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `saw`
--
ALTER TABLE `saw`
 ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `sawmill`
--
ALTER TABLE `sawmill`
 ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `saw_plank`
--
ALTER TABLE `saw_plank`
 ADD PRIMARY KEY (`saw_id`,`plank_id`);

--
-- Индексы таблицы `saw_worker`
--
ALTER TABLE `saw_worker`
 ADD PRIMARY KEY (`worker_id`,`saw_id`);

--
-- Индексы таблицы `settings`
--
ALTER TABLE `settings`
 ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `stamp_type`
--
ALTER TABLE `stamp_type`
 ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `station`
--
ALTER TABLE `station`
 ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `user`
--
ALTER TABLE `user`
 ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `user_branch`
--
ALTER TABLE `user_branch`
 ADD PRIMARY KEY (`user_id`,`branch_id`);

--
-- Индексы таблицы `user_role`
--
ALTER TABLE `user_role`
 ADD PRIMARY KEY (`user_id`,`role_id`);

--
-- Индексы таблицы `user_widget`
--
ALTER TABLE `user_widget`
 ADD PRIMARY KEY (`user_id`,`widget_id`);

--
-- Индексы таблицы `way`
--
ALTER TABLE `way`
 ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `widget`
--
ALTER TABLE `widget`
 ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `wood`
--
ALTER TABLE `wood`
 ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `wood_provider`
--
ALTER TABLE `wood_provider`
 ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `worker`
--
ALTER TABLE `worker`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `board`
--
ALTER TABLE `board`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT для таблицы `board_item`
--
ALTER TABLE `board_item`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=64;
--
-- AUTO_INCREMENT для таблицы `branch`
--
ALTER TABLE `branch`
MODIFY `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT для таблицы `cargo_type`
--
ALTER TABLE `cargo_type`
MODIFY `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `carrier`
--
ALTER TABLE `carrier`
MODIFY `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT для таблицы `cash`
--
ALTER TABLE `cash`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT для таблицы `cash_type`
--
ALTER TABLE `cash_type`
MODIFY `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT для таблицы `consignee`
--
ALTER TABLE `consignee`
MODIFY `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT для таблицы `container`
--
ALTER TABLE `container`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT для таблицы `destination`
--
ALTER TABLE `destination`
MODIFY `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT для таблицы `dryer`
--
ALTER TABLE `dryer`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT для таблицы `dryer_queue`
--
ALTER TABLE `dryer_queue`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT для таблицы `exporter`
--
ALTER TABLE `exporter`
MODIFY `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT для таблицы `exporter_group`
--
ALTER TABLE `exporter_group`
MODIFY `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT для таблицы `incoming`
--
ALTER TABLE `incoming`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT для таблицы `loading_place`
--
ALTER TABLE `loading_place`
MODIFY `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT для таблицы `location`
--
ALTER TABLE `location`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT для таблицы `model_names`
--
ALTER TABLE `model_names`
MODIFY `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=44;
--
-- AUTO_INCREMENT для таблицы `owner`
--
ALTER TABLE `owner`
MODIFY `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT для таблицы `parabel`
--
ALTER TABLE `parabel`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT для таблицы `parabel_provider`
--
ALTER TABLE `parabel_provider`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT для таблицы `parabel_type`
--
ALTER TABLE `parabel_type`
MODIFY `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT для таблицы `payment`
--
ALTER TABLE `payment`
MODIFY `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT для таблицы `plank`
--
ALTER TABLE `plank`
MODIFY `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT для таблицы `plank_group`
--
ALTER TABLE `plank_group`
MODIFY `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT для таблицы `plant`
--
ALTER TABLE `plant`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT для таблицы `role`
--
ALTER TABLE `role`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT для таблицы `saw`
--
ALTER TABLE `saw`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT для таблицы `sawmill`
--
ALTER TABLE `sawmill`
MODIFY `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT для таблицы `settings`
--
ALTER TABLE `settings`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT для таблицы `stamp_type`
--
ALTER TABLE `stamp_type`
MODIFY `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT для таблицы `station`
--
ALTER TABLE `station`
MODIFY `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT для таблицы `user`
--
ALTER TABLE `user`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT для таблицы `way`
--
ALTER TABLE `way`
MODIFY `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT для таблицы `widget`
--
ALTER TABLE `widget`
MODIFY `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT для таблицы `wood`
--
ALTER TABLE `wood`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT для таблицы `wood_provider`
--
ALTER TABLE `wood_provider`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT для таблицы `worker`
--
ALTER TABLE `worker`
MODIFY `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
