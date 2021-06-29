-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 
-- Czas generowania: 29 Cze 2021, 14:31
-- Wersja serwera: 8.0.21
-- Wersja PHP: 7.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `s119`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `checkboxes`
--

CREATE TABLE `checkboxes` (
  `id` int UNSIGNED NOT NULL,
  `task_id` int UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `state` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Zrzut danych tabeli `checkboxes`
--

INSERT INTO `checkboxes` (`id`, `task_id`, `name`, `state`) VALUES
(143, 74, 'zalogować się', 0),
(144, 74, 'opowiedzieć o stronie', 0),
(145, 74, 'pokazać jak działa', 0),
(146, 74, 'otworzyć harnasia ', 0),
(147, 75, 'nie spóźnić się', 0),
(148, 75, 'zarobić hajs', 0),
(149, 76, 'Kraul', 0),
(150, 76, 'Plecki', 0),
(151, 77, 'kupić grilla', 0),
(152, 77, 'kupić jedzienie', 0),
(153, 77, 'kupić napoje', 0),
(154, 77, 'sponiewierać się', 1),
(160, 79, 'Pokazać ekrany logowania/rejestracji', 0),
(161, 79, 'Pokazać ekran główny', 0),
(162, 79, 'Dodawanie/edycja wydarzeń', 0),
(163, 79, 'Usuwanie i dodawanie tasków', 0);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `links`
--

CREATE TABLE `links` (
  `id` int UNSIGNED NOT NULL,
  `task_id` int UNSIGNED NOT NULL,
  `guest_email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Zrzut danych tabeli `links`
--

INSERT INTO `links` (`id`, `task_id`, `guest_email`) VALUES
(48, 76, 'michalbla@student.agh.edu.pl'),
(49, 74, 'marek15kwak@gmail.com'),
(50, 77, 'marek15kwak@gmail.com'),
(51, 77, 'wymyslony@kolega.pl'),
(52, 79, 'marek15kwak@gmail.com'),
(53, 74, 'piotrwegrzyn@student.agh.edu.pl'),
(54, 77, 'piotrwegrzyn@student.agh.edu.pl'),
(55, 79, 'michalbla@student.agh.edu.pl');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `tasks`
--

CREATE TABLE `tasks` (
  `id` int UNSIGNED NOT NULL,
  `user_email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `begin_time` datetime NOT NULL,
  `end_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Zrzut danych tabeli `tasks`
--

INSERT INTO `tasks` (`id`, `user_email`, `name`, `description`, `begin_time`, `end_time`) VALUES
(74, 'michalbla@student.agh.edu.pl', 'Oddanie projektu z www', 'strona: s119.labagh.pl, platforma: MS Teams', '2021-06-29 18:00:00', '2021-06-29 18:15:00'),
(75, 'michalbla@student.agh.edu.pl', 'praca', 'pierwszy dzien', '2021-07-01 08:30:00', '2021-07-01 16:30:00'),
(76, 'marek15kwak@gmail.com', 'Basen', 'Lecimy', '2021-06-29 17:00:00', '2021-06-29 18:00:00'),
(77, 'michalbla@student.agh.edu.pl', 'Grill z ziomeczkami', 'jedziemy na działeczke i robimy grilla', '2021-07-03 17:00:00', '2021-07-05 12:00:00'),
(79, 'piotrwegrzyn@student.agh.edu.pl', 'Zadanie 100', 'W tym zadaniu chodzi o pokazanie wielu funkcjonalności witryny', '2021-06-29 18:00:00', '2021-06-29 18:15:00');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users`
--

CREATE TABLE `users` (
  `id` int UNSIGNED NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Zrzut danych tabeli `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `created`) VALUES
(24, 'michalbla@student.agh.edu.pl', '$2y$10$/XpmAGqdrSqjTC1fIEkG6uKo5EEVhSwftsOSSKhQOHG/XFLQwDu66', '2021-06-29 13:17:19'),
(26, 'marek15kwak@gmail.com', '$2y$10$sutrysw8bzVAzbTLE25BReXaNQnyGYtewjGFxrPq35818ghyKS9li', '2021-06-29 13:22:03'),
(27, 'wymyslony@kolega.pl', '$2y$10$hpwqbC7TkU/D3vwDK1/VT.0hD0yZPywZlOXkrQYo6s2iCwE3pPWfO', '2021-06-29 13:32:30'),
(28, 'piotrwegrzyn@student.agh.edu.pl', '$2y$10$ORT7Nwkj35FlxBbhPLHz2OYxPi6tAmQYMbpcl3S9tIzHlcFupunC6', '2021-06-29 13:41:29');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `checkboxes`
--
ALTER TABLE `checkboxes`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `links`
--
ALTER TABLE `links`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT dla zrzuconych tabel
--

--
-- AUTO_INCREMENT dla tabeli `checkboxes`
--
ALTER TABLE `checkboxes`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=164;

--
-- AUTO_INCREMENT dla tabeli `links`
--
ALTER TABLE `links`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT dla tabeli `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT dla tabeli `users`
--
ALTER TABLE `users`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
