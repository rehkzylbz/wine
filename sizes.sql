
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


-- --------------------------------------------------------

--
-- Структура таблицы `sizes`
--
-- Создание: Ноя 29 2020 г., 12:27
-- Последнее обновление: Ноя 29 2020 г., 12:32
--

DROP TABLE IF EXISTS `sizes`;
CREATE TABLE `sizes` (
  `id` int(10) UNSIGNED NOT NULL COMMENT 'PK',
  `name` varchar(64) NOT NULL DEFAULT '' COMMENT 'size name',
  `w` smallint(5) UNSIGNED NOT NULL DEFAULT '150' COMMENT 'size max width',
  `h` smallint(5) UNSIGNED NOT NULL DEFAULT '150' COMMENT 'size max height',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'size use status'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `sizes`
--

INSERT INTO `sizes` (`id`, `name`, `w`, `h`, `status`) VALUES
(1, 'big', 800, 600, 1),
(2, 'med', 640, 480, 1),
(3, 'min', 320, 240, 1),
(4, 'mic', 150, 150, 1);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `sizes`
--
ALTER TABLE `sizes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `size_status` (`status`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `sizes`
--
ALTER TABLE `sizes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK', AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
