-- phpMyAdmin SQL Dump
-- version 4.0.4.2
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Дек 26 2018 г., 13:11
-- Версия сервера: 5.6.13
-- Версия PHP: 5.4.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `softsale`
--
CREATE DATABASE IF NOT EXISTS `softsale` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `softsale`;

-- --------------------------------------------------------

--
-- Дублирующая структура для представления `catalog_view`
--
CREATE TABLE IF NOT EXISTS `catalog_view` (
`id` int(11) unsigned
,`name` varchar(255)
,`description` text
,`category_id` int(11) unsigned
,`developer_id` int(11) unsigned
,`seller_id` int(11) unsigned
,`price` decimal(15,2) unsigned
,`category_name` varchar(255)
,`developer_name` varchar(255)
,`seller_name` varchar(255)
);
-- --------------------------------------------------------

--
-- Структура таблицы `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Дамп данных таблицы `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Операционные системы'),
(2, 'Офисные системы'),
(3, 'Антивирусное ПО'),
(4, 'СУБД'),
(5, 'Средства разработки'),
(6, 'Прочее');

-- --------------------------------------------------------

--
-- Структура таблицы `clients`
--

CREATE TABLE IF NOT EXISTS `clients` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `is_seller` tinyint(1) NOT NULL,
  `is_purchaser` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Дамп данных таблицы `clients`
--

