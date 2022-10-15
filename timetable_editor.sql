-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 24, 2021 at 04:42 PM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.4.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `timetable_editor`
--

-- --------------------------------------------------------

--
-- Table structure for table `plan`
--

CREATE TABLE `plan` (
  `programme` varchar(100) NOT NULL,
  `semester` int(11) NOT NULL,
  `subject` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `plan`
--

INSERT INTO `plan` (`programme`, `semester`, `subject`) VALUES
('Компютърни науки', 1, 'ДИС I'),
('Компютърни науки', 1, 'Алгебра I'),
('Компютърни науки', 1, 'Увод в програмирането'),
('Компютърни науки', 1, 'Дискретни структури'),
('Компютърни науки', 2, 'Обектно-ориентирано програмиране'),
('Компютърни науки', 2, 'ДИС II'),
('Компютърни науки', 2, 'Езици, автомати и изчислимост'),
('Компютърни науки', 2, 'Геометрия'),
('Софтуерни инженерство', 1, 'ДИС I'),
('Софтуерни инженерство', 1, 'Алгебра I'),
('Софтуерни инженерство', 1, 'Дискретни структури'),
('Софтуерни инженерство', 1, 'Увод в програмирането');

-- --------------------------------------------------------

--
-- Table structure for table `prerequisites`
--

CREATE TABLE `prerequisites` (
  `subject` varchar(100) NOT NULL,
  `prerequisite` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `prerequisites`
--

INSERT INTO `prerequisites` (`subject`, `prerequisite`) VALUES
('Алгебра II', 'Алгебра I'),
('Геометрия', 'Алгебра I'),
('ДИС II', 'ДИС I'),
('Езици, автомати и изчислимост', 'Дискретни структури'),
('Компютърни архитектури', 'Увод в програмирането'),
('Логическо програмиране', 'Функционално програмиране'),
('Обектно-ориентирано програмиране', 'Увод в програмирането'),
('Операционни системи', 'Компютърни архитектури'),
('Системи за паралелна обработка', 'Компютърни архитектури'),
('Системи за паралелна обработка', 'Системно програмиране'),
('Системи за паралелна обработка', 'Структури от данни и програмиране'),
('Системно програмиране', 'Операционни системи'),
('Структури от данни и програмиране', 'Обектно-ориентирано програмиране'),
('Теория на игрите', 'Алгебра II'),
('Теория на игрите', 'ДИС II'),
('Функционално програмиране', 'Увод в програмирането');

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `name` varchar(100) NOT NULL,
  `lecturer` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`name`, `lecturer`) VALUES
('Алгебра I', 'Е. Великова'),
('Алгебра II', 'Е. Великова'),
('Геометрия', 'Г. Енева'),
('ДИС I', 'Г. Александров'),
('ДИС II', 'Г. Александров'),
('Дискретни структури', 'Г. Георгиев'),
('Езици, автомати и изчислимост', 'С. Герджиков'),
('Компютърни архитектури', 'Ю. Цукровски'),
('Логическо програмиране', 'Т. Тинчев'),
('Обектно-ориентирано програмиране', 'Н. Ангелова'),
('Операционни системи', 'Г. Георгиев'),
('Системи за паралелна обработка', 'В. Цунижев'),
('Системно програмиране', 'Г. Георгиев'),
('Структури от данни и програмиране', 'Н. Ангелова'),
('Теория на игрите', 'М. Кръстанов'),
('Увод в програмирането', 'Н. Ангелова'),
('Функционално програмиране', 'М. Нишева');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`username`, `email`, `password`) VALUES
('admin', 'admin@fmi.uni-sofia.bg', '$2y$10$oU6OaMW0BKxCA/LahUhj0ebAJkwHrFmn5zWYrhSbtvyUVhcAlSnJu');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `prerequisites`
--
ALTER TABLE `prerequisites`
  ADD PRIMARY KEY (`subject`,`prerequisite`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`username`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
