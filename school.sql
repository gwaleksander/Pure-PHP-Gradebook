-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 02 Gru 2025, 00:23
-- Wersja serwera: 10.4.27-MariaDB
-- Wersja PHP: 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `school`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `app_settings`
--

CREATE TABLE `app_settings` (
  `setting_key` varchar(50) NOT NULL,
  `setting_value` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Zrzut danych tabeli `app_settings`
--

INSERT INTO `app_settings` (`setting_key`, `setting_value`) VALUES
('current_semester', '2');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `bug_reports`
--

CREATE TABLE `bug_reports` (
  `reportID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `status` enum('new','read','resolved') NOT NULL DEFAULT 'new'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Zrzut danych tabeli `bug_reports`
--

INSERT INTO `bug_reports` (`reportID`, `userID`, `content`, `created_at`, `status`) VALUES
(1, 43, 'Zakładka \'Lista Dzieci\' nie działa.', '2025-11-27 13:55:59', 'resolved'),
(3, 4, 'W przeciwieństwie do rozpowszechnionych opinii, Lorem Ipsum nie jest tylko przypadkowym tekstem. Ma ono korzenie w klasycznej łacińskiej literaturze z 45 roku przed Chrystusem, czyli ponad 2000 lat temu! Richard McClintock, wykładowca łaciny na uniwersytecie Hampden-Sydney w Virginii, przyjrzał się uważniej jednemu z najbardziej niejasnych słów w Lorem Ipsum – consectetur – i po wielu poszukiwaniach odnalazł niezaprzeczalne źródło: Lorem Ipsum pochodzi z fragmentów (1.10.32 i 1.10.33) „de Finibus Bonorum et Malorum”, czyli „O granicy dobra i zła”, napisanej właśnie w 45 p.n.e. przez Cycerona. Jest to bardzo popularna w czasach renesansu rozprawa na temat etyki. Pierwszy wiersz Lorem Ipsum, „Lorem ipsum dolor sit amet...” pochodzi właśnie z sekcji 1.10.32.', '2025-11-27 14:17:37', 'read'),
(8, 13, 'Przycisk zmień hasło nie wygląda, żeby był na swoim miejscu.', '2025-11-27 14:30:27', 'resolved'),
(9, 13, 'mój pasek boczny się zepsuł!1!', '2025-11-27 15:42:20', 'new'),
(12, 1, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', '2025-12-01 23:17:49', 'new');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `classes`
--

CREATE TABLE `classes` (
  `classID` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `teacherID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Zrzut danych tabeli `classes`
--

INSERT INTO `classes` (`classID`, `name`, `teacherID`) VALUES
(1, 'Klasa 1A', 1),
(2, 'Klasa 1B', 2),
(3, 'Klasa 2A', 3),
(6, 'Klasa 3A', 4);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `class_subjects_teacher`
--

CREATE TABLE `class_subjects_teacher` (
  `classID` int(11) NOT NULL,
  `subjectID` int(11) NOT NULL,
  `teacherID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Zrzut danych tabeli `class_subjects_teacher`
--

INSERT INTO `class_subjects_teacher` (`classID`, `subjectID`, `teacherID`) VALUES
(1, 1, 1),
(1, 2, 2),
(1, 3, 3),
(1, 4, 4),
(1, 5, 5),
(1, 6, 6),
(1, 7, 7),
(1, 8, 8),
(1, 9, 1),
(1, 10, 10),
(1, 11, 11),
(1, 12, 12),
(2, 1, 1),
(2, 2, 2),
(2, 3, 3),
(2, 4, 1),
(2, 5, 5),
(2, 6, 6),
(2, 7, 7),
(2, 8, 8),
(2, 9, 9),
(2, 10, 10),
(2, 11, 11),
(2, 12, 12),
(3, 1, 1),
(3, 2, 2),
(3, 3, 3),
(3, 4, 4),
(3, 5, 5),
(3, 6, 6),
(3, 7, 7),
(3, 8, 8),
(3, 9, 9),
(3, 10, 10),
(3, 11, 11),
(3, 12, 9),
(6, 4, 3),
(6, 6, 11),
(6, 9, 12),
(6, 13, 4);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `final_grades`
--

CREATE TABLE `final_grades` (
  `finalGradeID` int(11) NOT NULL,
  `studentID` int(11) NOT NULL,
  `subjectID` int(11) NOT NULL,
  `teacherID` int(11) NOT NULL,
  `grade_term1` int(11) DEFAULT NULL,
  `grade_final` int(11) DEFAULT NULL,
  `last_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Zrzut danych tabeli `final_grades`
--

INSERT INTO `final_grades` (`finalGradeID`, `studentID`, `subjectID`, `teacherID`, `grade_term1`, `grade_final`, `last_updated`) VALUES
(1, 15, 9, 1, 4, 1, '2025-12-02 00:01:37'),
(11, 15, 1, 1, NULL, NULL, '2025-11-25 12:10:38'),
(18, 13, 1, 1, 4, NULL, '2025-11-25 15:09:36'),
(19, 22, 9, 1, 3, NULL, '2025-11-25 15:29:23'),
(20, 15, 4, 4, 2, NULL, '2025-11-26 13:07:42'),
(21, 21, 4, 4, 4, NULL, '2025-11-26 13:07:43'),
(22, 22, 4, 4, 5, NULL, '2025-11-26 13:07:45'),
(23, 16, 4, 4, 4, NULL, '2025-11-26 13:07:47'),
(24, 13, 4, 4, 2, NULL, '2025-11-26 13:08:58'),
(25, 14, 4, 4, 5, NULL, '2025-11-26 13:08:14'),
(32, 21, 1, 1, 2, NULL, '2025-11-26 14:14:46'),
(33, 20, 1, 1, 4, NULL, '2025-11-26 14:14:53'),
(34, 19, 1, 1, 3, NULL, '2025-11-26 14:14:53'),
(35, 17, 9, 1, 1, 5, '2025-11-30 21:12:17'),
(36, 21, 9, 1, 4, NULL, '2025-11-26 15:30:42'),
(37, 35, 1, 1, 2, NULL, '2025-11-26 18:56:17'),
(38, 14, 9, 1, 4, NULL, '2025-11-28 17:03:46'),
(39, 18, 9, 1, 1, 4, '2025-12-02 00:01:25'),
(43, 16, 9, 1, 5, NULL, '2025-11-26 19:33:27'),
(44, 13, 9, 1, NULL, NULL, '2025-11-26 19:36:53'),
(48, 29, 1, 1, 4, NULL, '2025-11-26 19:39:27'),
(49, 32, 1, 1, NULL, NULL, '2025-11-26 19:42:50'),
(51, 28, 1, 1, NULL, NULL, '2025-11-26 19:50:09'),
(57, 26, 4, 1, 3, NULL, '2025-11-27 12:06:20'),
(60, 18, 1, 1, NULL, 3, '2025-12-01 21:55:06'),
(63, 14, 1, 1, NULL, 5, '2025-12-02 00:18:11');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `grades`
--

CREATE TABLE `grades` (
  `gradeID` int(11) NOT NULL,
  `studentID` int(11) NOT NULL,
  `subjectID` int(11) NOT NULL,
  `teacherID` int(11) NOT NULL,
  `grade` decimal(3,1) NOT NULL,
  `weight` tinyint(4) NOT NULL DEFAULT 1,
  `categoryID` int(11) NOT NULL,
  `comment` varchar(255) DEFAULT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp(),
  `semester` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Zrzut danych tabeli `grades`
--

INSERT INTO `grades` (`gradeID`, `studentID`, `subjectID`, `teacherID`, `grade`, `weight`, `categoryID`, `comment`, `date`, `semester`) VALUES
(1, 13, 1, 1, '4.0', 3, 1, 'Sprawdzian z algebry', '2025-10-15 10:00:00', 2),
(2, 13, 1, 1, '3.5', 2, 2, 'Kartkówka z geometrii', '2025-09-25 10:05:00', 1),
(3, 13, 2, 2, '3.0', 3, 1, 'Rozprawka', '2025-10-16 11:00:00', 2),
(4, 13, 3, 3, '2.0', 2, 3, 'Odmień być...', '2025-10-10 09:00:00', 1),
(5, 13, 4, 4, '4.0', 2, 2, 'Zadanie z dynamiki', '2025-10-11 13:00:00', 2),
(6, 13, 4, 4, '3.0', 1, 4, 'Praca domowa', '2025-10-01 13:05:00', 1),
(7, 13, 5, 5, '3.0', 3, 1, 'Sprawdzian z budowy atomu', '2025-10-12 14:00:00', 2),
(8, 13, 6, 6, '5.0', 1, 4, 'Starannie wykonana praca domowa', '2025-10-09 08:00:00', 1),
(9, 13, 6, 6, '4.5', 2, 6, 'Projekt o ekosystemach', '2025-10-20 12:00:00', 2),
(10, 13, 7, 7, '4.0', 2, 2, 'Stolice Europy', '2025-10-05 10:00:00', 1),
(11, 13, 8, 8, '5.0', 1, 3, 'Bardzo dobra odpowiedź o II WŚ', '2025-10-06 11:00:00', 2),
(12, 13, 9, 9, '4.5', 3, 1, 'Sprawdzian z arkusza kalkulacyjnego', '2025-10-07 12:00:00', 1),
(13, 13, 10, 10, '4.0', 1, 5, 'Aktywność na lekcji', '2025-10-08 13:00:00', 2),
(14, 13, 11, 11, '5.0', 1, 5, 'Świetne zaangażowanie', '2025-09-30 14:00:00', 1),
(15, 13, 11, 11, '6.0', 1, 5, 'Zaliczenie biegu na 100m', '2025-10-14 15:00:00', 2),
(16, 13, 12, 12, '5.0', 2, 6, 'Kreatywny projekt', '2025-10-02 09:00:00', 1),
(17, 14, 1, 1, '5.0', 3, 1, 'Bardzo dobry wynik', '2025-10-15 10:00:00', 2),
(18, 14, 2, 2, '4.5', 3, 1, NULL, '2025-10-16 11:00:00', 1),
(19, 14, 2, 2, '5.0', 1, 3, 'Wyczerpująca odpowiedź', '2025-09-22 11:00:00', 2),
(20, 14, 3, 3, '4.0', 2, 2, 'Słownictwo do poprawy', '2025-10-10 09:00:00', 1),
(21, 14, 4, 4, '5.0', 3, 1, NULL, '2025-10-11 13:00:00', 2),
(22, 14, 5, 5, '4.5', 2, 2, 'Kartkówka z pierwiastków', '2025-10-12 14:00:00', 1),
(23, 14, 6, 6, '5.0', 1, 4, 'Praca domowa na czas', '2025-10-09 08:00:00', 2),
(24, 14, 7, 7, '5.0', 2, 6, 'Świetna prezentacja o stolicach Europy', '2025-10-21 10:00:00', 1),
(25, 14, 8, 8, '4.0', 1, 3, NULL, '2025-10-06 11:00:00', 2),
(26, 14, 8, 8, '5.0', 3, 1, 'Bardzo dobrze napisany test', '2025-10-20 11:05:00', 1),
(27, 14, 9, 9, '5.0', 2, 6, 'Projekt strony internetowej', '2025-10-07 12:00:00', 2),
(28, 14, 10, 10, '5.0', 1, 5, 'Ciekawa uwaga na lekcji', '2025-10-08 13:00:00', 1),
(29, 14, 11, 11, '4.0', 1, 5, 'Dobra gra w zespole', '2025-10-14 15:00:00', 2),
(30, 14, 12, 12, '6.0', 1, 4, 'Piękna praca plastyczna', '2025-10-02 09:00:00', 1),
(31, 14, 12, 12, '5.0', 2, 6, 'Praca z gliny', '2025-10-18 09:00:00', 2),
(33, 15, 1, 1, '4.5', 2, 2, 'Poprawa kartkówki', '2025-10-01 10:05:00', 2),
(34, 15, 2, 2, '3.5', 2, 2, 'Dyktando', '2025-10-16 11:00:00', 1),
(35, 15, 3, 3, '3.0', 1, 3, 'Należy poćwiczyć wymowę', '2025-10-10 09:00:00', 2),
(36, 15, 4, 4, '2.5', 2, 2, NULL, '2025-10-11 13:00:00', 1),
(37, 15, 5, 5, '3.0', 1, 4, NULL, '2025-10-12 14:00:00', 2),
(38, 15, 6, 6, '4.0', 2, 2, 'Układ krwionośny', '2025-10-09 08:00:00', 1),
(39, 15, 7, 7, '3.5', 2, 2, NULL, '2025-10-21 10:00:00', 2),
(40, 15, 7, 7, '4.0', 1, 4, 'Praca domowa', '2025-10-15 10:05:00', 1),
(41, 15, 8, 8, '3.0', 3, 1, 'Sprawdzian ze średniowiecza', '2025-10-20 11:05:00', 2),
(42, 15, 9, 9, '4.0', 2, 6, 'Prosta prezentacja multimedialna', '2025-10-07 12:00:00', 1),
(43, 15, 10, 10, '3.0', 1, 3, 'Odpowiedź ustna', '2025-10-08 13:00:00', 2),
(44, 15, 11, 11, '5.0', 1, 5, 'Zaangażowanie na rozgrzewce', '2025-10-14 15:00:00', 1),
(45, 15, 12, 12, '4.0', 1, 4, 'Rysunek', '2025-10-18 09:00:00', 2),
(46, 16, 1, 1, '4.5', 3, 1, 'Bardzo dobrze', '2025-10-15 10:00:00', 1),
(47, 16, 2, 2, '5.0', 3, 1, 'Świetna analiza wiersza', '2025-10-16 11:00:00', 2),
(48, 16, 3, 3, '4.0', 2, 2, '', '2025-10-10 09:00:00', 1),
(49, 16, 3, 3, '5.0', 1, 5, 'Aktywność', '2025-10-17 09:05:00', 2),
(50, 16, 4, 4, '3.5', 3, 1, '', '2025-10-11 13:00:00', 1),
(51, 16, 5, 5, '4.0', 2, 2, 'Reakcje chemiczne', '2025-10-12 14:00:00', 2),
(52, 16, 6, 6, '5.0', 2, 6, 'Projekt o genetyce', '2025-10-20 12:00:00', 1),
(53, 16, 7, 7, '4.5', 2, 2, '', '2025-10-21 10:00:00', 2),
(54, 16, 8, 8, '5.0', 1, 3, 'Odpowiedź na temat starożytnego Rzymu', '2025-10-06 11:00:00', 1),
(55, 16, 9, 9, '4.0', 1, 4, '', '2025-10-07 12:00:00', 2),
(56, 16, 9, 9, '5.0', 2, 6, 'Zaawansowany projekt', '2025-10-21 12:05:00', 1),
(57, 16, 10, 10, '4.0', 1, 5, '', '2025-10-08 13:00:00', 2),
(58, 16, 11, 11, '4.5', 1, 5, 'Test sprawnościowy', '2025-10-14 15:00:00', 1),
(59, 16, 12, 12, '5.0', 2, 6, '', '2025-10-18 09:00:00', 2),
(61, 17, 2, 2, '4.0', 2, 2, '', '2025-10-02 11:05:00', 2),
(62, 17, 2, 2, '3.5', 3, 1, '', '2025-10-16 11:00:00', 1),
(63, 17, 3, 3, '4.0', 1, 4, '', '2025-10-10 09:00:00', 2),
(64, 17, 4, 4, '3.0', 3, 1, '', '2025-10-11 13:00:00', 1),
(65, 17, 5, 5, '4.0', 1, 3, '', '2025-10-12 14:00:00', 2),
(66, 17, 6, 6, '3.5', 2, 2, '', '2025-10-09 08:00:00', 1),
(67, 17, 7, 7, '4.0', 1, 5, '', '2025-10-21 10:00:00', 2),
(68, 17, 8, 8, '4.0', 2, 2, '', '2025-10-06 11:00:00', 1),
(69, 17, 9, 9, '5.0', 2, 6, 'Dobry projekt', '2025-10-21 12:05:00', 2),
(70, 17, 10, 10, '3.0', 1, 3, '', '2025-10-08 13:00:00', 1),
(71, 17, 10, 10, '4.0', 1, 5, 'Aktywność', '2025-10-22 13:05:00', 2),
(72, 17, 11, 11, '6.0', 1, 5, 'Najlepszy czas w biegu', '2025-10-14 15:00:00', 1),
(73, 17, 12, 12, '4.0', 1, 4, '', '2025-10-18 09:00:00', 2),
(74, 18, 1, 1, '5.0', 4, 2, '', '2025-09-25 10:05:00', 1),
(75, 18, 2, 2, '4.5', 3, 1, '', '2025-10-16 11:00:00', 2),
(76, 18, 3, 3, '5.0', 3, 1, 'Test gramatyczny', '2025-10-17 09:05:00', 1),
(77, 18, 4, 4, '4.0', 1, 4, '', '2025-10-11 13:00:00', 2),
(78, 18, 5, 5, '5.0', 2, 2, '', '2025-10-12 14:00:00', 1),
(79, 18, 5, 5, '4.5', 3, 1, '', '2025-10-22 14:05:00', 2),
(80, 18, 6, 6, '5.0', 2, 2, '', '2025-10-09 08:00:00', 1),
(81, 18, 7, 7, '4.5', 2, 2, '', '2025-10-21 10:00:00', 2),
(82, 18, 8, 8, '5.0', 1, 3, '', '2025-10-06 11:00:00', 1),
(83, 18, 9, 9, '4.5', 1, 4, '', '2025-10-21 12:05:00', 2),
(84, 18, 10, 10, '5.0', 1, 5, '', '2025-10-08 13:00:00', 1),
(85, 18, 11, 11, '4.0', 1, 5, '', '2025-10-14 15:00:00', 2),
(86, 18, 12, 12, '5.0', 2, 6, '', '2025-10-18 09:00:00', 1),
(87, 18, 12, 12, '6.0', 1, 4, 'Wspaniała praca', '2025-10-04 09:05:00', 2),
(88, 19, 1, 1, '3.5', 2, 2, NULL, '2025-10-15 10:00:00', 1),
(89, 19, 2, 2, '4.0', 1, 3, NULL, '2025-10-16 11:00:00', 2),
(90, 19, 3, 3, '3.0', 2, 2, NULL, '2025-10-17 09:05:00', 1),
(91, 19, 4, 4, '4.0', 3, 1, NULL, '2025-10-11 13:00:00', 2),
(92, 19, 5, 5, '3.5', 1, 4, NULL, '2025-10-12 14:00:00', 1),
(93, 19, 6, 6, '4.0', 2, 2, NULL, '2025-10-20 12:00:00', 2),
(94, 19, 6, 6, '4.5', 1, 3, 'Ciekawa obserwacja', '2025-10-08 08:05:00', 1),
(95, 19, 7, 7, '3.0', 2, 2, NULL, '2025-10-21 10:00:00', 2),
(96, 19, 8, 8, '4.0', 3, 1, NULL, '2025-10-20 11:05:00', 1),
(97, 19, 9, 9, '4.5', 2, 6, NULL, '2025-10-21 12:05:00', 2),
(98, 19, 10, 10, '4.0', 1, 5, NULL, '2025-10-08 13:00:00', 1),
(99, 19, 11, 11, '5.0', 1, 5, NULL, '2025-10-14 15:00:00', 2),
(100, 19, 11, 11, '4.5', 1, 5, 'Gra zespołowa', '2025-10-21 15:05:00', 1),
(101, 19, 12, 12, '4.0', 1, 4, NULL, '2025-10-18 09:00:00', 2),
(102, 20, 1, 1, '4.0', 3, 1, NULL, '2025-10-15 10:00:00', 1),
(103, 20, 2, 2, '3.5', 1, 3, NULL, '2025-10-16 11:00:00', 2),
(104, 20, 3, 3, '4.0', 2, 2, NULL, '2025-10-17 09:05:00', 1),
(105, 20, 4, 4, '5.0', 3, 1, 'Doskonały wynik', '2025-10-11 13:00:00', 2),
(106, 20, 5, 5, '4.5', 2, 2, NULL, '2025-10-12 14:00:00', 1),
(107, 20, 5, 5, '5.0', 2, 6, 'Projekt badawczy', '2025-10-20 14:05:00', 2),
(108, 20, 6, 6, '4.0', 1, 4, NULL, '2025-10-20 12:00:00', 1),
(109, 20, 7, 7, '4.0', 2, 2, NULL, '2025-10-21 10:00:00', 2),
(110, 20, 8, 8, '3.5', 2, 2, NULL, '2025-10-20 11:05:00', 1),
(111, 20, 9, 9, '5.0', 3, 1, 'Świetnie', '2025-10-07 12:00:00', 2),
(112, 20, 10, 10, '4.0', 1, 3, NULL, '2025-10-08 13:00:00', 1),
(113, 20, 11, 11, '4.0', 1, 5, NULL, '2025-10-14 15:00:00', 2),
(114, 20, 12, 12, '4.5', 1, 4, NULL, '2025-10-18 09:00:00', 1),
(115, 20, 12, 12, '5.0', 2, 6, NULL, '2025-10-22 09:05:00', 2),
(116, 21, 1, 1, '4.5', 2, 1, '', '2025-10-15 10:00:00', 1),
(117, 21, 1, 1, '5.0', 1, 5, 'Aktywność na lekcji', '2025-10-22 10:05:00', 2),
(118, 21, 2, 2, '4.0', 3, 1, NULL, '2025-10-16 11:00:00', 1),
(119, 21, 3, 3, '4.5', 2, 2, NULL, '2025-10-17 09:05:00', 2),
(120, 21, 4, 4, '4.0', 2, 2, NULL, '2025-10-11 13:00:00', 1),
(121, 21, 5, 5, '3.5', 3, 1, NULL, '2025-10-22 14:05:00', 2),
(122, 21, 6, 6, '4.0', 1, 3, NULL, '2025-10-20 12:00:00', 1),
(123, 21, 7, 7, '5.0', 2, 6, 'Świetna prezentacja', '2025-10-21 10:00:00', 2),
(124, 21, 8, 8, '4.5', 3, 1, NULL, '2025-10-20 11:05:00', 1),
(125, 21, 9, 9, '4.0', 1, 4, NULL, '2025-10-21 12:05:00', 2),
(126, 21, 10, 10, '5.0', 1, 5, NULL, '2025-10-08 13:00:00', 1),
(127, 21, 11, 11, '4.0', 1, 5, NULL, '2025-10-21 15:05:00', 2),
(128, 21, 12, 12, '5.0', 2, 6, NULL, '2025-10-22 09:05:00', 1),
(129, 22, 1, 1, '3.0', 3, 1, 'Do poprawy', '2025-10-15 10:00:00', 2),
(130, 22, 2, 2, '3.5', 2, 2, NULL, '2025-10-16 11:00:00', 1),
(131, 22, 3, 3, '4.0', 1, 4, NULL, '2025-10-17 09:05:00', 2),
(132, 22, 3, 3, '3.5', 2, 2, NULL, '2025-09-28 09:05:00', 1),
(133, 22, 4, 4, '3.0', 3, 1, NULL, '2025-10-11 13:00:00', 2),
(134, 22, 5, 5, '4.0', 1, 3, NULL, '2025-10-20 14:05:00', 1),
(135, 22, 6, 6, '3.5', 2, 2, NULL, '2025-10-20 12:00:00', 2),
(136, 22, 7, 7, '4.0', 1, 4, NULL, '2025-10-21 10:00:00', 1),
(137, 22, 8, 8, '4.0', 1, 5, NULL, '2025-10-20 11:05:00', 2),
(138, 22, 9, 9, '3.5', 2, 2, NULL, '2025-10-07 12:00:00', 1),
(139, 22, 9, 9, '4.0', 2, 6, 'Poprawny projekt', '2025-10-21 12:05:00', 2),
(140, 22, 10, 10, '3.0', 1, 3, NULL, '2025-10-08 13:00:00', 1),
(141, 22, 11, 11, '5.0', 1, 5, 'Dobra gra w piłkę nożną', '2025-10-21 15:05:00', 2),
(142, 22, 12, 12, '3.0', 1, 4, NULL, '2025-10-22 09:05:00', 1),
(143, 13, 2, 2, '3.5', 2, 2, 'Kartkówka z lektury', '2025-10-23 11:00:00', 2),
(144, 13, 7, 7, '4.0', 1, 3, 'Odpowiedź ustna o stolicach Azji', '2025-10-24 10:15:00', 1),
(145, 14, 1, 1, '4.0', 3, 1, 'Sprawdzian z funkcji kwadratowej', '2025-10-23 09:00:00', 2),
(146, 14, 10, 10, '4.5', 1, 5, 'Aktywność na lekcji', '2025-10-24 13:00:00', 1),
(147, 14, 5, 5, '4.0', 2, 2, 'Kartkówka z budowy komórki', '2025-10-25 14:00:00', 2),
(148, 15, 11, 11, '5.0', 1, 5, 'Gra w siatkówkę', '2025-10-23 15:00:00', 1),
(150, 16, 8, 8, '5.0', 2, 6, 'Prezentacja o starożytnej Grecji', '2025-10-23 11:30:00', 1),
(151, 16, 2, 2, '4.0', 1, 4, 'Zadanie domowe - analiza wiersza', '2025-10-24 08:00:00', 2),
(152, 17, 4, 4, '2.0', 2, 2, 'Należy powtórzyć zasady dynamiki', '2025-10-23 13:10:00', 1),
(153, 17, 9, 9, '4.5', 2, 6, 'Projekt - prosta strona HTML', '2025-10-24 12:00:00', 2),
(154, 17, 3, 3, '4.0', 1, 3, 'Odpowiedź ustna', '2025-10-25 09:20:00', 1),
(155, 18, 6, 6, '5.0', 3, 1, 'Bardzo dobry wynik ze sprawdzianu', '2025-10-23 08:15:00', 2),
(156, 18, 1, 1, '4.5', 2, 2, 'Kartkówka z algebry', '2025-10-25 09:00:00', 1),
(157, 19, 5, 5, '3.0', 2, 2, NULL, '2025-10-24 14:00:00', 2),
(158, 19, 12, 12, '4.0', 1, 4, 'Praca plastyczna', '2025-10-25 10:00:00', 1),
(159, 20, 8, 8, '4.0', 3, 1, 'Sprawdzian z I Wojny Światowej', '2025-10-23 11:30:00', 2),
(160, 20, 11, 11, '6.0', 1, 5, 'Najlepszy wynik w teście sprawnościowym', '2025-10-24 15:00:00', 1),
(161, 20, 4, 4, '3.5', 2, 2, 'Kartkówka z optyki', '2025-10-25 13:00:00', 2),
(162, 21, 2, 2, '5.0', 1, 3, 'Wypowiedź na temat lektury', '2025-10-23 10:45:00', 1),
(163, 21, 7, 7, '4.0', 1, 4, 'Praca domowa', '2025-10-25 08:00:00', 2),
(164, 22, 1, 1, '2.5', 2, 2, 'Do powtórzenia', '2025-10-23 09:00:00', 1),
(165, 22, 10, 10, '4.0', 1, 5, 'Aktywny udział w dyskusji', '2025-10-24 13:00:00', 2),
(166, 23, 3, 3, '4.5', 2, 2, 'Kartkówka ze słownictwa', '2025-10-23 09:15:00', 1),
(167, 23, 6, 6, '4.0', 1, 3, 'Odpowiedź z botaniki', '2025-10-24 08:30:00', 2),
(168, 24, 1, 1, '6.0', 3, 1, 'Celujący wynik ze sprawdzianu', '2025-10-23 09:00:00', 1),
(169, 24, 8, 8, '5.0', 1, 3, NULL, '2025-10-25 11:00:00', 2),
(170, 25, 2, 2, '3.0', 3, 1, 'Rozprawka - należy popracować nad stylem', '2025-10-23 10:50:00', 1),
(171, 25, 5, 5, '4.0', 1, 4, 'Zadanie domowe', '2025-10-24 08:00:00', 2),
(172, 25, 11, 11, '4.5', 1, 5, NULL, '2025-10-25 15:00:00', 1),
(173, 26, 9, 9, '5.0', 2, 6, 'Świetny projekt w arkuszu kalkulacyjnym', '2025-10-23 12:15:00', 2),
(174, 26, 4, 4, '3.5', 2, 2, 'Kartkówka', '2025-10-25 13:00:00', 1),
(175, 27, 7, 7, '4.0', 2, 2, 'Kartkówka z mapy Polski', '2025-10-23 10:00:00', 2),
(176, 27, 12, 12, '5.0', 1, 4, 'Kreatywna praca', '2025-10-24 09:45:00', 1),
(177, 28, 1, 1, '3.0', 2, 2, NULL, '2025-10-23 09:00:00', 2),
(178, 28, 2, 2, '4.0', 1, 3, 'Aktywność na lekcji', '2025-10-25 10:45:00', 1),
(179, 29, 3, 3, '5.0', 3, 1, 'Bardzo dobrze napisany test', '2025-10-23 09:15:00', 2),
(180, 29, 10, 10, '4.5', 1, 5, 'Ciekawa wypowiedź', '2025-10-24 13:00:00', 1),
(181, 29, 6, 6, '4.0', 2, 2, NULL, '2025-10-25 08:30:00', 2),
(182, 30, 4, 4, '4.0', 3, 1, 'Sprawdzian z termodynamiki', '2025-10-23 13:00:00', 1),
(183, 30, 8, 8, '5.0', 1, 4, 'Praca domowa', '2025-10-24 08:00:00', 2),
(184, 31, 5, 5, '1.0', 2, 2, 'Nieprzygotowanie do kartkówki', '2025-10-23 14:00:00', 1),
(185, 31, 1, 1, '3.0', 1, 4, NULL, '2025-10-24 08:00:00', 2),
(186, 32, 2, 2, '4.5', 2, 2, 'Dyktando', '2025-10-23 10:50:00', 1),
(187, 32, 9, 9, '4.0', 1, 3, 'Odpowiedź przy tablicy', '2025-10-24 12:00:00', 2),
(188, 32, 7, 7, '3.5', 2, 6, 'Projekt', '2025-10-25 10:00:00', 1),
(189, 33, 1, 1, '4.0', 3, 1, 'Sprawdzian', '2025-10-23 09:00:00', 2),
(190, 33, 3, 3, '3.5', 2, 2, 'Kartkówka z czasów', '2025-10-24 09:15:00', 1),
(191, 34, 6, 6, '5.0', 2, 2, 'Kartkówka z genetyki', '2025-10-23 08:30:00', 2),
(192, 34, 11, 11, '5.0', 1, 5, 'Zaangażowanie', '2025-10-24 15:00:00', 1),
(193, 34, 2, 2, '4.0', 1, 3, NULL, '2025-10-25 10:45:00', 2),
(194, 35, 8, 8, '3.0', 3, 1, NULL, '2025-10-23 11:00:00', 1),
(195, 35, 4, 4, '4.0', 1, 4, 'Zadanie domowe', '2025-10-24 08:00:00', 2),
(196, 36, 12, 12, '6.0', 2, 6, 'Wyróżniający się projekt', '2025-10-23 10:00:00', 1),
(197, 36, 5, 5, '4.5', 2, 2, 'Kartkówka z pierwiastków', '2025-10-24 14:00:00', 2),
(198, 37, 10, 10, '3.0', 1, 3, 'Odpowiedź ustna', '2025-10-23 13:00:00', 1),
(199, 37, 1, 1, '2.0', 3, 1, 'Sprawdzian do poprawy', '2025-10-25 09:00:00', 2),
(200, 38, 2, 2, '4.0', 2, 2, NULL, '2025-10-23 10:50:00', 1),
(201, 38, 7, 7, '5.0', 2, 6, 'Prezentacja o Australii', '2025-10-24 10:00:00', 2),
(202, 38, 3, 3, '4.0', 1, 5, 'Aktywność', '2025-10-25 09:15:00', 1),
(203, 39, 4, 4, '5.0', 3, 1, 'Bardzo dobry sprawdzian', '2025-10-23 13:00:00', 2),
(204, 39, 9, 9, '4.5', 1, 4, 'Praca domowa', '2025-10-24 08:00:00', 1),
(205, 40, 6, 6, '3.5', 2, 2, 'Kartkówka', '2025-10-23 08:30:00', 2),
(206, 40, 1, 1, '4.0', 1, 3, NULL, '2025-10-25 09:00:00', 1),
(207, 41, 8, 8, '4.5', 2, 6, 'Projekt o średniowieczu', '2025-10-23 11:00:00', 2),
(208, 41, 2, 2, '4.0', 3, 1, 'Wypracowanie', '2025-10-24 10:50:00', 1),
(209, 42, 11, 11, '5.0', 1, 5, NULL, '2025-10-23 15:00:00', 2),
(210, 42, 5, 5, '3.0', 2, 2, 'Kartkówka z reakcji chemicznych', '2025-10-24 14:00:00', 1),
(211, 42, 1, 1, '3.5', 1, 4, 'Zadanie domowe', '2025-10-25 08:00:00', 2),
(212, 13, 4, 4, '2.0', 3, 1, 'Sprawdzian z kinematyki - dużo błędów', '2025-10-26 13:00:00', 1),
(213, 13, 5, 5, '2.5', 2, 2, 'Kartkówka z tlenków', '2025-10-27 14:00:00', 2),
(214, 15, 3, 3, '2.0', 2, 2, 'Kartkówka z czasów przeszłych', '2025-10-26 09:15:00', 1),
(215, 15, 6, 6, '3.0', 1, 3, 'Odpowiedź ustna - braki w materiale', '2025-10-27 08:30:00', 2),
(217, 17, 5, 5, '2.0', 2, 2, 'Należy powtórzyć podstawy', '2025-10-28 14:05:00', 2),
(218, 19, 1, 1, '2.5', 2, 2, 'Kartkówka z ułamków', '2025-10-27 09:00:00', 1),
(219, 19, 8, 8, '3.0', 3, 1, 'Sprawdzian z okresu międzywojennego', '2025-10-28 11:00:00', 2),
(220, 19, 2, 2, '2.0', 1, 3, 'Słaba znajomość lektury', '2025-10-29 11:15:00', 1),
(221, 22, 5, 5, '1.5', 3, 1, 'Sprawdzian z chemii organicznej - do poprawy', '2025-10-26 14:00:00', 2),
(222, 22, 7, 7, '3.0', 2, 2, 'Kartkówka z ukształtowania terenu Polski', '2025-10-28 10:00:00', 1),
(223, 25, 4, 4, '2.0', 3, 1, 'Sprawdzian z hydrostatyki', '2025-10-26 13:00:00', 2),
(224, 25, 1, 1, '3.0', 1, 4, 'Praca domowa z błędami', '2025-10-27 08:00:00', 1),
(225, 28, 8, 8, '2.5', 2, 2, 'Kartkówka z dat', '2025-10-26 11:00:00', 2),
(226, 28, 5, 5, '2.0', 2, 2, NULL, '2025-10-29 14:00:00', 1),
(227, 31, 3, 3, '1.0', 2, 2, 'Brak przygotowania do lekcji', '2025-10-27 09:20:00', 2),
(228, 31, 2, 2, '2.0', 3, 1, 'Wypracowanie poniżej oczekiwań', '2025-10-28 10:50:00', 1),
(229, 31, 4, 4, '2.5', 1, 3, 'Odpowiedź ustna', '2025-10-29 13:00:00', 2),
(230, 35, 1, 1, '2.0', 3, 1, 'Niezaliczony sprawdzian z logarytmów', '2025-10-27 09:00:00', 1),
(231, 35, 9, 9, '3.0', 2, 6, 'Projekt zawiera błędy', '2025-10-29 12:00:00', 2),
(232, 37, 2, 2, '3.0', 2, 2, 'Dyktando', '2025-10-26 11:00:00', 1),
(233, 37, 6, 6, '2.5', 2, 2, 'Kartkówka z anatomii', '2025-10-28 08:30:00', 2),
(234, 40, 3, 3, '2.0', 2, 2, 'Słownictwo do poprawy', '2025-10-27 09:15:00', 1),
(235, 40, 5, 5, '3.0', 3, 1, 'Sprawdzian', '2025-10-28 14:00:00', 2),
(236, 42, 2, 2, '3.0', 1, 3, 'Odpowiedź ustna', '2025-10-26 10:45:00', 1),
(237, 42, 8, 8, '2.0', 3, 1, 'Sprawdzian z historii nowożytnej', '2025-10-27 11:00:00', 2),
(239, 15, 4, 4, '1.0', 3, 1, 'Kompletny brak wiedzy na sprawdzianie.', '2025-10-30 13:10:00', 2),
(240, 15, 2, 2, '2.0', 2, 2, 'Kartkówka z gramatyki, liczne błędy', '2025-10-28 11:00:00', 1),
(241, 15, 5, 5, '1.5', 2, 2, 'Kartkówka z symboli pierwiastków', '2025-10-29 14:00:00', 2),
(242, 15, 8, 8, '1.0', 1, 3, 'Brak przygotowania do odpowiedzi', '2025-10-27 11:30:00', 1),
(243, 17, 2, 2, '1.0', 3, 1, 'Wypracowanie nie na temat, błędy kardynalne', '2025-10-29 10:50:00', 2),
(244, 17, 3, 3, '2.0', 2, 2, 'Kartkówka ze słownictwa', '2025-10-30 09:15:00', 1),
(245, 17, 6, 6, '1.5', 3, 1, 'Sprawdzian z ekologii do poprawy', '2025-10-28 08:30:00', 2),
(246, 17, 7, 7, '1.0', 1, 3, 'Nieprzygotowanie do lekcji', '2025-10-29 10:00:00', 1),
(247, 31, 1, 1, '1.0', 3, 1, 'Sprawdzian z algebry, wynik 5%', '2025-10-29 09:05:00', 2),
(248, 31, 6, 6, '2.0', 2, 2, 'Kartkówka z budowy tkankowej', '2025-10-30 08:30:00', 1),
(249, 31, 8, 8, '1.0', 3, 1, 'Sprawdzian z II Wojny Światowej', '2025-10-28 11:00:00', 2),
(250, 31, 10, 10, '1.0', 1, 5, 'Brak aktywności, przeszkadzanie na lekcji', '2025-10-27 13:00:00', 1),
(251, 31, 7, 7, '2.0', 1, 4, 'Praca domowa zrobiona niesamodzielnie', '2025-10-29 08:00:00', 2),
(252, 37, 4, 4, '1.0', 3, 1, 'Sprawdzian z optyki - konieczna poprawa', '2025-10-29 13:00:00', 1),
(253, 37, 1, 1, '2.0', 2, 2, 'Kartkówka z działań na potęgach', '2025-10-30 09:00:00', 2),
(254, 37, 5, 5, '1.0', 3, 1, 'Sprawdzian z kwasów i zasad', '2025-10-28 14:00:00', 1),
(255, 37, 2, 2, '1.0', 1, 3, 'Odpowiedź ustna - brak podstawowej wiedzy', '2025-10-27 11:00:00', 2),
(256, 13, 9, 9, '5.0', 2, 6, 'Świetnie przygotowany projekt z bazy danych.', '2025-10-26 12:15:00', 1),
(257, 13, 11, 11, '4.5', 1, 5, 'Bardzo dobre wyniki w teście sprawnościowym.', '2025-10-27 15:00:00', 2),
(258, 13, 8, 8, '3.5', 2, 2, 'Kartkówka z okresu Oświecenia w Europie.', '2025-10-28 11:20:00', 1),
(259, 13, 2, 2, '5.0', 1, 3, 'Wzorowa odpowiedź ustna na temat romantyzmu.', '2025-10-29 10:45:00', 2),
(260, 13, 12, 12, '6.0', 1, 4, 'Wyjątkowo kreatywna praca plastyczna.', '2025-10-30 09:50:00', 1),
(261, 13, 4, 4, '3.0', 3, 1, 'Sprawdzian z termodynamiki - do konsultacji.', '2025-10-30 13:00:00', 2),
(262, 15, 1, 1, '6.0', 3, 3, 'czy to zadziala?', '2025-11-06 14:45:07', 1),
(263, 13, 1, 1, '5.0', 2, 5, 'Wspaniała odpowiedź', '2025-11-06 14:49:32', 2),
(264, 16, 1, 1, '2.0', 1, 6, '', '2025-11-06 14:50:22', 1),
(266, 27, 1, 1, '4.5', 4, 4, '', '2025-11-06 14:52:16', 1),
(267, 30, 1, 1, '3.5', 1, 2, '', '2025-11-06 14:52:46', 2),
(268, 29, 1, 1, '4.0', 1, 1, '', '2025-11-06 14:53:25', 1),
(269, 32, 1, 1, '1.0', 1, 3, '', '2025-11-06 14:53:44', 2),
(270, 26, 1, 1, '2.0', 1, 3, '', '2025-11-06 14:57:25', 1),
(271, 23, 1, 1, '2.0', 4, 1, '', '2025-11-06 16:44:35', 2),
(272, 20, 1, 1, '3.0', 1, 2, '', '2025-11-06 16:45:13', 1),
(273, 37, 1, 1, '3.0', 1, 1, '', '2025-11-06 16:46:27', 2),
(274, 15, 1, 1, '5.0', 5, 1, '', '2025-11-06 16:50:06', 1),
(275, 13, 1, 1, '3.0', 2, 2, '', '2025-11-06 17:53:16', 2),
(276, 21, 1, 1, '1.0', 4, 1, '', '2025-11-06 17:53:30', 1),
(277, 18, 1, 1, '2.5', 1, 2, 'Kartkówka z brył.', '2025-11-06 18:00:17', 2),
(278, 69, 1, 1, '6.0', 3, 4, 'Wspaniała praca domowa!', '2025-11-18 15:37:45', 1),
(280, 17, 1, 1, '3.5', 1, 5, '', '2025-11-19 12:42:20', 1),
(281, 15, 9, 1, '1.0', 4, 6, 'Okropny projekt strony internetowej', '2025-11-19 13:05:39', 2),
(282, 73, 4, 1, '5.0', 6, 1, '', '2025-11-19 13:10:19', 1),
(283, 68, 4, 1, '2.0', 1, 2, '', '2025-11-19 13:10:23', 2),
(284, 68, 4, 1, '4.0', 3, 3, '', '2025-11-19 13:10:31', 1),
(285, 73, 13, 4, '4.0', 1, 3, '', '2025-11-19 13:13:06', 2),
(286, 68, 13, 4, '4.0', 1, 3, '', '2025-11-19 13:13:10', 1),
(287, 69, 13, 4, '1.5', 1, 3, '', '2025-11-19 13:13:18', 2),
(288, 27, 4, 1, '5.0', 1, 2, '', '2025-11-19 13:23:51', 1),
(289, 28, 4, 1, '2.0', 1, 3, '', '2025-11-19 13:23:56', 2),
(290, 29, 4, 1, '1.0', 1, 3, '', '2025-11-19 13:23:59', 1),
(291, 32, 4, 1, '1.0', 1, 3, '', '2025-11-19 13:24:02', 2),
(292, 23, 4, 1, '6.0', 1, 3, '', '2025-11-19 13:24:06', 1),
(293, 24, 4, 1, '2.5', 1, 3, '', '2025-11-19 13:24:11', 2),
(294, 36, 1, 1, '4.0', 1, 6, '', '2025-11-20 22:14:42', 1),
(295, 38, 1, 1, '2.0', 1, 5, '', '2025-11-20 22:14:49', 2),
(296, 25, 1, 1, '2.0', 4, 2, '', '2025-11-24 20:21:24', 1),
(297, 34, 1, 1, '5.0', 1, 1, '', '2025-11-24 20:21:58', 2),
(298, 39, 1, 1, '4.5', 1, 5, '', '2025-11-24 21:01:53', 1),
(299, 18, 9, 1, '4.5', 3, 1, '', '2025-11-25 15:27:32', 1),
(300, 14, 9, 1, '3.5', 1, 4, '', '2025-11-25 15:27:43', 1),
(301, 20, 9, 1, '5.5', 4, 3, '', '2025-11-25 15:27:55', 1),
(302, 28, 4, 1, '3.5', 2, 4, 'Atomowa praca domowa', '2025-11-26 10:49:13', 1),
(303, 32, 4, 1, '6.0', 1, 1, '', '2025-11-26 10:49:42', 1),
(304, 69, 13, 4, '4.5', 2, 2, '', '2025-11-26 10:52:40', 1),
(305, 69, 13, 4, '4.0', 1, 3, 'poprawa odpowiedzi ustnej', '2025-11-26 10:53:15', 2),
(306, 22, 4, 4, '5.0', 4, 1, '', '2025-11-26 10:54:48', 1),
(307, 14, 4, 4, '6.0', 5, 1, '', '2025-11-26 13:07:57', 1),
(308, 14, 4, 4, '2.0', 1, 3, 'Z zaskoczenia!', '2025-11-26 13:08:07', 1),
(309, 13, 4, 4, '3.5', 1, 3, '', '2025-11-26 13:08:40', 1),
(310, 14, 1, 1, '3.5', 1, 2, '', '2025-11-26 14:14:39', 1),
(311, 21, 9, 1, '3.0', 1, 5, '', '2025-11-26 14:59:13', 1),
(312, 17, 9, 1, '2.0', 1, 4, '', '2025-11-26 15:24:22', 1),
(313, 17, 9, 1, '1.0', 3, 1, '', '2025-11-26 15:24:27', 1),
(314, 14, 1, 1, '4.0', 3, 1, '', '2025-11-26 20:06:31', 1),
(315, 32, 1, 1, '4.5', 3, 5, '', '2025-11-27 14:33:37', 1),
(316, 13, 9, 1, '4.0', 1, 4, '', '2025-11-27 15:42:41', 1),
(318, 15, 9, 1, '5.0', 4, 6, '', '2025-11-28 17:03:10', 1),
(319, 15, 9, 1, '2.0', 1, 1, '', '2025-12-01 20:21:49', 2),
(320, 22, 1, 1, '5.0', 3, 5, '', '2025-12-01 22:17:52', 1),
(321, 17, 1, 1, '2.0', 3, 3, '', '2025-12-02 00:11:38', 2);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `grade_categories`
--

CREATE TABLE `grade_categories` (
  `categoryID` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `default_weight` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Zrzut danych tabeli `grade_categories`
--

INSERT INTO `grade_categories` (`categoryID`, `name`, `default_weight`) VALUES
(1, 'Sprawdzian', 3),
(2, 'Kartkówka', 2),
(3, 'Odpowiedź ustna', 1),
(4, 'Praca domowa', 1),
(5, 'Aktywność', 1),
(6, 'Projekt', 2);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `messages`
--

CREATE TABLE `messages` (
  `messageID` int(11) NOT NULL,
  `senderID` int(11) NOT NULL,
  `receiverID` int(11) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message_content` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Zrzut danych tabeli `messages`
--

INSERT INTO `messages` (`messageID`, `senderID`, `receiverID`, `subject`, `message_content`, `is_read`, `created_at`) VALUES
(1, 1, 4, 'Prośba o sprawozdanie', 'Cześć. Wyślij mi sprawozdanie ucznia XYZ.\n\nBardzo dziękuję.', 1, '2025-11-26 11:48:23'),
(2, 1, 4, 'Przypomnienie o zmianie oceny z Historii', 'Dzień dobry, uprzejmie proszę o zmianę oceny dla ucznia Kamil Ślimak z przedmiotu Historia.', 1, '2025-11-26 11:49:35'),
(3, 1, 13, 'Praca domowa', 'Szanowny Panie, uprzejmie przypominam o obowiązku wykonywania prac domowych w terminie nie przekraczającym tygodnia. Oczekuję Pańskiej obecności na przyszłych zajęciach. Odpowie Pan z materiału ostatniej pracy domowej.\n\nPozdrawiam\nAnna Lewandowska', 1, '2025-11-26 11:51:00'),
(4, 1, 43, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'Suspendisse convallis, mauris at accumsan aliquam, sapien neque ultrices ante, at ornare nulla mauris sit amet felis. Sed purus erat, vestibulum at sollicitudin a, condimentum nec elit. Etiam commodo dui vel sapien vulputate, suscipit bibendum felis ornare. Phasellus et magna a turpis gravida rutrum. Maecenas mollis aliquam mollis. Nulla facilisi. Morbi a nulla ac elit aliquam condimentum. Vestibulum varius faucibus tempor. Proin vitae tempus magna. Nam augue risus, condimentum id augue ac, sodales pulvinar tortor. Pellentesque fermentum justo velit, id sodales ex pretium in. Fusce posuere cursus nunc.', 1, '2025-11-26 11:52:50'),
(5, 1, 13, 'Lorem ipsum dolor sit amet', 'Phasellus dapibus nisi vel velit semper, ut suscipit odio commodo. Morbi id tempus leo. Aenean posuere nisl non tempus placerat. Vivamus mattis, ligula sed pharetra dictum, turpis magna suscipit enim, in hendrerit urna enim sit amet augue. Quisque sit amet mattis nulla. Praesent in consectetur orci, vel accumsan eros. Nullam tincidunt sed quam feugiat sagittis.\n\nPozdrawiam\nAnna Lewandowska', 1, '2025-11-26 11:53:18'),
(6, 1, 14, 'Lorem ipsum dolor sit amet', 'Phasellus dapibus nisi vel velit semper, ut suscipit odio commodo. Morbi id tempus leo. Aenean posuere nisl non tempus placerat. Vivamus mattis, ligula sed pharetra dictum, turpis magna suscipit enim, in hendrerit urna enim sit amet augue. Quisque sit amet mattis nulla. Praesent in consectetur orci, vel accumsan eros. Nullam tincidunt sed quam feugiat sagittis.\n\nPozdrawiam\nAnna Lewandowska', 0, '2025-11-26 11:53:18'),
(7, 1, 15, 'Lorem ipsum dolor sit amet', 'Phasellus dapibus nisi vel velit semper, ut suscipit odio commodo. Morbi id tempus leo. Aenean posuere nisl non tempus placerat. Vivamus mattis, ligula sed pharetra dictum, turpis magna suscipit enim, in hendrerit urna enim sit amet augue. Quisque sit amet mattis nulla. Praesent in consectetur orci, vel accumsan eros. Nullam tincidunt sed quam feugiat sagittis.\n\nPozdrawiam\nAnna Lewandowska', 0, '2025-11-26 11:53:18'),
(8, 1, 16, 'Lorem ipsum dolor sit amet', 'Phasellus dapibus nisi vel velit semper, ut suscipit odio commodo. Morbi id tempus leo. Aenean posuere nisl non tempus placerat. Vivamus mattis, ligula sed pharetra dictum, turpis magna suscipit enim, in hendrerit urna enim sit amet augue. Quisque sit amet mattis nulla. Praesent in consectetur orci, vel accumsan eros. Nullam tincidunt sed quam feugiat sagittis.\n\nPozdrawiam\nAnna Lewandowska', 0, '2025-11-26 11:53:18'),
(9, 1, 17, 'Lorem ipsum dolor sit amet', 'Phasellus dapibus nisi vel velit semper, ut suscipit odio commodo. Morbi id tempus leo. Aenean posuere nisl non tempus placerat. Vivamus mattis, ligula sed pharetra dictum, turpis magna suscipit enim, in hendrerit urna enim sit amet augue. Quisque sit amet mattis nulla. Praesent in consectetur orci, vel accumsan eros. Nullam tincidunt sed quam feugiat sagittis.\n\nPozdrawiam\nAnna Lewandowska', 0, '2025-11-26 11:53:18'),
(10, 1, 18, 'Lorem ipsum dolor sit amet', 'Phasellus dapibus nisi vel velit semper, ut suscipit odio commodo. Morbi id tempus leo. Aenean posuere nisl non tempus placerat. Vivamus mattis, ligula sed pharetra dictum, turpis magna suscipit enim, in hendrerit urna enim sit amet augue. Quisque sit amet mattis nulla. Praesent in consectetur orci, vel accumsan eros. Nullam tincidunt sed quam feugiat sagittis.\n\nPozdrawiam\nAnna Lewandowska', 0, '2025-11-26 11:53:18'),
(11, 1, 19, 'Lorem ipsum dolor sit amet', 'Phasellus dapibus nisi vel velit semper, ut suscipit odio commodo. Morbi id tempus leo. Aenean posuere nisl non tempus placerat. Vivamus mattis, ligula sed pharetra dictum, turpis magna suscipit enim, in hendrerit urna enim sit amet augue. Quisque sit amet mattis nulla. Praesent in consectetur orci, vel accumsan eros. Nullam tincidunt sed quam feugiat sagittis.\n\nPozdrawiam\nAnna Lewandowska', 0, '2025-11-26 11:53:18'),
(12, 1, 20, 'Lorem ipsum dolor sit amet', 'Phasellus dapibus nisi vel velit semper, ut suscipit odio commodo. Morbi id tempus leo. Aenean posuere nisl non tempus placerat. Vivamus mattis, ligula sed pharetra dictum, turpis magna suscipit enim, in hendrerit urna enim sit amet augue. Quisque sit amet mattis nulla. Praesent in consectetur orci, vel accumsan eros. Nullam tincidunt sed quam feugiat sagittis.\n\nPozdrawiam\nAnna Lewandowska', 0, '2025-11-26 11:53:18'),
(13, 1, 21, 'Lorem ipsum dolor sit amet', 'Phasellus dapibus nisi vel velit semper, ut suscipit odio commodo. Morbi id tempus leo. Aenean posuere nisl non tempus placerat. Vivamus mattis, ligula sed pharetra dictum, turpis magna suscipit enim, in hendrerit urna enim sit amet augue. Quisque sit amet mattis nulla. Praesent in consectetur orci, vel accumsan eros. Nullam tincidunt sed quam feugiat sagittis.\n\nPozdrawiam\nAnna Lewandowska', 0, '2025-11-26 11:53:18'),
(14, 1, 22, 'Lorem ipsum dolor sit amet', 'Phasellus dapibus nisi vel velit semper, ut suscipit odio commodo. Morbi id tempus leo. Aenean posuere nisl non tempus placerat. Vivamus mattis, ligula sed pharetra dictum, turpis magna suscipit enim, in hendrerit urna enim sit amet augue. Quisque sit amet mattis nulla. Praesent in consectetur orci, vel accumsan eros. Nullam tincidunt sed quam feugiat sagittis.\n\nPozdrawiam\nAnna Lewandowska', 0, '2025-11-26 11:53:18'),
(15, 1, 43, 'Nullam volutpat leo nulla', 'In sed justo leo. Nunc non enim quam. In a lacus mattis, gravida lacus ut, vulputate eros. Donec sed venenatis diam. Vivamus porta congue placerat. Donec mauris est, faucibus non arcu nec, sagittis cursus ex. Mauris accumsan tincidunt nunc quis semper. Phasellus nec nisi metus. Morbi sit amet nibh eget dui commodo efficitur vel ut nibh.', 1, '2025-11-26 11:53:41'),
(16, 1, 44, 'Nullam volutpat leo nulla', 'In sed justo leo. Nunc non enim quam. In a lacus mattis, gravida lacus ut, vulputate eros. Donec sed venenatis diam. Vivamus porta congue placerat. Donec mauris est, faucibus non arcu nec, sagittis cursus ex. Mauris accumsan tincidunt nunc quis semper. Phasellus nec nisi metus. Morbi sit amet nibh eget dui commodo efficitur vel ut nibh.', 0, '2025-11-26 11:53:41'),
(17, 1, 45, 'Nullam volutpat leo nulla', 'In sed justo leo. Nunc non enim quam. In a lacus mattis, gravida lacus ut, vulputate eros. Donec sed venenatis diam. Vivamus porta congue placerat. Donec mauris est, faucibus non arcu nec, sagittis cursus ex. Mauris accumsan tincidunt nunc quis semper. Phasellus nec nisi metus. Morbi sit amet nibh eget dui commodo efficitur vel ut nibh.', 0, '2025-11-26 11:53:41'),
(18, 1, 46, 'Nullam volutpat leo nulla', 'In sed justo leo. Nunc non enim quam. In a lacus mattis, gravida lacus ut, vulputate eros. Donec sed venenatis diam. Vivamus porta congue placerat. Donec mauris est, faucibus non arcu nec, sagittis cursus ex. Mauris accumsan tincidunt nunc quis semper. Phasellus nec nisi metus. Morbi sit amet nibh eget dui commodo efficitur vel ut nibh.', 0, '2025-11-26 11:53:41'),
(19, 1, 47, 'Nullam volutpat leo nulla', 'In sed justo leo. Nunc non enim quam. In a lacus mattis, gravida lacus ut, vulputate eros. Donec sed venenatis diam. Vivamus porta congue placerat. Donec mauris est, faucibus non arcu nec, sagittis cursus ex. Mauris accumsan tincidunt nunc quis semper. Phasellus nec nisi metus. Morbi sit amet nibh eget dui commodo efficitur vel ut nibh.', 0, '2025-11-26 11:53:41'),
(20, 1, 48, 'Nullam volutpat leo nulla', 'In sed justo leo. Nunc non enim quam. In a lacus mattis, gravida lacus ut, vulputate eros. Donec sed venenatis diam. Vivamus porta congue placerat. Donec mauris est, faucibus non arcu nec, sagittis cursus ex. Mauris accumsan tincidunt nunc quis semper. Phasellus nec nisi metus. Morbi sit amet nibh eget dui commodo efficitur vel ut nibh.', 0, '2025-11-26 11:53:41'),
(21, 1, 49, 'Nullam volutpat leo nulla', 'In sed justo leo. Nunc non enim quam. In a lacus mattis, gravida lacus ut, vulputate eros. Donec sed venenatis diam. Vivamus porta congue placerat. Donec mauris est, faucibus non arcu nec, sagittis cursus ex. Mauris accumsan tincidunt nunc quis semper. Phasellus nec nisi metus. Morbi sit amet nibh eget dui commodo efficitur vel ut nibh.', 0, '2025-11-26 11:53:41'),
(22, 1, 50, 'Nullam volutpat leo nulla', 'In sed justo leo. Nunc non enim quam. In a lacus mattis, gravida lacus ut, vulputate eros. Donec sed venenatis diam. Vivamus porta congue placerat. Donec mauris est, faucibus non arcu nec, sagittis cursus ex. Mauris accumsan tincidunt nunc quis semper. Phasellus nec nisi metus. Morbi sit amet nibh eget dui commodo efficitur vel ut nibh.', 0, '2025-11-26 11:53:41'),
(23, 1, 51, 'Nullam volutpat leo nulla', 'In sed justo leo. Nunc non enim quam. In a lacus mattis, gravida lacus ut, vulputate eros. Donec sed venenatis diam. Vivamus porta congue placerat. Donec mauris est, faucibus non arcu nec, sagittis cursus ex. Mauris accumsan tincidunt nunc quis semper. Phasellus nec nisi metus. Morbi sit amet nibh eget dui commodo efficitur vel ut nibh.', 0, '2025-11-26 11:53:41'),
(24, 1, 52, 'Nullam volutpat leo nulla', 'In sed justo leo. Nunc non enim quam. In a lacus mattis, gravida lacus ut, vulputate eros. Donec sed venenatis diam. Vivamus porta congue placerat. Donec mauris est, faucibus non arcu nec, sagittis cursus ex. Mauris accumsan tincidunt nunc quis semper. Phasellus nec nisi metus. Morbi sit amet nibh eget dui commodo efficitur vel ut nibh.', 0, '2025-11-26 11:53:41'),
(25, 1, 53, 'Nullam volutpat leo nulla', 'In sed justo leo. Nunc non enim quam. In a lacus mattis, gravida lacus ut, vulputate eros. Donec sed venenatis diam. Vivamus porta congue placerat. Donec mauris est, faucibus non arcu nec, sagittis cursus ex. Mauris accumsan tincidunt nunc quis semper. Phasellus nec nisi metus. Morbi sit amet nibh eget dui commodo efficitur vel ut nibh.', 0, '2025-11-26 11:53:41'),
(26, 1, 54, 'Nullam volutpat leo nulla', 'In sed justo leo. Nunc non enim quam. In a lacus mattis, gravida lacus ut, vulputate eros. Donec sed venenatis diam. Vivamus porta congue placerat. Donec mauris est, faucibus non arcu nec, sagittis cursus ex. Mauris accumsan tincidunt nunc quis semper. Phasellus nec nisi metus. Morbi sit amet nibh eget dui commodo efficitur vel ut nibh.', 0, '2025-11-26 11:53:41'),
(27, 1, 72, 'Phasellus dapibus nisi vel velit semper', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque interdum sodales odio, in sagittis enim congue ultricies. Aliquam erat volutpat. Nunc viverra imperdiet commodo. Nulla lacus leo, auctor eget mollis sed, pulvinar ut lectus. Praesent at fermentum velit, in efficitur dolor. Quisque porttitor pellentesque neque, id accumsan lorem molestie non. Aenean in lorem metus. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur a luctus neque. Etiam in est arcu. Sed eu dui vel lacus finibus gravida. Proin at nunc odio. Nullam sit amet lectus ut urna posuere molestie vel ut libero. Nunc lacus mauris, hendrerit ac metus eu, dignissim cursus erat. Vestibulum id eros dui. Fusce finibus ultricies egestas.', 0, '2025-11-26 11:53:52'),
(28, 1, 2, 'Phasellus dapibus nisi vel velit semper', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque interdum sodales odio, in sagittis enim congue ultricies. Aliquam erat volutpat. Nunc viverra imperdiet commodo. Nulla lacus leo, auctor eget mollis sed, pulvinar ut lectus. Praesent at fermentum velit, in efficitur dolor. Quisque porttitor pellentesque neque, id accumsan lorem molestie non. Aenean in lorem metus. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur a luctus neque. Etiam in est arcu. Sed eu dui vel lacus finibus gravida. Proin at nunc odio. Nullam sit amet lectus ut urna posuere molestie vel ut libero. Nunc lacus mauris, hendrerit ac metus eu, dignissim cursus erat. Vestibulum id eros dui. Fusce finibus ultricies egestas.', 0, '2025-11-26 11:53:52'),
(29, 1, 9, 'Phasellus dapibus nisi vel velit semper', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque interdum sodales odio, in sagittis enim congue ultricies. Aliquam erat volutpat. Nunc viverra imperdiet commodo. Nulla lacus leo, auctor eget mollis sed, pulvinar ut lectus. Praesent at fermentum velit, in efficitur dolor. Quisque porttitor pellentesque neque, id accumsan lorem molestie non. Aenean in lorem metus. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur a luctus neque. Etiam in est arcu. Sed eu dui vel lacus finibus gravida. Proin at nunc odio. Nullam sit amet lectus ut urna posuere molestie vel ut libero. Nunc lacus mauris, hendrerit ac metus eu, dignissim cursus erat. Vestibulum id eros dui. Fusce finibus ultricies egestas.', 0, '2025-11-26 11:53:52'),
(30, 1, 4, 'Phasellus dapibus nisi vel velit semper', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque interdum sodales odio, in sagittis enim congue ultricies. Aliquam erat volutpat. Nunc viverra imperdiet commodo. Nulla lacus leo, auctor eget mollis sed, pulvinar ut lectus. Praesent at fermentum velit, in efficitur dolor. Quisque porttitor pellentesque neque, id accumsan lorem molestie non. Aenean in lorem metus. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur a luctus neque. Etiam in est arcu. Sed eu dui vel lacus finibus gravida. Proin at nunc odio. Nullam sit amet lectus ut urna posuere molestie vel ut libero. Nunc lacus mauris, hendrerit ac metus eu, dignissim cursus erat. Vestibulum id eros dui. Fusce finibus ultricies egestas.', 1, '2025-11-26 11:53:52'),
(31, 1, 6, 'Phasellus dapibus nisi vel velit semper', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque interdum sodales odio, in sagittis enim congue ultricies. Aliquam erat volutpat. Nunc viverra imperdiet commodo. Nulla lacus leo, auctor eget mollis sed, pulvinar ut lectus. Praesent at fermentum velit, in efficitur dolor. Quisque porttitor pellentesque neque, id accumsan lorem molestie non. Aenean in lorem metus. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur a luctus neque. Etiam in est arcu. Sed eu dui vel lacus finibus gravida. Proin at nunc odio. Nullam sit amet lectus ut urna posuere molestie vel ut libero. Nunc lacus mauris, hendrerit ac metus eu, dignissim cursus erat. Vestibulum id eros dui. Fusce finibus ultricies egestas.', 0, '2025-11-26 11:53:52'),
(32, 1, 7, 'Phasellus dapibus nisi vel velit semper', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque interdum sodales odio, in sagittis enim congue ultricies. Aliquam erat volutpat. Nunc viverra imperdiet commodo. Nulla lacus leo, auctor eget mollis sed, pulvinar ut lectus. Praesent at fermentum velit, in efficitur dolor. Quisque porttitor pellentesque neque, id accumsan lorem molestie non. Aenean in lorem metus. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur a luctus neque. Etiam in est arcu. Sed eu dui vel lacus finibus gravida. Proin at nunc odio. Nullam sit amet lectus ut urna posuere molestie vel ut libero. Nunc lacus mauris, hendrerit ac metus eu, dignissim cursus erat. Vestibulum id eros dui. Fusce finibus ultricies egestas.', 0, '2025-11-26 11:53:52'),
(33, 1, 3, 'Phasellus dapibus nisi vel velit semper', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque interdum sodales odio, in sagittis enim congue ultricies. Aliquam erat volutpat. Nunc viverra imperdiet commodo. Nulla lacus leo, auctor eget mollis sed, pulvinar ut lectus. Praesent at fermentum velit, in efficitur dolor. Quisque porttitor pellentesque neque, id accumsan lorem molestie non. Aenean in lorem metus. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur a luctus neque. Etiam in est arcu. Sed eu dui vel lacus finibus gravida. Proin at nunc odio. Nullam sit amet lectus ut urna posuere molestie vel ut libero. Nunc lacus mauris, hendrerit ac metus eu, dignissim cursus erat. Vestibulum id eros dui. Fusce finibus ultricies egestas.', 0, '2025-11-26 11:53:52'),
(34, 1, 11, 'Phasellus dapibus nisi vel velit semper', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque interdum sodales odio, in sagittis enim congue ultricies. Aliquam erat volutpat. Nunc viverra imperdiet commodo. Nulla lacus leo, auctor eget mollis sed, pulvinar ut lectus. Praesent at fermentum velit, in efficitur dolor. Quisque porttitor pellentesque neque, id accumsan lorem molestie non. Aenean in lorem metus. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur a luctus neque. Etiam in est arcu. Sed eu dui vel lacus finibus gravida. Proin at nunc odio. Nullam sit amet lectus ut urna posuere molestie vel ut libero. Nunc lacus mauris, hendrerit ac metus eu, dignissim cursus erat. Vestibulum id eros dui. Fusce finibus ultricies egestas.', 0, '2025-11-26 11:53:52'),
(35, 1, 5, 'Phasellus dapibus nisi vel velit semper', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque interdum sodales odio, in sagittis enim congue ultricies. Aliquam erat volutpat. Nunc viverra imperdiet commodo. Nulla lacus leo, auctor eget mollis sed, pulvinar ut lectus. Praesent at fermentum velit, in efficitur dolor. Quisque porttitor pellentesque neque, id accumsan lorem molestie non. Aenean in lorem metus. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur a luctus neque. Etiam in est arcu. Sed eu dui vel lacus finibus gravida. Proin at nunc odio. Nullam sit amet lectus ut urna posuere molestie vel ut libero. Nunc lacus mauris, hendrerit ac metus eu, dignissim cursus erat. Vestibulum id eros dui. Fusce finibus ultricies egestas.', 0, '2025-11-26 11:53:52'),
(36, 1, 12, 'Phasellus dapibus nisi vel velit semper', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque interdum sodales odio, in sagittis enim congue ultricies. Aliquam erat volutpat. Nunc viverra imperdiet commodo. Nulla lacus leo, auctor eget mollis sed, pulvinar ut lectus. Praesent at fermentum velit, in efficitur dolor. Quisque porttitor pellentesque neque, id accumsan lorem molestie non. Aenean in lorem metus. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur a luctus neque. Etiam in est arcu. Sed eu dui vel lacus finibus gravida. Proin at nunc odio. Nullam sit amet lectus ut urna posuere molestie vel ut libero. Nunc lacus mauris, hendrerit ac metus eu, dignissim cursus erat. Vestibulum id eros dui. Fusce finibus ultricies egestas.', 0, '2025-11-26 11:53:52'),
(37, 1, 8, 'Phasellus dapibus nisi vel velit semper', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque interdum sodales odio, in sagittis enim congue ultricies. Aliquam erat volutpat. Nunc viverra imperdiet commodo. Nulla lacus leo, auctor eget mollis sed, pulvinar ut lectus. Praesent at fermentum velit, in efficitur dolor. Quisque porttitor pellentesque neque, id accumsan lorem molestie non. Aenean in lorem metus. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur a luctus neque. Etiam in est arcu. Sed eu dui vel lacus finibus gravida. Proin at nunc odio. Nullam sit amet lectus ut urna posuere molestie vel ut libero. Nunc lacus mauris, hendrerit ac metus eu, dignissim cursus erat. Vestibulum id eros dui. Fusce finibus ultricies egestas.', 0, '2025-11-26 11:53:52'),
(38, 1, 10, 'Phasellus dapibus nisi vel velit semper', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque interdum sodales odio, in sagittis enim congue ultricies. Aliquam erat volutpat. Nunc viverra imperdiet commodo. Nulla lacus leo, auctor eget mollis sed, pulvinar ut lectus. Praesent at fermentum velit, in efficitur dolor. Quisque porttitor pellentesque neque, id accumsan lorem molestie non. Aenean in lorem metus. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur a luctus neque. Etiam in est arcu. Sed eu dui vel lacus finibus gravida. Proin at nunc odio. Nullam sit amet lectus ut urna posuere molestie vel ut libero. Nunc lacus mauris, hendrerit ac metus eu, dignissim cursus erat. Vestibulum id eros dui. Fusce finibus ultricies egestas.', 0, '2025-11-26 11:53:52'),
(39, 13, 1, 'Ocena z odpowiedzi ustnej', 'Szanowna Pani,\n\nprzypominam o wpisaniu mi oceny z odpowiedzi.\n\nPozdrawiam kamil', 1, '2025-11-26 11:54:56'),
(40, 63, 1, 'Aktualizacja panelu wiadomości', 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).', 1, '2025-11-26 12:18:42'),
(41, 1, 2, 'What is Lorem Ipsum?', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 0, '2025-11-26 12:19:20'),
(42, 43, 4, 'Suspendisse malesuada risus mauris', 'Sed elementum odio in justo euismod, a porttitor augue suscipit. Nulla vestibulum metus sed nunc vehicula volutpat. Sed id purus dignissim, finibus justo sed, mattis enim. Vestibulum consectetur porta felis, ac lacinia metus. Curabitur condimentum nulla nec sodales molestie. Maecenas quis sapien diam. Praesent diam mauris, rhoncus sit amet ipsum id, pulvinar finibus nibh. Aenean id lorem sit amet magna varius ultrices nec et diam. Nullam ac rhoncus lectus. Donec eget efficitur erat.\n\nZbigniew Wójcik', 1, '2025-11-26 13:07:01'),
(43, 1, 37, 'Baran', 'Marek Baran', 0, '2025-11-26 15:01:17'),
(44, 72, 13, 'Spotkanie przewodniczących klas.', 'Duis placerat pulvinar mauris sed iaculis. Sed non dolor feugiat, pharetra lorem non, dictum risus. Vestibulum non purus eu risus accumsan imperdiet elementum non metus. Praesent ultrices in sem in molestie. Donec tempor elit non euismod ullamcorper. Proin ultricies at ligula sed suscipit. Vivamus feugiat suscipit nisi, ut faucibus eros. Sed at interdum enim, a condimentum ante. Quisque vitae fermentum dolor. Duis et arcu eget est tristique blandit. Aenean ac justo sit amet dolor luctus euismod.', 1, '2025-11-26 15:26:22'),
(45, 68, 1, 'Czym jest Lorem Ipsum?', 'Lorem Ipsum jest tekstem stosowanym jako przykładowy wypełniacz w przemyśle poligraficznym. Został po raz pierwszy użyty w XV w. przez nieznanego drukarza do wypełnienia tekstem próbnej książki. Pięć wieków później zaczął być używany przemyśle elektronicznym, pozostając praktycznie niezmienionym. Spopularyzował się w latach 60. XX w. wraz z publikacją arkuszy Letrasetu, zawierających fragmenty Lorem Ipsum, a ostatnio z zawierającym różne wersje Lorem Ipsum oprogramowaniem przeznaczonym do realizacji druków na komputerach osobistych, jak Aldus PageMaker.', 1, '2025-11-27 14:13:35'),
(46, 43, 4, 'Do czego tego użyć?', 'Ogólnie znana teza głosi, iż użytkownika może rozpraszać zrozumiała zawartość strony, kiedy ten chce zobaczyć sam jej wygląd. Jedną z mocnych stron używania Lorem Ipsum jest to, że ma wiele różnych „kombinacji” zdań, słów i akapitów, w przeciwieństwie do zwykłego: „tekst, tekst, tekst”, sprawiającego, że wygląda to „zbyt czytelnie” po polsku. Wielu webmasterów i designerów używa Lorem Ipsum jako domyślnego modelu tekstu i wpisanie w internetowej wyszukiwarce ‘lorem ipsum’ spowoduje znalezienie bardzo wielu stron, które wciąż są w budowie. Wiele wersji tekstu ewoluowało i zmieniało się przez lata, czasem przez przypadek, czasem specjalnie (humorystyczne wstawki itd).', 0, '2025-11-27 14:15:02'),
(47, 1, 13, 'Wiadomość dla całej klasy 1A', 'Standardowy blok Lorem Ipsum, używany od XV wieku, jest odtworzony niżej dla zainteresowanych. Fragmenty 1.10.32 i 1.10.33 z „de Finibus Bonorum et Malorum” Cycerona, są odtworzone w dokładnej, oryginalnej formie, wraz z angielskimi tłumaczeniami H. Rackhama z 1914 roku.', 1, '2025-11-27 14:34:20'),
(48, 1, 14, 'Wiadomość dla całej klasy 1A', 'Standardowy blok Lorem Ipsum, używany od XV wieku, jest odtworzony niżej dla zainteresowanych. Fragmenty 1.10.32 i 1.10.33 z „de Finibus Bonorum et Malorum” Cycerona, są odtworzone w dokładnej, oryginalnej formie, wraz z angielskimi tłumaczeniami H. Rackhama z 1914 roku.', 0, '2025-11-27 14:34:20'),
(49, 1, 15, 'Wiadomość dla całej klasy 1A', 'Standardowy blok Lorem Ipsum, używany od XV wieku, jest odtworzony niżej dla zainteresowanych. Fragmenty 1.10.32 i 1.10.33 z „de Finibus Bonorum et Malorum” Cycerona, są odtworzone w dokładnej, oryginalnej formie, wraz z angielskimi tłumaczeniami H. Rackhama z 1914 roku.', 0, '2025-11-27 14:34:20'),
(50, 1, 16, 'Wiadomość dla całej klasy 1A', 'Standardowy blok Lorem Ipsum, używany od XV wieku, jest odtworzony niżej dla zainteresowanych. Fragmenty 1.10.32 i 1.10.33 z „de Finibus Bonorum et Malorum” Cycerona, są odtworzone w dokładnej, oryginalnej formie, wraz z angielskimi tłumaczeniami H. Rackhama z 1914 roku.', 0, '2025-11-27 14:34:20'),
(51, 1, 17, 'Wiadomość dla całej klasy 1A', 'Standardowy blok Lorem Ipsum, używany od XV wieku, jest odtworzony niżej dla zainteresowanych. Fragmenty 1.10.32 i 1.10.33 z „de Finibus Bonorum et Malorum” Cycerona, są odtworzone w dokładnej, oryginalnej formie, wraz z angielskimi tłumaczeniami H. Rackhama z 1914 roku.', 0, '2025-11-27 14:34:20'),
(52, 1, 18, 'Wiadomość dla całej klasy 1A', 'Standardowy blok Lorem Ipsum, używany od XV wieku, jest odtworzony niżej dla zainteresowanych. Fragmenty 1.10.32 i 1.10.33 z „de Finibus Bonorum et Malorum” Cycerona, są odtworzone w dokładnej, oryginalnej formie, wraz z angielskimi tłumaczeniami H. Rackhama z 1914 roku.', 0, '2025-11-27 14:34:20'),
(53, 1, 19, 'Wiadomość dla całej klasy 1A', 'Standardowy blok Lorem Ipsum, używany od XV wieku, jest odtworzony niżej dla zainteresowanych. Fragmenty 1.10.32 i 1.10.33 z „de Finibus Bonorum et Malorum” Cycerona, są odtworzone w dokładnej, oryginalnej formie, wraz z angielskimi tłumaczeniami H. Rackhama z 1914 roku.', 0, '2025-11-27 14:34:20'),
(54, 1, 20, 'Wiadomość dla całej klasy 1A', 'Standardowy blok Lorem Ipsum, używany od XV wieku, jest odtworzony niżej dla zainteresowanych. Fragmenty 1.10.32 i 1.10.33 z „de Finibus Bonorum et Malorum” Cycerona, są odtworzone w dokładnej, oryginalnej formie, wraz z angielskimi tłumaczeniami H. Rackhama z 1914 roku.', 0, '2025-11-27 14:34:20'),
(55, 1, 21, 'Wiadomość dla całej klasy 1A', 'Standardowy blok Lorem Ipsum, używany od XV wieku, jest odtworzony niżej dla zainteresowanych. Fragmenty 1.10.32 i 1.10.33 z „de Finibus Bonorum et Malorum” Cycerona, są odtworzone w dokładnej, oryginalnej formie, wraz z angielskimi tłumaczeniami H. Rackhama z 1914 roku.', 0, '2025-11-27 14:34:20'),
(56, 1, 22, 'Wiadomość dla całej klasy 1A', 'Standardowy blok Lorem Ipsum, używany od XV wieku, jest odtworzony niżej dla zainteresowanych. Fragmenty 1.10.32 i 1.10.33 z „de Finibus Bonorum et Malorum” Cycerona, są odtworzone w dokładnej, oryginalnej formie, wraz z angielskimi tłumaczeniami H. Rackhama z 1914 roku.', 0, '2025-11-27 14:34:20'),
(57, 13, 4, 'lnareg', 'jergiej[ergp', 0, '2025-11-28 17:02:01'),
(58, 13, 7, 'temat', 'lorem ipsum', 0, '2025-11-30 20:40:03'),
(59, 13, 10, 'Prośba o wpisanie oceny.', 'Do i have consent to record this meeting can you run this by clearance? hot johnny coming through have bandwidth, so this proposal is a win-win situation which will cause a stellar paradigm shift, and produce a multi-fold increase in deliverables not enough bandwidth, so i need to pee and then go to another meeting, so timeframe. I have zero cycles for this optimize for search, for locked and loaded who\'s the goto on this job with the way forward , but criticality reach out, so but what\'s the real problem we\'re trying to solve here?. Synergize productive mindfulness. Even dead cats bounce we need to button up our approach, so collaboration through advanced technlogy this is not the hill i want to die on 4-blocker, closing these latest prospects is like putting socks on an octopus, but personal development.', 0, '2025-12-01 23:13:13'),
(60, 1, 13, 'What is Lorem Ipsum?', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 0, '2025-12-01 23:17:44'),
(61, 63, 79, 'Witamy nową uczennicę', 'OŚWIADCZENIE W SPRAWIE TREŚCI GENEROWANYCH PRZEZ SI\nProducenci opisują, jak ich gra korzysta z treści generowanych przez SI, w następujący sposób:\n\nW produkcji gry wykorzystano sztuczną inteligencję do modulacji głosów oraz generowania piosenek i dźwięków.\n\nWYMAGANIA SYSTEMOWE\nWindowsmacOS\nKONFIGURACJA MINIMALNA:\nWymaga 64-bitowego procesora i systemu operacyjnego\nSYSTEM OPERACYJNY: Windows 10\nPROCESOR: i5-3320M @ 2.60 GHz / AMD Ryzen 3 3200u\nPAMIĘĆ: 4 GB RAM\nKARTA GRAFICZNA: Radeon HD 5000+\nMIEJSCE NA DYSKU: 4 GB dostępnej przestrzeni\nKONFIGURACJA ZALECANA:\nWymaga 64-bitowego procesora i systemu operacyjnego', 0, '2025-12-01 23:28:58'),
(62, 13, 1, 'Where can I get some?', 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don\'t look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn\'t anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.', 0, '2025-12-02 00:00:40');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `notes`
--

CREATE TABLE `notes` (
  `noteID` int(11) NOT NULL,
  `studentID` int(11) NOT NULL,
  `teacherID` int(11) NOT NULL,
  `note_content` text NOT NULL,
  `note_type` enum('positive','negative','neutral') NOT NULL DEFAULT 'neutral',
  `date_added` datetime NOT NULL DEFAULT current_timestamp(),
  `semester` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Zrzut danych tabeli `notes`
--

INSERT INTO `notes` (`noteID`, `studentID`, `teacherID`, `note_content`, `note_type`, `date_added`, `semester`) VALUES
(1, 13, 1, 'Uczeń wzorowo reprezentował szkołę na olimpiadzie Matematyki.', 'positive', '2025-11-06 17:29:01', 2),
(2, 13, 1, 'Uczeń nazwał kolegę z klasy \"dziką świnią\"', 'negative', '2025-11-06 17:29:40', 1),
(4, 15, 1, 'Uczeń nie utrzymuje higieny osobistej....', 'neutral', '2025-11-06 17:59:42', 1),
(5, 27, 1, 'Uczennica pomogła innemu uczniowi w zadaniu domowym.', 'positive', '2025-11-18 10:44:22', 2),
(6, 17, 1, 'Pochwała za udział w przedstawieniu szkolnym', 'positive', '2025-11-18 12:52:46', 1),
(7, 13, 1, 'Kamil notorycznie nie odrabia pracy domowej..', 'neutral', '2025-11-18 12:53:19', 2),
(8, 69, 4, 'Uczeń ciągle rozmawia na lekcji.', 'negative', '2025-11-18 15:56:17', 1),
(9, 15, 4, 'Uczeń nie zachowuje należytej kultury osobistej na lekcji Historii', 'negative', '2025-11-19 13:13:50', 2),
(10, 33, 1, 'Maksymilian używa wulgaryzmów na lekcji', 'negative', '2025-11-20 22:15:11', 1),
(11, 25, 1, 'Alicja jest uprzejma.', 'positive', '2025-11-24 18:49:31', 2),
(13, 22, 1, 'Rafał rzadko uczęszcza na lekcje Matematyki.', 'neutral', '2025-11-24 19:10:58', 2),
(17, 18, 1, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.', 'neutral', '2025-11-25 11:14:01', 2),
(18, 22, 1, 'Rafał pomógł koledze w zadaniu domowym.', 'positive', '2025-11-25 15:59:30', 1),
(19, 15, 4, 'Uczeń dobrowolnie zgłosił się, aby pomóc przy odśnieżaniu parkingu szkoły.', 'positive', '2025-11-26 10:54:17', 1),
(20, 29, 1, 'Joanna posiada zbyt niską frekwencję.', 'negative', '2025-11-26 14:59:40', 1),
(21, 29, 1, 'Joanna notorycznie przychodzi nieprzygotowana na zajęcia.', 'negative', '2025-11-27 12:08:32', 2),
(22, 31, 1, 'Michał nie wróbluje na lekcji.', 'negative', '2025-12-01 20:15:44', 1);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `parents`
--

CREATE TABLE `parents` (
  `parentID` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `PESEL` char(11) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `birth_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Zrzut danych tabeli `parents`
--

INSERT INTO `parents` (`parentID`, `first_name`, `last_name`, `PESEL`, `address`, `birth_date`) VALUES
(43, 'Anna', 'Michalska', '85010112345', '', '1985-01-01'),
(44, 'Mirosław', 'Jaworski', '82020223456', 'ul. Szkolna 2, Warszawa', '1982-02-02'),
(45, 'Jadwiga', 'Jaworska', '83030334567', 'ul. Szkolna 2, Warszawa', '1983-03-03'),
(46, 'Franciszek', 'Biernacki', '80040445678', 'ul. Uczniowska 3, Warszawa', '1980-04-04'),
(47, 'Janusz', 'Orkowski', '79050556789', 'ul. Klasowa 4, Warszawa', '1979-05-05'),
(48, 'Helena', 'Orkowska', '81060667890', 'ul. Klasowa 4, Warszawa', '1981-06-06'),
(49, 'Józef', 'Sobczak', '78070778901', 'ul. Uczniowska 5, Warszawa', '1978-07-07'),
(50, 'Ryszard', 'Chmielewski', '84080889012', 'ul. Uczniowska 6, Warszawa', '1984-08-08'),
(51, 'Wiesław', 'Walczak', '82090990123', 'ul. Uczniowska 7, Warszawa', '1982-09-09'),
(52, 'Małgorzata', 'Witkowska', '85101001234', 'ul. Uczniowska 8, Warszawa', '1985-10-10'),
(53, 'Mariusz', 'Czajkowski', '83111112345', 'ul. Uczniowska 9, Warszawa', '1983-11-11'),
(54, 'Agata', 'Kozłowska', '86121223456', 'ul. Uczniowska 10, Warszawa', '1986-12-12'),
(55, 'Tomasz', 'Sokołowski', '81011334567', 'ul. Szkolna 11, Kraków', '1981-01-13'),
(56, 'Aleksandra', 'Szczepańska', '84021445678', 'ul. Szkolna 12, Kraków', '1984-02-14'),
(57, 'Eugeniusz', 'Sawicki', '79031556789', 'ul. Szkolna 13, Kraków', '1979-03-15'),
(58, 'Wanda', 'Michalak', '82041667890', 'ul. Szkolna 14, Kraków', '1982-04-16'),
(59, 'Krzysztof', 'Kaczmarczyk', '80051778901', 'ul. Szkolna 15, Kraków', '1980-05-17'),
(60, 'Paulina', 'Kalinowska', '85061889012', 'ul. Szkolna 16, Kraków', '1985-06-18'),
(61, 'Marcin', 'Lis', '81071990123', 'ul. Szkolna 17, Kraków', '1981-07-19'),
(62, 'Bogdan', 'Zaleski', '77082001234', 'ul. Szkolna 18, Kraków', '1977-08-20');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `students`
--

CREATE TABLE `students` (
  `studentID` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `PESEL` char(11) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `birth_date` date NOT NULL,
  `classID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Zrzut danych tabeli `students`
--

INSERT INTO `students` (`studentID`, `first_name`, `last_name`, `PESEL`, `address`, `birth_date`, `classID`) VALUES
(13, 'Kamil', 'Ślimak', '10452857230', 'ul. Uczniowska 22, Warszawa', '2010-01-01', 1),
(14, 'Zofia', 'Jaworska', '15220222222', 'ul. Szkolna 2, Warszawa', '2015-02-02', 1),
(15, 'Paweł', 'Biernacki', '15230333333', 'ul. Uczniowska 3, Warszawa', '2015-03-05', 1),
(16, 'Barbara', 'Orkowska', '15240444444', 'ul. Klasowa 4, Warszawa', '2015-04-04', 1),
(17, 'Maciej', 'Sobczak', '15250555555', 'ul. Uczniowska 5, Warszawa', '2015-05-05', 1),
(18, 'Krystyna', 'Chmielewska', '15260666666', 'ul. Uczniowska 6, Warszawa', '2015-06-06', 1),
(19, 'Jacek', 'Walczak', '15270777777', 'ul. Uczniowska 7, Warszawa', '2015-07-07', 1),
(20, 'Wojciech', 'Witkowski', '15280888888', 'ul. Uczniowska 8, Warszawa', '2015-08-08', 1),
(21, 'Dagmara', 'Czajkowska', '15290999999', 'ul. Uczniowska 9, Warszawa', '2015-09-09', 1),
(22, 'Rafał', 'Kozłowski', '15301010101', 'ul. Uczniowska 10, Warszawa', '2015-10-10', 1),
(23, 'Maria', 'Sokołowska', '15211111222', 'ul. Szkolna 11, Kraków', '2015-11-01', 2),
(24, 'Jakub', 'Szczepański', '15221222333', 'ul. Szkolna 12, Kraków', '2015-12-02', 2),
(25, 'Alicja', 'Sawicka', '15231333444', 'ul. Szkolna 13, Kraków', '2015-01-13', 2),
(26, 'Mikołaj', 'Michalak', '15241444555', 'ul. Szkolna 14, Kraków', '2015-02-14', 2),
(27, 'Karolina', 'Kaczmarczyk', '15251555666', 'ul. Szkolna 15, Kraków', '2015-03-15', 2),
(28, 'Tymoteusz', 'Kalinowski', '15261666777', 'ul. Szkolna 16, Kraków', '2015-04-16', 2),
(29, 'Joanna', 'Lis', '15271777888', 'ul. Szkolna 17, Kraków', '2015-05-17', 2),
(30, 'Oliwia', 'Zaleska', '15281888999', 'ul. Szkolna 18, Kraków', '2015-06-18', 2),
(31, 'Michał', 'Wróbel', '15291999000', 'ul. Szkolna 19, Kraków', '2015-07-19', 2),
(32, 'Marta', 'Malinowska', '15302010111', 'ul. Szkolna 20, Kraków', '2015-08-20', 2),
(33, 'Maksymilian', 'Jakubowski', '14210111333', 'ul. Edukacyjna 1, Poznań', '2014-01-01', 3),
(34, 'Kinga', 'Pawlak', '14220222444', 'ul. Edukacyjna 2, Poznań', '2014-02-02', 3),
(35, 'Kacper', 'Sikora', '14230333555', 'ul. Edukacyjna 3, Poznań', '2014-03-03', 3),
(36, 'Antonina', 'Nowicka', '14240444666', 'ul. Edukacyjna 4, Poznań', '2014-04-04', 3),
(37, 'Marek', 'Baran', '14250555777', 'ul. Edukacyjna 5, Poznań', '2014-05-05', 3),
(38, 'Patryk', 'Dudek', '14260666888', 'ul. Edukacyjna 6, Poznań', '2014-06-06', 3),
(39, 'Julia', 'Pietrzak', '14270777999', 'ul. Edukacyjna 7, Poznań', '2014-07-07', 3),
(40, 'Zenon', 'Jaworski', '14280888000', 'ul. Szkolna 2, Warszawa', '2014-08-08', 3),
(41, 'Izabela', 'Wasilewska', '14290999111', 'ul. Edukacyjna 9, Poznań', '2014-09-09', 3),
(42, 'Bartosz', 'Piątek', '14301010222', 'ul. Edukacyjna 10, Poznań', '2014-10-10', 3),
(68, 'Jakub', 'Kamilowski', '09653349285', 'ul. Wróblewskiego 221, Łódź', '2009-03-31', 6),
(69, 'Michał', 'Matczak', '08563487349', 'ul. Zielona 420, Warszawa', '2008-06-07', 6),
(73, 'Aleksander', 'Gwiazdowski', '85453325691', 'Poniatowska 10, Poznań', '2002-05-11', 6);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `student_parent`
--

CREATE TABLE `student_parent` (
  `studentID` int(11) NOT NULL,
  `parentID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Zrzut danych tabeli `student_parent`
--

INSERT INTO `student_parent` (`studentID`, `parentID`) VALUES
(13, 43),
(14, 44),
(14, 45),
(15, 46),
(16, 47),
(16, 48),
(17, 49),
(18, 50),
(19, 51),
(20, 52),
(21, 53),
(22, 54),
(23, 55),
(24, 56),
(25, 57),
(26, 58),
(27, 59),
(28, 60),
(29, 61),
(30, 62),
(33, 43),
(33, 49),
(34, 50),
(35, 51),
(36, 52),
(37, 53),
(38, 54),
(39, 55),
(40, 44),
(40, 45),
(41, 56),
(42, 57),
(73, 43);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `subjects`
--

CREATE TABLE `subjects` (
  `subjectID` int(11) NOT NULL,
  `name` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Zrzut danych tabeli `subjects`
--

INSERT INTO `subjects` (`subjectID`, `name`) VALUES
(6, 'Biologia'),
(5, 'Chemia'),
(4, 'Fizyka'),
(7, 'Geografia'),
(8, 'Historia'),
(9, 'Informatyka'),
(3, 'Język angielski'),
(2, 'Język polski'),
(1, 'Matematyka'),
(12, 'Plastyka'),
(13, 'Wiedza o historii'),
(14, 'Wiedza o kulturze'),
(10, 'Wiedza o społeczeństwie'),
(11, 'Wychowanie Fizyczne');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `teachers`
--

CREATE TABLE `teachers` (
  `teacherID` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `PESEL` char(11) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `birth_date` date NOT NULL,
  `hire_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Zrzut danych tabeli `teachers`
--

INSERT INTO `teachers` (`teacherID`, `first_name`, `last_name`, `PESEL`, `address`, `birth_date`, `hire_date`) VALUES
(1, 'Anna', 'Nowak', '80011012345', 'ul. Nauczycielska 1, Warszawa', '1980-01-10', '2010-09-01'),
(2, 'Jan', 'Kowalski', '75031523456', 'ul. Nauczycielska 2, Kraków', '1975-03-15', '2005-09-01'),
(3, 'Katarzyna', 'Wiśniewska', '82052034567', 'ul. Nauczycielska 3, Poznań', '1982-05-20', '2012-09-01'),
(4, 'Zbigniew', 'Wójcik', '78080145678', 'ul. Nauczycielska 24, Wieruszów', '1978-08-01', '2008-09-01'),
(5, 'Magdalena', 'Kowalczyk', '85112556789', 'ul. Nauczycielska 5, Wrocław', '1985-11-25', '2015-09-01'),
(6, 'Tomasz', 'Kamiński', '79021867890', 'ul. Nauczycielska 6, Łódź', '1979-02-18', '2009-09-01'),
(7, 'Piotr', 'Lewandowski', '81073078901', 'ul. Nauczycielska 7, Katowice', '1981-07-30', '2011-09-01'),
(8, 'Dorota', 'Ziółkowska', '88040589012', 'ul. Nauczycielska 8, Szczecin', '1988-04-05', '2018-09-01'),
(9, 'Marek', 'Szymański', '76061290123', 'ul. Nauczycielska 9, Lublin', '1976-06-12', '2006-09-01'),
(10, 'Weronika', 'Dąbrowska', '90090901234', 'ul. Nauczycielska 10, Bydgoszcz', '1990-09-09', '2020-09-01'),
(11, 'Grzegorz', 'Piotrowski', '83120112345', 'ul. Nauczycielska 11, Gdynia', '1983-12-01', '2013-09-01'),
(12, 'Justyna', 'Grabowska', '86101023456', 'ul. Nauczycielska 12, Sopot', '1986-10-10', '2016-09-01'),
(72, 'Kazimiera', 'VonShlaufus', '34060606645', 'Samego diabła 12, Choroszcz', '1934-06-06', '2006-06-06');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users`
--

CREATE TABLE `users` (
  `userID` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `role` enum('student','teacher','parent','admin') NOT NULL,
  `createdAt` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Zrzut danych tabeli `users`
--

INSERT INTO `users` (`userID`, `username`, `password`, `email`, `phone`, `role`, `createdAt`) VALUES
(1, 't', '$2y$10$lOTWDvo9v4AXBoDmXLGlYO4UXu9EUQoyL0KL/aVxSkNtam4GDI1zq', 'a.nowak@example.com', '111222333', 'teacher', '2025-10-22 13:57:51'),
(2, 'jkowalski', 'hashed_password', 'j.kowalski@example.com', '222333444', 'teacher', '2025-10-22 13:57:51'),
(3, 'kwisniewska', 'hashed_password', 'k.wisniewska@example.com', '333444555', 'teacher', '2025-10-22 13:57:51'),
(4, 'zwojcik', '$2y$10$Z9SOlStga0X8EFevD0h7EOvQJ49OqA5WPDgX1IGxxBTP2aPvNoOXy', 'z.wojcik@example.com', '444555666', 'teacher', '2025-10-22 13:57:51'),
(5, 'mkowalczyk', 'hashed_password', 'm.kowalczyk@example.com', '555666777', 'teacher', '2025-10-22 13:57:51'),
(6, 'tkaminska', 'hashed_password', 't.kaminska@example.com', '666777888', 'teacher', '2025-10-22 13:57:51'),
(7, 'plewandowski', 'hashed_password', 'p.lewandowski@example.com', '777888999', 'teacher', '2025-10-22 13:57:51'),
(8, 'dziolkowski', 'hashed_password', 'd.ziolkowski@example.com', '888999000', 'teacher', '2025-10-22 13:57:51'),
(9, 'mszymanska', 'hashed_password', 'm.szymanska@example.com', '999000111', 'teacher', '2025-10-22 13:57:51'),
(10, 'wdabrowski', 'hashed_password', 'w.dabrowski@example.com', '100111222', 'teacher', '2025-10-22 13:57:51'),
(11, 'gpiotrowska', 'hashed_password', 'g.piotrowska@example.com', '112112112', 'teacher', '2025-10-22 13:57:51'),
(12, 'jgrabowski', 'hashed_password', 'j.grabowski@example.com', '213213213', 'teacher', '2025-10-22 13:57:51'),
(13, 's', '$2y$10$Hm2LGwBJe5sT.Kw.cf7qQef6p7A52u32b4UKDIkizx4qRfCN4rTlG', 'k.michalskii@example.net', '842314000', 'student', '2025-09-01 21:37:00'),
(14, 'zjaworska', 'hashed_password', 'z.jaworska@example.com', NULL, 'student', '2025-10-22 13:57:51'),
(15, 'ss', '$2y$10$C5IkcNBd1IQ5nh4hMjltG.ANlTYL3IjnaJzl3kuFadkoeSB0uggdK', 'p.biernacki@example.com', '', 'student', '2025-10-22 13:57:51'),
(16, 'borkowska', 'hashed_password', 'b.orkowska@example.com', NULL, 'student', '2025-10-22 13:57:51'),
(17, 'msobczak', '$2y$10$1.FOrKNlbh3A8ZpMGhIBjuRUvBu0FdQb.ZAqq4feooWOCjg89OdqS', 'm.sobczak@example.com', NULL, 'student', '2025-10-22 13:57:51'),
(18, 'kchmielewska', 'hashed_password', 'k.chmielewska@example.com', NULL, 'student', '2025-10-22 13:57:51'),
(19, 'jwalczak', 'hashed_password', 'j.walczak@example.com', NULL, 'student', '2025-10-22 13:57:51'),
(20, 'wwitkowski', 'hashed_password', 'w.witkowski@example.com', NULL, 'student', '2025-10-22 13:57:51'),
(21, 'dczajkowska', 'hashed_password', 'd.czajkowska@example.com', NULL, 'student', '2025-10-22 13:57:51'),
(22, 'rkozlowski', 'hashed_password', 'r.kozlowski@example.com', NULL, 'student', '2025-10-22 13:57:51'),
(23, 'msokolowska', 'hashed_password', 'm.sokolowska1@example.com', NULL, 'student', '2025-10-22 13:57:51'),
(24, 'jszczepanski', 'hashed_password', 'j.szczepanski@example.com', NULL, 'student', '2025-10-22 13:57:51'),
(25, 'asawicka', 'hashed_password', 'a.sawicka@example.com', NULL, 'student', '2025-10-22 13:57:51'),
(26, 'mmichalak', 'hashed_password', 'm.michalak@example.com', NULL, 'student', '2025-10-22 13:57:51'),
(27, 'kkaczmarczyk', 'hashed_password', 'k.kaczmarczyk@example.com', NULL, 'student', '2025-10-22 13:57:51'),
(28, 'tkalinowski', 'hashed_password', 't.kalinowski@example.com', NULL, 'student', '2025-10-22 13:57:51'),
(29, 'jlis', 'hashed_password', 'j.lis@example.com', NULL, 'student', '2025-10-22 13:57:51'),
(30, 'ozaleska', 'hashed_password', 'o.zaleska@example.com', NULL, 'student', '2025-10-22 13:57:51'),
(31, 'mwrobel', 'hashed_password', 'm.wrobel@example.com', NULL, 'student', '2025-10-22 13:57:51'),
(32, 'mmalinowska', 'hashed_password', 'm.malinowska@example.com', NULL, 'student', '2025-10-22 13:57:51'),
(33, 'mjakubowski', 'hashed_password', 'm.jakubowski1@example.com', NULL, 'student', '2025-10-22 13:57:51'),
(34, 'kpawlak', 'hashed_password', 'k.pawlak@example.com', NULL, 'student', '2025-10-22 13:57:51'),
(35, 'ksikora', 'hashed_password', 'k.sikora@example.com', NULL, 'student', '2025-10-22 13:57:51'),
(36, 'anowicka', 'hashed_password', 'a.nowicka@example.com', NULL, 'student', '2025-10-22 13:57:51'),
(37, 'mbaran', 'hashed_password', 'm.baran@example.com', NULL, 'student', '2025-10-22 13:57:51'),
(38, 'pdudek', 'hashed_password', 'p.dudek@example.com', NULL, 'student', '2025-10-22 13:57:51'),
(39, 'jpietrzak', 'hashed_password', 'j.pietrzak@example.com', NULL, 'student', '2025-10-22 13:57:51'),
(40, 'zjaworski', 'hashed_password', 'z.jaworski@example.com', NULL, 'student', '2025-10-22 13:57:51'),
(41, 'iwasilewska', 'hashed_password', 'i.wasilewska@example.com', NULL, 'student', '2025-10-22 13:57:51'),
(42, 'bpiatek', 'hashed_password', 'b.piatek@example.com', NULL, 'student', '2025-10-22 13:57:51'),
(43, 'p', '$2y$10$OzGH9OMAqi4WKDohoddYFuzjd7pdZvSdcCFHkNsicc3tSNbNhTt1i', 'a.michalska@example.com', '501501501', 'parent', '2025-10-22 13:57:51'),
(44, 'mjaworski', 'hashed_password', 'm.jaworski@example.com', '502502502', 'parent', '2025-10-22 13:57:51'),
(45, 'jjaworska', 'hashed_password', 'j.jaworska@example.com', '503503503', 'parent', '2025-10-22 13:57:51'),
(46, 'fbiernacki', 'hashed_password', 'f.biernacki@example.com', '504504504', 'parent', '2025-10-22 13:57:51'),
(47, 'jorkowski', 'hashed_password', 'j.orkowski@example.com', '505505505', 'parent', '2025-10-22 13:57:51'),
(48, 'horkowska', 'hashed_password', 'h.orkowska@example.com', '506506506', 'parent', '2025-10-22 13:57:51'),
(49, 'pp', '$2y$10$IuIc0iT3qDNN/1afUptHNuWRXypveOHGgZiyfdSmCHEJclwTzaJhi', 'j.sobczak@example.com', '507507507', 'parent', '2025-10-22 13:57:51'),
(50, 'rchmielewski', 'hashed_password', 'r.chmielewski@example.com', '508508508', 'parent', '2025-10-22 13:57:51'),
(51, 'wwalczak', 'hashed_password', 'w.walczak@example.com', '509509509', 'parent', '2025-10-22 13:57:51'),
(52, 'mwitkowska', 'hashed_password', 'm.witkowska@example.com', '510510510', 'parent', '2025-10-22 13:57:51'),
(53, 'mczajkowski', 'hashed_password', 'm.czajkowski@example.com', '511511511', 'parent', '2025-10-22 13:57:51'),
(54, 'akozlowska', 'hashed_password', 'a.kozlowska@example.com', '512512512', 'parent', '2025-10-22 13:57:51'),
(55, 'tsokolowski', 'hashed_password', 't.sokolowski@example.com', '513513513', 'parent', '2025-10-22 13:57:51'),
(56, 'aszczepanska', 'hashed_password', 'a.szczepanska@example.com', '514514514', 'parent', '2025-10-22 13:57:51'),
(57, 'esawicki', 'hashed_password', 'e.sawicki@example.com', '515515515', 'parent', '2025-10-22 13:57:51'),
(58, 'wmichalak', 'hashed_password', 'w.michalak@example.com', '516516516', 'parent', '2025-10-22 13:57:51'),
(59, 'kkaczmarczyk_p', 'hashed_password', 'k.kaczmarczyk_p@example.com', '517517517', 'parent', '2025-10-22 13:57:51'),
(60, 'pkaliowska', 'hashed_password', 'p.kalinowska@example.com', '518518518', 'parent', '2025-10-22 13:57:51'),
(61, 'mlis', 'hashed_password', 'm.lis@example.com', '519519519', 'parent', '2025-10-22 13:57:51'),
(62, 'bzaleski', 'hashed_password', 'b.zaleski@example.com', '520520520', 'parent', '2025-10-22 13:57:51'),
(63, 'a', '$2y$10$Ju91/XEy/PY4WKh4msTDEeEu10tIfzfABIQZTmceCLpkngHPUalwu', 'admin@example.com', '', 'admin', '2025-11-18 12:58:18'),
(68, 'jk', '$2y$10$AYRYI8RXO9opwjpveiepFO8KKNMX8/w2AQsq5ooStJfoPU8OHx1MC', 'new@user.ex', '', 'student', '2025-11-18 15:25:12'),
(69, 'mata', '$2y$10$YymOgVy8yIExSIuiwlwiVOISq6ofYHXeOX46GXCcsFmAn.VaaNRp.', 'mata@mata.com', '133742111', 'student', '2025-11-18 15:36:11'),
(72, 'tt', '$2y$10$5a8uAnFZbQR8c0XDVvMg3eiigx5GI8f27GV.8kG4SzfeSMKHKXUnC', 'tt@example.com', '666666666', 'teacher', '2025-11-18 15:52:44'),
(73, 'alegwi', '$2y$10$bNrUdYf5XgQfjHdjCW611.DKFdkc2Edxe7GgLRIVqFQ6FnX2DUwSu', 'alegwi@plodzpl.pl', '863022993', 'student', '2025-11-18 21:32:21'),
(77, 'testowy', '$2y$10$U7IyUE3KkR.AIDkLq266d.OIp1rbO/fbIBowHaSEMAhZxBZ19T1AW', 'testowy@niematakiegoemailu.polonia', '920123337', 'student', '2025-11-26 14:55:18'),
(79, 'kasia', '$2y$10$dqhzDdsKNVn2VLlLB0kLyeDqp80YELZlRwcCnlwli1RVG/ysOGY4K', 'kasia@kasia.kasia', '111222333', 'student', '2025-12-01 23:25:36');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `app_settings`
--
ALTER TABLE `app_settings`
  ADD PRIMARY KEY (`setting_key`);

--
-- Indeksy dla tabeli `bug_reports`
--
ALTER TABLE `bug_reports`
  ADD PRIMARY KEY (`reportID`),
  ADD KEY `fk_bug_user` (`userID`);

--
-- Indeksy dla tabeli `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`classID`),
  ADD UNIQUE KEY `name_unique` (`name`),
  ADD KEY `fk_class_teacher` (`teacherID`);

--
-- Indeksy dla tabeli `class_subjects_teacher`
--
ALTER TABLE `class_subjects_teacher`
  ADD PRIMARY KEY (`classID`,`subjectID`,`teacherID`),
  ADD KEY `fk_cst_subject` (`subjectID`),
  ADD KEY `fk_cst_teacher` (`teacherID`);

--
-- Indeksy dla tabeli `final_grades`
--
ALTER TABLE `final_grades`
  ADD PRIMARY KEY (`finalGradeID`),
  ADD UNIQUE KEY `unique_grade_entry` (`studentID`,`subjectID`),
  ADD KEY `fk_fg_student` (`studentID`),
  ADD KEY `fk_fg_subject` (`subjectID`),
  ADD KEY `fk_fg_teacher` (`teacherID`);

--
-- Indeksy dla tabeli `grades`
--
ALTER TABLE `grades`
  ADD PRIMARY KEY (`gradeID`),
  ADD KEY `fk_grade_student` (`studentID`),
  ADD KEY `fk_grade_subject` (`subjectID`),
  ADD KEY `fk_grade_teacher` (`teacherID`),
  ADD KEY `fk_grade_category` (`categoryID`);

--
-- Indeksy dla tabeli `grade_categories`
--
ALTER TABLE `grade_categories`
  ADD PRIMARY KEY (`categoryID`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indeksy dla tabeli `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`messageID`),
  ADD KEY `fk_msg_sender` (`senderID`),
  ADD KEY `fk_msg_receiver` (`receiverID`);

--
-- Indeksy dla tabeli `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`noteID`),
  ADD KEY `fk_note_student` (`studentID`),
  ADD KEY `fk_note_teacher` (`teacherID`);

--
-- Indeksy dla tabeli `parents`
--
ALTER TABLE `parents`
  ADD PRIMARY KEY (`parentID`),
  ADD UNIQUE KEY `PESEL` (`PESEL`);

--
-- Indeksy dla tabeli `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`studentID`),
  ADD UNIQUE KEY `PESEL` (`PESEL`),
  ADD KEY `fk_student_class` (`classID`);

--
-- Indeksy dla tabeli `student_parent`
--
ALTER TABLE `student_parent`
  ADD PRIMARY KEY (`studentID`,`parentID`),
  ADD KEY `fk_sp_parent` (`parentID`);

--
-- Indeksy dla tabeli `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`subjectID`),
  ADD UNIQUE KEY `name_unique` (`name`);

--
-- Indeksy dla tabeli `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`teacherID`),
  ADD UNIQUE KEY `PESEL` (`PESEL`);

--
-- Indeksy dla tabeli `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userID`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT dla zrzuconych tabel
--

--
-- AUTO_INCREMENT dla tabeli `bug_reports`
--
ALTER TABLE `bug_reports`
  MODIFY `reportID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT dla tabeli `classes`
--
ALTER TABLE `classes`
  MODIFY `classID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT dla tabeli `final_grades`
--
ALTER TABLE `final_grades`
  MODIFY `finalGradeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT dla tabeli `grades`
--
ALTER TABLE `grades`
  MODIFY `gradeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=322;

--
-- AUTO_INCREMENT dla tabeli `grade_categories`
--
ALTER TABLE `grade_categories`
  MODIFY `categoryID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT dla tabeli `messages`
--
ALTER TABLE `messages`
  MODIFY `messageID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT dla tabeli `notes`
--
ALTER TABLE `notes`
  MODIFY `noteID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT dla tabeli `subjects`
--
ALTER TABLE `subjects`
  MODIFY `subjectID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT dla tabeli `users`
--
ALTER TABLE `users`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- Ograniczenia dla zrzutów tabel
--

--
-- Ograniczenia dla tabeli `bug_reports`
--
ALTER TABLE `bug_reports`
  ADD CONSTRAINT `fk_bug_user` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`) ON DELETE CASCADE;

--
-- Ograniczenia dla tabeli `classes`
--
ALTER TABLE `classes`
  ADD CONSTRAINT `fk_class_teacher` FOREIGN KEY (`teacherID`) REFERENCES `teachers` (`teacherID`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ograniczenia dla tabeli `class_subjects_teacher`
--
ALTER TABLE `class_subjects_teacher`
  ADD CONSTRAINT `fk_cst_class` FOREIGN KEY (`classID`) REFERENCES `classes` (`classID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_cst_subject` FOREIGN KEY (`subjectID`) REFERENCES `subjects` (`subjectID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_cst_teacher` FOREIGN KEY (`teacherID`) REFERENCES `teachers` (`teacherID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ograniczenia dla tabeli `final_grades`
--
ALTER TABLE `final_grades`
  ADD CONSTRAINT `fk_fg_student` FOREIGN KEY (`studentID`) REFERENCES `students` (`studentID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_fg_subject` FOREIGN KEY (`subjectID`) REFERENCES `subjects` (`subjectID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_fg_teacher` FOREIGN KEY (`teacherID`) REFERENCES `teachers` (`teacherID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ograniczenia dla tabeli `grades`
--
ALTER TABLE `grades`
  ADD CONSTRAINT `fk_grade_category` FOREIGN KEY (`categoryID`) REFERENCES `grade_categories` (`categoryID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_grade_student` FOREIGN KEY (`studentID`) REFERENCES `students` (`studentID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_grade_subject` FOREIGN KEY (`subjectID`) REFERENCES `subjects` (`subjectID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_grade_teacher` FOREIGN KEY (`teacherID`) REFERENCES `teachers` (`teacherID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ograniczenia dla tabeli `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `fk_msg_receiver` FOREIGN KEY (`receiverID`) REFERENCES `users` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_msg_sender` FOREIGN KEY (`senderID`) REFERENCES `users` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ograniczenia dla tabeli `notes`
--
ALTER TABLE `notes`
  ADD CONSTRAINT `fk_note_student` FOREIGN KEY (`studentID`) REFERENCES `students` (`studentID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_note_teacher` FOREIGN KEY (`teacherID`) REFERENCES `teachers` (`teacherID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ograniczenia dla tabeli `parents`
--
ALTER TABLE `parents`
  ADD CONSTRAINT `fk_parent_user` FOREIGN KEY (`parentID`) REFERENCES `users` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ograniczenia dla tabeli `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `fk_student_class` FOREIGN KEY (`classID`) REFERENCES `classes` (`classID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_student_user` FOREIGN KEY (`studentID`) REFERENCES `users` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ograniczenia dla tabeli `student_parent`
--
ALTER TABLE `student_parent`
  ADD CONSTRAINT `fk_sp_parent` FOREIGN KEY (`parentID`) REFERENCES `parents` (`parentID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_sp_student` FOREIGN KEY (`studentID`) REFERENCES `students` (`studentID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ograniczenia dla tabeli `teachers`
--
ALTER TABLE `teachers`
  ADD CONSTRAINT `fk_teacher_user` FOREIGN KEY (`teacherID`) REFERENCES `users` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
