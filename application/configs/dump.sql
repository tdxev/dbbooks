CREATE TABLE `categories` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
  `user_id` INT UNSIGNED NOT NULL,
  `category_name` VARCHAR(50) UNIQUE NOT NULL
);

INSERT INTO `categories` VALUES (default, 1, 'Design');
INSERT INTO `categories` VALUES (default, 1, 'Programare');
INSERT INTO `categories` VALUES (default, 1, 'Pentesting');

CREATE TABLE `subcategories`(
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
  `user_id` INT UNSIGNED NOT NULL,
  `category_id` INT UNSIGNED NOT NULL,
  `subcategory_name` VARCHAR(50) NOT NULL
);

INSERT INTO `subcategories` VALUES (default, 1, 1, 'Photoshop');
INSERT INTO `subcategories` VALUES (default, 1, 1, 'Flash');

INSERT INTO `subcategories` VALUES (default, 1, 2, 'C++');
INSERT INTO `subcategories` VALUES (default, 1, 2, 'Delphi');
INSERT INTO `subcategories` VALUES (default, 1, 2, 'Java');
INSERT INTO `subcategories` VALUES (default, 1, 2, 'Javascript');
INSERT INTO `subcategories` VALUES (default, 1, 2, 'Html');
INSERT INTO `subcategories` VALUES (default, 1, 2, 'PHP');
INSERT INTO `subcategories` VALUES (default, 1, 2, 'Python');

INSERT INTO `subcategories` VALUES (default, 1, 3, 'XSS');
INSERT INTO `subcategories` VALUES (default, 1, 3, 'SQL injection');
INSERT INTO `subcategories` VALUES (default, 1, 3, 'LFI');
INSERT INTO `subcategories` VALUES (default, 1, 3, 'RFI');
INSERT INTO `subcategories` VALUES (default, 1, 3, 'CSRF');


CREATE TABLE `languages` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
  `name` VARCHAR(100)
);

INSERT INTO `languages` VALUES (default, 'English');
INSERT INTO `languages` VALUES (default, 'Romanian');
INSERT INTO `languages` VALUES (default, 'French');
INSERT INTO `languages` VALUES (default, 'Dutch');
INSERT INTO `languages` VALUES (default, 'Russian');


CREATE TABLE `books` (
  `id` INT UNSIGNED PRIMARY KEY AUTO_INCREMENT NOT NULL,
  `title` VARCHAR(200) NOT NULL,
  `description` TEXT,
  `content` TEXT,
  `author` VARCHAR(200),
  `creator` VARCHAR(200),
  `producer` VARCHAR(200),
  `language_id` INT UNSIGNED NOT NULL,
  `creation_date` DATETIME NOT NULL,
  `modification_date` TIMESTAMP NOT NULL,
  `uploader` INT UNSIGNED  NOT NULL,
  `file_hash` VARCHAR(32) UNIQUE,
  `file_location` VARCHAR(200) UNIQUE,
  `file_size` VARCHAR(20),
  `file_url` VARCHAR(32) UNIQUE,
  `category_id` INT UNSIGNED NOT NULL,
  `subcategories` VARCHAR(255)
);


CREATE TABLE `users` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
  `username` VARCHAR(50) UNIQUE NOT NULL,
  `password` VARCHAR(32) NOT NULL,
  `email`    VARCHAR(100) UNIQUE NOT NULL,
  `webpage`  VARCHAR(255) NOT NULL,
  `group`    INT UNSIGNED NOT NULL,
  `registration_date` DATETIME NOT NULL,
  `registration_ip` VARCHAR(32) NOT NULL,
  `activation_key` VARCHAR(32) NOT NULL
);