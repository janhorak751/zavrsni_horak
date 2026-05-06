-- ============================================================
--  Baza podataka: tenis_klub
--  Autor: Jan Horak | Tehnička škola Daruvar
-- ============================================================

CREATE DATABASE IF NOT EXISTS `tenis_klub`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `tenis_klub`;

-- ============================================================
-- Tablica: clan
-- ============================================================
CREATE TABLE `clan` (
  `ID_Clana`           INT(11)      NOT NULL AUTO_INCREMENT,
  `Ime`                VARCHAR(50)  NOT NULL,
  `Prezime`            VARCHAR(50)  NOT NULL,
  `Email`              VARCHAR(100) NOT NULL UNIQUE,
  `Telefon`            VARCHAR(20)  NOT NULL,
  `Godisnja_clanarina` TINYINT(1)   NOT NULL DEFAULT 0,
  `Datum_upisa`        DATE         NOT NULL,
  PRIMARY KEY (`ID_Clana`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Tablica: teren
-- ============================================================
CREATE TABLE `teren` (
  `ID_Terena` INT(11)                     NOT NULL AUTO_INCREMENT,
  `Naziv`     VARCHAR(50)                 NOT NULL,
  `Tip`       ENUM('Otvoreni','Zatvoreni') NOT NULL,
  `Opis`      VARCHAR(200)                DEFAULT NULL,
  `Dostupan`  TINYINT(1)                  NOT NULL DEFAULT 1,
  PRIMARY KEY (`ID_Terena`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Tablica: cjenik
-- ============================================================
CREATE TABLE `cjenik` (
  `ID_Cjenika`     INT(11)                                    NOT NULL AUTO_INCREMENT,
  `Doba_dana`      ENUM('Prijepodne','Poslijepodne','Navecer') NOT NULL,
  `Cijena_po_satu` DECIMAL(10,2)                              NOT NULL,
  `Opis`           VARCHAR(100)                               DEFAULT NULL,
  PRIMARY KEY (`ID_Cjenika`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Tablica: rezervacija
-- ============================================================
CREATE TABLE `rezervacija` (
  `ID_Rezervacije`    INT(11)       NOT NULL AUTO_INCREMENT,
  `ID_Clana`          INT(11)       NOT NULL,
  `ID_Terena`         INT(11)       NOT NULL,
  `ID_Cjenika`        INT(11)       NOT NULL,
  `Datum`             DATE          NOT NULL,
  `Vrijeme_pocetka`   TIME          NOT NULL,
  `Vrijeme_zavrsetka` TIME          NOT NULL,
  `Ukupna_cijena`     DECIMAL(10,2) DEFAULT NULL,
  `Napomena`          VARCHAR(200)  DEFAULT NULL,
  PRIMARY KEY (`ID_Rezervacije`),
  CONSTRAINT `fk_clan`   FOREIGN KEY (`ID_Clana`)   REFERENCES `clan`   (`ID_Clana`),
  CONSTRAINT `fk_teren`  FOREIGN KEY (`ID_Terena`)  REFERENCES `teren`  (`ID_Terena`),
  CONSTRAINT `fk_cjenik` FOREIGN KEY (`ID_Cjenika`) REFERENCES `cjenik` (`ID_Cjenika`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Trigger: automatski izracun ukupne cijene
-- ============================================================
DELIMITER $$
CREATE TRIGGER `izracunaj_cijenu`
BEFORE INSERT ON `rezervacija`
FOR EACH ROW
BEGIN
  DECLARE v_cijena DECIMAL(10,2);
  DECLARE v_sati   DECIMAL(10,2);

  SELECT `Cijena_po_satu` INTO v_cijena
  FROM `cjenik`
  WHERE `ID_Cjenika` = NEW.`ID_Cjenika`;

  SET v_sati = TIMESTAMPDIFF(MINUTE, NEW.`Vrijeme_pocetka`, NEW.`Vrijeme_zavrsetka`) / 60.0;

  SET NEW.`Ukupna_cijena` = v_cijena * v_sati;
END$$
DELIMITER ;

-- ============================================================
-- Testni podaci: clan
-- ============================================================
INSERT INTO `clan` (`Ime`, `Prezime`, `Email`, `Telefon`, `Godisnja_clanarina`, `Datum_upisa`) VALUES
('Marko',  'Kovac',      'marko.kovac@gmail.com',     '091 111 1111', 1, '2024-01-15'),
('Ana',    'Horvat',     'ana.horvat@gmail.com',      '091 222 2222', 1, '2024-02-20'),
('Ivan',   'Babic',      'ivan.babic@gmail.com',      '091 333 3333', 0, '2024-03-10'),
('Petra',  'Novak',      'petra.novak@gmail.com',     '091 444 4444', 1, '2024-04-05'),
('Luka',   'Maric',      'luka.maric@gmail.com',      '091 555 5555', 0, '2024-05-18'),
('Sara',   'Juric',      'sara.juric@gmail.com',      '091 666 6666', 1, '2024-06-22'),
('Tomislav','Peric',     'tomislav.peric@gmail.com',  '091 777 7777', 1, '2024-07-30'),
('Maja',   'Vukasin',    'maja.vukasin@gmail.com',    '091 888 8888', 0, '2024-08-14'),
('Josip',  'Knezevic',   'josip.knezevic@gmail.com',  '091 999 9999', 1, '2024-09-03'),
('Nina',   'Brkic',      'nina.brkic@gmail.com',      '091 101 0101', 1, '2024-10-11');

-- ============================================================
-- Testni podaci: teren
-- ============================================================
INSERT INTO `teren` (`Naziv`, `Tip`, `Opis`, `Dostupan`) VALUES
('Teren A', 'Otvoreni',  'Šljaka, uz tribine',            1),
('Teren B', 'Otvoreni',  'Šljaka, standardne dimenzije',  1),
('Teren C', 'Zatvoreni', 'Tvrda podloga, zatvorena hala', 1),
('Teren D', 'Zatvoreni', 'Tepih podloga, trening sala',   1),
('Teren E', 'Otvoreni',  'Šljaka, natjecateljski teren',  0);

-- ============================================================
-- Testni podaci: cjenik
-- ============================================================
INSERT INTO `cjenik` (`Doba_dana`, `Cijena_po_satu`, `Opis`) VALUES
('Prijepodne',   25.00, '07:00 – 13:00'),
('Poslijepodne', 40.00, '13:00 – 19:00'),
('Navecer',      55.00, '19:00 – 23:00');

-- ============================================================
-- Testni podaci: rezervacija (trigger racuna cijenu)
-- ============================================================
INSERT INTO `rezervacija` (`ID_Clana`, `ID_Terena`, `ID_Cjenika`, `Datum`, `Vrijeme_pocetka`, `Vrijeme_zavrsetka`, `Napomena`) VALUES
(1,  1, 1, '2025-04-07', '08:00', '10:00', 'Jutarnji trening'),
(2,  3, 3, '2025-04-08', '19:00', '20:00', NULL),
(3,  2, 2, '2025-04-09', '14:00', '15:30', 'Trening s prijateljem'),
(4,  4, 1, '2025-04-10', '09:00', '10:00', NULL),
(5,  1, 2, '2025-04-11', '15:00', '16:00', NULL),
(6,  3, 3, '2025-04-12', '20:00', '21:30', 'Večernji trening'),
(7,  4, 1, '2025-04-14', '10:00', '11:00', NULL),
(8,  2, 2, '2025-04-15', '13:00', '14:00', NULL),
(9,  1, 1, '2025-04-16', '07:00', '08:30', 'Jutarnji trening'),
(10, 3, 3, '2025-04-17', '21:00', '22:00', NULL);

COMMIT;
