CREATE DATABASE  IF NOT EXISTS `admin_dashboard`;
USE `admin_dashboard`;

DROP TABLE IF EXISTS `cards`;
CREATE TABLE `cards` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `text` varchar(255) NOT NULL,
  `date` varchar(45) NOT NULL,
  `username` varchar(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
);

DROP TABLE IF EXISTS `login`;
CREATE TABLE `login` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ip` varchar(255) NOT NULL,
  `date` varchar(45) NOT NULL,
  `email` varchar(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
);

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(45) NOT NULL,
  `useremail` varchar(45) NOT NULL,
  `userpass` varchar(255) NOT NULL,
  `admin` tinyint NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username_UNIQUE` (`username`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  UNIQUE KEY `useremail_UNIQUE` (`useremail`),
  UNIQUE KEY `userpass_UNIQUE` (`userpass`)
);