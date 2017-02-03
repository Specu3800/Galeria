-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Czas generowania: 03 Lut 2017, 19:36
-- Wersja serwera: 10.1.16-MariaDB
-- Wersja PHP: 5.6.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `suliborski`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `albumy`
--

CREATE TABLE `albumy` (
  `id` int(11) NOT NULL,
  `tytul` varchar(100) COLLATE utf8_polish_ci NOT NULL,
  `data` date NOT NULL,
  `id_uzytkownika` varchar(20) COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `albumy`
--

INSERT INTO `albumy` (`id`, `tytul`, `data`, `id_uzytkownika`) VALUES
(1, 'Góry', '2016-10-07', '14'),
(2, 'Gry', '2016-10-25', '17'),
(3, 'Polana', '2016-11-01', '14'),
(4, 'Morze', '2016-11-02', '14'),
(5, 'Las', '2016-11-11', '14'),
(6, 'Jezioro', '2016-11-17', '14');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `names`
--

CREATE TABLE `names` (
  `id` int(11) NOT NULL,
  `surname` varchar(30) COLLATE utf8_polish_ci NOT NULL,
  `birthday` date NOT NULL,
  `number` tinyint(4) NOT NULL DEFAULT '99',
  `sex` enum('male','female') COLLATE utf8_polish_ci NOT NULL,
  `languages` set('en','pl','es','fr','rus') COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `names`
--

INSERT INTO `names` (`id`, `surname`, `birthday`, `number`, `sex`, `languages`) VALUES
(2, 'Newman', '1970-04-04', 11, 'male', 'pl,es'),
(5, 'Wattsonn', '2000-07-04', 21, 'male', 'en,es,fr'),
(7, 'Sherlck', '1900-05-21', 17, 'male', 'en,pl,es,fr,rus'),
(14, 'wadwa', '0000-00-00', 19, 'male', 'en,es'),
(15, 'wadwa', '0000-00-00', 18, 'male', 'en,es'),
(16, 'gbvbfse', '1923-12-12', 10, 'male', 'en,pl,es'),
(19, 'Obama', '1994-07-04', 12, 'female', 'en'),
(23, '', '0000-00-00', 3, 'male', ''),
(24, '', '0000-00-00', 2, 'male', ''),
(25, 'egesef', '0000-00-00', 6, 'male', ''),
(26, 'sefesf', '0000-00-00', 14, 'male', ''),
(27, 'esfesf', '0000-00-00', 7, 'male', ''),
(28, 'sefesfes', '0000-00-00', 15, 'male', ''),
(29, 'fesefs', '0000-00-00', 9, 'male', ''),
(30, 'rgftet', '0000-00-00', 13, 'male', ''),
(31, 'ewtrwer', '0000-00-00', 8, 'male', ''),
(32, 'efwef', '0000-00-00', 5, 'male', ''),
(33, '', '0000-00-00', 1, 'male', ''),
(35, 'wadwa', '0000-00-00', 20, 'male', 'en,pl,es'),
(36, 'efes', '0000-00-00', 4, 'male', ''),
(37, 'sfdgrsgre', '0000-00-00', 16, 'male', '');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `uzytkownicy`
--

CREATE TABLE `uzytkownicy` (
  `id` int(11) NOT NULL,
  `login` varchar(20) COLLATE utf8_polish_ci NOT NULL,
  `haslo` varchar(32) COLLATE utf8_polish_ci NOT NULL,
  `email` varchar(128) COLLATE utf8_polish_ci NOT NULL,
  `zarejestrowany` date NOT NULL,
  `uprawnienia` enum('uzytkownik','moderator','administrator','') COLLATE utf8_polish_ci NOT NULL,
  `aktywny` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `uzytkownicy`
--

INSERT INTO `uzytkownicy` (`id`, `login`, `haslo`, `email`, `zarejestrowany`, `uprawnienia`, `aktywny`) VALUES
(14, 'Specu3800', '53e40bf7a9b7f4a53b38f8a9ac9b7695', 'Specu3800@gmail.com', '2016-11-04', 'administrator', 1),
(15, 'uzytkownik', '6a2c42a912526ae7f5f0b165b2abac35', 'uzytkownik@gmail.com', '2016-11-14', 'uzytkownik', 1),
(16, 'moderator', '6a2c42a912526ae7f5f0b165b2abac35', 'moderator@gmail.com', '2016-11-14', 'moderator', 1),
(17, 'administrator', '6a2c42a912526ae7f5f0b165b2abac35', 'administrator@gmail.com', '2016-11-14', 'administrator', 1),
(18, 'uzytkownikNieaktywny', '6a2c42a912526ae7f5f0b165b2abac35', 'uzytkownik_nieaktywny@gmail.com', '2016-11-14', 'uzytkownik', 0),
(19, 'karoliinax', '044e4cfc5aa79c24c16c2aa9785be851', 'karoliinax@interia.pl', '2016-11-25', 'uzytkownik', 1);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `zdjecia`
--

CREATE TABLE `zdjecia` (
  `id` int(11) NOT NULL,
  `tytul` varchar(255) COLLATE utf8_polish_ci NOT NULL,
  `id_albumu` int(11) NOT NULL,
  `data` date NOT NULL,
  `id_uzytkownika` varchar(20) COLLATE utf8_polish_ci NOT NULL,
  `zaakceptowane` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `zdjecia`
--

INSERT INTO `zdjecia` (`id`, `tytul`, `id_albumu`, `data`, `id_uzytkownika`, `zaakceptowane`) VALUES
(1, 'gory1', 1, '2016-10-07', '14', 1),
(2, 'gory2', 1, '2016-10-07', '14', 1),
(3, 'Polanaaa', 1, '2016-10-07', '14', 0),
(4, 'gory4', 1, '2016-10-07', '14', 1),
(5, 'gry5', 2, '2016-10-01', '17', 1),
(6, 'gry6', 2, '2016-10-07', '17', 1),
(7, 'gry7', 2, '2016-10-07', '17', 1),
(8, 'gry8', 2, '2016-10-07', '17', 0),
(9, 'gry9', 2, '2016-10-07', '17', 1),
(10, 'polana10', 3, '2016-10-25', '14', 1),
(11, 'polana11', 3, '2016-10-25', '14', 0),
(12, 'polana12', 3, '2016-11-01', '14', 1),
(13, 'morze13', 4, '2016-11-01', '14', 1),
(14, 'morze14', 4, '2016-11-01', '14', 1),
(15, 'morze15', 4, '2016-11-01', '14', 1),
(16, 'las16', 5, '2016-11-01', '14', 1),
(17, 'las17', 5, '2016-11-01', '14', 1),
(18, 'las18', 5, '2016-11-01', '14', 1),
(19, 'las19', 5, '2016-11-01', '14', 1),
(20, 'jezioro20', 6, '2016-11-01', '14', 1),
(21, 'jezioro21', 6, '2016-11-01', '14', 1),
(22, 'jezioro22', 6, '2016-11-01', '14', 1),
(34, '', 2, '2016-11-26', '17', 1),
(35, '', 2, '2016-11-26', '17', 1),
(36, 'sefesf', 2, '2016-11-26', '17', 1),
(37, '', 2, '2016-11-26', '17', 1),
(38, '', 2, '2016-11-26', '17', 1),
(39, '', 2, '2016-11-26', '17', 1),
(40, '', 2, '2016-11-26', '17', 1),
(41, '', 2, '2016-11-26', '17', 1),
(42, '', 2, '2016-11-26', '17', 1),
(43, '', 2, '2016-11-26', '17', 1);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `zdjecia_komentarze`
--

CREATE TABLE `zdjecia_komentarze` (
  `id` int(11) NOT NULL,
  `id_zdjecia` int(11) NOT NULL,
  `id_uzytkownika` int(11) NOT NULL,
  `data` date NOT NULL,
  `komentarz` text COLLATE utf8_polish_ci NOT NULL,
  `zaakceptowany` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `zdjecia_komentarze`
--

INSERT INTO `zdjecia_komentarze` (`id`, `id_zdjecia`, `id_uzytkownika`, `data`, `komentarz`, `zaakceptowany`) VALUES
(1, 5, 14, '2016-11-20', 'Wiedźmin <3', 1),
(2, 5, 14, '2016-09-01', 'pierwszy', 1),
(3, 5, 14, '2016-09-29', 'trzeci', 1),
(4, 5, 14, '2016-09-15', 'drugi', 1),
(5, 5, 14, '2016-12-01', 'ostatni', 1),
(6, 5, 14, '2016-10-23', 'czwarty', 1),
(25, 5, 23, '2017-02-03', 'Wspaniała produkcja !', 1);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `zdjecia_oceny`
--

CREATE TABLE `zdjecia_oceny` (
  `id_zdjecia` int(11) NOT NULL,
  `id_uzytkownika` int(11) NOT NULL,
  `ocena` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `zdjecia_oceny`
--

INSERT INTO `zdjecia_oceny` (`id_zdjecia`, `id_uzytkownika`, `ocena`) VALUES
(5, 14, 10),
(5, 16, 2),
(5, 17, 1),
(1, 17, 5),
(20, 19, 8),
(21, 19, 8),
(1, 19, 5),
(7, 17, 3),
(13, 17, 5),
(9, 17, 10),
(5, 23, 10);

--
-- Indeksy dla zrzutów tabel
--

--
-- Indexes for table `albumy`
--
ALTER TABLE `albumy`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `names`
--
ALTER TABLE `names`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `uzytkownicy`
--
ALTER TABLE `uzytkownicy`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zdjecia`
--
ALTER TABLE `zdjecia`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zdjecia_komentarze`
--
ALTER TABLE `zdjecia_komentarze`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT dla tabeli `albumy`
--
ALTER TABLE `albumy`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT dla tabeli `names`
--
ALTER TABLE `names`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;
--
-- AUTO_INCREMENT dla tabeli `uzytkownicy`
--
ALTER TABLE `uzytkownicy`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
--
-- AUTO_INCREMENT dla tabeli `zdjecia`
--
ALTER TABLE `zdjecia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;
--
-- AUTO_INCREMENT dla tabeli `zdjecia_komentarze`
--
ALTER TABLE `zdjecia_komentarze`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