INSERT INTO `clients` (`id`, `name`, `address`, `phone`, `email`, `is_seller`, `is_purchaser`) VALUES
(1, 'Владелец сервиса', 'Адрес владельца сервиса', '', '', 1, 1),
(2, 'ООО Ваш Софт', 'Адрес ООО Ваш Софт', '312456321456', 'info@usoft.by', 1, 0),
(3, 'Василий Петрович', 'Адрес Петровича', '', '', 0, 1),
(4, 'ЧТУП Соколович', '', '', 'sokolovich@mail.by', 0, 1),
(5, 'Пупкин В.П.', 'Адрес пупкина', '1232465', 'pupkin@gmail.com', 0, 1),
(6, 'Петров Василий Иванович', 'г. Минск, ул. Ленина 999-99', '(999)999-99-99', 'petrov@email.by', 0, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `developers`
--

CREATE TABLE IF NOT EXISTS `developers` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Дамп данных таблицы `developers`
--

INSERT INTO `developers` (`id`, `name`) VALUES
(1, 'Собственное'),
(2, 'Microsoft'),
(3, 'Adobe');

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE IF NOT EXISTS `orders` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `dates` date NOT NULL,
  `purchaser_id` int(11) unsigned NOT NULL,
  `status_id` int(11) unsigned NOT NULL,
  `software_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `summa` decimal(18,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `status_id` (`status_id`),
  KEY `purchaser_id` (`purchaser_id`),
  KEY `software_id` (`software_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Дамп данных таблицы `orders`
--

INSERT INTO `orders` (`id`, `dates`, `purchaser_id`, `status_id`, `software_id`, `quantity`, `price`, `summa`) VALUES
(1, '2018-12-01', 3, 1, 1, 1, '299.99', '299.99'),
(2, '2018-12-21', 1, 2, 1, 1, '299.99', '299.99'),
(3, '2018-12-21', 1, 4, 3, 2, '390.00', '780.00'),
(4, '2018-12-25', 4, 1, 1, 2, '299.99', '599.98'),
(5, '2018-12-25', 4, 1, 3, 1, '390.00', '390.00');

-- --------------------------------------------------------

--
-- Дублирующая структура для представления `order_view`
--
CREATE TABLE IF NOT EXISTS `order_view` (
`id` int(11) unsigned
,`dates` date
,`purchaser_id` int(11) unsigned
,`login` varchar(32)
,`client_id` int(11) unsigned
,`client_name` varchar(255)
,`address` varchar(255)
,`phone` varchar(20)
,`email` varchar(255)
,`status_id` int(11) unsigned
,`status_name` varchar(20)
,`software_id` int(11)
,`software_name` varchar(255)
,`seller_id` int(11) unsigned
,`seller_name` varchar(255)
,`quantity` int(11)
,`price` decimal(15,2)
,`summa` decimal(18,2)
);
-- --------------------------------------------------------

--
-- Структура таблицы `roles`
--

CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(11) unsigned NOT NULL,
  `name` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `roles`
--

INSERT INTO `roles` (`id`, `name`) VALUES
(1, 'Администратор'),
(2, 'Клиент');

-- --------------------------------------------------------

--
-- Структура таблицы `software`
--

CREATE TABLE IF NOT EXISTS `software` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `category_id` int(11) unsigned NOT NULL,
  `developer_id` int(11) unsigned NOT NULL,
  `seller_id` int(11) unsigned NOT NULL,
  `price` decimal(15,2) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  KEY `developer_id` (`developer_id`),
  KEY `seller_id` (`seller_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Дамп данных таблицы `software`
--

INSERT INTO `software` (`id`, `name`, `description`, `category_id`, `developer_id`, `seller_id`, `price`) VALUES
(1, 'Microsoft Windows 10 Home', 'Microsoft Windows 10 — новая операционная система для широкого набора устройств: ПК, серверов, телевизоров, планшетов и смартфонов.', 1, 2, 2, '299.99'),
(2, 'Microsoft Office Home And Business 2016', 'Microsoft Office для дома и бизнеса 2016 — пакет программного обеспечения для упорядочивания работы в домашних задачах и с документами на малых предприятиях. Новая версия офисных продуктов Microsoft включает в себя приложения: Word, Excel, PowerPoint, Outlook и OneNote.', 2, 2, 2, '599.97'),
(3, 'Adobe Dreamweaver CC 2017', 'Adobe Dreamweaver CC — лидирующее программное обеспечение для веб-дизайна и разработки визуальных проектов, создания и редактирования веб-сайтов и мобильных приложений. Интуитивно понятный интерфейс включает множество мощных средств для управления контентом, значительно ускоряя работу.', 5, 3, 2, '390.00');

-- --------------------------------------------------------

--
-- Структура таблицы `statuses`
--

CREATE TABLE IF NOT EXISTS `statuses` (
  `id` int(11) unsigned NOT NULL,
  `name` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `statuses`
--

INSERT INTO `statuses` (`id`, `name`) VALUES
(1, 'Принят'),
(2, 'Исполнен'),
(3, 'Отменен продавцом'),
(4, 'Отменен покупателем');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(32) NOT NULL,
  `password` varchar(32) NOT NULL,
  `role_id` int(11) unsigned NOT NULL,
  `client_id` int(11) unsigned NOT NULL,
  `token` varchar(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `login` (`login`),
  KEY `role_id` (`role_id`),
  KEY `client_id` (`client_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `login`, `password`, `role_id`, `client_id`, `token`) VALUES
(1, 'admin', 'c4ca4238a0b923820dcc509a6f75849b', 1, 1, '21232f297a57a5a743894a0e4a801fc35a4072d415673'),
(2, 'u-soft', 'c4ca4238a0b923820dcc509a6f75849b', 2, 2, '8b91f2c756134da087b761ebdbd1aefa5a407c35e7ec5'),
(3, 'petrovich', 'c4ca4238a0b923820dcc509a6f75849b', 3, 3, ''),
(4, 'petrov', 'c4ca4238a0b923820dcc509a6f75849b', 2, 6, 'f396c3b74762b1fee69b10abb875139b5a407bdacad0b');

-- --------------------------------------------------------

--
-- Дублирующая структура для представления `user_view`
--
CREATE TABLE IF NOT EXISTS `user_view` (
`id` int(11) unsigned
,`login` varchar(32)
,`password` varchar(32)
,`role_id` int(11) unsigned
,`client_id` int(11) unsigned
,`token` varchar(45)
,`client_name` varchar(255)
,`address` varchar(255)
,`phone` varchar(20)
,`email` varchar(255)
,`is_seller` tinyint(1)
,`is_purchaser` tinyint(1)
,`role_name` varchar(20)
);
-- --------------------------------------------------------

--
-- Структура для представления `catalog_view`
--
DROP TABLE IF EXISTS `catalog_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `catalog_view` AS select `software`.`id` AS `id`,`software`.`name` AS `name`,`software`.`description` AS `description`,`software`.`category_id` AS `category_id`,`software`.`developer_id` AS `developer_id`,`software`.`seller_id` AS `seller_id`,`software`.`price` AS `price`,`categories`.`name` AS `category_name`,`developers`.`name` AS `developer_name`,`clients`.`name` AS `seller_name` from (`clients` join (`developers` join (`categories` join `software` on((`categories`.`id` = `software`.`category_id`))) on((`developers`.`id` = `software`.`developer_id`))) on((`clients`.`id` = `software`.`seller_id`)));

-- --------------------------------------------------------

--
-- Структура для представления `order_view`
--
DROP TABLE IF EXISTS `order_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `order_view` AS select `orders`.`id` AS `id`,`orders`.`dates` AS `dates`,`orders`.`purchaser_id` AS `purchaser_id`,`users`.`login` AS `login`,`users`.`client_id` AS `client_id`,`clients`.`name` AS `client_name`,`clients`.`address` AS `address`,`clients`.`phone` AS `phone`,`clients`.`email` AS `email`,`orders`.`status_id` AS `status_id`,`statuses`.`name` AS `status_name`,`orders`.`software_id` AS `software_id`,`software`.`name` AS `software_name`,`software`.`seller_id` AS `seller_id`,`clients_1`.`name` AS `seller_name`,`orders`.`quantity` AS `quantity`,`orders`.`price` AS `price`,`orders`.`summa` AS `summa` from (`clients` `clients_1` join (`statuses` join (`software` join (`clients` join (`users` join `orders` on((`users`.`id` = `orders`.`purchaser_id`))) on((`clients`.`id` = `users`.`client_id`))) on((`software`.`id` = `orders`.`software_id`))) on((`statuses`.`id` = `orders`.`status_id`))) on((`clients_1`.`id` = `software`.`seller_id`)));

-- --------------------------------------------------------

--
-- Структура для представления `user_view`
--
DROP TABLE IF EXISTS `user_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `user_view` AS select `users`.`id` AS `id`,`users`.`login` AS `login`,`users`.`password` AS `password`,`users`.`role_id` AS `role_id`,`users`.`client_id` AS `client_id`,`users`.`token` AS `token`,`clients`.`name` AS `client_name`,`clients`.`address` AS `address`,`clients`.`phone` AS `phone`,`clients`.`email` AS `email`,`clients`.`is_seller` AS `is_seller`,`clients`.`is_purchaser` AS `is_purchaser`,`roles`.`name` AS `role_name` from (`roles` join (`clients` join `users` on((`clients`.`id` = `users`.`client_id`))) on((`roles`.`id` = `users`.`role_id`)));

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
